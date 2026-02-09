# Implementación de Colas para Anulación SUNAT

## 📋 Descripción

Se ha implementado un sistema de colas (Jobs) para manejar de forma asíncrona la respuesta de tickets de anulación de documentos en SUNAT. Esto evita que el cliente tenga que esperar la respuesta de SUNAT (que puede demorar varios segundos o minutos), mejorando significativamente la experiencia de usuario.

**Esta implementación se integró con el controlador existente `SaleController::updateStatus()` sin romper la funcionalidad actual.**

## 🚀 Características

- ✅ **Procesamiento Asíncrono**: El cliente recibe una respuesta inmediata mientras el proceso continúa en segundo plano
- ✅ **Reintentos Automáticos**: El job se reintenta hasta 5 veces con backoff exponencial
- ✅ **Seguimiento de Estado**: Se puede consultar el estado de la anulación en cualquier momento
- ✅ **Logging Completo**: Todos los eventos son registrados para debugging
- ✅ **Manejo de Errores**: Errores son capturados y almacenados para revisión
- ✅ **Retrocompatibilidad**: Los campos legacy (`estado_sunat`, `fecha_baja_sunat`, `hora_baja_sunat`) se actualizan automáticamente

## 📁 Archivos Modificados/Creados

### 1. Job Principal
**`app/Jobs/ProcessSunatVoidedTicket.php`** ✨ NUEVO
- Procesa el ticket de anulación de forma asíncrona
- 5 intentos con backoff: [30s, 60s, 120s, 240s, 480s]
- Timeout de 120 segundos por intento
- Actualiza automáticamente tanto campos nuevos como legacy

### 2. Servicio Modificado
**`app/Services/SalesSunatService.php`** 🔄 MODIFICADO
- Método `saleInvoiceAnulacion()` ahora usa colas
- Retorna respuesta inmediata con el ticket
- Estado inicial: `PROCESANDO_ANULACION`
- Despacha el Job con delay de 30 segundos

### 3. Controlador Actualizado
**`app/Modules/Sale/Infrastructure/Controllers/SaleController.php`** 🔄 MODIFICADO
- Método `updateStatus()` ahora detecta respuestas asíncronas
- Retorna información del ticket cuando usa colas
- Mantiene compatibilidad con respuestas síncronas
- Nuevo método `checkSunatVoidedStatus($id)` para consultar estado

### 4. Migración de Base de Datos
**`database/migrations/2026_02_07_110159_add_sunat_voided_fields_to_sales_table.php`** ✨ NUEVO
- `sunat_status`: Estado del proceso (PROCESANDO_ANULACION, ANULADO, ERROR_ANULACION, etc.)
- `sunat_ticket`: Ticket de SUNAT para seguimiento
- `sunat_response`: Respuesta completa de SUNAT en JSON
- `sunat_voided_at`: Timestamp de anulación exitosa
- **Los campos legacy se mantienen para retrocompatibilidad**

## 🔧 Configuración

### Paso 1: Ejecutar la Migración

```bash
cd backend-hsgestion
php artisan migrate
```

Esto agregará los nuevos campos a la tabla `sales`:
- `sunat_status`
- `sunat_ticket`
- `sunat_response`
- `sunat_voided_at`

### Paso 2: Configurar el Driver de Colas

Edita tu archivo `.env`:

```env
# =============================================
# OPCIÓN 1: Para desarrollo/testing (sin colas reales)
# =============================================
QUEUE_CONNECTION=sync

# =============================================
# OPCIÓN 2: Para producción usando Database
# =============================================
QUEUE_CONNECTION=database

# =============================================
# OPCIÓN 3: Para producción usando Redis (RECOMENDADO)
# =============================================
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Paso 3: Crear Tablas de Jobs (si usas database)

Si elegiste `QUEUE_CONNECTION=database`:

```bash
php artisan queue:table
php artisan queue:failed-table
php artisan migrate
```

### Paso 4: Iniciar el Worker de Colas (PRODUCCIÓN)

**⚠️ IMPORTANTE**: En producción SIEMPRE debes tener un worker corriendo.

```bash
# Worker básico
php artisan queue:work --tries=5 --timeout=120

# Worker con más opciones (recomendado)
php artisan queue:work --tries=5 --timeout=120 --sleep=3 --max-jobs=1000

