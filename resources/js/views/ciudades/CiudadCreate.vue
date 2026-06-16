<template>
  <div>
    <h2 class="titulo-form">{{ isEdit ? 'Editar Ciudad' : 'Crear Ciudad' }}</h2>
    <form @submit.prevent="submit" class="form space-y-4 max-w-md ml-4">
      <div class="form-row">
        <label>Nombre</label>
        <input type="text" v-model="form.nombre" required maxlength="100" class="input input-bordered w-full" placeholder="Ingrese el nombre de la ciudad"/>
      </div>

      <div class="form-actions flex gap-2">
        <button type="submit" class="btn btn-primary" :disabled="loading">Guardar</button>
        <router-link to="/ciudades" class="btn">Cancelar</router-link>
      </div>

      <p v-if="success" class="text-success">{{ isEdit ? 'Ciudad actualizada' : 'Ciudad creada correctamente.' }}</p>
      <p v-if="error" class="text-error">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';
import { useCiudadStore } from '../../stores/ciudad';

const router = useRouter();
const route = useRoute();
const form = reactive({ nombre: '' });
const loading = ref(false);
const success = ref(false);
const error = ref('');
const apiBase = '/api/v1';
const store = useCiudadStore();
const isEdit = computed(()=> !!route.params.id);

onMounted(async ()=>{
  if(isEdit.value){
    loading.value = true; error.value = '';
    try{
      const { data } = await axios.get(`${apiBase}/ciudades/${route.params.id}`);
      const payload = data?.datos ?? data; const item = payload?.data ?? payload;
      form.nombre = item?.nombre || '';
    }catch(e){ error.value = 'No se pudo cargar la ciudad'; }
    finally{ loading.value = false; }
  }
});

async function submit() {
  loading.value = true; error.value = ''; success.value = false;
  try {
    const payload = { nombre: form.nombre?.trim() };
    if(isEdit.value){
      await axios.put(`${apiBase}/ciudades/${route.params.id}`, payload);
    } else {
      await store.create(payload);
    }
    success.value = true;
    setTimeout(()=> router.push('/ciudades'), 800);
  } catch (e) {
    error.value = isEdit.value ? 'No se pudo actualizar la ciudad' : 'No se pudo crear la ciudad';
  } finally { loading.value = false; }
}
</script>