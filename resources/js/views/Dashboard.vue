<!--
  DASHBOARD - Responsive mobile-first design
  Breakpoints: 1 col (mobile) -> 2 cols (sm:640px) -> 3 cols (lg:1024px)
-->
<template>
    <div class="w-full max-w-6xl mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8">
    <h1 :class="['text-xl sm:text-2xl font-bold mb-4 sm:mb-6', themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">Panel de Control</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
      <!-- Tarjeta Almacenes -->
      <div :class="['rounded-xl p-4 sm:p-5 shadow-lg flex flex-col w-full transition-transform hover:scale-[1.02]', themeStore.isDarkMode ? 'bg-gray-800' : 'bg-white border border-blue-200']">
        <p :class="['text-xs uppercase mb-2 tracking-wider', themeStore.isDarkMode ? 'text-gray-400' : 'text-blue-600']">Almacenes</p>
        <div class="flex items-center gap-3">
          <span :class="['text-2xl sm:text-3xl font-bold', themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">{{ stats.almacenes }}</span>
          <span class="bg-teal-500 rounded-full p-2 text-white flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
          </span>
        </div>
        <p :class="['text-sm mt-2', themeStore.isDarkMode ? 'text-teal-300' : 'text-teal-700']">Activos</p>
      </div>

      <!-- Tarjeta Materiales -->
      <div :class="['rounded-xl p-4 sm:p-5 shadow-lg flex flex-col w-full transition-transform hover:scale-[1.02]', themeStore.isDarkMode ? 'bg-gray-800' : 'bg-white border border-blue-200']">
        <p :class="['text-xs uppercase mb-2 tracking-wider', themeStore.isDarkMode ? 'text-gray-400' : 'text-blue-600']">Materiales</p>
        <div class="flex items-center gap-3">
          <span :class="['text-2xl sm:text-3xl font-bold', themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">{{ stats.materiales }}</span>
          <span class="bg-blue-500 rounded-full p-2 text-white flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
          </span>
        </div>
        <p :class="['text-sm mt-2', themeStore.isDarkMode ? 'text-blue-300' : 'text-blue-700']">Registrados</p>
      </div>

      <!-- Tarjeta Pedidos -->
      <div :class="['rounded-xl p-4 sm:p-5 shadow-lg flex flex-col w-full transition-transform hover:scale-[1.02]', themeStore.isDarkMode ? 'bg-gray-800' : 'bg-white border border-blue-200']">
        <p :class="['text-xs uppercase mb-2 tracking-wider', themeStore.isDarkMode ? 'text-gray-400' : 'text-blue-600']">Pedidos</p>
        <div class="flex items-center gap-3">
          <span :class="['text-2xl sm:text-3xl font-bold', themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">{{ stats.pedidos_ultimo_mes }}</span>
          <span class="bg-orange-500 rounded-full p-2 text-white flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
          </span>
        </div>
        <p :class="['text-sm mt-2', themeStore.isDarkMode ? 'text-orange-300' : 'text-orange-700']">Este mes</p>
      </div>

      <!-- Tarjeta Ciudades activas -->
      <div :class="['rounded-xl p-4 sm:p-5 shadow-lg flex flex-col w-full transition-transform hover:scale-[1.02]', themeStore.isDarkMode ? 'bg-gray-800' : 'bg-white border border-blue-200']">
        <p :class="['text-xs uppercase mb-2 tracking-wider', themeStore.isDarkMode ? 'text-gray-400' : 'text-blue-600']">Ciudades activas</p>
        <div class="flex items-center gap-3">
          <span :class="['text-2xl sm:text-3xl font-bold', themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">{{ stats.ciudades_activas }}</span>
          <span class="bg-purple-500 rounded-full p-2 text-white flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L3 7v10l9 5 9-5V7l-9-5z"/>
            </svg>
          </span>
        </div>
        <p :class="['text-sm mt-2', themeStore.isDarkMode ? 'text-purple-300' : 'text-purple-700']">Registradas</p>
      </div>

      <!-- Tarjeta Instalaciones por terminar -->
      <div :class="['rounded-xl p-4 sm:p-5 shadow-lg flex flex-col w-full transition-transform hover:scale-[1.02]', themeStore.isDarkMode ? 'bg-gray-800' : 'bg-white border border-blue-200']">
        <p :class="['text-xs uppercase mb-2 tracking-wider', themeStore.isDarkMode ? 'text-gray-400' : 'text-blue-600']">Tramos pendientes</p>
        <div class="flex items-center gap-3">
          <span :class="['text-2xl sm:text-3xl font-bold', themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">{{ stats.instalaciones_por_terminar }}</span>
          <span class="bg-rose-500 rounded-full p-2 text-white flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8"/>
            </svg>
          </span>
        </div>
        <p :class="['text-sm mt-2', themeStore.isDarkMode ? 'text-rose-300' : 'text-rose-700']">Pendientes</p>
      </div>

      <!-- Tarjeta Salidas este mes -->
      <div :class="['rounded-xl p-4 sm:p-5 shadow-lg flex flex-col w-full transition-transform hover:scale-[1.02]', themeStore.isDarkMode ? 'bg-gray-800' : 'bg-white border border-blue-200']">
        <p :class="['text-xs uppercase mb-2 tracking-wider', themeStore.isDarkMode ? 'text-gray-400' : 'text-blue-600']">Salidas</p>
        <div class="flex items-center gap-3">
          <span :class="['text-2xl sm:text-3xl font-bold', themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">{{ stats.salidas_este_mes }}</span>
          <span class="bg-emerald-500 rounded-full p-2 text-white flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </span>
        </div>
        <p :class="['text-sm mt-2', themeStore.isDarkMode ? 'text-emerald-300' : 'text-emerald-700']">Este mes</p>
      </div>
    </div>

  </div>
</template>

<script setup>
import { onMounted, reactive } from 'vue';
import { useThemeStore } from '../stores/theme';
import axios from 'axios';

const themeStore = useThemeStore();

const stats = reactive({
  almacenes: 0,
  materiales: 0,
  pedidos_ultimo_mes: 0,
  ciudades_activas: 0,
  instalaciones_por_terminar: 0,
  salidas_este_mes: 0,
});

onMounted(async ()=>{
  try{
    const { data } = await axios.get('/api/v1/stats/dashboard');
    const payload = data?.datos ?? data;
    stats.almacenes = Number(payload.almacenes || 0);
    stats.materiales = Number(payload.materiales || 0);
    stats.pedidos_ultimo_mes = Number(payload.pedidos_ultimo_mes || 0);
    stats.ciudades_activas = Number(payload.ciudades_activas || 0);
    stats.instalaciones_por_terminar = Number(payload.instalaciones_por_terminar || 0);
    stats.salidas_este_mes = Number(payload.salidas_este_mes || 0);
  }catch(e){
    // Silencio en UI; podríamos añadir toast si se requiere
  }
});
</script>
