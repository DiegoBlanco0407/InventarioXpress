<template>
  <div class="h-auto page-bg min-h-0">
    <div class="header-tabla">
      <h2 class="titulo-tabla">Tramo-Calle</h2>
      <div class="flex items-center gap-2 ml-auto">
        <button @click="showFilters = !showFilters" class="btn btn-ghost btn-sm gap-2">
          <span>Filtros</span>
          <svg class="w-4 h-4 rotate-180" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
            <path d="M8 1.25a2.101 2.101 0 00-1.785.996l.64.392-.642-.388-5.675 9.373-.006.01a2.065 2.065 0 00.751 2.832c.314.183.67.281 1.034.285h11.366a2.101 2.101 0 001.791-1.045 2.064 2.064 0 00-.006-2.072L9.788 2.25l-.003-.004A2.084 2.084 0 008 1.25z"/>
          </svg>
        </button>
        <router-link v-if="isEditor" to="/tramo-calle/crear" class="btn btn-primary">Crear</router-link>
      </div>
    </div>
    <!-- Scroll y altura solo aquí -->
    <div class="card-panel p-4 mb-4 fade-in scroll-panel" style="--panel-offset: 160px;">
      <div v-show="showFilters" class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
        <div class="relative">
          <input v-model.trim="fTramo" type="text"
                 class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-orange-400"
                 placeholder="Filtrar por tramo..." />
          <button v-if="fTramo" @click="fTramo=''" class="absolute right-2 top-2 text-white/60 hover:text-white">×</button>
        </div>
        <div class="relative">
          <input v-model.trim="fCalle" type="text"
                 class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-violet-400"
                 placeholder="Filtrar por calle..." />
          <button v-if="fCalle" @click="fCalle=''" class="absolute right-2 top-2 text-white/60 hover:text-white">×</button>
        </div>
      </div>
      <p v-if="error" class="text-error mb-2">{{ error }}</p>
      <p v-else-if="loading" class="text-gray-400 mb-2 ml-4">Cargando...</p>
      <DataTable
        v-else
        :headers="['ID','Tramo','Calle']"
        :keys="['id_tramo_calle','tramo_nombre','calle_nombre']"
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
              ¿Seguro que deseas eliminar el tramo-calle 
              <span class="font-medium">{{ confirmTarget?.tramo_nombre }} - {{ confirmTarget?.calle_nombre }}</span>?
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
import { useTramoCalleStore } from '../../stores/tramoCalle';
import DataTable from '../../components/DataTable.vue';

const store = useTramoCalleStore();
const auth = useAuthStore();
const isAdmin = computed(()=> Number(auth.user?.rol) === 1);
const isEditor = computed(()=> [1,2].includes(Number(auth.user?.rol)));
const showFilters = ref(false);
const items = computed(()=>store.items);
const pagination = computed(()=>store.pagination);
const loading = ref(true);
const error = ref('');

const visibleItems = computed(() =>
  items.value.map(item => ({
    ...item,
    tramo_nombre: item.tramo ? item.tramo.nombre : 'N/A',
    calle_nombre: item.calle ? item.calle.nombre : 'N/A'
  }))
);

const fTramo = ref('');
const fCalle = ref('');
const filteredItems = computed(()=>{
  const list = visibleItems.value || [];
  const qTramo = fTramo.value.toLowerCase();
  const qCalle = fCalle.value.toLowerCase();
  return list.filter(r => {
    const okTramo = !qTramo || String(r.tramo_nombre||'').toLowerCase().includes(qTramo);
    const okCalle = !qCalle || String(r.calle_nombre||'').toLowerCase().includes(qCalle);
    return okTramo && okCalle;
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

function getId(row){ return row.id_tramo_calle ?? row.id ?? row.idTramoCalle; }
function onEdit(row){ const id = getId(row); if(!id) return; window.location.href = `/tramo-calle/editar/${id}`; }

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
  
  try {
    if (typeof store.delete === 'function') {
      await store.delete(id);
    } else if (typeof store.remove === 'function') {
      await store.remove(id);
    } else if (typeof store.destroy === 'function') {
      await store.destroy(id);
    } else {
      throw new Error('No se encontró un método de eliminación en el store');
    }
    
    // Solo cerrar modal si todo salió bien
    closeConfirm();
    await store.fetch();
  } catch (e) {
    console.error('Error al eliminar:', e);
    let errorMsg = 'Error al eliminar';
    
    const data = e?.response?.data;
    if (data?.mensaje) {
      errorMsg = data.mensaje;
    } else if (data?.message) {
      errorMsg = data.message;
    } else if (data?.error) {
      errorMsg = data.error;
    } else if (e.message) {
      errorMsg = e.message;
    }
    
    if (e?.response?.status === 500) {
      errorMsg = 'No se puede eliminar. El tramo-calle tiene datos relacionados.';
    }
    
    confirmError.value = errorMsg;
    confirmLoading.value = false;
  }
}
</script>
