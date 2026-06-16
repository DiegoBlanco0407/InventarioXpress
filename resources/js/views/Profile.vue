<template>
  <div class="max-w-4xl mx-auto mt-8 px-4" v-if="mounted">
    <h1 :class="['text-2xl font-bold mb-6', isDarkMode ? 'text-white' : 'text-blue-800']">Mi perfil</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Perfil -->
      <div :class="['rounded-xl shadow-2xl p-6', isDarkMode ? 'bg-base-100' : 'bg-white border border-blue-100']">
        <div class="flex items-center gap-4">
          <div class="relative">
            <div :class="['group w-16 h-16 rounded-full overflow-hidden cursor-pointer flex items-center justify-center relative', isDarkMode ? 'bg-white/10' : 'bg-blue-100']" @click="triggerAvatar" title="Cambiar imagen">
              <img v-if="auth.user?.ruta_imagen" :src="auth.user.ruta_imagen" alt="avatar" class="w-full h-full object-cover" />
              <svg v-else xmlns="http://www.w3.org/2000/svg" :class="['h-8 w-8', isDarkMode ? 'text-white/80' : 'text-blue-700']" viewBox="0 0 52 52" fill="currentColor" aria-hidden="true">
                <path d="M50,43v2.2c0,2.6-2.2,4.8-4.8,4.8H6.8C4.2,50,2,47.8,2,45.2V43c0-5.8,6.8-9.4,13.2-12.2 c0.2-0.1,0.4-0.2,0.6-0.3c0.5-0.2,1-0.2,1.5,0.1c2.6,1.7,5.5,2.6,8.6,2.6s6.1-1,8.6-2.6c0.5-0.3,1-0.3,1.5-0.1 c0.2,0.1,0.4,0.2,0.6,0.3C43.2,33.6,50,37.1,50,43z M26,2c6.6,0,11.9,5.9,11.9,13.2S32.6,28.4,26,28.4s-11.9-5.9-11.9-13.2 S19.4,2,26,2z"/>
              </svg>
              <!-- Hover overlay -->
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white text-[10px]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mb-0.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M9 3a1 1 0 00-.894.553L7.382 5H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2h-2.382l-.724-1.447A1 1 0 0013 3H9zm3 4a5 5 0 110 10 5 5 0 010-10zm0 2a3 3 0 100 6 3 3 0 000-6z" />
                </svg>
                <span>Cambiar</span>
              </div>
            </div>
            <input type="file" accept="image/*" class="hidden" ref="avatarInput" @change="onAvatarSelected" />
          </div>
          <div>
            <p :class="['text-lg font-semibold', isDarkMode ? 'text-white' : 'text-blue-800']">{{ auth.user?.nombre }}</p>
            <p :class="[isDarkMode ? 'text-white/70' : 'text-blue-600']">{{ auth.user?.email }}</p>
            <p v-if="avatarError" class="text-error text-sm mt-1">{{ avatarError }}</p>
          </div>
        </div>
        <div class="mt-6 grid grid-cols-1 gap-3 max-w-md">
          <div>
            <label :class="['text-sm', isDarkMode ? 'text-white/60' : 'text-blue-700']">Nombre</label>
            <input class="input input-bordered w-full" v-model="profile.nombre" />
          </div>
          <div>
            <label :class="['text-sm', isDarkMode ? 'text-white/60' : 'text-blue-700']">Email</label>
            <input class="input input-bordered w-full" v-model="profile.email" />
          </div>
          <div class="flex gap-2">
            <button class="btn btn-primary" @click="saveProfile" :disabled="profileLoading">Guardar</button>
            <span v-if="profileSuccess" class="text-success self-center">Guardado</span>
          </div>
          <p v-if="profileError" class="text-error">{{ profileError }}</p>
        </div>
      </div>

      <!-- Cambiar contraseña -->
      <div :class="['rounded-xl shadow-2xl p-6', isDarkMode ? 'bg-base-100' : 'bg-white border border-blue-100']">
        <h2 :class="['text-lg font-semibold mb-4', isDarkMode ? 'text-white' : 'text-blue-800']">Cambiar contraseña</h2>
        <div class="space-y-3 max-w-md">
          <input type="password" class="input input-bordered w-full" placeholder="Contraseña actual" v-model="form.old_password" />
          <input type="password" class="input input-bordered w-full" placeholder="Nueva contraseña" v-model="form.password" />
          <input type="password" class="input input-bordered w-full" placeholder="Confirmar nueva contraseña" v-model="form.password_confirmation" />
          <div class="flex gap-2">
            <button class="btn btn-primary" @click="submit" :disabled="loading">Actualizar</button>
            <span v-if="success" class="text-success self-center">Actualizada</span>
          </div>
          <p v-if="error" class="text-error">{{ error }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { reactive, ref, watch, onMounted, computed } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';
import { useThemeStore } from '../stores/theme';

const auth = useAuthStore();
const themeStore = useThemeStore();
const mounted = ref(false);
const isDarkMode = computed(() => themeStore.isDarkMode);
const form = reactive({ old_password: '', password: '', password_confirmation: '' });
const loading = ref(false);
const error = ref('');
const success = ref(false);

const profile = reactive({ nombre: auth.user?.nombre || '', email: auth.user?.email || '' });
const profileLoading = ref(false);
const profileError = ref('');
const profileSuccess = ref(false);

const avatarInput = ref(null);
const avatarError = ref('');

async function submit(){
  error.value = ''; success.value = false; loading.value = true;
  try{
    await axios.post('/api/v1/auth/change-password', form);
    success.value = true;
    form.old_password = form.password = form.password_confirmation = '';
  }catch(e){
    error.value = e?.response?.data?.mensaje || 'No se pudo actualizar la contraseña';
  }finally{
    loading.value = false;
  }
}

async function saveProfile(){
  profileError.value = ''; profileSuccess.value = false; profileLoading.value = true;
  try{
    const ok = await auth.updateProfile({ nombre: profile.nombre, email: profile.email });
    profileSuccess.value = !!ok;
  }catch(e){
    profileError.value = auth.error || 'No se pudo guardar el perfil';
  }finally{
    profileLoading.value = false;
  }
}

function triggerAvatar(){
  avatarError.value = '';
  avatarInput.value?.click();
}

async function onAvatarSelected(event){
  const file = event.target.files && event.target.files[0];
  if(!file) return;
  try{
    const ok = await auth.uploadAvatar(file);
    if(!ok){
      avatarError.value = auth.error || 'No se pudo subir la imagen';
    }
  }catch(e){
    avatarError.value = auth.error || 'No se pudo subir la imagen';
  }finally{
    event.target.value = '';
  }
}

// Mantener el formulario sincronizado con el usuario cargado tras recargar la página
watch(() => auth.user, (u) => {
  if (u) {
    profile.nombre = u.nombre || '';
    profile.email = u.email || '';
  }
}, { immediate: true });

// Establecer mounted a true después de que el componente se monte
onMounted(() => {
  mounted.value = true;
});
</script>
