<template>
  <div class="h-auto page-bg min-h-0">
    <div class="header-tabla">
      <h2 class="titulo-tabla">Salidas</h2>
      <div class="flex items-center gap-2 ml-auto">
        <button @click="showFilters = !showFilters" class="btn btn-ghost btn-sm gap-2">
          <span>Filtros</span>
          <svg class="w-4 h-4 rotate-180" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
            <path d="M8 1.25a2.101 2.101 0 00-1.785.996l.64.392-.642-.388-5.675 9.373-.006.01a2.065 2.065 0 00.751 2.832c.314.183.67.281 1.034.285h11.366a2.101 2.101 0 001.791-1.045 2.064 2.064 0 00-.006-2.072L9.788 2.25l-.003-.004A2.084 2.084 0 008 1.25z"/>
          </svg>
        </button>
        <router-link v-if="isEditor" to="/salidas/crear" class="btn btn-primary">Crear</router-link>
      </div>
    </div>
    <!-- Scroll y altura solo aquí -->
    <div class="card-panel p-4 mb-4 fade-in scroll-panel" style="--panel-offset: 160px;">
      <div v-show="showFilters" class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
        <div class="relative">
          <input v-model="fFechaInicio" type="date"
                 class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-violet-400"
                 placeholder="Fecha inicio" />
          <button v-if="fFechaInicio" @click="fFechaInicio=''" class="absolute right-2 top-2 text-white/60 hover:text-white">×</button>
        </div>
        <div class="relative">
          <input v-model="fFechaFin" type="date"
                 class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-orange-400"
                 placeholder="Fecha fin" />
          <button v-if="fFechaFin" @click="fFechaFin=''" class="absolute right-2 top-2 text-white/60 hover:text-white">×</button>
        </div>
        <div class="relative md:col-span-3">
          <input v-model.trim="fDestino" type="text"
                 class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-orange-400"
                 placeholder="Filtrar por destinación (tramo/calle) o material..." />
          <button v-if="fDestino" @click="fDestino=''" class="absolute right-2 top-2 text-white/60 hover:text-white">×</button>
        </div>
      </div>
      <p v-if="error" class="text-error mb-2">{{ error }}</p>
      <p v-else-if="loading" class="text-gray-400 mb-2 ml-4">Cargando...</p>
      <DataTable v-else :headers="['ID','Fecha','Destinación','Material','Cantidad']"
                 :keys="['id_salida','fecha','tramo_calle_label','material_nombre','cantidad_total']"
                 :rows="filteredItems"
                 :showActions="isEditor"
                 @edit="onEdit"
                 @delete="onDelete" />

<!-- Modern Pagination Component -->
      <div class="flex items-center justify-center gap-2 my-6 px-4">
        <!-- Primera pÃ¡gina -->
        <button 
          v-if="pagination.current_page > 1"
          @click="goToPage(1)" 
          class="btn btn-sm bg-gradient-to-r from-gray-700 to-gray-800 text-white border-none shadow-md hover:shadow-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
          </svg>
        </button>
        
        <!-- PÃ¡gina anterior -->
        <button 
          @click="goToPage(pagination.current_page - 1)" 
          :disabled="pagination.current_page <= 1"
          class="btn btn-sm bg-gradient-to-r from-gray-700 to-gray-800 text-white border-none shadow-md hover:shadow-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gradient-to-r disabled:from-gray-800 disabled:to-gray-900 disabled:shadow-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        
        <!-- Indicador de pÃ¡gina -->
        <div class="px-4 py-1 rounded-lg bg-gradient-to-r from-gray-800/80 to-gray-900/80 text-white/90 border border-white/10 shadow-inner">
          <span class="text-sm font-medium">{{ pagination.current_page }}</span>
          <span class="text-xs text-white/70 mx-1">/</span>
          <span class="text-xs text-white/70">{{ pagination.last_page }}</span>
        </div>
        
        <!-- PÃ¡gina siguiente -->
        <button 
          @click="goToPage(pagination.current_page + 1)" 
          :disabled="pagination.current_page >= pagination.last_page"
          class="btn btn-sm bg-gradient-to-r from-gray-700 to-gray-800 text-white border-none shadow-md hover:shadow-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gradient-to-r disabled:from-gray-800 disabled:to-gray-900 disabled:shadow-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
        
        <!-- Ãšltima pÃ¡gina -->
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
              ¿Seguro que deseas eliminar la salida
              <span class="font-medium">{{ confirmTarget?.id_salida }}</span>?
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
import { useSalidaStore } from '../../stores/salida';
import DataTable from '../../components/DataTable.vue';

const store = useSalidaStore();
const auth = useAuthStore();
const roleNum = computed(()=> Number(auth.user?.rol));
const isAdmin = computed(()=> roleNum.value === 1);
const isEditor = computed(()=> roleNum.value === 1 || roleNum.value === 2);
const items = computed(()=>store.items);
const pagination = computed(()=>store.pagination);
const loading = ref(true);
const error = computed(()=>store.error);
const showFilters = ref(false);
const fFechaInicio = ref('');
const fFechaFin = ref('');
const fDestino = ref('');

// Modal state
const confirmOpen = ref(false);
const confirmTarget = ref(null);
const confirmLoading = ref(false);
const confirmError = ref('');

// Forzar reactividad con un contador de actualizaciones
const updateKey = ref(0);
const forceUpdate = () => {
  updateKey.value++;
};

const visibleItems = computed(() =>
  (items.value || []).map(s => {
    const detalles = Array.isArray(s.detalles) ? s.detalles : [];
    const cantidad_total = detalles.reduce((acc, d) => acc + (Number(d.cantidad) || 0), 0);
    // Si hay múltiples materiales, mostramos el primero y totalizamos la cantidad
    const material_nombre = detalles.length > 0 && detalles[0].material ? detalles[0].material.concepto : '—';
    return {
      ...s,
      material_nombre,
      cantidad_total
    };
  })
);

const filteredItems = computed(()=>{
  const list = visibleItems.value || [];
  const q = (fDestino.value || '').toLowerCase();
  return list.filter(r => {
    const withinFecha = (!fFechaInicio.value || r.fecha >= fFechaInicio.value) && (!fFechaFin.value || r.fecha <= fFechaFin.value);
    const byDestino = !q || String(r.tramo_calle_label||'').toLowerCase().includes(q) || String(r.material_nombre||'').toLowerCase().includes(q);
    return withinFecha && byDestino;
  });
});

onMounted(async () => {
  loading.value = true;
  try { await store.fetch(); } finally { loading.value = false; }
});

async function goToPage(page){
  if(page>=1 && page<=pagination.value.last_page){ await store.fetch(page); }
}

function getId(row){ return row.id_salida ?? row.id ?? row.idSalida; }

function onEdit(row){ 
  const id = getId(row); 
  if(!id) return; 
  window.location.href = `/salidas/editar/${id}`; 
}

function onDelete(row) {
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
    } else if (typeof store.destroy === 'function') {
      await store.destroy(id);
    } else {
      throw new Error('No se encontró un método de eliminación en el store');
    }
    
    // PASO 4: Forzar actualización del componente
    forceUpdate();
    
    // PASO 5: Opcional - Recargar datos del servidor para asegurar sincronización
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
    let errorMsg = 'No se pudo eliminar la salida';
    
    if (e?.response?.status === 401) {
      errorMsg = 'Error de autenticación. Por favor, vuelve a iniciar sesión.';
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
      errorMsg = 'No se puede eliminar. La salida tiene datos relacionados.';
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
