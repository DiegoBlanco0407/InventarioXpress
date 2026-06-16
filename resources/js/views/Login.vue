<!--
  LOGIN - Responsive mobile-first design
  Full-width on mobile, centered card on tablet+
-->
<template>
  <div class="min-h-screen flex items-center justify-center px-4 py-8 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900">
    <div class="w-full max-w-sm sm:max-w-md space-y-6">
      <!-- Logo/Title -->
      <div class="text-center">
        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">
          Inventario<span class="text-orange-400">X</span>press
        </h1>
        <p class="text-blue-200 text-sm sm:text-base">Inicia sesión para continuar</p>
      </div>
      
      <!-- Login Card -->
      <div class="bg-gray-800/50 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/10 p-6 sm:p-8">
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
            <input 
              type="email" 
              placeholder="tu@email.com" 
              class="w-full px-4 py-3 rounded-xl bg-gray-700/50 border border-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" 
              v-model="email" 
              @keyup.enter="doLogin" 
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1.5">Contraseña</label>
            <input 
              type="password" 
              placeholder="••••••••" 
              class="w-full px-4 py-3 rounded-xl bg-gray-700/50 border border-white/10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" 
              v-model="password" 
              @keyup.enter="doLogin" 
            />
          </div>
          <button 
            class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold shadow-lg shadow-orange-500/25 transition-all disabled:opacity-50 disabled:cursor-not-allowed mt-2" 
            @click="doLogin" 
            :disabled="loading"
          >
            <span v-if="loading" class="flex items-center justify-center gap-2">
              <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Entrando...
            </span>
            <span v-else>Entrar</span>
          </button>
          <p v-if="error" class="text-red-400 text-sm text-center mt-2 p-2 bg-red-500/10 rounded-lg">{{ error }}</p>
        </div>
      </div>
      
      <!-- Back to home link -->
      <div class="text-center">
        <router-link to="/" class="text-blue-300 hover:text-white text-sm transition-colors">
          ← Volver al inicio
        </router-link>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, computed } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useThemeStore } from '../stores/theme';
import { useRouter } from 'vue-router';

const router = useRouter();
const auth = useAuthStore();
const themeStore = useThemeStore();
const email = ref('');
const password = ref('');
const loading = computed(()=>auth.loading);
const error = computed(()=>auth.error);

async function doLogin(){
  await auth.login(email.value, password.value);
  if(auth.token){
    themeStore.setDarkMode(true);
    router.push('/');
  }
}
</script>
