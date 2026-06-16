<template>
  <div class="hero min-h-screen bg-base-200">
    <div class="hero-content flex-col w-full max-w-md">
      <div class="text-center">
        <h1 class="text-3xl font-bold mb-4">Crear cuenta</h1>
      </div>
      <div class="card w-full shadow-2xl bg-base-100">
        <div class="card-body space-y-3">
          <input type="text" placeholder="Nombre" class="input input-bordered w-full" v-model.trim="form.nombre" />
          <input type="email" placeholder="Email" class="input input-bordered w-full" v-model.trim="form.email" />
          <input type="password" placeholder="Contraseña (min 6)" class="input input-bordered w-full" v-model="form.password" />
          <input type="password" placeholder="Confirmar contraseña" class="input input-bordered w-full" v-model="form.password_confirmation" />
          <button class="btn btn-primary w-full" @click="submit" :disabled="loading">Crear cuenta</button>
          <p v-if="error" class="text-error">{{ error }}</p>
          <p v-if="success" class="text-success">Cuenta creada. Redirigiendo…</p>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { reactive, ref, computed } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const auth = useAuthStore();
const router = useRouter();
const form = reactive({ nombre: '', email: '', password: '', password_confirmation: '' });
const loading = computed(()=>auth.loading);
const error = computed(()=>auth.error);
const success = ref(false);

async function submit(){
  if(!form.nombre || !form.email || !form.password || !form.password_confirmation){
    auth.error = 'Completa todos los campos'; return;
  }
  if(form.password.length < 6){ auth.error = 'La contraseña debe tener al menos 6 caracteres'; return; }
  if(form.password !== form.password_confirmation){ auth.error = 'Las contraseñas no coinciden'; return; }
  const ok = await auth.register(form);
  if(ok){ success.value = true; router.push('/dashboard'); }
}
</script>
