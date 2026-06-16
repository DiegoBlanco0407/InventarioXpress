<template>
  <div>
    <h2 class="titulo-form">Modificar salida</h2>
    <form class="form space-y-4 max-w-md ml-4" @submit.prevent="submit">
      <div>
        <label>Fecha</label>
        <input type="date" v-model="form.fecha" required class="input input-bordered w-full" placeholder="Fecha" />
      </div>
      <div>
        <label>Tramo (Calle)</label>
        <div class="relative" @keydown.esc="tcOpen=false" v-click-outside="() => tcOpen=false">
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="tcOpen=!tcOpen; matOpen=false; almOpen=false" @blur="setTimeout(()=> tcOpen=false, 120)">
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
      <div>
        <label>Material</label>
        <div class="relative" @keydown.esc="matOpen=false" v-click-outside="() => matOpen=false">
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="matOpen=!matOpen; tcOpen=false; almOpen=false" @blur="setTimeout(()=> matOpen=false, 120)">
            <span v-if="!currentMaterialLabel" class="text-white/60">Seleccione material</span>
            <span v-else>{{ currentMaterialLabel }}</span>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div v-if="matOpen" class="absolute z-[70] mt-1 w-full rounded-lg bg-[rgba(17,24,39,.95)] border border-white/15 shadow-xl overflow-auto max-h-60">
            <div v-for="m in materiales" :key="m.id_material" @mousedown.prevent @click="selectMaterial(m)" class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20" :class="{ 'bg-orange-500/25 font-medium': m.id_material === form.id_material }">{{ m.concepto }}</div>
          </div>
        </div>
      </div>
      <div>
        <label>Almacén</label>
        <div class="relative" @keydown.esc="almOpen=false" v-click-outside="() => almOpen=false">
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="almOpen=!almOpen; tcOpen=false; matOpen=false" @blur="setTimeout(()=> almOpen=false, 120)">
            <span v-if="!currentAlmLabel" class="text-white/60">Seleccione almacén</span>
            <span v-else>{{ currentAlmLabel }}</span>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div v-if="almOpen" class="absolute z-[70] mt-1 w-full rounded-lg bg-[rgba(17,24,39,.95)] border border-white/15 shadow-xl overflow-auto max-h-60">
            <div v-for="a in almacenes" :key="a.id_almacen" @mousedown.prevent @click="selectAlmacen(a)" class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20" :class="{ 'bg-orange-500/25 font-medium': a.id_almacen === form.id_almacen }">{{ a.nombre }}</div>
          </div>
        </div>
      </div>
      <div>
        <label>Cantidad</label>
        <input type="number" min="1" v-model.number="form.cantidad" required class="input input-bordered w-full" placeholder="Cantidad" />
      </div>
      <div class="form-actions flex gap-2">
        <button class="btn btn-primary" :disabled="loading">Guardar cambios</button>
        <router-link to="/salidas" class="btn">Cancelar</router-link>
      </div>
      <p v-if="success" class="text-success">Salida actualizada</p>
      <p v-if="error" class="text-error">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();
const apiBase = '/api/v1';
const form = reactive({ fecha: '', id_tramo_calle: '', id_material: '', id_almacen: '', cantidad: 1 });
const error = ref('');
const loading = ref(false);
const success = ref(false);
const tramosCalle = ref([]);
const materiales = ref([]);
const almacenes = ref([]);
const tcOpen = ref(false);
const matOpen = ref(false);
const almOpen = ref(false);

const currentTramoCalleLabel = computed(() => {
  const id = Number(form.id_tramo_calle || 0);
  const tc = (tramosCalle.value || []).find(x => Number(x.id_tramo_calle) === id);
  if (!tc) return '';
  const tramo = tc.tramo?.nombre || 'Tramo';
  const calle = tc.calle?.nombre || 'Calle';
  return `${tramo} (${calle})`;
});
const currentMaterialLabel = computed(() => {
  const id = Number(form.id_material || 0);
  const m = (materiales.value || []).find(x => Number(x.id_material) === id);
  return m ? m.concepto : '';
});
const currentAlmLabel = computed(() => {
  const id = Number(form.id_almacen || 0);
  const a = (almacenes.value || []).find(x => Number(x.id_almacen) === id);
  return a ? a.nombre : '';
});
function selectTramoCalle(tc){ form.id_tramo_calle = tc.id_tramo_calle; tcOpen.value = false; }
function selectMaterial(m){ form.id_material = m.id_material; matOpen.value = false; }
function selectAlmacen(a){ form.id_almacen = a.id_almacen; almOpen.value = false; }

