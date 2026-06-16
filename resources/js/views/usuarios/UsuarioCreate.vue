<template>
  <div>
    <h2 class="titulo-form">Crear Usuario</h2>
    <form @submit.prevent="submit" class="form space-y-4 max-w-md ml-4">
      <div class="">
        <label>Nombre</label>
        <input type="text" v-model="form.nombre" required maxlength="100" class="input input-bordered w-full" placeholder="Ingrese el nombre del usuario"/>
      </div>

      <div class="">
        <label>Email</label>
        <input type="email" v-model="form.email" required class="input input-bordered w-full" placeholder="Ingrese el email del usuario"/>
      </div>

      <div class="form-row">
        <label>Rol</label>
        <div class="relative" @keydown.esc="roleOpen=false" v-click-outside="() => roleOpen=false">
          <button
            type="button"
            class="w-full text-left px-3 py-2 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-orange-400 pr-10"
            @click="roleOpen = !roleOpen"
            @blur="setTimeout(()=> roleOpen=false, 120)"
          >
            <span v-if="!currentRoleLabel" class="text-white/60">Selecciona el rol</span>
            <span v-else>{{ currentRoleLabel }}</span>
            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
            </svg>
          </button>
          <div
            v-if="roleOpen"
            class="absolute z-30 mt-1 w-full rounded-lg bg-[rgba(17,24,39,.95)] border border-white/15 shadow-xl overflow-auto max-h-60"
          >
            <div
              v-for="r in roles"
              :key="r.id_rol || r.id || r.value"
              @mousedown.prevent
              @click="selectRole(r)"
              class="px-3 py-2 cursor-pointer text-white hover:bg-orange-500/20"
              :class="{ 'bg-orange-500/25 font-medium': (r.id_rol || r.id || r.value) === form.rol }"
            >
              {{ r.nombre || r.label }}
            </div>
          </div>
        </div>
      </div>

      <div class="form-row">
        <label>Password</label>
        <input type="password" v-model="form.password" required minlength="6" class="input input-bordered w-full" placeholder="Ingrese la contraseña del usuario"/>
      </div>

      <div class="form-actions flex gap-2">
        <button type="submit" class="btn btn-primary" :disabled="loading">Guardar</button>
        <router-link to="/usuarios" class="btn">Cancelar</router-link>
      </div>

      <p v-if="success" class="text-success">Usuario creado correctamente.</p>
      <p v-if="error" class="text-error">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const router = useRouter();
const apiBase = '/api/v1';
const auth = useAuthStore();
const isAdmin = computed(()=> Number(auth.user?.rol) === 1);

const form = reactive({ nombre: '', email: '', rol: null, password: '' });
const loading = ref(false);
const success = ref(false);
const error = ref('');
const roles = ref([]);
const roleOpen = ref(false);
const currentRoleLabel = computed(() => {
  const id = form.rol;
  const r = (roles.value || []).find(x => (x.id_rol || x.id || x.value) === id);
  return r ? (r.nombre || r.label) : '';
});
function selectRole(r){
  form.rol = r.id_rol ?? r.id ?? r.value;
  roleOpen.value = false;
}

async function submit() {
  loading.value = true; error.value = ''; success.value = false;
  try {
    const payload = { ...form };
    // Si rol se deja vacío, eliminarlo
    if (payload.rol === null || payload.rol === '') delete payload.rol;
    await axios.post(`${apiBase}/usuarios`, payload);
    success.value = true;
    setTimeout(()=> router.push('/usuarios'), 900);
  } catch (e) {
    console.error('Error al crear usuario:', e);
    // Manejar errores de validación de Laravel
    if (e.response?.status === 422) {
      const errors = e.response?.data?.errors || {};
      if (errors.email) {
        error.value = 'El email ya está registrado';
      } else {
        error.value = e.response?.data?.message || 'Error de validación';
      }
    } else if (e.response?.data?.message || e.response?.data?.mensaje) {
      error.value = e.response.data.message || e.response.data.mensaje;
    } else {
      error.value = 'No se pudo crear el usuario';
    }
  } finally { loading.value = false; }
}

// La autorización se gestiona en el router via meta.requiresAdmin
onMounted(async () => {
  try {
    const endpoints = ['/api/v1/roles', '/api/v1/rol', '/api/v1/roles/list', '/api/v1/roles?per_page=1000'];
    let loaded = false;
    for (const url of endpoints) {
      try {
        const { data } = await axios.get(url);
        console.log('Roles response from', url, data);
        const root = data?.datos ?? data;
        const arr = Array.isArray(root?.data) ? root.data
                  : Array.isArray(root?.roles) ? root.roles
                  : Array.isArray(root?.items) ? root.items
                  : Array.isArray(root?.lista) ? root.lista
                  : (Array.isArray(root) ? root : []);
        if (Array.isArray(arr) && arr.length) {
          roles.value = arr.map(r => ({
            id_rol: r.id_rol ?? r.id ?? r.value,
            nombre: r.nombre ?? r.label ?? r.rol ?? r.nombre_rol ?? String(r.id_rol ?? r.id ?? r.value)
          }));
          loaded = true;
          break;
        }
      } catch (e) {
        console.warn('Roles fetch failed for', url, e?.response?.status);
      }
    }
    if (!loaded) {
      console.error('No se pudieron cargar roles desde los endpoints probados. Usando fallback local.');
      roles.value = [
        { id_rol: 1, nombre: 'Admin' },
        { id_rol: 2, nombre: 'Jefe' },
        { id_rol: 3, nombre: 'Peón' },
      ];
    }
  } finally {}
});
</script>