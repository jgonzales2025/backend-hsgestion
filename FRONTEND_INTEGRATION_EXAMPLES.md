# 🎨 Ejemplos de Integración Frontend - Colas SUNAT

## 📋 Descripción

Este documento contiene ejemplos prácticos de cómo integrar el sistema de colas de anulación SUNAT en tu frontend (Vue.js, React, o JavaScript vanilla).

---

## 🔄 Flujo Completo de Usuario

```
1. Usuario hace clic en "Anular"
2. Frontend envía request a backend
3. Backend responde inmediatamente con ticket
4. Frontend muestra notificación y activa polling
5. Cada 10 segundos consulta el estado
6. Cuando termina, muestra resultado y detiene polling
```

---

## 📝 Ejemplo 1: Vue.js 3 (Composition API)

### Componente de Tabla de Ventas

```vue
<template>
  <div>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Serie-Número</th>
          <th>Estado SUNAT</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="sale in sales" :key="sale.id">
          <td>{{ sale.id }}</td>
          <td>{{ sale.serie }}-{{ sale.correlativo }}</td>
          <td>
            <StatusBadge :status="sale.sunat_status" />
          </td>
          <td>
            <button 
              @click="anularVenta(sale.id)"
              :disabled="isProcessing(sale.id)"
              class="btn btn-danger"
            >
              {{ getButtonText(sale.id) }}
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const sales = ref([]);
const processingIds = ref(new Set());
const pollingIntervals = ref(new Map());

// Anular venta
const anularVenta = async (saleId) => {
  try {
    processingIds.value.add(saleId);
    
    const response = await axios.post(`/api/sales/${saleId}/status`, {
      // datos necesarios según tu API
    });

    if (response.data.async) {
      // Respuesta asíncrona - iniciar polling
      showNotification('info', response.data.message);
      startPolling(saleId, response.data.ticket);
    } else {
      // Respuesta síncrona (legacy)
      showNotification('success', response.data.message);
      processingIds.value.delete(saleId);
      await reloadSales();
    }
  } catch (error) {
    showNotification('error', error.response?.data?.message || 'Error al anular');
    processingIds.value.delete(saleId);
  }
};

// Iniciar polling para consultar estado
const startPolling = (saleId, ticket) => {
  // Evitar múltiples pollings para la misma venta
  if (pollingIntervals.value.has(saleId)) {
    return;
  }

  let attempts = 0;
  const maxAttempts = 60; // 10 minutos máximo (60 * 10 segundos)

  const intervalId = setInterval(async () => {
    attempts++;

    try {
      const response = await axios.get(`/api/sales/${saleId}/sunat-status`);
      const status = response.data.sunat_status;

      // Actualizar la venta en la lista
      updateSaleInList(saleId, response.data);

      // Si terminó (exitoso o con error), detener polling
      if (status === 'ANULADO') {
        stopPolling(saleId);
        showNotification('success', '✅ Documento anulado exitosamente');
        processingIds.value.delete(saleId);
        await reloadSales();
      } else if (status && status.includes('ERROR')) {
        stopPolling(saleId);
        const errorMsg = response.data.sunat_response?.error || 'Error desconocido';
        showNotification('error', `❌ Error al anular: ${errorMsg}`);
        processingIds.value.delete(saleId);
      } else if (status === 'FAILED_ANULACION') {
        stopPolling(saleId);
        showNotification('error', '❌ El proceso de anulación falló después de varios intentos');
        processingIds.value.delete(saleId);
      }

      // Si llegó al máximo de intentos, detener
      if (attempts >= maxAttempts) {
        stopPolling(saleId);
        showNotification('warning', '⏱️ Tiempo de espera agotado. Consulte más tarde.');
        processingIds.value.delete(saleId);
      }
    } catch (error) {
      console.error('Error al consultar estado:', error);
      // No detener el polling por un error puntual, seguir intentando
    }
  }, 10000); // Cada 10 segundos

  pollingIntervals.value.set(saleId, intervalId);
};

// Detener polling
const stopPolling = (saleId) => {
  const intervalId = pollingIntervals.value.get(saleId);
  if (intervalId) {
    clearInterval(intervalId);
    pollingIntervals.value.delete(saleId);
  }
};

// Actualizar venta en la lista
const updateSaleInList = (saleId, newData) => {
  const index = sales.value.findIndex(s => s.id === saleId);
  if (index !== -1) {
    sales.value[index] = { ...sales.value[index], ...newData };
  }
};

// Helpers
const isProcessing = (saleId) => {
  return processingIds.value.has(saleId);
};

const getButtonText = (saleId) => {
  return isProcessing(saleId) ? 'Procesando...' : 'Anular';
};

const reloadSales = async () => {
  // Recargar lista de ventas
  const response = await axios.get('/api/sales');
  sales.value = response.data;
};

const showNotification = (type, message) => {
  // Implementa tu sistema de notificaciones
  console.log(`[${type.toUpperCase()}] ${message}`);
  // Ejemplo: Toastify, SweetAlert, etc.
};

// Limpiar al desmontar el componente
onUnmounted(() => {
  pollingIntervals.value.forEach((intervalId) => {
    clearInterval(intervalId);
  });
});

onMounted(() => {
  reloadSales();
});
</script>
```

