<!--
  ALMACEN LIST - Responsive mobile-first design
  Mobile: Stacked layout, full-width buttons
  Tablet+: Traditional table layout
-->
<template>
  <div class="h-auto page-bg min-h-0">
    <!-- Responsive Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 px-3 sm:px-5 pt-3 sm:pt-4">
      <h2 class="titulo-tabla text-lg sm:text-xl">Almacenes</h2>
      <div class="flex items-center gap-2">
        <button @click="showFilters = !showFilters" class="btn btn-ghost btn-sm gap-1 sm:gap-2 text-sm">
          <span class="hidden sm:inline">Filtros</span>
          <svg class="w-4 h-4" :class="showFilters ? 'rotate-180' : ''" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
            <path d="M8 1.25a2.101 2.101 0 00-1.785.996l.64.392-.642-.388-5.675 9.373-.006.01a2.065 2.065 0 00.751 2.832c.314.183.67.281 1.034.285h11.366a2.101 2.101 0 001.791-1.045 2.064 2.064 0 00-.006-2.072L9.788 2.25l-.003-.004A2.084 2.084 0 008 1.25z"/>
          </svg>
        </button>
        <router-link v-if="isEditor" to="/almacenes/crear" class="btn btn-primary btn-sm sm:btn-md">
          <span class="hidden sm:inline">Crear</span>
          <svg class="w-5 h-5 sm:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
          </svg>
        </router-link>
      </div>
    </div>
    
    <!-- Scroll y altura solo aquí -->
    <div class="card-panel p-3 sm:p-4 mx-3 sm:mx-5 mb-4 fade-in scroll-panel" style="--panel-offset: 160px;">
      <!-- Filtros - Responsive grid -->
      <div v-show="showFilters" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3 mb-4">
        <div class="relative">
          <input v-model.trim="fNombre"
                type="text"
                class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-orange-400"
                placeholder="Filtrar por nombre..." />
          <button v-if="fNombre" @click="fNombre=''" class="absolute right-2 top-2 text-white/60 hover:text-white">×</button>
        </div>
        <div class="relative">
          <input v-model.number="fCiudad"
                type="number" min="1"
                class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-violet-400"
                placeholder="Filtrar por ciudad (ID)..." />
          <button v-if="fCiudad" @click="fCiudad=null" class="absolute right-2 top-2 text-white/60 hover:text-white">×</button>
        </div>
        <div class="relative">
          <input v-model.trim="fCiudadNombre"
                 type="text"
                 class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                 placeholder="Filtrar por ciudad (nombre)..." />
          <button v-if="fCiudadNombre" @click="fCiudadNombre=''" class="absolute right-2 top-2 text-white/60 hover:text-white">×</button>
        </div>
      </div>

      <!-- Estados de carga y error -->
      <div v-if="error" class="text-error mb-2">{{ error }}</div>
      <div v-else-if="loading" class="text-gray-400 mb-2 ml-4">Cargando...</div>

      <!-- Tabla -->
      <DataTable
        v-if="!loading && !error"
        :headers="['ID','Nombre','Ciudad']"
        :keys="['id_almacen','nombre','ciudad']"
        :rows="filteredRows"
        :showActions="isEditor"
        @edit="onEdit"
        @delete="onDelete"
      />

      <!-- Sin resultados -->
      <div v-if="!loading && !error && filteredRows.length === 0" class="text-white/60 text-center py-8">
        No se encontraron almacenes
      </div>

      <!-- Modern Pagination Component -->
      <div class="flex items-center justify-center gap-2 my-6 px-4">
          <!-- Primera página -->
          <button 
            v-if="pagination.current_page > 1"
            @click="goToPage(1)" 
            class="btn btn-sm bg-gradient-to-r from-gray-700 to-gray-800 text-white border-none shadow-md hover:shadow-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
          </button>
          
          <!-- Página anterior -->
          <button 
            @click="goToPage(pagination.current_page - 1)" 
            :disabled="pagination.current_page <= 1"
            class="btn btn-sm bg-gradient-to-r from-gray-700 to-gray-800 text-white border-none shadow-md hover:shadow-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gradient-to-r disabled:from-gray-800 disabled:to-gray-900 disabled:shadow-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          
          <!-- Indicador de página -->
          <div class="px-4 py-1 rounded-lg bg-gradient-to-r from-gray-800/80 to-gray-900/80 text-white/90 border border-white/10 shadow-inner">
            <span class="text-sm font-medium">{{ pagination.current_page }}</span>
            <span class="text-xs text-white/70 mx-1">/</span>
            <span class="text-xs text-white/70">{{ pagination.last_page }}</span>
          </div>
          
          <!-- Página siguiente -->
          <button 
            @click="goToPage(pagination.current_page + 1)" 
            :disabled="pagination.current_page >= pagination.last_page"
            class="btn btn-sm bg-gradient-to-r from-gray-700 to-gray-800 text-white border-none shadow-md hover:shadow-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gradient-to-r disabled:from-gray-800 disabled:to-gray-900 disabled:shadow-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          
        <!-- Última página -->
        <button 
          v-if="pagination.current_page < pagination.last_page"
          @click="goToPage(pagination.last_page)" 
          class="btn btn-sm bg-gradient-to-r from-gray-700 to-gray-800 text-white border-none shadow-md hover:shadow-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <transition name="fade">
      <div v-if="confirmOpen" class="fixed inset-0 z-[12000]" @click.self="closeConfirm">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
          <div class="w-full max-w-sm rounded-xl bg-[rgba(17,24,39,.98)] border border-white/15 shadow-2xl p-5">
            <h3 class="text-lg font-semibold text-white mb-2">Confirmar eliminación</h3>
            <p class="text-white/80 mb-4">
              ¿Seguro que deseas eliminar el almacén 
              <span class="font-medium">{{ confirmTarget?.nombre }}</span>?
            </p>
            <p v-if="confirmError" class="text-error mb-3">{{ confirmError }}</p>
            <div class="flex justify-end gap-2">
              <button class="btn" :disabled="confirmLoading" @click="closeConfirm">Cancelar</button>
              <button class="btn bg-red-600 hover:bg-red-500 text-white" :disabled="confirmLoading" @click="confirmDelete">
                <span v-if="confirmLoading" class="loading loading-spinner mr-2"></span>
                Eliminar
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>

