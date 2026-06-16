<template>
  <div>
    <h2 class="titulo-form">Modificar pedido</h2>
    <form class="form space-y-4 max-w-md ml-4" @submit.prevent="submit">
      <div>
        <label>Fecha</label>
        <input type="date" v-model="form.fecha" required class="input input-bordered w-full" placeholder="Fecha"/>
      </div>
      <div>
        <label>Origen</label>
        <input type="text" v-model="form.origen" required maxlength="100" class="input input-bordered w-full" placeholder="Origen del pedido" />
      </div>
      <div>
        <label>Material</label>
        <div class="relative" @keydown.esc="matOpen=false" v-click-outside="() => matOpen=false">
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="matOpen=!matOpen; almOpen=false" @blur="setTimeout(()=> matOpen=false, 120)">
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
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="almOpen=!almOpen; matOpen=false" @blur="setTimeout(()=> almOpen=false, 120)">
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
        <router-link to="/pedidos" class="btn">Cancelar</router-link>
      </div>
      <p v-if="success" class="text-success">Pedido actualizado</p>
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
const form = reactive({ fecha: '', origen: '', id_material: '', id_almacen: '', cantidad: 1 });
const loading = ref(false);
const success = ref(false);
const error = ref('');
const apiBase = '/api/v1';
const materiales = ref([]);
const almacenes = ref([]);
const matOpen = ref(false);
const almOpen = ref(false);
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

onMounted(async ()=>{
  try {
    const { data } = await axios.get(`${apiBase}/materiales`);
    const payload = data?.datos ?? data;
    materiales.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
  try {
    const { data } = await axios.get(`${apiBase}/almacenes`);
    const payload = data?.datos ?? data;
    almacenes.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}

  loading.value = true; error.value = '';
  try {
    // 1) Intentar directamente cargar el detalle por id_pedido para preseleccionar
    let detallePrimario = await fetchDetalleFromApi(Number(route.params.id));

    // 2) Cargar el pedido desde índice público (evitar show protegido); si no se encuentra, como último recurso intenta show
    let item;
    try {
      const { data } = await axios.get(`${apiBase}/pedidos`);
      const root = data?.datos ?? data;
      const list = Array.isArray(root?.data) ? root.data : (Array.isArray(root) ? root : []);
      item = list.find(x => Number(x.id_pedido ?? x.id) === Number(route.params.id));
    } catch {}
    if (!item) {
      try {
        const { data } = await axios.get(`${apiBase}/pedidos/${route.params.id}`);
        const payload = data?.datos ?? data; item = payload?.data ?? payload;
      } catch {}
    }

    // 3) Relleno de campos del pedido
    form.fecha = item?.fecha || '';
    form.origen = item?.origen || '';
    const mid = [item?.id_material, item?.material?.id_material, item?.material_id, item?.material?.id].find(v => v!=null && v!=='');
    const aid = [item?.id_almacen, item?.almacen?.id_almacen, item?.almacen_id, item?.almacen?.id].find(v => v!=null && v!=='');
    form.id_material = mid ? Number(mid) : '';
    form.id_almacen = aid ? Number(aid) : '';

    // 4) Completar con detalle (API o arrays embebidos)
    if (!detallePrimario && item) detallePrimario = extractDetalle(item);
    form.cantidad = Number(item?.cantidad ?? 0);
    if (detallePrimario) {
      const didMat = [detallePrimario.id_material, detallePrimario.material?.id_material, detallePrimario.material_id, detallePrimario.material?.id].find(v => v!=null && v!=='');
      const didAlm = [detallePrimario.id_almacen, detallePrimario.almacen?.id_almacen, detallePrimario.almacen_id, detallePrimario.almacen?.id].find(v => v!=null && v!=='');
      if (!form.id_material && didMat) form.id_material = Number(didMat);
      if (!form.id_almacen && didAlm) form.id_almacen = Number(didAlm);
      if (!form.cantidad) form.cantidad = Number(detallePrimario.cantidad ?? 1);
      // Fallback por nombre
      if (!form.id_material) {
        const name = String(detallePrimario?.material?.concepto ?? detallePrimario?.material ?? '').toLowerCase();
        if (name) {
          const found = (materiales.value || []).find(m => String(m.concepto||'').toLowerCase() === name);
          if (found) form.id_material = found.id_material;
        }
      }
      if (!form.id_almacen) {
        const name = String(detallePrimario?.almacen?.nombre ?? detallePrimario?.almacen ?? '').toLowerCase();
        if (name) {
          const found = (almacenes.value || []).find(a => String(a.nombre||'').toLowerCase() === name);
          if (found) form.id_almacen = found.id_almacen;
        }
      }
    }

    if (!form.cantidad) form.cantidad = 1;
  } catch (e) {
    error.value = 'No se pudo cargar el pedido';
  } finally { loading.value = false; }
});

// Intenta varias rutas comunes para pedido_detalle utilizando id_pedido
async function fetchDetalleFromApi(idPedido){
  const tryList = async (url, params) => {
    try {
      const { data } = await axios.get(url, params ? { params } : undefined);
      const root = data?.datos ?? data;
      const list = Array.isArray(root?.data) ? root.data : (Array.isArray(root) ? root : []);
      if (Array.isArray(list) && list.length) {
        // Si viene listado completo, filtramos por id_pedido/pedido_id
        const row = list.find(d => Number(d.id_pedido ?? d.pedido_id) === Number(idPedido)) || list[0];
        return row || null;
      }
      // Si viene un objeto directo
      if (root && typeof root === 'object' && !Array.isArray(root)) return root;
    } catch (_) {}
    return null;
  };
  // Patrones con underscore y dash (singular/plural)
  const bases = ['pedido_detalle', 'pedido-detalle', 'pedido_detalles', 'pedido-detalles'];
  for (const base of bases) {
    // Query por id_pedido
    let r = await tryList(`/api/v1/${base}`, { id_pedido: idPedido });
    if (r) return r;
    // Query por pedido
    r = await tryList(`/api/v1/${base}`, { pedido: idPedido });
    if (r) return r;
    // Sin query, listado completo
    r = await tryList(`/api/v1/${base}`);
    if (r && Number(r.id_pedido ?? r.pedido_id) === Number(idPedido)) return r;
  }
  // Rutas anidadas (singular/plural del sufijo)
  const nested = ['detalle', 'detalles'];
  for (const suf of nested) {
    const r = await tryList(`/api/v1/pedidos/${idPedido}/${suf}`);
    if (r) return r;
  }
  return null;
}

function extractDetalle(pedido){
  const candidates = [
    pedido?.detalles,
    pedido?.pedido_detalles,
    pedido?.detalle,
    pedido?.items,
    pedido?.lineas,
    pedido?.line_items
  ];
  for (const c of candidates) {
    if (Array.isArray(c) && c.length) return c[0];
  }
  return null;
}

async function submit(){
  loading.value = true; error.value = ''; success.value = false;
  try {
    const payload = {
      fecha: form.fecha,
      origen: form.origen,
      id_material: Number(form.id_material),
      id_almacen: Number(form.id_almacen),
      cantidad: Number(form.cantidad)
    };
    await axios.put(`${apiBase}/pedidos/${route.params.id}`, payload);
    success.value = true;
    setTimeout(() => router.push('/pedidos'), 900);
  } catch(e) {
    error.value = 'No se pudo actualizar el pedido';
  } finally { loading.value = false; }
}
</script>