onMounted(async () => {
  loading.value = true; error.value = '';
  try {
    await Promise.all([fetchTramosCalle(), fetchMateriales(), fetchAlmacenes()]);

    // 1) Intentar obtener el detalle por API pública para autoselección
    let det = await fetchSalidaDetalleFromApi(Number(route.params.id));

    // 2) Cargar la salida (show público) para fecha/tramo y, si no hay detalle aún, extraer del array "detalles"
    const { data } = await axios.get(`${apiBase}/salidas/${route.params.id}`);
    const payload = data?.datos ?? data; const item = payload?.data ?? payload;
    form.fecha = item?.fecha || new Date().toISOString().slice(0,10);
    form.id_tramo_calle = item?.id_tramo_calle ?? item?.tramo_calle?.id_tramo_calle ?? '';
    if (!det) {
      const arr = Array.isArray(item?.detalles) ? item.detalles : [];
      if (arr.length) det = arr[0];
    }

    // 3) Rellenar IDs/cantidad desde detalle; si faltan, intentar desde salida; luego fallback por nombre
    if (det) {
      const mid = [det.id_material, det.material?.id_material].find(v => v!=null && v!=='');
      const aid = [det.id_almacen, det.almacen?.id_almacen].find(v => v!=null && v!=='');
      if (mid) form.id_material = Number(mid);
      if (aid) form.id_almacen = Number(aid);
      if (!form.cantidad) form.cantidad = Number(det.cantidad ?? 1);
    }

    // Si aún no hubiese IDs, intentar campos planos en item (por compatibilidad)
    form.id_material = form.id_material || (item?.id_material ?? item?.material?.id_material ?? '');
    form.id_almacen = form.id_almacen || (item?.id_almacen ?? item?.almacen?.id_almacen ?? '');

    // Fallback por nombre si no tenemos IDs (comparación flexible)
    const norm = (s) => String(s ?? '').trim().toLowerCase();
    if (!form.id_material) {
      const name = norm(det?.material?.concepto ?? item?.material?.concepto ?? item?.material ?? '');
      if (name) {
        let found = (materiales.value || []).find(m => norm(m.concepto) === name);
        if (!found) found = (materiales.value || []).find(m => norm(m.concepto).includes(name) || name.includes(norm(m.concepto)));
        if (found) form.id_material = found.id_material;
      }
    }
    if (!form.id_almacen) {
      const name = norm(det?.almacen?.nombre ?? item?.almacen?.nombre ?? item?.almacen ?? '');
      if (name) {
        let found = (almacenes.value || []).find(a => norm(a.nombre) === name);
        if (!found) found = (almacenes.value || []).find(a => norm(a.nombre).includes(name) || name.includes(norm(a.nombre)));
        if (found) form.id_almacen = found.id_almacen;
      }
    }
    if (!form.cantidad) form.cantidad = Number(item?.cantidad ?? 1);
  } catch(e) {
    error.value = 'No se pudo cargar la salida';
  } finally { loading.value = false; }
});

async function fetchSalidaDetalleFromApi(idSalida){
  // /salida-detalle?id_salida=ID
  try {
    const { data } = await axios.get(`${apiBase}/salida-detalle`, { params: { id_salida: idSalida } });
    const root = data?.datos ?? data;
    if (root && typeof root === 'object' && !Array.isArray(root)) return root;
  } catch {}
  // /salidas/{id}/detalle
  try {
    const { data } = await axios.get(`${apiBase}/salidas/${idSalida}/detalle`);
    const root = data?.datos ?? data;
    if (root && typeof root === 'object' && !Array.isArray(root)) return root;
  } catch {}
  return null;
}

async function fetchTramosCalle(){
  try {
    const { data } = await axios.get(`${apiBase}/tramo-calle`);
    const payload = data?.datos ?? data;
    tramosCalle.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
}
async function fetchMateriales(){
  try {
    const { data } = await axios.get(`${apiBase}/materiales`);
    const payload = data?.datos ?? data;
    materiales.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
}
async function fetchAlmacenes(){
  try {
    const { data } = await axios.get(`${apiBase}/almacenes`);
    const payload = data?.datos ?? data;
    almacenes.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
}

async function submit(){
  loading.value = true; error.value = ''; success.value = false;
  try {
    const payload = {
      fecha: form.fecha,
      id_tramo_calle: Number(form.id_tramo_calle),
      id_material: Number(form.id_material),
      id_almacen: Number(form.id_almacen),
      cantidad: Number(form.cantidad)
    };
    await axios.put(`${apiBase}/salidas/${route.params.id}`, payload);
    success.value = true;
    setTimeout(()=> router.push('/salidas'), 900);
  } catch(e) {
    error.value = 'No se pudo actualizar la salida';
  } finally { loading.value = false; }
}
</script>
