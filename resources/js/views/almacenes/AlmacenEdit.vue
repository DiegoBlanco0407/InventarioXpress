<template>
  <div>
    <h2 class="titulo-form">Modificar almacén</h2>
    <form class="form space-y-4 max-w-md ml-4" @submit.prevent="submit">
      <div>
        <label>Nombre</label>
        <input type="text" v-model="form.nombre" required maxlength="100" class="input input-bordered w-full" placeholder="Ingrese el nombre del almacén" />
      </div>
      <div>
        <label>Ciudad</label>
        <div class="relative" @keydown.esc="ciuOpen=false" v-click-outside="() => ciuOpen=false">
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="ciuOpen=!ciuOpen" @blur="setTimeout(()=> ciuOpen=false, 120)">
            <span v-if="!currentCiudadLabel" class="text-white/60">Seleccione ciudad</span>
            <span v-else>{{ currentCiudadLabel }}</span>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div v-if="ciuOpen" class="absolute z-[70] mt-1 w-full rounded-lg bg-[rgba(17,24,39,.95)] border border-white/15 shadow-xl overflow-auto max-h-60">
            <div v-for="c in ciudades" :key="c.id_ciudad" @mousedown.prevent @click="selectCiudad(c)" class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20" :class="{ 'bg-orange-500/25 font-medium': c.id_ciudad === form.ciudad }">{{ c.nombre }}</div>
          </div>
        </div>
      </div>
      <div class="form-actions flex gap-2">
        <button class="btn btn-primary" :disabled="loading">Guardar cambios</button>
        <router-link to="/almacenes" class="btn">Cancelar</router-link>
      </div>
      <p v-if="success" class="text-success">Almacén actualizado</p>
      <p v-if="error" class="text-error">{{ error }}</p>
    </form>
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

onMounted(async ()=>{
  try {
    const { data } = await axios.get(`${apiBase}/ciudades`);
    const payload = data?.datos ?? data;
    ciudades.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
  loading.value = true; error.value = '';
  try{
    // Único llamado público: listado y búsqueda por ID
    const { data } = await axios.get(`${apiBase}/almacenes`);
    const root = data?.datos ?? data;
    const list = Array.isArray(root?.data) ? root.data : (Array.isArray(root) ? root : []);
    const it = list.find(x => Number(x.id_almacen ?? x.id) === Number(route.params.id));
    if (it) {
      form.nombre = it.nombre || '';
      form.ciudad = it.ciudad ?? it.id_ciudad ?? null;
    } else {
      throw new Error('Almacén no encontrado');
    }
  } catch (e2) {
    error.value = 'No se pudo cargar el almacén';
  }
  finally{ loading.value = false; }
});

async function submit() {
  loading.value = true; error.value = ''; success.value = false;
  try {
    const payload = { nombre: form.nombre?.trim(), ciudad: Number(form.ciudad) };
    await axios.put(`${apiBase}/almacenes/${route.params.id}`, payload);
    success.value = true;
    setTimeout(() => router.push('/almacenes'), 900);
  } catch (e) {
    error.value = 'No se pudo actualizar el almacén';
  } finally {
    loading.value = false;
  }
}
</script>