### Componente de Badge de Estado

```vue
<template>
  <span :class="badgeClass">
    {{ badgeText }}
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  status: String
});

const badgeClass = computed(() => {
  const baseClass = 'badge ';
  switch (props.status) {
    case 'PROCESANDO_ANULACION':
      return baseClass + 'bg-warning text-dark';
    case 'ANULADO':
      return baseClass + 'bg-success';
    case 'ERROR_ANULACION':
    case 'ERROR_TICKET':
    case 'FAILED_ANULACION':
      return baseClass + 'bg-danger';
    default:
      return baseClass + 'bg-secondary';
  }
});

const badgeText = computed(() => {
  switch (props.status) {
    case 'PROCESANDO_ANULACION':
      return '⏳ Procesando';
    case 'ANULADO':
      return '✅ Anulado';
    case 'ERROR_ANULACION':
      return '❌ Error';
    case 'ERROR_TICKET':
      return '⚠️ Error Técnico';
    case 'FAILED_ANULACION':
      return '🔴 Fallido';
    default:
      return '-';
  }
});
</script>
```

---

## 📝 Ejemplo 2: React (Hooks)

```jsx
import React, { useState, useEffect, useCallback } from 'react';
import axios from 'axios';

const SalesTable = () => {
  const [sales, setSales] = useState([]);
  const [processingIds, setProcessingIds] = useState(new Set());
  const [pollingIntervals, setPollingIntervals] = useState(new Map());

  // Anular venta
  const anularVenta = async (saleId) => {
    try {
      setProcessingIds(prev => new Set(prev).add(saleId));

      const response = await axios.post(`/api/sales/${saleId}/status`);

      if (response.data.async) {
        // Respuesta asíncrona
        showNotification('info', response.data.message);
        startPolling(saleId, response.data.ticket);
      } else {
        // Respuesta síncrona
        showNotification('success', response.data.message);
        setProcessingIds(prev => {
          const newSet = new Set(prev);
          newSet.delete(saleId);
          return newSet;
        });
        loadSales();
      }
    } catch (error) {
      showNotification('error', error.response?.data?.message || 'Error al anular');
      setProcessingIds(prev => {
        const newSet = new Set(prev);
        newSet.delete(saleId);
        return newSet;
      });
    }
  };

  // Iniciar polling
  const startPolling = useCallback((saleId, ticket) => {
    if (pollingIntervals.has(saleId)) return;

    let attempts = 0;
    const maxAttempts = 60;

    const intervalId = setInterval(async () => {
      attempts++;

      try {
        const response = await axios.get(`/api/sales/${saleId}/sunat-status`);
        const status = response.data.sunat_status;

        // Actualizar venta en la lista
        setSales(prev => 
          prev.map(sale => 
            sale.id === saleId ? { ...sale, ...response.data } : sale
          )
        );

        if (status === 'ANULADO') {
          stopPolling(saleId);
          showNotification('success', '✅ Documento anulado exitosamente');
          setProcessingIds(prev => {
            const newSet = new Set(prev);
            newSet.delete(saleId);
            return newSet;
          });
          loadSales();
        } else if (status && status.includes('ERROR')) {
          stopPolling(saleId);
          showNotification('error', `❌ Error al anular`);
          setProcessingIds(prev => {
            const newSet = new Set(prev);
            newSet.delete(saleId);
            return newSet;
          });
        }

        if (attempts >= maxAttempts) {
          stopPolling(saleId);
          showNotification('warning', '⏱️ Tiempo agotado');
          setProcessingIds(prev => {
            const newSet = new Set(prev);
            newSet.delete(saleId);
            return newSet;
          });
        }
      } catch (error) {
        console.error('Error al consultar estado:', error);
      }
    }, 10000);

    setPollingIntervals(prev => new Map(prev).set(saleId, intervalId));
  }, [pollingIntervals]);

  // Detener polling
  const stopPolling = useCallback((saleId) => {
    const intervalId = pollingIntervals.get(saleId);
    if (intervalId) {
      clearInterval(intervalId);
      setPollingIntervals(prev => {
        const newMap = new Map(prev);
        newMap.delete(saleId);
        return newMap;
      });
    }
  }, [pollingIntervals]);

  // Cargar ventas
  const loadSales = async () => {
    const response = await axios.get('/api/sales');
    setSales(response.data);
  };

  const showNotification = (type, message) => {
    console.log(`[${type.toUpperCase()}] ${message}`);
    // Implementa tu sistema de notificaciones
  };

  // Limpiar al desmontar
  useEffect(() => {
    return () => {
      pollingIntervals.forEach(intervalId => clearInterval(intervalId));
    };
  }, [pollingIntervals]);

  useEffect(() => {
    loadSales();
  }, []);

  return (
    <table className="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Serie-Número</th>
          <th>Estado SUNAT</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        {sales.map(sale => (
          <tr key={sale.id}>
            <td>{sale.id}</td>
            <td>{sale.serie}-{sale.correlativo}</td>
            <td>
              <StatusBadge status={sale.sunat_status} />
            </td>
            <td>
              <button
                onClick={() => anularVenta(sale.id)}
                disabled={processingIds.has(sale.id)}
                className="btn btn-danger"
              >
                {processingIds.has(sale.id) ? 'Procesando...' : 'Anular'}
              </button>
            </td>
          </tr>
        ))}
      </tbody>
    </table>
  );
};

const StatusBadge = ({ status }) => {
  const getBadgeClass = () => {
    switch (status) {
      case 'PROCESANDO_ANULACION':
        return 'badge bg-warning text-dark';
      case 'ANULADO':
        return 'badge bg-success';
      case 'ERROR_ANULACION':
      case 'ERROR_TICKET':
      case 'FAILED_ANULACION':
        return 'badge bg-danger';
      default:
        return 'badge bg-secondary';
    }
  };

  const getBadgeText = () => {
    switch (status) {
      case 'PROCESANDO_ANULACION':
        return '⏳ Procesando';
      case 'ANULADO':
        return '✅ Anulado';
      case 'ERROR_ANULACION':
        return '❌ Error';
      case 'ERROR_TICKET':
        return '⚠️ Error Técnico';
      case 'FAILED_ANULACION':
        return '🔴 Fallido';
      default:
        return '-';
    }
  };

  return <span className={getBadgeClass()}>{getBadgeText()}</span>;
};

export default SalesTable;
```