<script setup>
import { onMounted, computed, ref } from 'vue';
import { useAuthStore } from '../../stores/auth';
import { useAlmacenStore } from '../../stores/almacen';
import DataTable from '../../components/DataTable.vue';

// Filtros
const fCiudad = ref(null);
const fNombre = ref('');
const fCiudadNombre = ref('');
const showFilters = ref(false);

// Auth
const auth = useAuthStore();

// Check both rol and role fields to handle API inconsistencies
const roleRaw = computed(() => auth.user?.rol ?? auth.user?.role ?? '');
const roleNum = computed(() => Number(roleRaw.value));

// Special case handling for user ID 3
const isSpecialUser = computed(() => auth.user?.id === 3);

// Admin is role 1, or user ID 3
const isAdmin = computed(() => roleNum.value === 1 || isSpecialUser.value);

// Editor is role 1 or 2, or user ID 3
const isEditor = computed(() => roleNum.value === 1 || roleNum.value === 2 || isSpecialUser.value);

// Store
const store = useAlmacenStore();
const items = computed(() => {
  const storeItems = store.items;
  // Asegurarnos de que sea un array
  if (!storeItems) return [];
  if (Array.isArray(storeItems)) return storeItems;
  // Si es un proxy o tiene _rawValue, intentar accederlo
  if (storeItems._rawValue && Array.isArray(storeItems._rawValue)) return storeItems._rawValue;
  return [];
});
const pagination = computed(() => store.pagination || { current_page: 1, last_page: 1 });

// Forzar reactividad con un contador de actualizaciones
const updateKey = ref(0);
const forceUpdate = () => {
  updateKey.value++;
};

// Estados
const loading = ref(true);
const error = ref('');

// Delete confirmation state
const confirmOpen = ref(false);
const confirmTarget = ref(null);
const confirmLoading = ref(false);
const confirmError = ref('');

// Transformar items para mostrar nombre de ciudad
const rows = computed(() => {
  const itemsArray = items.value;
  if (!Array.isArray(itemsArray)) {
    console.warn('items.value no es un array:', itemsArray);
    return [];
  }
  
  return itemsArray.map(item => ({
    ...item,
    ciudad: item.ciudad_ref?.nombre || item.ciudad_nombre || 'N/A'
  }));
});

// Filtrar rows
const filteredRows = computed(() => {
  let result = rows.value;
  
  // Filtro por nombre
  if (fNombre.value) {
    const searchTerm = fNombre.value.toLowerCase();
    result = result.filter(r => 
      String(r.nombre || '').toLowerCase().includes(searchTerm)
    );
  }
  
  // Filtro por ID de ciudad
  if (fCiudad.value) {
    result = result.filter(r => 
      Number(r.id_ciudad || r.ciudad) === Number(fCiudad.value)
    );
  }
  
  // Filtro por nombre de ciudad
  if (fCiudadNombre.value) {
    const searchTerm = fCiudadNombre.value.toLowerCase();
    result = result.filter(r => 
      String(r.ciudad || '').toLowerCase().includes(searchTerm)
    );
  }
  
  return result;
});

// Cargar datos al montar
onMounted(async () => {
  loading.value = true;
  error.value = '';
  
  try {
    // Verificar estado de autenticación
    console.log('Auth user:', auth.user);
    console.log('Auth token:', auth.token);
    console.log('Is authenticated:', auth.isAuthenticated);
    
    await store.fetch();
    console.log('Almacenes cargados:', items.value);
    console.log('Pagination:', pagination.value);
  } catch (e) {
    console.error('Error al cargar almacenes:', e);
    error.value = e.message || 'Error de conexión';
  } finally {
    loading.value = false;
  }
});

// Paginación
async function goToPage(page) {
  if (page >= 1 && page <= pagination.value.last_page) {
    loading.value = true;
    try {
      await store.fetch(page);
    } catch (e) {
      console.error('Error al cambiar de página:', e);
      error.value = 'Error al cargar la página';
    } finally {
      loading.value = false;
    }
  }
}

