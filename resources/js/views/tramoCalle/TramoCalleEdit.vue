<template>
  <div>
    <h2 class="titulo-form">Modificar Tramo-Calle</h2>
    <form @submit.prevent="submit" class="form space-y-4 max-w-md ml-4">
      <div class="form-row">
        <label>Tramo</label>
        <div class="relative" @keydown.esc="trOpen=false" v-click-outside="() => trOpen=false">
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="trOpen=!trOpen; calOpen=false" @blur="setTimeout(()=> trOpen=false, 120)">
            <span v-if="!currentTramoLabel" class="text-white/60">Seleccione tramo</span>
            <span v-else>{{ currentTramoLabel }}</span>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div v-if="trOpen" class="absolute z-[70] mt-1 w-full rounded-lg bg-[rgba(17,24,39,.95)] border border-white/15 shadow-xl overflow-auto max-h-60">
            <div v-for="t in tramos" :key="t.id_tramo" @mousedown.prevent @click="selectTramo(t)" class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20" :class="{ 'bg-orange-500/25 font-medium': t.id_tramo === form.id_tramo }">{{ t.nombre }}</div>
          </div>
        </div>
      </div>

      <div class="form-row">
        <label>Calle</label>
        <div class="relative" @keydown.esc="calOpen=false" v-click-outside="() => calOpen=false">
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="calOpen=!calOpen; trOpen=false" @blur="setTimeout(()=> calOpen=false, 120)">
            <span v-if="!currentCalleLabel" class="text-white/60">Seleccione calle</span>
            <span v-else>{{ currentCalleLabel }}</span>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div v-if="calOpen" class="absolute z-[70] mt-1 w-full rounded-lg bg-[rgba(17,24,39,.95)] border border-white/15 shadow-xl overflow-auto max-h-60">
            <div v-for="c in calles" :key="c.id_calle" @mousedown.prevent @click="selectCalle(c)" class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20" :class="{ 'bg-orange-500/25 font-medium': c.id_calle === form.id_calle }">{{ c.nombre }}</div>
          </div>
        </div>
      </div>

      <div class="form-actions flex gap-2">
        <button type="submit" class="btn btn-primary" :disabled="loading">Guardar cambios</button>
        <router-link to="/tramo-calle" class="btn">Cancelar</router-link>
      </div>

      <p v-if="success" class="text-success">Relación actualizada</p>
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
const form = reactive({ id_tramo: '', id_calle: '' });
const tramos = ref([]);
const calles = ref([]);
const trOpen = ref(false);
const calOpen = ref(false);
const loading = ref(false);
const success = ref(false);
const error = ref('');

const currentTramoLabel = computed(() => {
  const id = Number(form.id_tramo || 0);
  const t = (tramos.value || []).find(x => Number(x.id_tramo) === id);
  return t ? t.nombre : '';
});
const currentCalleLabel = computed(() => {
  const id = Number(form.id_calle || 0);
  const c = (calles.value || []).find(x => Number(x.id_calle) === id);
  return c ? c.nombre : '';
});
function selectTramo(t){ form.id_tramo = t.id_tramo; trOpen.value = false; }
function selectCalle(c){ form.id_calle = c.id_calle; calOpen.value = false; }

onMounted(async () => {
  await Promise.all([fetchTramos(), fetchCalles()]);
  loading.value = true; error.value='';
  try {
    const { data } = await axios.get(`${apiBase}/tramo-calle/${route.params.id}`);
    const payload = data?.datos ?? data; const item = payload?.data ?? payload;
    form.id_tramo = item?.id_tramo ?? item?.tramo?.id_tramo ?? '';
    form.id_calle = item?.id_calle ?? item?.calle?.id_calle ?? '';
  } catch (e) { error.value = 'No se pudo cargar la relación'; }
  finally { loading.value = false; }
});

async function fetchTramos(){
  try { const { data } = await axios.get(`${apiBase}/tramos`);
    const payload = data?.datos ?? data; tramos.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
}
async function fetchCalles(){
  try { const { data } = await axios.get(`${apiBase}/calles`);
    const payload = data?.datos ?? data; calles.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
}

async function submit(){
  loading.value = true; error.value=''; success.value=false;
  try{
    const payload = { id_tramo: Number(form.id_tramo), id_calle: Number(form.id_calle) };
    await axios.put(`${apiBase}/tramo-calle/${route.params.id}`, payload);
    success.value = true;
    setTimeout(()=> router.push('/tramo-calle'), 800);
  }catch(e){ error.value = 'No se pudo actualizar la relación tramo-calle'; }
  finally{ loading.value = false; }
}
</script>
