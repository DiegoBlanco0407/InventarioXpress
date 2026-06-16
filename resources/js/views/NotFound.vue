<template>
  <div class="min-h-[calc(100vh-160px)] w-full flex items-center justify-center px-6">
    <div class="relative max-w-xl w-full text-center glass-panel rounded-3xl p-10 border border-white/10 shadow-2xl overflow-hidden min-h-[400px] grid place-items-center">
      <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-pink-500/30 to-violet-500/30 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-gradient-to-tr from-cyan-500/20 to-orange-500/20 rounded-full blur-3xl pointer-events-none"></div>

      <div class="flex flex-col items-center justify-center gap-4">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/10 border border-white/20">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-violet-400" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 5a1 1 0 10-2 0v6a1 1 0 00.293.707l3 3a1 1 0 001.414-1.414L13 12.586V7z"/>
          </svg>
        </div>

        <h1 class="text-2xl sm:text-3xl font-bold text-white">Página no encontrada</h1>
        <p class="text-white/70">La URL que has introducido no existe o ha cambiado. Comprueba la dirección o vuelve al inicio.</p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
          <router-link to="/dashboard" class="btn btn-primary">Ir a la página principal</router-link>
          <router-link to="/" class="btn">Inicio</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
</script>