---

## 📝 Ejemplo 3: JavaScript Vanilla

```javascript
// sales-manager.js
class SalesManager {
  constructor() {
    this.processingIds = new Set();
    this.pollingIntervals = new Map();
    this.maxPollingAttempts = 60; // 10 minutos
  }

  // Anular venta
  async anularVenta(saleId) {
    try {
      this.processingIds.add(saleId);
      this.updateButtonState(saleId, true);

      const response = await fetch(`/api/sales/${saleId}/status`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${this.getToken()}`
        }
      });

      const data = await response.json();

      if (data.async) {
        // Respuesta asíncrona
        this.showNotification('info', data.message);
        this.startPolling(saleId, data.ticket);
      } else {
        // Respuesta síncrona
        this.showNotification('success', data.message);
        this.processingIds.delete(saleId);
        this.updateButtonState(saleId, false);
        this.reloadTable();
      }
    } catch (error) {
      this.showNotification('error', 'Error al anular el documento');
      this.processingIds.delete(saleId);
      this.updateButtonState(saleId, false);
    }
  }

  // Iniciar polling
  startPolling(saleId, ticket) {
    if (this.pollingIntervals.has(saleId)) {
      return;
    }

    let attempts = 0;

    const intervalId = setInterval(async () => {
      attempts++;

      try {
        const response = await fetch(`/api/sales/${saleId}/sunat-status`, {
          headers: {
            'Authorization': `Bearer ${this.getToken()}`
          }
        });

        const data = await response.json();
        const status = data.sunat_status;

        // Actualizar badge en la tabla
        this.updateStatusBadge(saleId, status);

        // Verificar si terminó
        if (status === 'ANULADO') {
          this.stopPolling(saleId);
          this.showNotification('success', '✅ Documento anulado exitosamente');
          this.processingIds.delete(saleId);
          this.updateButtonState(saleId, false);
          this.reloadTable();
        } else if (status && status.includes('ERROR')) {
          this.stopPolling(saleId);
          this.showNotification('error', '❌ Error al anular el documento');
          this.processingIds.delete(saleId);
          this.updateButtonState(saleId, false);
        } else if (status === 'FAILED_ANULACION') {
          this.stopPolling(saleId);
          this.showNotification('error', '❌ El proceso falló después de varios intentos');
          this.processingIds.delete(saleId);
          this.updateButtonState(saleId, false);
        }

        // Tiempo máximo agotado
        if (attempts >= this.maxPollingAttempts) {
          this.stopPolling(saleId);
          this.showNotification('warning', '⏱️ Tiempo agotado. Consulte el estado más tarde.');
          this.processingIds.delete(saleId);
          this.updateButtonState(saleId, false);
        }
      } catch (error) {
        console.error('Error al consultar estado:', error);
      }
    }, 10000); // Cada 10 segundos

    this.pollingIntervals.set(saleId, intervalId);
  }

  // Detener polling
  stopPolling(saleId) {
    const intervalId = this.pollingIntervals.get(saleId);
    if (intervalId) {
      clearInterval(intervalId);
      this.pollingIntervals.delete(saleId);
    }
  }

  // Actualizar estado del botón
  updateButtonState(saleId, isDisabled) {
    const button = document.querySelector(`button[data-sale-id="${saleId}"]`);
    if (button) {
      button.disabled = isDisabled;
      button.textContent = isDisabled ? 'Procesando...' : 'Anular';
    }
  }

  // Actualizar badge de estado
  updateStatusBadge(saleId, status) {
    const badge = document.querySelector(`#status-badge-${saleId}`);
    if (!badge) return;

    // Limpiar clases
    badge.className = 'badge';

    // Agregar clase según estado
    switch (status) {
      case 'PROCESANDO_ANULACION':
        badge.classList.add('bg-warning', 'text-dark');
        badge.textContent = '⏳ Procesando';
        break;
      case 'ANULADO':
        badge.classList.add('bg-success');
        badge.textContent = '✅ Anulado';
        break;
      case 'ERROR_ANULACION':
      case 'ERROR_TICKET':
      case 'FAILED_ANULACION':
        badge.classList.add('bg-danger');
        badge.textContent = '❌ Error';
        break;
      default:
        badge.classList.add('bg-secondary');
        badge.textContent = '-';
    }
  }

  // Mostrar notificación (implementa según tu librería)
  showNotification(type, message) {
    console.log(`[${type.toUpperCase()}] ${message}`);
    
    // Ejemplo con SweetAlert2
    // Swal.fire({
    //   icon: type,
    //   title: type === 'error' ? 'Error' : 'Información',
    //   text: message
    // });
  }

  // Recargar tabla
  reloadTable() {
    // Implementa según tu sistema
    console.log('Recargando tabla...');
  }

  // Obtener token de autorización
  getToken() {
    return localStorage.getItem('auth_token') || '';
  }

  // Limpiar todos los intervalos
  cleanup() {
    this.pollingIntervals.forEach(intervalId => clearInterval(intervalId));
    this.pollingIntervals.clear();
  }
}

