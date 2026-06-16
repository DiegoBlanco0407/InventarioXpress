<template>
  <div class="h-auto page-bg min-h-0">
    <div class="header-tabla">
      <h2 class="titulo-tabla">Tramos de red</h2>
      <div class="flex items-center gap-2 ml-auto">
        <button @click="showFilters = !showFilters" class="btn btn-ghost btn-sm gap-2">
          <span>Filtros</span>
          <svg class="w-4 h-4 rotate-180" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
            <path d="M8 1.25a2.101 2.101 0 00-1.785.996l.64.392-.642-.388-5.675 9.373-.006.01a2.065 2.065 0 00.751 2.832c.314.183.67.281 1.034.285h11.366a2.101 2.101 0 001.791-1.045 2.064 2.064 0 00-.006-2.072L9.788 2.25l-.003-.004A2.084 2.084 0 008 1.25z"/>
          </svg>
        </button>
        <router-link v-if="isEditor" to="/instalaciones/crear" class="btn btn-primary">Crear</router-link>
      </div>
    </div>
    <!-- Scroll y altura solo aquí -->
    <div class="card-panel p-4 mb-4 fade-in scroll-panel" style="--panel-offset: 160px;">
      <div v-show="showFilters" class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
        <div class="relative">
          <input v-model.trim="fUbicacion" type="text"
                 class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-orange-400"
                 placeholder="Filtrar por tramo o calle..." />
          <button v-if="fUbicacion" @click="fUbicacion=''" class="absolute right-2 top-2 text-white/60 hover:text-white">×</button>
        </div>
        <div class="relative">
          <input v-model.trim="fMaterial" type="text"
                 class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-violet-400"
                 placeholder="Filtrar por material..." />
          <button v-if="fMaterial" @click="fMaterial=''" class="absolute right-2 top-2 text-white/60 hover:text-white">×</button>
        </div>
        <div class="flex items-center gap-3">
          <label class="inline-flex items-center gap-2 select-none">
            <input v-model="applyComparador" type="checkbox" class="checkbox checkbox-sm" />
            <span>Condición de instalación</span>
          </label>
          <div class="relative" @keydown.esc="compOpen=false" v-click-outside="() => compOpen=false">
            <button type="button" class="w-44 whitespace-nowrap text-left px-3 py-1.5 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="compOpen=!compOpen" @blur="setTimeout(()=> compOpen=false, 120)">
              <span>{{ compLabel }}</span>
              <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
            </button>
            <div v-if="compOpen" class="absolute z-[70] mt-1 w-48 rounded-lg bg-[rgba(17,24,39,.95)] border border-white/15 shadow-xl overflow-auto max-h-60">
              <div v-for="o in compOptions" :key="o.value" @mousedown.prevent @click="setComparador(o.value)" class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20" :class="{ 'bg-orange-500/25 font-medium': o.value === comparador }">{{ o.label }}</div>
            </div>
          </div>
        </div>
      </div>
      <p v-if="error" class="text-error mb-2">{{ error }}</p>
      <p v-else-if="loading" class="text-gray-400 mb-2 ml-4">Cargando...</p>
      <DataTable
        v-else
        :headers="['Tramo (Calle)','Material','Instalado','A instalar','Almacén']"
        :keys="['tramo_calle_label','material_concepto','instalado','a_instalar','almacen_nombre']"
        :rows="filteredItems"
        :showActions="isEditor"
        @edit="onEdit"
        @delete="onDelete"
      />

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
              ¿Seguro que deseas eliminar la instalación 
              <span class="font-medium">{{ confirmTarget?.tramo_calle_label }}</span>?
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
import { useInstalacionStore } from '../../stores/instalacion';
import DataTable from '../../components/DataTable.vue';

const store = useInstalacionStore();
const auth = useAuthStore();
const isAdmin = computed(()=> Number(auth.user?.rol) === 1);
const isEditor = computed(()=> [1,2].includes(Number(auth.user?.rol)));
const items = computed(()=>store.items);
const pagination = computed(()=>store.pagination);
const loading = ref(true);
const error = ref('');

