<!--
  ALMACEN CREATE/EDIT - Responsive mobile-first design
  Full-width form on mobile, constrained on tablet+
-->
<template>
  <div class="w-full max-w-2xl mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6">
    <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 text-blue-200">{{ isEdit ? 'Editar almacén' : 'Crear almacén' }}</h2>
    
    <div class="bg-gray-800/50 backdrop-blur-lg rounded-2xl border border-white/10 p-4 sm:p-6">
      <form class="space-y-4 sm:space-y-5" @submit.prevent="submit">
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1.5">Nombre</label>
          <input 
            type="text" 
            v-model="form.nombre" 
            required 
            maxlength="100" 
            class="w-full px-4 py-3 rounded-xl bg-gray-700/50 border border-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" 
            placeholder="Ingrese el nombre del almacén" 
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-1.5">Ciudad</label>
          <div class="relative" @keydown.esc="ciuOpen=false" v-click-outside="() => ciuOpen=false">
            <button 
              type="button" 
              class="w-full text-left px-4 py-3 rounded-xl bg-gray-700/50 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-orange-500 pr-10 transition-all" 
              @click="ciuOpen=!ciuOpen" 
              @blur="setTimeout(()=> ciuOpen=false, 120)"
            >
              <span v-if="!currentCiudadLabel" class="text-gray-400">Seleccione ciudad</span>
              <span v-else>{{ currentCiudadLabel }}</span>
              <svg class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
            </button>
            <div v-if="ciuOpen" class="absolute z-[70] mt-2 w-full rounded-xl bg-gray-800 border border-white/15 shadow-2xl overflow-auto max-h-60">
              <div 
                v-for="c in ciudades" 
                :key="c.id_ciudad" 
                @mousedown.prevent 
                @click="selectCiudad(c)" 
                class="px-4 py-3 cursor-pointer text-white hover:bg-orange-500/20 transition-colors" 
                :class="{ 'bg-orange-500/25 font-medium': c.id_ciudad === form.ciudad }"
              >{{ c.nombre }}</div>
            </div>
          </div>
        </div>
        
        <!-- Form Actions - Full width buttons on mobile -->
        <div class="flex flex-col sm:flex-row gap-3 pt-2">
          <button 
            class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold shadow-lg shadow-orange-500/25 transition-all disabled:opacity-50" 
            :disabled="loading"
          >
            {{ loading ? 'Guardando...' : 'Guardar' }}
          </button>
          <router-link 
            to="/almacenes" 
            class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white font-medium text-center transition-all"
          >
            Cancelar
          </router-link>
        </div>
        
        <p v-if="success" class="text-green-400 text-sm p-3 bg-green-500/10 rounded-lg">{{ isEdit ? 'Almacén actualizado' : 'Almacén creado correctamente.' }}</p>
        <p v-if="error" class="text-red-400 text-sm p-3 bg-red-500/10 rounded-lg">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();
const apiBase = '/api/v1';
const form = reactive({ nombre: '', ciudad: null });
const ciudades = ref([]);
const ciuOpen = ref(false);
const currentCiudadLabel = computed(() => {
  const id = Number(form.ciudad || 0);
  const c = (ciudades.value || []).find(x => Number(x.id_ciudad) === id);
  return c ? c.nombre : '';
});
function selectCiudad(c){ form.ciudad = c.id_ciudad; ciuOpen.value = false; }
const loading = ref(false);
const success = ref(false);
const error = ref('');
const isEdit = computed(()=> !!route.params.id);

onMounted(async ()=>{
  try {
    const { data } = await axios.get(`${apiBase}/ciudades`);
    const payload = data?.datos ?? data;
    ciudades.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
  if(isEdit.value){
    loading.value = true; error.value = '';
    try{
      const { data } = await axios.get(`${apiBase}/almacenes/${route.params.id}`);
      const payload = data?.datos ?? data;
      const item = payload?.data ?? payload;
      form.nombre = item?.nombre || '';
      form.ciudad = item?.ciudad ?? item?.id_ciudad ?? null;
    }catch(e){ error.value = 'No se pudo cargar el almacén'; }
    finally{ loading.value = false; }
  }
});

async function submit() {
  loading.value = true; error.value = ''; success.value = false;
  try {
    const payload = { nombre: form.nombre?.trim(), ciudad: Number(form.ciudad) };
    if(isEdit.value){
      await axios.put(`${apiBase}/almacenes/${route.params.id}`, payload);
    } else {
      await axios.post(`${apiBase}/almacenes`, payload);
    }
    success.value = true;
    setTimeout(() => router.push('/almacenes'), 900);
  } catch (e) {
    error.value = isEdit.value ? 'No se pudo actualizar el almacén' : 'No se pudo crear el almacén';
  } finally {
    loading.value = false;
  }
}
</script>