// Uso
const salesManager = new SalesManager();

// En tus event listeners
document.addEventListener('DOMContentLoaded', () => {
  // Agregar event listeners a los botones de anular
  document.querySelectorAll('.btn-anular').forEach(button => {
    button.addEventListener('click', (e) => {
      const saleId = e.target.dataset.saleId;
      salesManager.anularVenta(saleId);
    });
  });
});

// Limpiar al salir de la página
window.addEventListener('beforeunload', () => {
  salesManager.cleanup();
});
```

---

## 📊 Ejemplo 4: jQuery

```javascript
// sales-anulacion.js
$(document).ready(function() {
  const processingIds = new Set();
  const pollingIntervals = new Map();
  const MAX_ATTEMPTS = 60;

  // Event listener para botón anular
  $(document).on('click', '.btn-anular', function() {
    const saleId = $(this).data('sale-id');
    anularVenta(saleId);
  });

  // Anular venta
  function anularVenta(saleId) {
    if (processingIds.has(saleId)) {
      return;
    }

    processingIds.add(saleId);
    updateButton(saleId, true);

    $.ajax({
      url: `/api/sales/${saleId}/status`,
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${getToken()}`
      },
      success: function(response) {
        if (response.async) {
          // Respuesta asíncrona
          showNotification('info', response.message);
          startPolling(saleId, response.ticket);
        } else {
          // Respuesta síncrona
          showNotification('success', response.message);
          processingIds.delete(saleId);
          updateButton(saleId, false);
          reloadTable();
        }
      },
      error: function(xhr) {
        const message = xhr.responseJSON?.message || 'Error al anular';
        showNotification('error', message);
        processingIds.delete(saleId);
        updateButton(saleId, false);
      }
    });
  }

  // Iniciar polling
  function startPolling(saleId, ticket) {
    if (pollingIntervals.has(saleId)) {
      return;
    }

    let attempts = 0;

    const intervalId = setInterval(function() {
      attempts++;

      $.ajax({
        url: `/api/sales/${saleId}/sunat-status`,
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${getToken()}`
        },
        success: function(response) {
          const status = response.sunat_status;

          // Actualizar badge
          updateStatusBadge(saleId, status);

          // Verificar si terminó
          if (status === 'ANULADO') {
            stopPolling(saleId);
            showNotification('success', '✅ Documento anulado exitosamente');
            processingIds.delete(saleId);
            updateButton(saleId, false);
            reloadTable();
          } else if (status && status.includes('ERROR')) {
            stopPolling(saleId);
            showNotification('error', '❌ Error al anular');
            processingIds.delete(saleId);
            updateButton(saleId, false);
          }

          // Tiempo máximo
          if (attempts >= MAX_ATTEMPTS) {
            stopPolling(saleId);
            showNotification('warning', '⏱️ Tiempo agotado');
            processingIds.delete(saleId);
            updateButton(saleId, false);
          }
        },
        error: function(error) {
          console.error('Error al consultar estado:', error);
        }
      });
    }, 10000);

    pollingIntervals.set(saleId, intervalId);
  }

  // Detener polling
  function stopPolling(saleId) {
    const intervalId = pollingIntervals.get(saleId);
    if (intervalId) {
      clearInterval(intervalId);
      pollingIntervals.delete(saleId);
    }
  }

  // Actualizar botón
  function updateButton(saleId, isDisabled) {
    const $button = $(`.btn-anular[data-sale-id="${saleId}"]`);
    $button.prop('disabled', isDisabled);
    $button.text(isDisabled ? 'Procesando...' : 'Anular');
  }

  // Actualizar badge
  function updateStatusBadge(saleId, status) {
    const $badge = $(`#status-badge-${saleId}`);
    if (!$badge.length) return;

    $badge.removeClass().addClass('badge');

    switch (status) {
      case 'PROCESANDO_ANULACION':
        $badge.addClass('bg-warning text-dark').text('⏳ Procesando');
        break;
      case 'ANULADO':
        $badge.addClass('bg-success').text('✅ Anulado');
        break;
      case 'ERROR_ANULACION':
      case 'ERROR_TICKET':
      case 'FAILED_ANULACION':
        $badge.addClass('bg-danger').text('❌ Error');
        break;
      default:
        $badge.addClass('bg-secondary').text('-');
    }
  }

  // Mostrar notificación
  function showNotification(type, message) {
    // Implementa según tu librería
    console.log(`[${type.toUpperCase()}] ${message}`);
    
    // Ejemplo con Toastr
    // toastr[type](message);
  }

  // Recargar tabla
  function reloadTable() {
    // Implementa según tu sistema
    location.reload();
  }

  // Obtener token
  function getToken() {
    return localStorage.getItem('auth_token') || '';
  }

  // Limpiar al salir
  $(window).on('beforeunload', function() {
    pollingIntervals.forEach(function(intervalId) {
      clearInterval(intervalId);
    });
  });
});
```

---

## 🎨 HTML de Ejemplo

```html
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ventas - Sistema HS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Ventas</h2>
    
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Serie-Número</th>
          <th>Cliente</th>
          <th>Total</th>
          <th>Estado SUNAT</th>
          <th>Ticket</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>123</td>
          <td>F001-00001234</td>
          <td>Juan Pérez</td>
          <td>S/ 1,500.00</td>
          <td>
            <span id="status-badge-123" class="badge bg-secondary">-</span>
          </td>
          <td id="ticket-123">-</td>
          <td>
            <button 
              class="btn btn-danger btn-sm btn-anular" 
              data-sale-id="123"
            >
              Anular
            </button>
          </td>
        </tr>
        <!-- Más filas... -->
      </tbody>
    </table>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="sales-anulacion.js"></script>