// Obtener ID del almacén
function getId(row) {
  return row.id_almacen ?? row.id ?? row.idAlmacen;
}

// Editar almacén
function onEdit(row) {
  const id = getId(row);
  if (!id) {
    console.error('ID no válido para editar:', row);
    return;
  }
  window.location.href = `/almacenes/editar/${id}`;
}

// Eliminar almacén
async function onDelete(row) {
  const scrollY = window.scrollY;
  
  confirmTarget.value = row;
  confirmError.value = '';
  confirmLoading.value = false;
  confirmOpen.value = true;
  
  // Bloquear scroll
  document.body.style.overflow = 'hidden';
  document.body.style.position = 'fixed';
  document.body.style.top = `-${scrollY}px`;
  document.body.style.width = '100%';
}

// Cerrar modal de confirmación
function closeConfirm() {
  confirmOpen.value = false;
  confirmTarget.value = null;
  confirmError.value = '';
  confirmLoading.value = false;
  
  // Restaurar scroll
  const scrollY = document.body.style.top;
  document.body.style.overflow = '';
  document.body.style.position = '';
  document.body.style.top = '';
  document.body.style.width = '';
  
  if (scrollY) {
    window.scrollTo(0, parseInt(scrollY || '0') * -1);
  }
}

// Confirmar eliminación
async function confirmDelete() {
  if (!confirmTarget.value) return;
  
  confirmLoading.value = true;
  confirmError.value = '';
  
  const id = getId(confirmTarget.value);
  if (!id) {
    confirmError.value = 'ID no válido';
    confirmLoading.value = false;
    return;
  }
  
  // PASO 1: Actualización optimista (eliminar del UI inmediatamente)
  // Guardar estado original por si falla
  const originalItems = [...items.value];
  
  // Crear una nueva referencia del array items en el store 
  // (importante para mantener reactividad)
  if (Array.isArray(store.items)) {
    // Actualizar directamente el array del store
    store.items = store.items.filter(item => getId(item) !== id);
  } else {
    console.warn('store.items no es un array, no se puede actualizar localmente');
  }
  
  // PASO 2: Cerrar el modal para feedback inmediato
  closeConfirm();
  
  try {
    // PASO 3: Eliminar del servidor
    if (typeof store.delete === 'function') {
      await store.delete(id);
    } else if (typeof store.remove === 'function') {
      await store.remove(id);
    } else if (typeof store.deleteAlmacen === 'function') {
      await store.deleteAlmacen(id);
    } else {
      throw new Error('No se encontró un método de eliminación en el store');
    }
    
    // PASO 4: Forzar actualización del componente
    // Esto asegura que la UI se actualice
    forceUpdate();
    
    // PASO 5: Opcional - Recargar datos del servidor para asegurar sincronización
    // Lo hacemos sin mostrar indicador de carga para no interrumpir la experiencia
    try {
      await store.fetch(pagination.value.current_page);
    } catch (fetchErr) {
      console.warn('Error al recargar datos después de eliminar:', fetchErr);
    }
    
  } catch (e) {
    console.error('Error al eliminar:', e);
    
    // PASO 6: Si falla, revertir la actualización optimista
    if (Array.isArray(store.items)) {
      store.items = originalItems;
    }
    
    // PASO 7: Mostrar error
    const data = e?.response?.data || {};
    let errorMsg = 'No se pudo eliminar el almacén';
    
    // No mostrar error de autenticación a menos que realmente sea código 401
    if (e?.response?.status === 401 && auth.token) {
      // Intentar refrescar la sesión
      try {
        await auth.me();
        // Si llegamos aquí, la autenticación sigue siendo válida
        // Podemos intentar reejecutar la eliminación
        if (typeof store.remove === 'function') {
          await store.remove(id);
          return; // Si tiene éxito, salimos de la función
        }
      } catch (authErr) {
        // La sesión realmente está vencida
        errorMsg = 'La sesión ha expirado. Por favor, vuelve a iniciar sesión.';
        // Redirigir al login después de un breve retraso
        setTimeout(() => {
          auth.logout();
          window.location.href = '/login';
        }, 1500);
      }
    } else if (data.mensaje) {
      errorMsg = data.mensaje;
    } else if (data.message) {
      errorMsg = data.message;
    } else if (data.error) {
      errorMsg = data.error;
    } else if (e.message) {
      errorMsg = e.message;
    }
    
    if (e?.response?.status === 500) {
      errorMsg = 'No se puede eliminar. El almacén tiene datos relacionados.';
    }
    
    // Mostrar error en el modal en lugar de un alert para mejorar UX
    confirmError.value = errorMsg;
    
    // Asegurarnos de mantener el objeto target para poder mostrarlo correctamente en el modal
    if (!confirmTarget.value && originalItems.length > 0) {
      const originalItem = originalItems.find(item => getId(item) === id);
      if (originalItem) {
        confirmTarget.value = originalItem;
      }
    }
    
    confirmOpen.value = true;
  }
  
  confirmLoading.value = false;
}
</script>