# ✅ CHECKLIST - Implementación de Colas para Anulación SUNAT

## 📋 Lista de Verificación

### ✨ FASE 1: PREPARACIÓN (5 min)

- [ ] **1.1** Hacer backup de la base de datos
- [ ] **1.2** Hacer commit del código actual
- [ ] **1.3** Verificar que Laravel está funcionando correctamente
- [ ] **1.4** Verificar versión de PHP (mínimo 8.0)
- [ ] **1.5** Verificar permisos de escritura en `storage/logs`

---

### 📦 FASE 2: INSTALACIÓN DE ARCHIVOS (2 min)

- [ ] **2.1** Verificar que existe `app/Jobs/ProcessSunatVoidedTicket.php`
- [ ] **2.2** Verificar que existe la migración en `database/migrations/`
- [ ] **2.3** Verificar cambios en `app/Services/SalesSunatService.php`
- [ ] **2.4** Verificar cambios en `app/Modules/Sale/Infrastructure/Controllers/SaleController.php`

---

### 🗄️ FASE 3: BASE DE DATOS (3 min)

- [ ] **3.1** Ejecutar migración:
  ```bash
  php artisan migrate
  ```

- [ ] **3.2** Verificar que los campos fueron creados:
  ```sql
  DESCRIBE sales;
  -- Debe mostrar: sunat_status, sunat_ticket, sunat_response, sunat_voided_at
  ```

- [ ] **3.3** *(Opcional)* Crear tablas de jobs si usarás `database` como driver:
  ```bash
  php artisan queue:table
  php artisan queue:failed-table
  php artisan migrate
  ```

---

### ⚙️ FASE 4: CONFIGURACIÓN (5 min)

- [ ] **4.1** Editar archivo `.env` y configurar driver de colas:
  - [ ] **Desarrollo**: `QUEUE_CONNECTION=sync`
  - [ ] **Producción Database**: `QUEUE_CONNECTION=database`
  - [ ] **Producción Redis**: `QUEUE_CONNECTION=redis`

- [ ] **4.2** Si usas Redis, verificar configuración:
  ```env
  REDIS_HOST=127.0.0.1
  REDIS_PASSWORD=null
  REDIS_PORT=6379
  ```

- [ ] **4.3** Limpiar caché de configuración:
  ```bash
  php artisan config:clear
  php artisan cache:clear
  ```

---

### 🛣️ FASE 5: RUTAS (2 min)

- [ ] **5.1** Agregar ruta para consultar estado en `routes/api.php`:
  ```php
  Route::get('/sales/{id}/sunat-status', [SaleController::class, 'checkSunatVoidedStatus']);
  ```

- [ ] **5.2** Verificar que la ruta existe:
  ```bash
  php artisan route:list | grep sunat-status
  ```

---

### 🧪 FASE 6: TESTING BÁSICO (10 min)

#### Test en Desarrollo (Síncrono)

- [ ] **6.1** Configurar `.env`:
  ```env
  QUEUE_CONNECTION=sync
  ```

- [ ] **6.2** Intentar anular una venta de prueba

- [ ] **6.3** Verificar que se ejecuta inmediatamente

- [ ] **6.4** Revisar logs:
  ```bash
  tail -f storage/logs/laravel.log
  ```

#### Test en Producción (Asíncrono)

- [ ] **6.5** Configurar `.env`:
  ```env
  QUEUE_CONNECTION=database
  ```

- [ ] **6.6** Iniciar worker en una terminal:
  ```bash
  php artisan queue:work --tries=5 --timeout=120
  ```

- [ ] **6.7** En otra terminal, intentar anular una venta

- [ ] **6.8** Verificar respuesta inmediata con ticket

- [ ] **6.9** Observar el worker procesando el job

- [ ] **6.10** Verificar que se actualizó la base de datos:
  ```sql
  SELECT id, sunat_status, sunat_ticket, sunat_response 
  FROM sales 
  WHERE id = [ID_DE_PRUEBA];
  ```

---

### 🔍 FASE 7: VERIFICACIÓN DE FUNCIONALIDAD (5 min)

- [ ] **7.1** Anular una venta y verificar respuesta del endpoint

- [ ] **7.2** Consultar estado con el nuevo endpoint:
  ```bash
  curl -X GET http://localhost:8000/api/sales/123/sunat-status
  ```

