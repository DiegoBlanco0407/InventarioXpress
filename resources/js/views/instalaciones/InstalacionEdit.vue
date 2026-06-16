<template>
  <div>
    <h2 class="titulo-form">Modificar Tramo de red</h2>
    <form @submit.prevent="submit" class="form space-y-4 max-w-md ml-4">
      <div class="form-row">
        <label>Tramo (Calle)</label>
        <div class="relative" @keydown.esc="tcOpen=false" v-click-outside="() => tcOpen=false">
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="tcOpen=!tcOpen" @blur="setTimeout(()=> tcOpen=false, 120)">
            <span v-if="!currentTramoCalleLabel" class="text-white/60">Seleccione tramo (calle)</span>
            <span v-else>{{ currentTramoCalleLabel }}</span>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div v-if="tcOpen" class="absolute z-[70] mt-1 w-full rounded-lg bg-[rgba(17,24,39,.95)] border border-white/15 shadow-xl overflow-auto max-h-60">
            <div v-for="tc in tramosCalle" :key="tc.id_tramo_calle" @mousedown.prevent @click="selectTramoCalle(tc)" class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20" :class="{ 'bg-orange-500/25 font-medium': tc.id_tramo_calle === form.id_tramo_calle }">
              {{ (tc.tramo?.nombre || 'Tramo') + ' (' + (tc.calle?.nombre || 'Calle') + ')' }}
            </div>
          </div>
        </div>
      </div>

      <div class="form-row">
        <label>Material</label>
        <div class="relative" @keydown.esc="matOpen=false" v-click-outside="() => matOpen=false">
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="matOpen=!matOpen" @blur="setTimeout(()=> matOpen=false, 120)">
            <span v-if="!currentMaterialLabel" class="text-white/60">Seleccione material</span>
            <span v-else>{{ currentMaterialLabel }}</span>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div v-if="matOpen" class="absolute z-[70] mt-1 w-full rounded-lg bg-[rgba(17,24,39,.95)] border border-white/15 shadow-xl overflow-auto max-h-60">
            <div v-for="m in materiales" :key="m.id_material" @mousedown.prevent @click="selectMaterial(m)" class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20" :class="{ 'bg-orange-500/25 font-medium': m.id_material === form.id_material }">{{ m.concepto }}</div>
          </div>
        </div>
      </div>

      <div class="form-row">
        <label>Almacén</label>
        <div class="relative" @keydown.esc="almOpen=false" v-click-outside="() => almOpen=false">
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="almOpen=!almOpen" @blur="setTimeout(()=> almOpen=false, 120)">
            <span v-if="!currentAlmLabel" class="text-white/60">Seleccione almacén</span>
            <span v-else>{{ currentAlmLabel }}</span>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div v-if="almOpen" class="absolute z-[70] mt-1 w-full rounded-lg bg-[rgba(17,24,39,.95)] border border-white/15 shadow-xl overflow-auto max-h-60">
            <div v-for="a in almacenes" :key="a.id_almacen" @mousedown.prevent @click="selectAlmacen(a)" class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20" :class="{ 'bg-orange-500/25 font-medium': a.id_almacen === form.id_almacen }">{{ a.nombre }}</div>
          </div>
        </div>
      </div>

      <div class="form-row">
        <label>Instalado</label>
        <input type="number" min="0" v-model.number="form.instalado" required class="input input-bordered w-full" />
      </div>

      <div class="form-row">
        <label>A instalar</label>
        <input type="number" min="0" v-model.number="form.a_instalar" required class="input input-bordered w-full" />
      </div>

      <div class="form-actions flex gap-2">
        <button type="submit" class="btn btn-primary" :disabled="loading">Guardar cambios</button>
        <router-link to="/instalaciones" class="btn">Cancelar</router-link>
      </div>

      <p v-if="success" class="text-success">Instalación actualizada</p>
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

const form = reactive({ id_tramo_calle: '', id_material: '', instalado: 0, a_instalar: 0, id_almacen: '' });
const tramosCalle = ref([]);
const materiales = ref([]);
const almacenes = ref([]);
const tcOpen = ref(false);
const matOpen = ref(false);
const almOpen = ref(false);
const loading = ref(false);
const success = ref(false);
const error = ref('');

const currentTramoCalleLabel = computed(() => {
  const id = Number(form.id_tramo_calle || 0);
  const tc = (tramosCalle.value || []).find(x => Number(x.id_tramo_calle) === id);
  if (!tc) return '';
  const tramo = tc.tramo?.nombre || 'Tramo';
  const calle = tc.calle?.nombre || 'Calle';
  return `${tramo} (${calle})`;
});
function selectTramoCalle(tc){ form.id_tramo_calle = tc.id_tramo_calle; tcOpen.value = false; }

