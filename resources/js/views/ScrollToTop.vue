<template>
  <!-- Componente invisible que fuerza el desplazamiento al inicio -->
</template>

<script>
import { onBeforeMount, onMounted, nextTick } from 'vue';
import { useRoute } from 'vue-router';

export default {
  name: 'ScrollToTop',
  setup() {
    const route = useRoute();

    const forceScrollToTop = () => {
      // Forzar el desplazamiento al inicio de múltiples maneras para asegurar compatibilidad
      window.scrollTo(0, 0);
      document.documentElement.scrollTop = 0;
      document.body.scrollTop = 0; // Para Safari
    };

    onBeforeMount(() => {
      forceScrollToTop();
    });

    onMounted(() => {
      forceScrollToTop();
      
      // Forzar un segundo desplazamiento después de que el DOM se actualice
      nextTick(() => {
        forceScrollToTop();
        
        // Intentar una vez más después de un pequeño retraso
        setTimeout(() => {
          forceScrollToTop();
        }, 100);
      });
    });

    return {};
  }
};
</script>
