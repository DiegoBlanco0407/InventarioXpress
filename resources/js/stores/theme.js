import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useThemeStore = defineStore('theme', () => {
  // Estado - por defecto es modo oscuro
  const isDarkMode = ref(localStorage.getItem('darkMode') !== 'false');
  
  // Acciones
  function toggleDarkMode() {
    isDarkMode.value = !isDarkMode.value;
    updateTheme();
  }
  
  function setDarkMode(value) {
    isDarkMode.value = value;
    updateTheme();
  }
  
  function updateTheme() {
    if (isDarkMode.value) {
      document.documentElement.classList.remove('light-mode');
      localStorage.setItem('darkMode', 'true');
    } else {
      document.documentElement.classList.add('light-mode');
      localStorage.setItem('darkMode', 'false');
    }
  }
  
  // Inicializar tema al cargar
  function initTheme() {
    updateTheme();
  }
  
  return {
    isDarkMode,
    toggleDarkMode,
    setDarkMode,
    updateTheme,
    initTheme
  };
});