const currentMaterialLabel = computed(() => {
  const id = Number(form.id_material || 0);
  const m = (materiales.value || []).find(x => Number(x.id_material) === id);
  return m ? m.concepto : '';
});
function selectMaterial(m){ form.id_material = m.id_material; matOpen.value = false; }

const currentAlmLabel = computed(() => {
  const id = Number(form.id_almacen || 0);
  const a = (almacenes.value || []).find(x => Number(x.id_almacen) === id);
  return a ? a.nombre : '';
});
function selectAlmacen(a){ form.id_almacen = a.id_almacen; almOpen.value = false; }

onMounted(async () => {
  await Promise.all([fetchTramosCalle(), fetchMateriales(), fetchAlmacenes()]);
  loading.value = true; error.value = '';
  try {
    const { id_tramo, id_material, id_almacen } = route.params;
    const url = `${apiBase}/instalaciones/${id_tramo}/${id_material}/${id_almacen}`;
    const { data } = await axios.get(url);
    const payload = data?.datos ?? data; const item = payload?.data ?? payload;
    form.id_tramo_calle = item?.id_tramo_calle ?? item?.tramo_calle?.id_tramo_calle ?? '';
    form.id_material = item?.id_material ?? item?.material?.id_material ?? '';
    form.instalado = Number(item?.instalado ?? 0);
    form.a_instalar = Number(item?.a_instalar ?? 0);
    form.id_almacen = item?.id_almacen ?? item?.almacen?.id_almacen ?? '';

    // Fallback: si no tenemos id_tramo_calle, intentar deducirlo
    if (!form.id_tramo_calle) {
      const norm = (s) => String(s ?? '').trim().toLowerCase();
      // 1) Por ids de tramo/calle si existen en la respuesta o en params
      const tramoId = Number(item?.id_tramo ?? item?.tramo_calle?.id_tramo ?? route.params.id_tramo ?? 0);
      const calleId = Number(item?.id_calle ?? item?.tramo_calle?.id_calle ?? 0);
      let found = null;
      if (tramoId && calleId) {
        found = (tramosCalle.value || []).find(tc => Number(tc.id_tramo) === tramoId && Number(tc.id_calle) === calleId);
      }
      // 2) Por nombres, si no se encontró por ids
      if (!found) {
        const tramoName = norm(item?.tramo_calle?.tramo?.nombre ?? item?.tramo?.nombre);
        const calleName = norm(item?.tramo_calle?.calle?.nombre ?? item?.calle?.nombre);
        if (tramoName || calleName) {
          found = (tramosCalle.value || []).find(tc => {
            const tn = norm(tc?.tramo?.nombre);
            const cn = norm(tc?.calle?.nombre);
            const tramoOk = !tramoName || tn === tramoName || tn.includes(tramoName) || tramoName.includes(tn);
            const calleOk = !calleName || cn === calleName || cn.includes(calleName) || calleName.includes(cn);
            return tramoOk && calleOk;
          });
        }
      }
      if (found) form.id_tramo_calle = found.id_tramo_calle;
    }
  } catch (e) {
    error.value = 'No se pudo cargar la instalación';
  } finally { loading.value = false; }
});

async function fetchTramosCalle(){
  try { const { data } = await axios.get(`${apiBase}/tramo-calle`);
    const payload = data?.datos ?? data;
    tramosCalle.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
}
async function fetchMateriales(){
  try { const { data } = await axios.get(`${apiBase}/materiales`);
    const payload = data?.datos ?? data;
    materiales.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
}
async function fetchAlmacenes(){
  try { const { data } = await axios.get(`${apiBase}/almacenes`);
    const payload = data?.datos ?? data;
    almacenes.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
}

async function submit(){
  loading.value = true; error.value = ''; success.value = false;
  try {
    const selected = (tramosCalle.value || []).find(x => x.id_tramo_calle === Number(form.id_tramo_calle));
    const payload = {
      id_tramo: Number(selected?.id_tramo || 0),
      id_tramo_calle: Number(form.id_tramo_calle),
      id_material: Number(form.id_material),
      instalado: Number(form.instalado),
      a_instalar: Number(form.a_instalar),
      id_almacen: Number(form.id_almacen)
    };
    const { id_tramo, id_material, id_almacen } = route.params;
    const url = `${apiBase}/instalaciones/${id_tramo}/${id_material}/${id_almacen}`;
    await axios.put(url, payload);
    success.value = true;
    setTimeout(()=> router.push('/instalaciones'), 900);
  } catch (e) {
    error.value = 'No se pudo actualizar la instalación';
  } finally { loading.value = false; }
}
</script>