const showFilters = ref(false);
const fUbicacion = ref('');
const fMaterial = ref('');
const applyComparador = ref(false);
const comparador = ref('>');
const compOptions = [
  { value: '>', label: 'mayor' },
  { value: '<', label: 'menor' },
  { value: '>=', label: 'mayor o igual' },
  { value: '<=', label: 'menor o igual' },
  { value: '==', label: 'igual' }
];
const compOpen = ref(false);
const compLabel = computed(()=> (compOptions.find(o=>o.value===comparador.value)?.label) || 'mayor');
function setComparador(v){ comparador.value = v; compOpen.value = false; }

const visibleItems = computed(() =>
  (items.value || []).map(item => {
    const label = item.tramo_calle_label;
    if (label && typeof label === 'string') {
      return {
        ...item,
        tramo_calle_label: label,
        material_concepto: item.material ? item.material.concepto : 'N/A',
        almacen_nombre: item.almacen ? (item.almacen.nombre || 'N/A') : (item.almacen_nombre || 'N/A')
      };
    }
    const tramoNombre = item.tramo?.nombre ?? 'Tramo';
    const calleNombre = item.tramo_calle?.calle?.nombre ?? item.tramoCalle?.calle?.nombre ?? 'Calle';
    return {
      ...item,
      tramo_calle_label: `${tramoNombre} (${calleNombre})`,
      material_concepto: item.material ? item.material.concepto : 'N/A',
      almacen_nombre: item.almacen ? (item.almacen.nombre || 'N/A') : (item.almacen_nombre || 'N/A')
    };
  })
);

const filteredItems = computed(()=>{
  const list = visibleItems.value || [];
  const qU = (fUbicacion.value || '').toLowerCase();
  const qM = (fMaterial.value || '').toLowerCase();
  return list.filter(r => {
    const byUbic = !qU || String(r.tramo_calle_label||'').toLowerCase().includes(qU);
    const byMat = !qM || String(r.material_concepto||'').toLowerCase().includes(qM);
    let byComp = true;
    if (applyComparador.value) {
      const a = Number(String(r.a_instalar).toString().replace(',', '.')) || 0;
      const b = Number(String(r.instalado).toString().replace(',', '.')) || 0;
      const eps = 1e-6;
      switch (comparador.value) {
        case '>': byComp = a > b; break;
        case '<': byComp = a < b; break;
        case '>=': byComp = a > b || Math.abs(a - b) < eps; break;
        case '<=': byComp = a < b || Math.abs(a - b) < eps; break;
        case '=': byComp = Math.abs(a - b) < eps; break;
        default: byComp = a > b;
      }
    }
    return byUbic && byMat && byComp;
  });
});

onMounted(async ()=>{
  loading.value = true; error.value = '';
  try { await store.fetch(); } catch(e) { error.value = 'Error de conexión'; }
  finally { loading.value = false; }
});

async function goToPage(page){
  if(page>=1 && page<=pagination.value.last_page){ await store.fetch(page); }
}

// Delete confirmation state
const confirmOpen = ref(false);
const confirmTarget = ref(null);
const confirmLoading = ref(false);
const confirmError = ref('');

// Forzar reactividad con un contador de actualizaciones
const updateKey = ref(0);
const forceUpdate = () => {
  updateKey.value++;
};

function getId(row) { 
  return row.id_instalacion ?? row.id ?? 
         (row.id_tramo && row.id_material && row.id_almacen ? 
         `${row.id_tramo}/${row.id_material}/${row.id_almacen}` : null);
}

function onEdit(row) {
  const id_tramo = Number(row.id_tramo ?? row.tramo?.id_tramo ?? row.tramo_id ?? row.tramo?.id);
  const id_material = Number(row.id_material ?? row.material?.id_material ?? row.material_id ?? row.material?.id);
  const id_almacen = Number(row.id_almacen ?? row.almacen?.id_almacen ?? row.almacen_id ?? row.almacen?.id);
  if (!id_tramo || !id_material || !id_almacen) return;
  window.location.href = `/instalaciones/editar/${id_tramo}/${id_material}/${id_almacen}`;
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
    let errorMsg = 'No se pudo eliminar la instalación';
    
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
      errorMsg = 'No se puede eliminar. La instalación tiene datos relacionados.';
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
