<template>
  <div>
    <h2 class="titulo-form">Modificar stock</h2>
    <form class="form space-y-4 max-w-md ml-4" @submit.prevent="submit">
      <div>
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
      <div>
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
      <div>
        <label>Cantidad</label>
        <input type="number" min="0" v-model.number="form.cantidad" required class="input input-bordered w-full" placeholder="Cantidad" />
      </div>
      <div>
        <label>Necesidades</label>
        <input type="number" step="any" v-model.number="form.necesidades" class="input input-bordered w-full" placeholder="Necesidades" />
      </div>
      <div>
        <label>Pedir</label>
        <input type="number" step="any" v-model.number="form.pedir" class="input input-bordered w-full" placeholder="Pedir" />
      </div>
      <div class="form-actions flex gap-2">
        <button class="btn btn-primary" :disabled="loading">Guardar cambios</button>
        <router-link to="/stock" class="btn">Cancelar</router-link>
      </div>
      <p v-if="success" class="text-success">Stock actualizado</p>
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
const form = reactive({ id_almacen: '', id_material: '', cantidad: 0, necesidades: 0, pedir: 0 });
const almacenes = ref([]);
const materiales = ref([]);
const almOpen = ref(false);
const matOpen = ref(false);
const loading = ref(false);
const success = ref(false);
const error = ref('');

const currentAlmLabel = computed(() => {
  const id = Number(form.id_almacen || 0);
  const a = (almacenes.value || []).find(x => Number(x.id_almacen) === id);
  return a ? a.nombre : '';
});
function selectAlmacen(a){ form.id_almacen = a.id_almacen; almOpen.value = false; }
const currentMaterialLabel = computed(() => {
  const id = Number(form.id_material || 0);
  const m = (materiales.value || []).find(x => Number(x.id_material) === id);
  return m ? m.concepto : '';
});
function selectMaterial(m){ form.id_material = m.id_material; matOpen.value = false; }

onMounted(async ()=>{
  await Promise.all([fetchAlmacenes(), fetchMateriales()]);
  loading.value = true; error.value='';
  try{
    const { id_almacen, id_material } = route.params;
    // show compuesto protegido: intentar y fallback a listado público
    try {
      const { data } = await axios.get(`${apiBase}/stock/${id_almacen}/${id_material}`);
      const payload = data?.datos ?? data; const item = payload?.data ?? payload;
      form.id_almacen = item?.id_almacen ?? item?.almacen?.id_almacen ?? id_almacen;
      form.id_material = item?.id_material ?? item?.material?.id_material ?? id_material;
      form.cantidad = Number(item?.cantidad ?? 0);
      form.necesidades = Number(item?.necesidades ?? 0);
      form.pedir = Number(item?.pedir ?? 0);
    } catch (_e) {
      const { data } = await axios.get(`${apiBase}/stock`);
      const root = data?.datos ?? data;
      const list = Array.isArray(root?.data) ? root.data : (Array.isArray(root) ? root : []);
      const it = list.find(x => Number(x.id_almacen) === Number(id_almacen) && Number(x.id_material) === Number(id_material));
      if (!it) throw new Error('Stock no encontrado');
      form.id_almacen = it.id_almacen;
      form.id_material = it.id_material;
      form.cantidad = Number(it.cantidad ?? 0);
      form.necesidades = Number(it.necesidades ?? 0);
      form.pedir = Number(it.pedir ?? 0);
    }
  }catch(e){ error.value = 'No se pudo cargar el stock'; }
  finally{ loading.value=false; }
});

async function fetchAlmacenes(){
  try { const { data } = await axios.get(`${apiBase}/almacenes`);
    const payload = data?.datos ?? data;
    almacenes.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
}
async function fetchMateriales(){
  try { const { data } = await axios.get(`${apiBase}/materiales`);
    const payload = data?.datos ?? data;
    materiales.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
}

async function submit(){
  loading.value = true; error.value=''; success.value=false;
  try{
    const payload = { id_almacen: Number(form.id_almacen), id_material: Number(form.id_material), cantidad: Number(form.cantidad), necesidades: Number(form.necesidades), pedir: Number(form.pedir) };
    await axios.put(`${apiBase}/stock/${form.id_almacen}/${form.id_material}`, payload);
    success.value = true;
    setTimeout(()=> router.push('/stock'), 900);
  }catch(e){ error.value = 'No se pudo actualizar el stock'; }
  finally{ loading.value = false; }
}
</script>