- [ ] **7.3** Verificar campos en la base de datos:
  - [ ] `sunat_status` tiene valor correcto
  - [ ] `sunat_ticket` contiene el ticket
  - [ ] `sunat_response` contiene JSON de respuesta
  - [ ] `sunat_voided_at` tiene timestamp (si está anulado)

- [ ] **7.4** Verificar campos legacy (retrocompatibilidad):
  - [ ] `estado_sunat` = 'ANULADA'
  - [ ] `fecha_baja_sunat` tiene fecha
  - [ ] `hora_baja_sunat` tiene hora

- [ ] **7.5** Revisar logs para errores:
  ```bash
  grep -i "error" storage/logs/laravel.log | tail -20
  ```

---

### 🚀 FASE 8: PRODUCCIÓN (15 min)

#### Configurar Supervisor

- [ ] **8.1** Instalar Supervisor:
  ```bash
  sudo apt-get update
  sudo apt-get install supervisor
  ```

- [ ] **8.2** Crear archivo de configuración:
  ```bash
  sudo nano /etc/supervisor/conf.d/laravel-worker-hsgestion.conf
  ```

- [ ] **8.3** Copiar contenido (ver `QUICK_START_QUEUE.md`)

- [ ] **8.4** Actualizar rutas en el archivo de configuración

- [ ] **8.5** Recargar Supervisor:
  ```bash
  sudo supervisorctl reread
  sudo supervisorctl update
  ```

- [ ] **8.6** Iniciar workers:
  ```bash
  sudo supervisorctl start laravel-worker-hsgestion:*
  ```

- [ ] **8.7** Verificar estado:
  ```bash
  sudo supervisorctl status
  ```

- [ ] **8.8** Ver logs del worker:
  ```bash
  sudo tail -f storage/logs/worker.log
  ```

#### Configurar Limpieza Automática (Cron)

- [ ] **8.9** Editar crontab:
  ```bash
  crontab -e
  ```

- [ ] **8.10** Agregar línea para limpiar jobs antiguos:
  ```
  0 2 * * * cd /ruta/a/backend-hsgestion && php artisan queue:prune-failed --hours=48
  ```

---

### 🎨 FASE 9: INTEGRACIÓN FRONTEND (30 min)

- [ ] **9.1** Actualizar código del botón "Anular"

- [ ] **9.2** Implementar detección de respuesta asíncrona (`response.data.async`)

- [ ] **9.3** Implementar sistema de polling

- [ ] **9.4** Implementar actualización de badge de estado

- [ ] **9.5** Implementar notificaciones al usuario

- [ ] **9.6** Probar flujo completo en frontend

- [ ] **9.7** Manejar caso de recarga de página (recuperar estados pendientes)

---

### 📊 FASE 10: MONITOREO (5 min)

- [ ] **10.1** Configurar acceso a logs:
  ```bash
  # Alias útil en ~/.bashrc
  alias sunat-logs="tail -f /ruta/a/storage/logs/laravel.log | grep SUNAT"
  ```

- [ ] **10.2** *(Opcional)* Instalar Laravel Horizon para dashboard:
  ```bash
  composer require laravel/horizon
  php artisan horizon:install
  ```

- [ ] **10.3** Configurar alertas de jobs fallidos (email, Slack, etc.)

- [ ] **10.4** Documentar ubicación de logs para el equipo

---

### 🔒 FASE 11: SEGURIDAD (5 min)

- [ ] **11.1** Verificar que solo usuarios autorizados pueden anular (rol "Gerencia")

- [ ] **11.2** Verificar que el token de SUNAT no se expone en logs

- [ ] **11.3** Verificar permisos de archivos:
  ```bash
  chmod -R 775 storage
  chmod -R 775 bootstrap/cache
  ```

- [ ] **11.4** Verificar que `.env` no está en el repositorio

---

### 📝 FASE 12: DOCUMENTACIÓN (5 min)

- [ ] **12.1** Leer `SUNAT_QUEUE_SETUP.md`

- [ ] **12.2** Leer `QUICK_START_QUEUE.md`

- [ ] **12.3** Compartir documentación con el equipo

- [ ] **12.4** Documentar proceso de deployment

- [ ] **12.5** Crear guía de troubleshooting para el equipo

---

### ✅ FASE 13: VALIDACIÓN FINAL (10 min)

#### Pruebas Funcionales

- [ ] **13.1** Anular 5 ventas diferentes

