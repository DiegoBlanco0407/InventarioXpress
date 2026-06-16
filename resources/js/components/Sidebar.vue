<template>
  <aside class="w-64 min-h-screen bg-base-100 border-r">
    <ul class="menu p-4">
      <li><router-link to="/">Página principal</router-link></li>
      <li><router-link to="/almacenes">Almacenes</router-link></li>
      <li><router-link to="/materiales">Materiales</router-link></li>
      <li><router-link to="/pedidos">Pedidos</router-link></li>
      <li><router-link to="/stock">Stock</router-link></li>
      <li>
        <details>
          <summary>Lugares</summary>
          <ul>
            <li><router-link to="/ciudades">Ciudades</router-link></li>
            <li><router-link to="/calles">Calles</router-link></li>
            <li><router-link to="/tramos">Tramos</router-link></li>
            <li><router-link to="/tramo-calle">Tramo-Calle</router-link></li>
          </ul>
        </details>
      </li>
      <li><router-link to="/instalaciones">Tramos de Red</router-link></li>
      <li v-if="isAdmin"><router-link to="/usuarios">Usuarios</router-link></li>
      <li><router-link to="/salidas">Salidas</router-link></li>
    </ul>
  </aside>
</template>
<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const isAdmin = computed(() => Number(auth.user?.rol) === 1)
</script>
