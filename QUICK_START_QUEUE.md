# 🚀 Guía Rápida - Implementación de Colas para Anulación SUNAT

## ⏱️ Tiempo estimado: 5 minutos

## 📋 Pasos de Instalación

### 1️⃣ Ejecutar Migración (1 min)

```bash
cd backend-hsgestion
php artisan migrate
```

✅ Esto agrega los campos: `sunat_status`, `sunat_ticket`, `sunat_response`, `sunat_voided_at`

---

### 2️⃣ Configurar Colas (1 min)

Edita tu archivo `.env`:

#### OPCIÓN A: Desarrollo (sin colas reales)
```env
QUEUE_CONNECTION=sync
```
👉 **NO necesitas workers**, todo se ejecuta síncronamente.

#### OPCIÓN B: Producción con Database
```env
QUEUE_CONNECTION=database
```

Luego ejecuta:
```bash
php artisan queue:table
php artisan queue:failed-table
php artisan migrate
```

#### OPCIÓN C: Producción con Redis (RECOMENDADO)
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

### 3️⃣ Agregar Ruta para Consultar Estado (1 min)

Edita tu archivo de rutas (ej: `routes/api.php`):

```php
use App\Modules\Sale\Infrastructure\Controllers\SaleController;

// Ruta nueva para consultar estado de anulación
Route::get('/sales/{id}/sunat-status', [SaleController::class, 'checkSunatVoidedStatus']);
```

---

### 4️⃣ Iniciar Worker (SOLO PRODUCCIÓN) (1 min)

```bash
# Iniciar worker básico
php artisan queue:work --tries=5 --timeout=120

# O con más opciones
php artisan queue:work --tries=5 --timeout=120 --sleep=3 --max-jobs=1000
```

⚠️ **IMPORTANTE**: En producción usa Supervisor (ver abajo).

---

## ✅ ¡Listo! Ya está funcionando

### Probar la Anulación

**Request actual (no cambia):**
```bash
POST /api/sales/{id}/status
```

**Respuesta NUEVA (con colas):**
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

### Consultar Estado

**Request nuevo:**
```bash
GET /api/sales/{id}/sunat-status
```

**Response:**
```json
{
  "status": true,
  "sale_id": 123,
  "sunat_status": "ANULADO",
  "sunat_ticket": "1234567890",
  "sunat_response": { ... },
  "sunat_voided_at": "2026-02-07 14:30:45"
}
```

---

## 📊 Estados Posibles

| Estado | Significado | Usuario debe... |
|--------|-------------|----------------|
| `PROCESANDO_ANULACION` | ⏳ En proceso | Esperar o consultar más tarde |
| `ANULADO` | ✅ Exitoso | Nada, ya está anulado |
| `ERROR_ANULACION` | ❌ SUNAT rechazó | Revisar error en `sunat_response` |
| `ERROR_TICKET` | ⚠️ Error técnico | Consultar logs |
| `FAILED_ANULACION` | 🔴 Falló todo | Contactar soporte |

---

## 🔧 Supervisor (Producción - Recomendado)

### Instalar
```bash
sudo apt-get install supervisor
```

### Crear archivo `/etc/supervisor/conf.d/laravel-worker-hsgestion.conf`
```ini
[program:laravel-worker-hsgestion]
process_name=%(program_name)s_%(process_num)02d
command=php /ruta/completa/a/backend-hsgestion/artisan queue:work --sleep=3 --tries=5 --timeout=120
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

### Iniciar
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker-hsgestion:*
```

### Ver estado
```bash
sudo supervisorctl status
```

---

## 🐛 Debugging

### Ver logs en tiempo real
```bash
tail -f storage/logs/laravel.log | grep "SUNAT"
```

### Ver jobs en cola
```bash
php artisan queue:monitor
```

### Ver jobs fallidos
```bash
php artisan queue:failed
```

### Reintentar jobs fallidos
```bash
php artisan queue:retry all
```

### Ver estado en base de datos
```sql
SELECT id, serie, correlativo, sunat_status, sunat_ticket 
FROM sales 
WHERE sunat_status = 'PROCESANDO_ANULACION';
```

---

## ⚠️ Importante

1. **Desarrollo**: Usa `QUEUE_CONNECTION=sync` (no necesitas workers)
2. **Producción**: Usa `database` o `redis` + Supervisor
3. **Al actualizar código**: Reinicia workers con `supervisorctl restart`
4. **Delay inicial**: El job espera 30 segundos antes de consultar SUNAT
5. **Reintentos**: 5 intentos con backoff exponencial

---

## 📚 Documentación Completa

Para más detalles, ver: `SUNAT_QUEUE_SETUP.md`

---

## 🎯 Diferencia Clave

### ANTES (Bloqueante)
```
Usuario → Click Anular → [Espera 20-60 segundos] → Respuesta
```

### AHORA (No Bloqueante)
```
Usuario → Click Anular → [Respuesta inmediata con ticket] → Continúa trabajando
Background → Job procesa en 30s → Actualiza BD
```

---

**¿Dudas?** Revisa `SUNAT_QUEUE_SETUP.md` o los logs en `storage/logs/laravel.log`