- [ ] **13.2** Verificar que todas se procesan correctamente

- [ ] **13.3** Forzar un error (ticket inválido) y verificar manejo

- [ ] **13.4** Detener el worker y verificar que los jobs se encolan

- [ ] **13.5** Reiniciar el worker y verificar que procesa jobs pendientes

#### Verificación de Estados

- [ ] **13.6** Verificar estado `PROCESANDO_ANULACION`

- [ ] **13.7** Verificar estado `ANULADO`

- [ ] **13.8** Verificar estado `ERROR_ANULACION` (si aplica)

- [ ] **13.9** Verificar jobs fallidos en la tabla `failed_jobs`

#### Performance

- [ ] **13.10** Verificar tiempo de respuesta del endpoint (debe ser < 2 segundos)

- [ ] **13.11** Verificar que el worker no consume excesiva memoria

- [ ] **13.12** Verificar que los logs no crecen descontroladamente

---

### 🎯 FASE 14: DEPLOYMENT (5 min)

- [ ] **14.1** Hacer commit de todos los cambios

- [ ] **14.2** Crear tag de versión:
  ```bash
  git tag -a v1.0.0-sunat-queue -m "Implementación de colas para anulación SUNAT"
  git push origin v1.0.0-sunat-queue
  ```

- [ ] **14.3** Hacer merge a la rama de producción

- [ ] **14.4** Deployar en servidor de producción

- [ ] **14.5** Ejecutar migraciones en producción:
  ```bash
  php artisan migrate --force
  ```

- [ ] **14.6** Reiniciar workers en producción:
  ```bash
  sudo supervisorctl restart laravel-worker-hsgestion:*
  ```

- [ ] **14.7** Verificar que todo funciona en producción

---

### 📢 FASE 15: COMUNICACIÓN (5 min)

- [ ] **15.1** Notificar al equipo sobre el cambio

- [ ] **15.2** Explicar nuevo flujo de anulación

- [ ] **15.3** Compartir documentación

- [ ] **15.4** Programar sesión de Q&A si es necesario

- [ ] **15.5** Actualizar manual de usuario

---

## 🐛 TROUBLESHOOTING RÁPIDO

### ❌ Problema: Jobs no se ejecutan

**Solución:**
```bash
# Verificar que el worker está corriendo
ps aux | grep "queue:work"

# Si no está, iniciarlo
php artisan queue:work --tries=5 --timeout=120

# Con Supervisor
sudo supervisorctl status
sudo supervisorctl start laravel-worker-hsgestion:*
```

### ❌ Problema: Error "Undefined column sunat_status"

**Solución:**
```bash
# Verificar que la migración se ejecutó
php artisan migrate:status

# Ejecutar migración
php artisan migrate

# Verificar en BD
DESCRIBE sales;
```

### ❌ Problema: Jobs quedan en "failed"

**Solución:**
```bash
# Ver error
php artisan queue:failed

# Reintentar
php artisan queue:retry all

# Ver logs
tail -50 storage/logs/laravel.log
```

### ❌ Problema: Respuesta lenta del endpoint

**Solución:**
- Verificar que `QUEUE_CONNECTION` está configurado correctamente
- Verificar que no está esperando respuesta síncrona de SUNAT
- Revisar logs para identificar cuellos de botella

---

## 📊 MÉTRICAS DE ÉXITO

- ✅ Tiempo de respuesta del endpoint < 2 segundos
- ✅ Jobs procesados exitosamente > 95%
- ✅ Sin quejas de usuarios sobre lentitud
- ✅ Workers corriendo sin interrupciones
- ✅ Logs sin errores críticos

---

## 📚 RECURSOS

- **Documentación Laravel Queues**: https://laravel.com/docs/queues
- **Documentación Supervisor**: http://supervisord.org/
- **Documentación del proyecto**: Ver archivos `.md` en la raíz

---

## ✅ SIGN-OFF

- [ ] **Developer**: _________________________ Fecha: _________
- [ ] **QA**: ________________________________ Fecha: _________
- [ ] **DevOps**: ____________________________ Fecha: _________
- [ ] **Product Owner**: _____________________ Fecha: _________

---

**Proyecto**: Sistema de Gestión HS  
**Fecha de creación**: 07 de Febrero 2026  
**Versión**: 1.0.0  
**Estado**: ✅ COMPLETO