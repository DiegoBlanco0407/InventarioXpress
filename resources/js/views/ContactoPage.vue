<template>
  <ScrollToTop />
  <div id="top" class="min-h-screen bg-gradient-to-br from-blue-900 to-cyan-700 dark:from-gray-900 dark:to-blue-900 text-white py-16">
    <div class="container mx-auto px-6">
      <div class="max-w-4xl mx-auto bg-white/10 dark:bg-white/5 backdrop-blur-sm rounded-xl p-8 shadow-xl border border-white/20">
        <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center">Contacto</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
          <!-- Información de contacto -->
          <div>
            <h2 class="text-xl font-semibold mb-4 text-orange-300">Información de Contacto</h2>
            
            <div class="space-y-4">
              <div class="flex items-start">
                <div class="text-2xl mr-4 text-cyan-400">📍</div>
                <div>
                  <h3 class="font-medium">Dirección</h3>
                  <p class="text-blue-100">Calle Brasil 9A, 1º</p>
                  <p class="text-blue-100">40200 Cuéllar, Segovia, España</p>
                </div>
              </div>
              
              <div class="flex items-start">
                <div class="text-2xl mr-4 text-cyan-400">📞</div>
                <div>
                  <h3 class="font-medium">Teléfono</h3>
                  <p class="text-blue-100">+34 657 70 91 64</p>
                  <p class="text-blue-100">Lunes a Viernes: 9:00 - 18:00</p>
                </div>
              </div>
              
              <div class="flex items-start">
                <div class="text-2xl mr-4 text-cyan-400">✉️</div>
                <div>
                  <h3 class="font-medium">Email</h3>
                  <p class="text-blue-100">info@inventarioxpress.com</p>
                  <p class="text-blue-100">soporte@inventarioxpress.com</p>
                </div>
              </div>
              
              <div class="flex items-start">
                <div class="text-2xl mr-4 text-cyan-400">🌐</div>
                <div>
                  <h3 class="font-medium">Redes Sociales</h3>
                  <div class="flex space-x-4 mt-2">
                    <a href="#" class="text-blue-100 hover:text-white transition-colors">LinkedIn</a>
                    <a href="#" class="text-blue-100 hover:text-white transition-colors">Twitter</a>
                    <a href="#" class="text-blue-100 hover:text-white transition-colors">Facebook</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Formulario de contacto -->
          <div>
            <h2 class="text-xl font-semibold mb-4 text-orange-300">Envíanos un mensaje</h2>
            
            <form class="space-y-4">
              <div>
                <label for="nombre" class="block mb-1">Nombre</label>
                <input type="text" id="nombre" class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-white" placeholder="Tu nombre">
              </div>
              
              <div>
                <label for="email" class="block mb-1">Email</label>
                <input type="email" id="email" class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-white" placeholder="tu@email.com">
              </div>
              
              <div>
                <label for="asunto" class="block mb-1">Asunto</label>
                <input type="text" id="asunto" class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-white" placeholder="Asunto del mensaje">
              </div>
              
              <div>
                <label for="mensaje" class="block mb-1">Mensaje</label>
                <textarea id="mensaje" rows="5" class="w-full px-4 py-2 rounded-lg bg-white/10 border border-white/20 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-white" placeholder="Escribe tu mensaje aquí..."></textarea>
              </div>
              
              <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                Enviar Mensaje
              </button>
            </form>
          </div>
        </div>
        
        <div class="mt-10 pt-6 border-t border-white/20 text-center">
          <router-link to="/" class="text-cyan-300 hover:text-white transition-colors">
            &larr; Volver a la página principal
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { onBeforeMount, onMounted, nextTick } from 'vue';
import ScrollToTop from './ScrollToTop.vue';

export default {
  name: 'ContactoPage',
  components: { ScrollToTop },
  setup() {
    // Forzar el desplazamiento al inicio antes de montar el componente
    onBeforeMount(() => {
      document.documentElement.scrollTop = 0;
      document.body.scrollTop = 0; // Para Safari
    });
    
    onMounted(() => {
      // Asegurar que la página se desplaza al inicio cuando se monta el componente
      window.scrollTo(0, 0);
      
      // Forzar un segundo desplazamiento después de que el DOM se actualice
      nextTick(() => {
        window.scrollTo(0, 0);
      });
    });
  }
}
</script>
