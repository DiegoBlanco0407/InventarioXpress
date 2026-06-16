<template>
  <div>
    <h2 class="titulo-form">{{ isEdit ? 'Editar material' : 'Crear material' }}</h2>
    <form class="form space-y-4 max-w-md ml-4" @submit.prevent="submit">
      <div>
        <label>Concepto (nombre completo del material)</label>
        <input type="text" v-model="form.concepto" required maxlength="255" class="input input-bordered w-full" placeholder="Ej: KIT AISLAMIENTO UNION 50 MM" />
      </div>
      <div class="form-actions flex gap-2">
        <button class="btn btn-primary" :disabled="loading">Guardar</button>
        <router-link to="/materiales" class="btn">Cancelar</router-link>
      </div>
      <p v-if="success" class="text-success">{{ isEdit ? 'Material actualizado' : 'Material creado correctamente.' }}</p>
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
const form = reactive({ concepto: '' });
const loading = ref(false);
const success = ref(false);
const error = ref('');
const apiBase = '/api/v1';
const isEdit = computed(()=> !!route.params.id);

onMounted(async ()=>{
  if(isEdit.value){
    loading.value = true; error.value = '';
    try{
      const { data } = await axios.get(`${apiBase}/materiales/${route.params.id}`);
      const payload = data?.datos ?? data; const item = payload?.data ?? payload;
      form.concepto = item?.concepto || '';
    }catch(e){ error.value = 'No se pudo cargar el material'; }
    finally{ loading.value = false; }
  }
});

async function submit() {
  loading.value = true; error.value = ''; success.value = false;
  try {
    const payload = { concepto: form.concepto?.trim() };
    if(isEdit.value){
      await axios.put(`${apiBase}/materiales/${route.params.id}`, payload);
    } else {
      await axios.post(`${apiBase}/materiales`, payload);
    }
    success.value = true;
    setTimeout(() => router.push('/materiales'), 900);
  } catch (e) {
    error.value = isEdit.value ? 'No se pudo actualizar el material' : 'No se pudo crear el material';
  } finally {
    loading.value = false;
  }
}
</script>
