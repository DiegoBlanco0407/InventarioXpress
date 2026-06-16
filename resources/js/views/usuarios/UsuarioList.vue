<template>
  <div class="h-auto page-bg min-h-0">
    <div class="header-tabla">
      <h2 class="titulo-tabla">Usuarios</h2>
      <div class="flex items-center gap-2 ml-auto">
        <button @click="showFilters = !showFilters" class="btn btn-ghost btn-sm gap-2">
          <span>Filtros</span>
          <svg class="w-4 h-4 rotate-180" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
            <path d="M8 1.25a2.101 2.101 0 00-1.785.996l.64.392-.642-.388-5.675 9.373-.006.01a2.065 2.065 0 00.751 2.832c.314.183.67.281 1.034.285h11.366a2.101 2.101 0 001.791-1.045 2.064 2.064 0 00-.006-2.072L9.788 2.25l-.003-.004A2.084 2.084 0 008 1.25z"/>
          </svg>
        </button>
        <router-link v-if="isAdmin" to="/usuarios/crear" class="btn btn-primary">Crear</router-link>
      </div>
    </div>
    <!-- Scroll y altura solo aquí -->
    <div class="card-panel p-4 mb-4 fade-in scroll-panel overflow-visible" style="--panel-offset: 160px;">
      <div v-show="showFilters" class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3 overflow-visible relative z-[10000]">
        <div class="relative">
          <input v-model.trim="fNombre" type="text"
                 class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-orange-400"
                 placeholder="Filtrar por nombre..." />
          <button v-if="fNombre" @click="fNombre=''" class="absolute right-2 top-2 text-white/60 hover:text-white">×</button>
        </div>
        <div class="relative">
          <input v-model.trim="fEmail" type="text"
                 class="w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-violet-400"
                 placeholder="Filtrar por email..." />
          <button v-if="fEmail" @click="fEmail=''" class="absolute right-2 top-2 text-white/60 hover:text-white">×</button>
        </div>
        <div class="relative z-[10000]" @keydown.esc="rolOpen=false" v-click-outside="() => rolOpen=false">
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="rolOpen=!rolOpen" @blur="blurCloseRolMenu">
            <span class="text-white/60" v-if="!fRol">Todos los roles</span>
            <span v-else>{{ fRol }}</span>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div v-if="rolOpen" class="absolute z-[10001] mt-1 w-full rounded-lg bg-[rgba(17,24,39,.95)] border border-white/15 shadow-xl overflow-auto max-h-60">
            <div @mousedown.prevent @click="setRolFilter('')" class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20" :class="{ 'bg-orange-500/25 font-medium': !fRol }">Todos los roles</div>
            <div v-for="r in rolOptions" :key="r.value" @mousedown.prevent @click="setRolFilter(r.value)" class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20" :class="{ 'bg-orange-500/25 font-medium': r.value === fRol }">{{ r.label }}</div>
          </div>
        </div>
      </div>
      <p v-if="error" class="text-error mb-2">{{ error }}</p>
      <p v-else-if="loading" class="text-gray-400 mb-2 ml-4">Cargando...</p>
      <DataTable v-else :headers="['ID','Nombre','Email','Rol']" :keys="['id_usuario','nombre','email','rol_nombre']" :rows="filteredItems" :showActions="isEditor" @edit="onEdit" @delete="onDelete" />

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
  </div>
  <transition name="fade">
    <div v-if="confirmOpen" class="fixed inset-0 z-[12000]" @click.self="closeConfirm">
      <div class="absolute inset-0 bg-black/60"></div>
      <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-sm rounded-xl bg-[rgba(17,24,39,.98)] border border-white/15 shadow-2xl p-5">
          <h3 class="text-lg font-semibold text-white mb-2">Confirmar eliminación</h3>
          <p class="text-white/80 mb-4">¿Seguro que deseas eliminar al usuario <span class="font-medium">{{ confirmTarget?.nombre }}</span>?</p>
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
</template>
<script setup>
import { onMounted, computed, ref } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../../stores/auth';
import { useUsuarioStore } from '../../stores/usuario';
import DataTable from '../../components/DataTable.vue';

const store = useUsuarioStore();
const auth = useAuthStore();
const items = computed(()=>store.items);
const pagination = computed(()=>store.pagination);
const loading = ref(true);
const error = ref('');

const isAdmin = computed(()=> Number(auth.user?.rol) === 1);
const isEditor = computed(()=> Number(auth.user?.rol) === 1);

const visibleItems = computed(() =>
  items.value.map(item => ({
    ...item,
    rol_nombre: item.rol_ref ? item.rol_ref.nombre : (item.rolRef ? item.rolRef.nombre : 'N/A')
  }))
);