# Worker específico para la cola de SUNAT
php artisan queue:work --queue=default --tries=5 --timeout=120
```

**💡 TIP**: En desarrollo con `QUEUE_CONNECTION=sync` no necesitas iniciar ningún worker.

## 🎯 Cómo Funciona

### Flujo de Anulación (Con Colas)

```
1. Usuario hace click en "Anular" → POST /api/sales/{id}/status
2. SaleController::updateStatus() valida permisos y reglas de negocio
3. Se actualiza el estado local de la venta a "anulado"
4. Se llama a SalesSunatService::saleInvoiceAnulacion($sale)
5. El servicio envía la solicitud a SUNAT y obtiene un TICKET
6. Se despacha un Job (ProcessSunatVoidedTicket) con delay de 30s
7. Se responde INMEDIATAMENTE al usuario con el ticket
8. ✨ Usuario puede continuar trabajando sin esperar ✨
9. (30 segundos después) El Job consulta el ticket en SUNAT
10. El Job actualiza la base de datos con el resultado
11. Usuario puede consultar el estado cuando quiera
```

### Respuestas del Endpoint

#### Respuesta Asíncrona (CON COLAS - Nuevo comportamiento)

**Request**: `POST /api/sales/123/status`

**Response**:
```json
{
  "message": "Solicitud de anulación enviada a SUNAT. El proceso continuará en segundo plano.",
  "status": true,
  "ticket": "1234567890",
  "sunat_status": "PROCESANDO_ANULACION",
  "async": true,
  "info": "La anulación se está procesando en segundo plano. Puede consultar el estado más tarde."
}
```

#### Respuesta Síncrona (SIN COLAS - Comportamiento legacy)

Si por alguna razón las colas están desactivadas (`QUEUE_CONNECTION=sync`), el sistema procesará todo de inmediato:

**Response**:
```json
{
  "message": "Documento anulado correctamente",
  "status": true
}
```

### Consultar Estado de Anulación

Puedes agregar esta ruta a tu archivo de rutas:

```php
// En routes/api.php
Route::get('/sales/{id}/sunat-status', [SaleController::class, 'checkSunatVoidedStatus']);
```

**Request**: `GET /api/sales/123/sunat-status`

**Response**:
```json
{
  "status": true,
  "sale_id": 123,
  "sunat_status": "ANULADO",
  "sunat_ticket": "1234567890",
  "sunat_response": {
    "success": true,
    "fecha_respuesta": "2026-02-07",
    "hora_respuesta": "14:30:45",
    "message": "Documento anulado exitosamente"
  },
  "sunat_voided_at": "2026-02-07 14:30:45",
  "estado_sunat": "ANULADA",
  "fecha_baja_sunat": "2026-02-07",
  "hora_baja_sunat": "14:30:45"
}
```

## 🔄 Estados Posibles

### Estados Nuevos (`sunat_status`)

| Estado | Descripción | Acción del Usuario |
|--------|-------------|-------------------|
| `PROCESANDO_ANULACION` | Ticket enviado a SUNAT, esperando respuesta | Esperar o consultar más tarde |
| `ANULADO` | Documento anulado exitosamente en SUNAT | Ninguna - Proceso completo ✅ |
| `ERROR_ANULACION` | SUNAT rechazó la anulación | Revisar el error en `sunat_response` |
| `ERROR_TICKET` | Error al consultar el ticket | Revisar logs o reintentar |
| `FAILED_ANULACION` | Job falló después de todos los reintentos | Contactar soporte técnico |

### Estados Legacy (`estado_sunat`)

Los campos legacy se actualizan automáticamente:
- `ANULADA`: Cuando el proceso termina exitosamente
- `ERROR_ANULACION`: Cuando hay un error
- `FAILED_ANULACION`: Cuando falla completamente

## 📊 Monitoreo y Debugging

### Ver Logs en Tiempo Real

```bash
# Ver todos los logs
tail -f storage/logs/laravel.log

# Filtrar solo logs de SUNAT
tail -f storage/logs/laravel.log | grep "SUNAT"

# Ver logs del ticket específico
tail -f storage/logs/laravel.log | grep "ticket_1234567890"
```

### Ver Jobs en Cola

```bash
# Ver el estado de las colas
php artisan queue:monitor

# Ver workers activos
php artisan queue:work --once  # Procesar solo un job y salir
```

### Ver Jobs Fallidos

```bash
# Listar jobs fallidos
php artisan queue:failed

# Reintentar un job específico
php artisan queue:retry {job-id}

# Reintentar todos los jobs fallidos
php artisan queue:retry all

# Limpiar jobs fallidos antiguos
php artisan queue:flush
```

### Consultar Base de Datos

```sql
-- Ver ventas en proceso de anulación
SELECT id, serie, correlativo, sunat_status, sunat_ticket, created_at
FROM sales
WHERE sunat_status = 'PROCESANDO_ANULACION';

-- Ver ventas anuladas exitosamente
SELECT id, serie, correlativo, sunat_status, sunat_voided_at
FROM sales
WHERE sunat_status = 'ANULADO';

