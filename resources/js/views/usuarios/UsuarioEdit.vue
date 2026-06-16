<template>
  <div>
    <h2 class="titulo-form">Modificar Usuario</h2>
    <form @submit.prevent="submit" class="form space-y-4 max-w-md ml-4">
      <div class="form-row">
        <label>Nombre</label>
        <input v-model="form.nombre" type="text" required class="input input-bordered w-full" />
      </div>

      <div class="form-row">
        <label>Email</label>
        <input v-model="form.email" type="email" required class="input input-bordered w-full" />
      </div>

      <div class="form-row">
        <label>Nueva Contraseña (dejar en blanco para no cambiar)</label>
        <input 
          v-model="form.password" 
          type="password" 
          class="input input-bordered w-full" 
          :class="{ 'border-red-500': form.password && form.password !== form.password_confirmation }"
        />
      </div>

      <div class="form-row">
        <label>Confirmar Nueva Contraseña</label>
        <input 
          v-model="form.password_confirmation" 
          type="password" 
          class="input input-bordered w-full"
          :class="{ 'border-red-500': form.password && form.password !== form.password_confirmation }"
        />
        <p v-if="form.password && form.password !== form.password_confirmation" class="text-red-500 text-sm mt-1">
          Las contraseñas no coinciden
        </p>
      </div>

      <div class="form-row">
        <label>Rol</label>
        <div class="relative" @keydown.esc="rolOpen=false" v-click-outside="() => rolOpen=false">
          <button type="button" class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10" @click="rolOpen=!rolOpen" @blur="setTimeout(()=> rolOpen=false, 120)">
            <span v-if="!form.rol">Seleccione un rol</span>
            <span v-else>{{ roles.find(r => r.id_rol == form.rol)?.nombre || form.rol }}</span>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div v-if="rolOpen" class="absolute z-[70] mt-1 w-full rounded-lg bg-[rgba(17,24,39,.95)] border border-white/15 shadow-xl overflow-auto max-h-60">
            <div v-for="rol in roles" :key="rol.id_rol" @mousedown.prevent @click="selectRol(rol)" class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20" :class="{ 'bg-orange-500/25 font-medium': rol.id_rol === form.rol }">
              {{ rol.nombre }}
            </div>
          </div>
        </div>
      </div>

      <div class="form-actions flex gap-2">
        <button type="submit" class="btn btn-primary" :disabled="loading">
          <span v-if="loading" class="loading loading-spinner"></span>
          Guardar cambios
        </button>
        <router-link to="/usuarios" class="btn">Cancelar</router-link>
      </div>

      <p v-if="success" class="text-success">Usuario actualizado correctamente</p>
      <p v-if="error" class="text-error">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();
const apiBase = '/api/v1';

const loading = ref(false);
const success = ref(false);
const error = ref('');
const roles = ref([]);
const rolOpen = ref(false);

const form = reactive({
  nombre: '',
  email: '',
  password: '',
  password_confirmation: '',
  rol: ''
});

// Fetch user data
onMounted(async () => {
  loading.value = true;
  error.value = '';
  
  try {
    // Fetch roles and user data in parallel
    const [rolesRes, userRes] = await Promise.all([
      axios.get(`${apiBase}/roles`),
      axios.get(`${apiBase}/usuarios/${route.params.id}`)
    ]);
    
    // Normalize roles response so it's always an array
    const rawRoles = rolesRes.data?.datos ?? rolesRes.data?.data ?? rolesRes.data ?? [];
    roles.value = Array.isArray(rawRoles)
      ? rawRoles
      : (Array.isArray(rawRoles?.data) ? rawRoles.data : []);
    
    // Extract user data from the response
    const userData = userRes.data?.datos;
    
    if (!userData) {
      throw new Error('No se encontraron datos del usuario');
    }
    
    // Debug: log the full response
    console.log('Full API Response:', userRes.data);
    console.log('Extracted user data:', userData);
    
    // Update form with user data - using the exact property names from the response
    form.nombre = userData.nombre || '';
    form.email = userData.email || '';
    form.rol = userData.rol_ref?.id_rol || userData.rol || '';
    
    // Debug: log the form data after setting
    console.log('Form data after setting:', JSON.parse(JSON.stringify(form)));
    
  } catch (err) {
    console.error('Error loading user data:', err);
    error.value = `Error al cargar los datos del usuario: ${err.message || 'Error desconocido'}`;
    
    // If it's a 404, the user might not exist
    if (err.response?.status === 404) {
      error.value = 'Usuario no encontrado';
    }
  } finally {
    loading.value = false;
  }
});

function selectRol(rol) {
  form.rol = rol.id_rol;
  rolOpen.value = false;
}

async function submit() {
  if (loading.value) return;
  
  // Check if passwords match if a new password is provided
  if (form.password && form.password !== form.password_confirmation) {
    error.value = 'Las contraseñas no coinciden';
    return;
  }
  
  loading.value = true;
  success.value = false;
  error.value = '';
  
  try {
    const payload = {
      nombre: form.nombre,
      email: form.email,
      rol: form.rol
    };
    
    // Only include password if it was changed and confirmed
    if (form.password && form.password === form.password_confirmation) {
      payload.password = form.password;
      payload.password_confirmation = form.password_confirmation;
    }
    
    await axios.put(`${apiBase}/usuarios/${route.params.id}`, payload);
    
    success.value = true;
    setTimeout(() => {
      router.push('/usuarios');
    }, 1000);
    
  } catch (err) {
    const errorData = err.response?.data;
    error.value = errorData?.message || 'Error al actualizar el usuario';
    console.error('Update error:', err);
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.titulo-form {
  @apply text-2xl font-bold mb-6 text-white;
}

.form-row {
  @apply space-y-1;
}

.form-row label {
  @apply block text-sm font-medium text-gray-300;
}

.input {
  @apply w-full px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-orange-400;
}

.btn {
  @apply px-4 py-2 rounded-lg font-medium transition-colors;
}

.btn-primary {
  @apply bg-orange-500 text-white hover:bg-orange-600 disabled:bg-orange-400;
}

.btn-ghost {
  @apply bg-transparent text-white hover:bg-white/10;
}

.text-success {
  @apply text-green-400;
}

.text-error {
  @apply text-red-400;
}
</style>