const showFilters = ref(false);
const fNombre = ref('');
const fEmail = ref('');
const fRol = ref('');
const rolOpen = ref(false);
const roles = ref([]);
const confirmOpen = ref(false);
const confirmTarget = ref(null);
const confirmLoading = ref(false);
const confirmError = ref('');
const rolOptions = computed(() => (roles.value || []).map(r => ({
  value: r.nombre ?? r.label ?? r.rol ?? String(r.id_rol ?? r.id ?? r.value),
  label: r.nombre ?? r.label ?? r.rol ?? String(r.id_rol ?? r.id ?? r.value)
})));
const filteredItems = computed(() => {
  const list = visibleItems.value || [];
  const qNombre = (fNombre.value || '').toLowerCase();
  const qEmail = (fEmail.value || '').toLowerCase();
  const rol = fRol.value;
  return list.filter(u => {
    const byNombre = !qNombre || String(u.nombre||'').toLowerCase().includes(qNombre);
    const byEmail = !qEmail || String(u.email||'').toLowerCase().includes(qEmail);
    const byRol = !rol || String(u.rol_nombre||'') === String(rol);
    return byNombre && byEmail && byRol;
  });
});

onMounted(async ()=>{
  loading.value = true; error.value = '';
  try { await store.fetch(); } catch(e) { error.value = 'Error de conexión'; }
  // cargar roles de API para el filtro
  try {
    const endpoints = ['/api/v1/roles', '/api/v1/rol', '/api/v1/roles/list', '/api/v1/roles?per_page=1000'];
    for (const url of endpoints) {
      try {
        const { data } = await axios.get(url);
        const root = data?.datos ?? data;
        const arr = Array.isArray(root?.data) ? root.data
                  : Array.isArray(root?.roles) ? root.roles
                  : Array.isArray(root?.items) ? root.items
                  : Array.isArray(root?.lista) ? root.lista
                  : (Array.isArray(root) ? root : []);
        if (arr.length) { roles.value = arr; break; }
      } catch {}
    }
    if (!roles.value.length) {
      roles.value = [
        { id_rol: 1, nombre: 'admin' },
        { id_rol: 2, nombre: 'jefe' },
        { id_rol: 3, nombre: 'peon' }
      ];
    }
  } catch {}
  finally { loading.value = false; }
});

async function goToPage(page){
  if(page>=1 && page<=pagination.value.last_page){ await store.fetch(page); }
}

function getId(row){ return row.id_usuario ?? row.id ?? row.idUsuario; }
function onEdit(row){ const id = getId(row); if(!id) return; window.location.href = `/usuarios/editar/${id}`; }
function onDelete(row) {
  // Guardar posición de scroll actual
  const scrollY = window.scrollY;
  
  // Configurar el modal
  confirmTarget.value = row;
  confirmError.value = '';
  confirmLoading.value = false;
  confirmOpen.value = true;
  
  // Bloquear scroll del body
  document.body.style.overflow = 'hidden';
  document.body.style.position = 'fixed';
  document.body.style.top = `-${scrollY}px`;
  document.body.style.width = '100%';
}
function closeConfirm() {
  // Resetear estados
  confirmOpen.value = false;
  confirmTarget.value = null;
  confirmError.value = '';
  
  // Limpiar el estado de carga si aún está activo
  if (confirmLoading.value) {
    confirmLoading.value = false;
  }
  
  // Restaurar scroll y limpiar estilos
  document.body.style.overflow = '';
  document.body.style.position = '';
  document.body.style.top = '';
  document.body.style.width = '';
}
async function confirmDelete(){
  if(!confirmTarget.value) return;
  confirmLoading.value = true;
  confirmError.value = '';
  try {
    const id = getId(confirmTarget.value);
    // Cerrar el modal inmediatamente para feedback visual
    const wasOpen = confirmOpen.value;
    closeConfirm();
    
    // Realizar la eliminación
    if (typeof store.delete==='function') await store.delete(id);
    else if (typeof store.remove==='function') await store.remove(id);
    else if (typeof store.destroy==='function') await store.destroy(id);
    
    // Recargar datos
    await store.fetch();
  } catch(e) {
    console.error('Delete failed', e);
    let errorMsg = 'No se pudo eliminar';
    
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
      errorMsg = 'No se puede eliminar. El usuario tiene datos relacionados.';
    }
    
    confirmError.value = errorMsg;
  } finally { confirmLoading.value = false; }
}

function setRolFilter(val){ fRol.value = val; rolOpen.value = false; }

function blurCloseRolMenu() {
  setTimeout(() => {
    rolOpen.value = false;
  }, 120);
}

</script>