-- Ver ventas con errores
SELECT id, serie, correlativo, sunat_status, sunat_response
FROM sales
WHERE sunat_status IN ('ERROR_ANULACION', 'ERROR_TICKET', 'FAILED_ANULACION');

-- Ver jobs en cola (si usas database)
SELECT * FROM jobs ORDER BY created_at DESC LIMIT 10;

-- Ver jobs fallidos
SELECT * FROM failed_jobs ORDER BY failed_at DESC LIMIT 10;
```

## 🛠️ Configuración de Supervisor (PRODUCCIÓN)

Para mantener el worker corriendo permanentemente en producción, usa Supervisor:

### Instalar Supervisor (Ubuntu/Debian)

```bash
sudo apt-get install supervisor
```

### Crear Configuración

Crea el archivo `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker-hsgestion]
process_name=%(program_name)s_%(process_num)02d
command=php /ruta/completa/a/backend-hsgestion/artisan queue:work --sleep=3 --tries=5 --timeout=120 --max-jobs=1000
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/ruta/completa/a/backend-hsgestion/storage/logs/worker.log
stopwaitsecs=3600
```

### Iniciar Supervisor

```bash
# Recargar configuración
sudo supervisorctl reread
sudo supervisorctl update

# Iniciar workers
sudo supervisorctl start laravel-worker-hsgestion:*

# Ver estado
sudo supervisorctl status

# Ver logs en tiempo real
sudo supervisorctl tail -f laravel-worker-hsgestion:laravel-worker-hsgestion_00 stdout
```

### Comandos Útiles de Supervisor

```bash
# Reiniciar workers (después de actualizar código)
sudo supervisorctl restart laravel-worker-hsgestion:*

# Detener workers
sudo supervisorctl stop laravel-worker-hsgestion:*

# Ver logs
sudo tail -f /ruta/completa/a/backend-hsgestion/storage/logs/worker.log
```

## 🧪 Testing

### Test 1: Desarrollo (Sin Colas Reales)

En `.env`:
```env
QUEUE_CONNECTION=sync
```

Hacer una anulación:
```bash
curl -X POST http://localhost:8000/api/sales/123/status \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

✅ El proceso se ejecutará síncronamente y debería completarse de inmediato.

### Test 2: Producción (Con Colas)

En `.env`:
```env
QUEUE_CONNECTION=database
```

**Terminal 1** - Iniciar worker:
```bash
php artisan queue:work --tries=5 --timeout=120
```

**Terminal 2** - Hacer anulación:
```bash
curl -X POST http://localhost:8000/api/sales/123/status \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Terminal 3** - Ver logs:
```bash
tail -f storage/logs/laravel.log | grep "SUNAT"
```

✅ Deberías ver la respuesta inmediata con el ticket, y luego el job procesándose en el worker.

### Test 3: Consultar Estado

```bash
curl -X GET http://localhost:8000/api/sales/123/sunat-status \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## ⚠️ Consideraciones Importantes

### 1. Delay Inicial
El Job espera **30 segundos** antes de consultar el ticket por primera vez. Esto se puede ajustar en `SalesSunatService.php`:

```php
// Cambiar el delay aquí (línea ~353)
->delay(now()->addSeconds(30)); // Cambiar a 60, 120, etc.
```

### 2. Reintentos
El Job se reintenta automáticamente con backoff exponencial:
- Intento 1: Inmediato
- Intento 2: +30 segundos
- Intento 3: +60 segundos
- Intento 4: +120 segundos
- Intento 5: +240 segundos
- Intento 6: +480 segundos (último)

### 3. Workers en Producción
**NUNCA** uses `php artisan queue:work` directamente en producción sin Supervisor. Si el proceso muere, los jobs se quedarán sin procesar.

### 4. Actualización de Código
Cuando despliegues nuevo código, SIEMPRE reinicia los workers:

```bash
# Con Supervisor
sudo supervisorctl restart laravel-worker-hsgestion:*

# Sin Supervisor (no recomendado)
# Mata el proceso y vuelve a iniciarlo
```

### 5. Limpieza de Jobs Antiguos
Configura un cron para limpiar jobs antiguos:

```bash
# En crontab -e
0 2 * * * cd /ruta/a/backend-hsgestion && php artisan queue:prune-failed --hours=48
```

## 🔐 Seguridad

- ✅ El token de SUNAT se pasa de forma segura al job (no se expone en logs)
- ✅ Las respuestas se almacenan en la base de datos para auditoría
- ✅ Los logs NO incluyen información sensible (tokens, passwords, etc.)
- ✅ Solo usuarios con rol "Gerencia" pueden anular documentos (validación existente)

