<template>
  <div class="min-h-[calc(100vh-160px)] w-full flex items-center justify-center px-6">
    <div class="relative max-w-xl w-full text-center glass-panel rounded-3xl p-10 border border-white/10 shadow-2xl overflow-hidden min-h-[400px] grid place-items-center">
      <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-orange-500/30 to-violet-500/30 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-gradient-to-tr from-cyan-500/20 to-pink-500/20 rounded-full blur-3xl pointer-events-none"></div>

      <div class="flex flex-col items-center justify-center gap-4">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/10 border border-white/20">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-orange-400" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M12 2.25c-.414 0-.75.336-.75.75v7.5a.75.75 0 001.5 0V3a.75.75 0 00-.75-.75zm0 13.5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
          </svg>
        </div>

        <h1 class="text-2xl sm:text-3xl font-bold text-white">Acceso no autorizado</h1>
        <p class="text-white/70">No tienes permisos para acceder a esta página. Si crees que es un error, contacta con un administrador.</p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
          <router-link :to="backUrl" class="btn btn-primary">Volver</router-link>
          <router-link v-if="!isAuthed" to="/login" class="btn">Iniciar sesión</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const route = useRoute()
const auth = useAuthStore()
const isAuthed = computed(()=> auth.isAuthenticated)
const backUrl = computed(()=> route.query?.redirect || '/dashboard')
</script>
