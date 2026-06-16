<template>
  <div>
    <h2 class="titulo-form">Crear Instalación</h2>
    <form @submit.prevent="submit" class="form space-y-4 max-w-md ml-4">
      <div class="form-row">
        <label>Tramo (Calle)</label>
        <select v-model.number="form.id_tramo_calle" required class="select select-bordered w-full">
          <option value="" disabled>Seleccione tramo (calle)</option>
          <option v-for="tc in tramosCalle" :key="tc.id_tramo_calle" :value="tc.id_tramo_calle">
            {{ (tc.tramo?.nombre || 'Tramo') + ' (' + (tc.calle?.nombre || 'Calle') + ')' }}
          </option>
        </select>
      </div>

      <div class="form-row">
        <label>Material</label>
        <select v-model.number="form.id_material" required class="select select-bordered w-full">
          <option value="" disabled>Seleccione material</option>
          <option v-for="m in materiales" :key="m.id_material" :value="m.id_material">{{ m.concepto }}</option>
        </select>
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
        <button type="submit" class="btn btn-primary" :disabled="loading">Guardar</button>
        <router-link to="/instalaciones" class="btn">Cancelar</router-link>
      </div>

      <p v-if="success" class="text-success">Instalación creada correctamente.</p>
      <p v-if="error" class="text-error">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const apiBase = '/api/v1';

const form = reactive({ id_tramo_calle: '', id_material: '', instalado: 0, a_instalar: 0 });
const tramosCalle = ref([]);
const materiales = ref([]);
const loading = ref(false);
const success = ref(false);
const error = ref('');

onMounted(async () => {
  await Promise.all([fetchTramosCalle(), fetchMateriales()]);
});

async function fetchTramosCalle() {
  try {
    const { data } = await axios.get(`${apiBase}/tramo-calle`);
    const payload = data?.datos ?? data;
    tramosCalle.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
}

async function fetchMateriales() {
  try {
    const { data } = await axios.get(`${apiBase}/materiales`);
    const payload = data?.datos ?? data;
    materiales.value = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
  } catch {}
}

async function submit() {
  loading.value = true; error.value = ''; success.value = false;
  try {
    const selected = (tramosCalle.value || []).find(x => x.id_tramo_calle === Number(form.id_tramo_calle));
    const payload = {
      id_tramo: Number(selected?.id_tramo || 0),
      id_tramo_calle: Number(form.id_tramo_calle),
      id_material: Number(form.id_material),
      instalado: Number(form.instalado),
      a_instalar: Number(form.a_instalar)
    };
    if (!payload.id_tramo) throw new Error('Seleccione un tramo (calle) válido');
    await axios.post(`${apiBase}/instalaciones`, payload);

    success.value = true;
    setTimeout(()=> router.push('/instalaciones'), 900);
  } catch (e) {
    error.value = 'No se pudo crear la instalación';
  } finally { loading.value = false; }
}
</script>