## 🎨 Integración con Frontend

### Flujo Recomendado

1. **Usuario hace click en "Anular"**
   ```javascript
   const response = await axios.post('/api/sales/123/status');
   
   if (response.data.async) {
     // Es asíncrono, mostrar mensaje al usuario
     showNotification('La anulación se está procesando en SUNAT');
     
     // Guardar el ticket para consultar después
     const ticket = response.data.ticket;
     
     // Opcional: Iniciar polling cada 10 segundos
     const interval = setInterval(async () => {
       const status = await axios.get('/api/sales/123/sunat-status');
       
       if (status.data.sunat_status === 'ANULADO') {
         clearInterval(interval);
         showSuccess('Documento anulado exitosamente');
         refreshTable();
       } else if (status.data.sunat_status.includes('ERROR')) {
         clearInterval(interval);
         showError('Error al anular: ' + status.data.sunat_response.error);
       }
     }, 10000); // cada 10 segundos
   } else {
     // Es síncrono (legacy), ya está anulado
     showSuccess('Documento anulado correctamente');
     refreshTable();
   }
   ```

2. **Mostrar estado en la tabla**
   ```javascript
   // En tu componente de tabla
   function getStatusBadge(sale) {
     switch(sale.sunat_status) {
       case 'PROCESANDO_ANULACION':
         return '<span class="badge bg-warning">Procesando...</span>';
       case 'ANULADO':
         return '<span class="badge bg-success">Anulado</span>';
       case 'ERROR_ANULACION':
         return '<span class="badge bg-danger">Error</span>';
       default:
         return '<span class="badge bg-secondary">-</span>';
     }
   }
   ```

## 🚀 Mejoras Futuras (Opcional)

### 1. WebSockets para Notificaciones en Tiempo Real
Usar Laravel Echo + Pusher/Socket.io para notificar al frontend cuando el job termine:

```php
// En ProcessSunatVoidedTicket.php
use App\Events\SunatVoidedProcessed;

event(new SunatVoidedProcessed($sale, $result));
```

### 2. Laravel Horizon
Dashboard visual para monitorear colas:

```bash
composer require laravel/horizon
php artisan horizon:install
php artisan horizon
```

### 3. Notificaciones por Email
Notificar al usuario cuando la anulación se complete:

```php
Mail::to($user->email)->send(new SunatVoidedNotification($sale));
```

### 4. API de Consulta Masiva
Endpoint para consultar estado de múltiples ventas:

```php
Route::post('/sales/sunat-status/bulk', [SaleController::class, 'checkBulkSunatStatus']);
```

## 🆘 Troubleshooting

### Problema: El Job no se ejecuta

**Solución:**
```bash
# 1. Verificar que el worker esté corriendo
ps aux | grep "queue:work"

# 2. Verificar configuración de cola
php artisan queue:monitor

# 3. Ver jobs en la tabla (si usas database)
php artisan tinker
>>> DB::table('jobs')->count();

# 4. Reiniciar worker
sudo supervisorctl restart laravel-worker-hsgestion:*
```

### Problema: Jobs quedan en "failed"

**Solución:**
```bash
# 1. Ver el error
php artisan queue:failed

# 2. Revisar logs
tail -100 storage/logs/laravel.log

# 3. Reintentar
php artisan queue:retry all
```

### Problema: El estado no se actualiza

**Solución:**
```bash
# 1. Verificar que los campos existan
php artisan tinker
>>> Schema::hasColumn('sales', 'sunat_status');

# 2. Verificar la migración
php artisan migrate:status

# 3. Revisar el job
tail -f storage/logs/laravel.log | grep "ProcessSunatVoidedTicket"
```

### Problema: Timeout del Job

**Solución:**
Aumentar el timeout en `ProcessSunatVoidedTicket.php`:

```php
public $timeout = 240; // Cambiar de 120 a 240 segundos
```

Y también al iniciar el worker:
```bash
php artisan queue:work --timeout=240
```

## 📞 Soporte

Si tienes problemas, revisa en este orden:

1. **Logs de Laravel**: `storage/logs/laravel.log`
2. **Tabla de jobs fallidos**: `SELECT * FROM failed_jobs`
3. **Estado en la base de datos**: `SELECT * FROM sales WHERE id = X`
4. **Worker activo**: `ps aux | grep queue:work`
5. **Configuración de colas**: `.env` → `QUEUE_CONNECTION`

---

**Proyecto**: Sistema de Gestión HS  
**Fecha**: 07 de Febrero 2026  
**Versión**: 1.0.0  
**Autor**: Equipo de Desarrollo