</body>
</html>
```

---

## 📱 Consideraciones de UX

### 1. Feedback Visual Inmediato
- ✅ Deshabilitar botón mientras procesa
- ✅ Cambiar texto del botón a "Procesando..."
- ✅ Mostrar spinner o loading

### 2. Notificaciones Claras
- ✅ Notificación inmediata cuando se envía la solicitud
- ✅ Notificación cuando termina exitosamente
- ✅ Notificación clara en caso de error

### 3. Estados Visuales
- ⏳ **Amarillo**: Procesando
- ✅ **Verde**: Exitoso
- ❌ **Rojo**: Error

### 4. Polling Inteligente
- ⏱️ Consultar cada 10 segundos (ni muy rápido ni muy lento)
- 🛑 Detener automáticamente después de 10 minutos
- 🔄 Manejar errores de red sin detener el polling

### 5. Persistencia
- 💾 Si el usuario recarga la página, verificar estados pendientes
- 💾 Guardar tickets en localStorage para recuperar

---

## 🔍 Debug y Testing

### Probar el Flujo Completo

```javascript
// Console del navegador
async function testAnulacion() {
  const saleId = 123;
  
  // 1. Anular
  console.log('1. Anulando venta...');
  const response = await fetch(`/api/sales/${saleId}/status`, {
    method: 'POST',
    headers: { 'Authorization': 'Bearer TOKEN' }
  });
  const data = await response.json();
  console.log('Respuesta:', data);
  
  // 2. Consultar estado
  console.log('2. Consultando estado...');
  const statusResponse = await fetch(`/api/sales/${saleId}/sunat-status`, {
    headers: { 'Authorization': 'Bearer TOKEN' }
  });
  const statusData = await statusResponse.json();
  console.log('Estado:', statusData);
}

testAnulacion();
```

---

## 📚 Recursos Adicionales

- **Documentación completa**: `SUNAT_QUEUE_SETUP.md`
- **Guía rápida**: `QUICK_START_QUEUE.md`
- **Resumen**: `RESUMEN_IMPLEMENTACION.md`

---

**Fecha**: 07 de Febrero 2026  
**Proyecto**: Sistema de Gestión HS  
**Versión**: 1.0.0