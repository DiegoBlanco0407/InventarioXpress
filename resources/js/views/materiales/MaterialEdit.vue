<template>
  <div>
    <h2 class="titulo-form">Modificar material</h2>
    <form class="form space-y-4 max-w-md ml-4" @submit.prevent="submit">
      <div>
        <label>Concepto (nombre completo del material)</label>
        <input type="text" v-model="form.concepto" required maxlength="255" class="input input-bordered w-full" placeholder="Ej: KIT AISLAMIENTO UNION 50 MM" />
      </div>
      <div class="form-actions flex gap-2">
        <button class="btn btn-primary" :disabled="loading">Guardar cambios</button>
        <router-link to="/materiales" class="btn">Cancelar</router-link>
      </div>
      <p v-if="success" class="text-success">Material actualizado</p>
      <p v-if="error" class="text-error">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();
const form = reactive({ concepto: '' });
const loading = ref(false);
const success = ref(false);
const error = ref('');
const apiBase = '/api/v1';

onMounted(async ()=>{
  loading.value = true; error.value = '';
  try{
    // Intento 1: endpoint protegido (show)
    const { data } = await axios.get(`${apiBase}/materiales/${route.params.id}`);
    const payload = data?.datos ?? data; const item = payload?.data ?? payload;
    form.concepto = item?.concepto || '';
  }catch(e){
    // Intento 2: listado público y buscar por id
    try {
      const { data } = await axios.get(`${apiBase}/materiales`);
      const root = data?.datos ?? data;
      const list = Array.isArray(root?.data) ? root.data : (Array.isArray(root) ? root : []);
      const it = list.find(x => Number(x.id_material ?? x.id) === Number(route.params.id));
      if (it) {
        form.concepto = it.concepto || '';
      } else {
        throw new Error('Material no encontrado');
      }
    } catch (e2) {
      error.value = 'No se pudo cargar el material';
    }
  } finally { loading.value = false; }
});

async function submit() {
  loading.value = true; error.value = ''; success.value = false;
  try {
    const payload = { concepto: form.concepto?.trim() };
    await axios.put(`${apiBase}/materiales/${route.params.id}`, payload);
    success.value = true;
    setTimeout(() => router.push('/materiales'), 900);
  } catch (e) {
    error.value = 'No se pudo actualizar el material';
  } finally {
    loading.value = false;
  }
}
</script>
