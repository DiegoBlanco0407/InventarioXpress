<template>
  <div class="min-h-screen flex items-center justify-center bg-base-200 px-4">
    <div class="max-w-md w-full space-y-6">
      <div class="text-center">
        <h1 class="text-3xl font-bold">Bienvenido a Inventario<span class="text-orange-400">X</span>press</h1>
        <p class="text-gray-400 mt-2">Accede para continuar</p>
      </div>
      <div class="bg-base-100 rounded-xl shadow-2xl p-6 space-y-4">
        <router-link to="/login" class="btn btn-primary w-full">Iniciar sesión</router-link>
        <router-link to="/register" class="btn w-full">Crear cuenta</router-link>
      </div>
    </div>
  </div>
</template>
<script setup>
</script>
