<!--
  DATATABLE - Responsive mobile-first design
  Mobile (<md): Stacked card layout with labels
  Tablet+ (md:): Traditional table layout
-->
<template>
  <div class="rounded-lg shadow">
    <!-- Desktop Table View (hidden on mobile) -->
    <table :class="['table w-full tabla-scroll mt-[6px] hidden md:table', tableClass]">
      <thead :class="theadClass">
        <tr>
          <th v-for="h in headers" :key="h" :class="[
            thClass, 
            'sticky top-0 z-50 backdrop-blur-md border-b',
            themeStore.isDarkMode 
              ? 'bg-black/30 supports-[backdrop-filter]:bg-black/20 border-white/10' 
              : 'bg-blue-100/60 supports-[backdrop-filter]:bg-blue-50/40 border-blue-200'
          ]">
            {{ h }}
          </th>
          <th v-if="showActions" :class="[
            thClass, 
            'sticky top-0 z-50 backdrop-blur-md border-b text-right',
            themeStore.isDarkMode 
              ? 'bg-black/30 supports-[backdrop-filter]:bg-black/20 border-white/10' 
              : 'bg-blue-100/60 supports-[backdrop-filter]:bg-blue-50/40 border-blue-200'
          ]">
            Acciones
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(row, i) in rows"
          :key="row.id || i"
          :class="[trClass, i % 2 === 1 ? 'row-alt' : '', 'hover:row-hover transition']"
        >
          <td v-for="k in keys" :key="k" :class="tdClass">
            {{ row[k] }}
          </td>
          <td v-if="showActions" :class="[tdClass, 'text-right']">
            <div class="inline-flex items-center gap-2">
              <button :class="[
                'btn btn-ghost btn-xs',
                themeStore.isDarkMode ? '' : 'text-blue-700 hover:text-blue-900'
              ]" title="Modificar" @click="$emit('edit', row)">
                <!-- Pencil icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                  <path d="M5.433 13.917l-.917.917a1.667 1.667 0 01-2.357-2.357l.917-.917 2.357 2.357z"/>
                  <path d="M17.008 5.442l-2.45-2.45a1.667 1.667 0 00-2.357 0L4.35 10.843l2.357 2.357 7.85-7.85a1.667 1.667 0 000-2.357z"/>
                </svg>
              </button>
              <button class="btn btn-ghost btn-xs text-red-400 hover:text-red-300" title="Eliminar" @click="$emit('delete', row)">
                <!-- Trashcan icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                  <path d="M9 3a1 1 0 00-1 1v1H4.5a.75.75 0 000 1.5H5v12A2.5 2.5 0 007.5 21h9A2.5 2.5 0 0019 18.5V6.5h.5a.75.75 0 000-1.5H16V4a1 1 0 00-1-1H9zm1.5 2h3V5H10.5V5zM8 6.5h8.5v12A1 1 0 0115.5 19h-8A1 1 0 016.5 18.5V6.5H8z"/>
                </svg>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Mobile Card View (shown only on mobile) -->
    <div class="md:hidden space-y-3">
      <div 
        v-for="(row, i) in rows" 
        :key="row.id || i"
        :class="[
          'rounded-xl p-4 border transition-all',
          themeStore.isDarkMode 
            ? 'bg-gray-800/50 border-white/10 hover:bg-gray-800/70' 
            : 'bg-white border-blue-200 hover:shadow-md'
        ]"
      >
        <!-- Card content with labels -->
        <div class="space-y-2">
          <div v-for="(k, idx) in keys" :key="k" class="flex justify-between items-center">
            <span :class="[
              'text-xs uppercase font-semibold tracking-wide',
              themeStore.isDarkMode ? 'text-gray-400' : 'text-blue-600'
            ]">{{ headers[idx] }}</span>
            <span :class="[
              'text-sm font-medium text-right',
              themeStore.isDarkMode ? 'text-white' : 'text-gray-900'
            ]">{{ row[k] }}</span>
          </div>
        </div>
        
        <!-- Card actions -->
        <div v-if="showActions" class="flex justify-end gap-2 mt-4 pt-3 border-t" :class="themeStore.isDarkMode ? 'border-white/10' : 'border-blue-100'">
          <button 
            :class="[
              'flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors',
              themeStore.isDarkMode 
                ? 'bg-blue-500/20 text-blue-300 hover:bg-blue-500/30' 
                : 'bg-blue-100 text-blue-700 hover:bg-blue-200'
            ]" 
            @click="$emit('edit', row)"
          >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
              <path d="M5.433 13.917l-.917.917a1.667 1.667 0 01-2.357-2.357l.917-.917 2.357 2.357z"/>
              <path d="M17.008 5.442l-2.45-2.45a1.667 1.667 0 00-2.357 0L4.35 10.843l2.357 2.357 7.85-7.85a1.667 1.667 0 000-2.357z"/>
            </svg>
            Editar
          </button>
          <button 
            class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-medium bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors" 
            @click="$emit('delete', row)"
          >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
              <path d="M9 3a1 1 0 00-1 1v1H4.5a.75.75 0 000 1.5H5v12A2.5 2.5 0 007.5 21h9A2.5 2.5 0 0019 18.5V6.5h.5a.75.75 0 000-1.5H16V4a1 1 0 00-1-1H9zm1.5 2h3V5H10.5V5zM8 6.5h8.5v12A1 1 0 0115.5 19h-8A1 1 0 016.5 18.5V6.5H8z"/>
            </svg>
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { useThemeStore } from '../stores/theme';

const themeStore = useThemeStore();

defineProps({
  headers: { type: Array, default: () => [] },
  keys: { type: Array, default: () => [] },
  rows: { type: Array, default: () => [] },
  tableClass: { type: String, default: 'w-full text-sm text-white/90' },
  wrapperClass: { type: String, default: 'card-panel overflow-hidden' },
  theadClass: { type: String, default: 'thead-glass' },
  thClass: { type: String, default: 'px-4 py-3 text-left text-xs font-semibold tracking-wide uppercase text-white/90' },
  trClass: { type: String, default: 'border-b border-white/5 transition-colors' },
  tdClass: { type: String, default: 'px-4 py-3 align-middle text-sm text-white/90' },
  showActions: { type: Boolean, default: false }
})
</script>
