<template>
  <div class="landing-page" :class="{ 'light-mode': !isDarkMode }">
    <!-- Hero Section -->
    <section 
      :class="[
        'hero min-h-screen text-white flex items-center relative overflow-hidden',
        isDarkMode ? 'bg-gradient-to-br from-gray-900 to-blue-900' : 'bg-gradient-to-br from-blue-600 to-cyan-500'
      ]"
      ref="heroSection">
      
      <!-- Animated Background Elements -->
      <div class="absolute inset-0 overflow-hidden">
        <div v-for="i in 8" :key="i" 
             :class="[
               'absolute rounded-full bg-gradient-to-r floating-bubble',
               isDarkMode ? 'from-cyan-600/20 to-blue-800/10' : 'from-white/60 to-blue-50/70',
               i % 3 === 0 ? 'bubble-reverse' : ''
             ]"
             :style="{
               width: `${Math.random() * 40 + 10}rem`,
               height: `${Math.random() * 40 + 10}rem`,
               left: `${Math.random() * 100}%`,
               top: `${Math.random() * 100}%`,
               animationDuration: `${Math.random() * 15 + 30}s`, /* Duración más larga para movimiento más lento */
               animationDelay: `${Math.random() * 10}s`,
               animationFillMode: 'both', /* Mantiene los valores de inicio y fin */
               animationPlayState: 'running'
             }">
        </div>
      </div>
      
      <div class="container mx-auto px-6 z-10 relative">
        <div class="max-w-3xl fade-in" ref="heroContent">
          <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
            Gestiona tu inventario con <span :class="isDarkMode ? 'text-orange-300' : 'text-orange-500'">inteligencia</span>
          </h1>
          <p :class="[
            'text-xl md:text-2xl mb-10',
            isDarkMode ? 'text-blue-200' : 'text-blue-100'
          ]">
            El futuro de la gestión de inventarios está aquí. Simplifica lo complejo.
          </p>
          <div class="flex flex-wrap gap-4">
            <!-- Mostrar diferentes opciones según estado de autenticación -->
            <div v-if="auth.isAuthenticated">
              <router-link to="/dashboard" class="btn btn-primary bg-orange-500 hover:bg-orange-600 border-none px-8 py-3 rounded-lg text-white font-semibold transition-all">
                Ir a mi Dashboard
              </router-link>
              <button 
                @click="logout"
                class="btn btn-outline border-2 border-white hover:bg-white/10 px-8 py-3 rounded-lg text-white font-semibold transition-all ml-4">
                Cerrar Sesión
              </button>
            </div>
            <div v-else>
              <router-link to="/login" :class="[
                'btn btn-primary px-8 py-3 rounded-lg font-bold text-white transition-all shadow-lg',
                isDarkMode ? 'bg-orange-500 hover:bg-orange-600 border-none' : 'bg-blue-600 hover:bg-blue-700 border-2 border-blue-500 shadow-blue-400/30'
              ]">
                Iniciar Sesión
              </router-link>
              <div 
                class="tooltip-container relative inline-block ml-4"
                @mouseenter="showTooltip = true"
                @mouseleave="showTooltip = false"
              >
                <button 
                  class="btn btn-outline border-2 border-white hover:bg-white/10 px-8 py-3 rounded-lg text-white font-semibold transition-all cursor-not-allowed opacity-90 hover:opacity-100"
                  disabled
                >
                  Comenzar Gratis
                </button>
                <div 
                  class="tooltip absolute z-10 w-auto p-3 -mt-1 text-sm leading-tight text-white transform -translate-x-1/2 -translate-y-full left-1/2 rounded-lg shadow-lg"
                  :class="{'tooltip-show': showTooltip, 'tooltip-hide': !showTooltip}"
                >
                  <div class="tooltip-content bg-gradient-to-r from-blue-600 to-cyan-600 backdrop-blur-sm rounded-lg p-2 shadow-xl border border-white/20">
                    <p>¡Próximamente disponible!</p>
                    <svg class="tooltip-arrow absolute text-cyan-600 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255"><polygon class="fill-current" points="0,0 127.5,127.5 255,0"/></svg>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Scroll Indicator -->
      <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
      </div>
    </section>

    <!-- About Section -->
    <section :class="[
      'py-20',
      isDarkMode ? 'bg-gray-900' : 'bg-white'
    ]" ref="aboutSection">
      <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center slide-up" ref="aboutContent">
          <h2 :class="[
            'text-3xl md:text-4xl font-bold mb-6',
            isDarkMode ? 'text-blue-300' : 'text-blue-700'
          ]">Sobre InventarioXpress</h2>
          <p :class="[
            'text-lg md:text-xl leading-relaxed',
            isDarkMode ? 'text-gray-300' : 'text-gray-600'
          ]">
            La solución definitiva para optimizar la gestión de almacenes, controlar stock en tiempo real y maximizar la eficiencia operativa de tu empresa.
          </p>
          <div :class="[
            'mt-10 p-6 rounded-xl backdrop-blur-sm shadow-lg bg-gradient-to-r',
            isDarkMode ? 'from-blue-900/40 to-cyan-900/30' : 'from-blue-50 to-cyan-50'
          ]">
            <p :class="[
              'text-xl font-semibold',
              isDarkMode ? 'text-blue-200' : 'text-blue-700'
            ]">
              "Tu almacén, bajo control"
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section :class="[
      'py-20',
      isDarkMode ? 'bg-gray-800' : 'bg-gray-100'
    ]" ref="featuresSection">
      <div class="container mx-auto px-6">
        <h2 :class="[
          'text-3xl md:text-4xl font-bold mb-16 text-center',
          isDarkMode ? 'text-blue-300' : 'text-blue-700'
        ]">Características Clave</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <!-- Feature Card 1 -->
          <div class="feature-card" ref="featureCard1">
            <div :class="[
              'rounded-xl p-8 shadow-lg hover:shadow-xl transition-all h-full flex flex-col items-center text-center transform hover:-translate-y-1',
              isDarkMode ? 'bg-gray-900' : 'bg-white'
            ]">
              <div class="text-5xl mb-6">📦</div>
              <h3 :class="[
                'text-xl font-bold mb-4',
                isDarkMode ? 'text-blue-300' : 'text-blue-700'
              ]">Control en Tiempo Real</h3>
              <p :class="[
                isDarkMode ? 'text-gray-400' : 'text-gray-600'
              ]">
                Visibilidad instantánea de stock, movimientos y ubicaciones para tomar decisiones informadas.
              </p>
            </div>
          </div>
          
          <!-- Feature Card 2 -->
          <div class="feature-card" ref="featureCard2">
            <div :class="[
              'rounded-xl p-8 shadow-lg hover:shadow-xl transition-all h-full flex flex-col items-center text-center transform hover:-translate-y-1',
              isDarkMode ? 'bg-gray-900' : 'bg-white'
            ]">
              <div class="text-5xl mb-6">📍</div>
              <h3 :class="[
                'text-xl font-bold mb-4',
                isDarkMode ? 'text-blue-300' : 'text-blue-700'
              ]">Gestión Multi-almacén</h3>
              <p :class="[
                isDarkMode ? 'text-gray-400' : 'text-gray-600'
              ]">
                Administra múltiples ubicaciones desde un solo panel centralizado y unificado.
              </p>
            </div>
          </div>
          
          <!-- Feature Card 3 -->
          <div class="feature-card" ref="featureCard3">
            <div :class="[
              'rounded-xl p-8 shadow-lg hover:shadow-xl transition-all h-full flex flex-col items-center text-center transform hover:-translate-y-1',
              isDarkMode ? 'bg-gray-900' : 'bg-white'
            ]">
              <div class="text-5xl mb-6">📊</div>
              <h3 :class="[
                'text-xl font-bold mb-4',
                isDarkMode ? 'text-blue-300' : 'text-blue-700'
              ]">Reportes Inteligentes</h3>
              <p :class="[
                isDarkMode ? 'text-gray-400' : 'text-gray-600'
              ]">
                Analytics y métricas para decisiones basadas en datos que impulsan tu negocio.
              </p>
            </div>
          </div>
          
          <!-- Feature Card 4 -->
          <div class="feature-card" ref="featureCard4">
            <div :class="[
              'rounded-xl p-8 shadow-lg hover:shadow-xl transition-all h-full flex flex-col items-center text-center transform hover:-translate-y-1',
              isDarkMode ? 'bg-gray-900' : 'bg-white'
            ]">
              <div class="text-5xl mb-6">🔄</div>
              <h3 :class="[
                'text-xl font-bold mb-4',
                isDarkMode ? 'text-blue-300' : 'text-blue-700'
              ]">Trazabilidad Completa</h3>
              <p :class="[
                isDarkMode ? 'text-gray-400' : 'text-gray-600'
              ]">
                Seguimiento detallado de entradas, salidas y transferencias en todo momento.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 bg-gradient-to-br from-gray-900 to-blue-900 light:from-blue-600 light:to-cyan-500 text-white" ref="benefitsSection">
      <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
          <h2 class="text-3xl md:text-4xl font-bold mb-16">Beneficios</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Benefit 1 -->
            <div class="benefit-item" ref="benefitItem1">
              <div class="bg-white/5 light:bg-white/20 backdrop-blur-sm rounded-xl p-6 hover:bg-white/10 light:hover:bg-white/30 transition-all h-full">
                <h3 class="text-xl font-bold mb-4 text-orange-300 light:text-orange-500">Reduce Errores</h3>
                <p class="text-blue-100 light:text-blue-800">
                  Minimiza errores humanos con sistemas automatizados de control y validación.
                </p>
              </div>
            </div>
            
            <!-- Benefit 2 -->
            <div class="benefit-item" ref="benefitItem2">
              <div class="bg-white/5 light:bg-white/20 backdrop-blur-sm rounded-xl p-6 hover:bg-white/10 light:hover:bg-white/30 transition-all h-full">
                <h3 class="text-xl font-bold mb-4 text-orange-300 light:text-orange-500">Ahorra Tiempo</h3>
                <p class="text-blue-100 light:text-blue-800">
                  Optimiza procesos y elimina tareas repetitivas para enfocarte en lo importante.
                </p>
              </div>
            </div>
            
            <!-- Benefit 3 -->
            <div class="benefit-item" ref="benefitItem3">
              <div class="bg-white/5 light:bg-white/20 backdrop-blur-sm rounded-xl p-6 hover:bg-white/10 light:hover:bg-white/30 transition-all h-full">
                <h3 class="text-xl font-bold mb-4 text-orange-300 light:text-orange-500">Escala tu Negocio</h3>
                <p class="text-blue-100 light:text-blue-800">
                  Crece sin preocupaciones con una plataforma diseñada para escalar contigo.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer :class="[
      'py-12 text-white',
      isDarkMode ? 'bg-gray-950' : 'bg-blue-800'
    ]">
      <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Company Info -->
          <div>
            <h3 class="text-xl font-bold mb-4">InventarioXpress</h3>
            <p :class="isDarkMode ? 'text-blue-300' : 'text-blue-100'">
              La plataforma moderna de gestión integral de almacenes e inventarios.
            </p>
          </div>
          
          <!-- Quick Links -->
          <div>
            <h3 class="text-xl font-bold mb-4">Enlaces Rápidos</h3>
            <ul class="space-y-2">
              <li><router-link to="/login" :class="[isDarkMode ? 'text-blue-300' : 'text-blue-100', 'hover:text-white transition-colors']">Iniciar Sesión</router-link></li>
              <li><router-link :to="{ path: '/contacto', hash: '#top' }" :class="[isDarkMode ? 'text-blue-300' : 'text-blue-100', 'hover:text-white transition-colors']">Contacto</router-link></li>
              <li><router-link :to="{ path: '/terminos', hash: '#top' }" :class="[isDarkMode ? 'text-blue-300' : 'text-blue-100', 'hover:text-white transition-colors']">Términos y Condiciones</router-link></li>
              <li><router-link :to="{ path: '/privacidad', hash: '#top' }" :class="[isDarkMode ? 'text-blue-300' : 'text-blue-100', 'hover:text-white transition-colors']">Política de Privacidad</router-link></li>
            </ul>
          </div>
          
          <!-- Dark Mode Toggle -->
          <div>
            <h3 class="text-xl font-bold mb-4">Preferencias</h3>
            <div class="flex items-center">
              <span class="mr-3">{{ isDarkMode ? 'Modo Oscuro' : 'Modo Claro' }}</span>
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" :checked="isDarkMode" @click="toggleDarkMode" class="sr-only peer">
                <div :class="[
                  'w-11 h-6 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all',
                  isDarkMode ? 'bg-gray-700 peer-checked:bg-cyan-600' : 'bg-blue-300 peer-checked:bg-blue-600'
                ]"></div>
              </label>
            </div>
          </div>
        </div>
        
        <div :class="[
          'border-t mt-8 pt-8 text-center',
          isDarkMode ? 'border-gray-700' : 'border-blue-700'
        ]">
          <p :class="isDarkMode ? 'text-blue-400' : 'text-blue-100'">
            &copy; InventarioXpress 2025. Todos los derechos reservados.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useThemeStore } from '../stores/theme';

export default {
  name: 'LandingPage',
  setup() {
    // Auth store
    const auth = useAuthStore();
    const themeStore = useThemeStore();
    
    // Tooltip state
    const showTooltip = ref(false);
    
    // Logout function
    const logout = () => {
      auth.logout();
    };
    
    // Dark mode state: leemos directamente del themeStore
    const isDarkMode = computed(() => themeStore.isDarkMode);
    
    // Método para cambiar entre modo oscuro y claro usando el store global
    const toggleDarkMode = () => {
      themeStore.toggleDarkMode();
    };
    
    // Refs for intersection observer
    const heroSection = ref(null);
    const heroContent = ref(null);
    const aboutSection = ref(null);
    const aboutContent = ref(null);
    const featuresSection = ref(null);
    const featureCard1 = ref(null);
    const featureCard2 = ref(null);
    const featureCard3 = ref(null);
    const featureCard4 = ref(null);
    const benefitsSection = ref(null);
    const benefitItem1 = ref(null);
    const benefitItem2 = ref(null);
    const benefitItem3 = ref(null);
    
    // Intersection Observer for animations
    let observers = [];

    onMounted(() => {
      // Setup smooth scrolling
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href');
          const targetElement = document.querySelector(targetId);
          if (targetElement) {
            targetElement.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });
      });
      
      // Setup intersection observers for animations
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -10% 0px'
      };
      
      const createObserver = (element, className) => {
        if (!element.value) return;
        
        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add(className);
              observer.unobserve(entry.target);
            }
          });
        }, observerOptions);
        
        observer.observe(element.value);
        observers.push(observer);
      };
      
      // Apply observers to elements
      createObserver(heroContent, 'active');
      createObserver(aboutContent, 'active');
      createObserver(featureCard1, 'active');
      createObserver(featureCard2, 'active');
      createObserver(featureCard3, 'active');
      createObserver(featureCard4, 'active');
      createObserver(benefitItem1, 'active');
      createObserver(benefitItem2, 'active');
      createObserver(benefitItem3, 'active');
    });
    
    onUnmounted(() => {
      // Cleanup observers
      observers.forEach(observer => observer.disconnect());
    });
    
    return {
      auth,
      logout,
      isDarkMode,
      toggleDarkMode,
      showTooltip,
      heroSection,
      heroContent,
      aboutSection,
      aboutContent,
      featuresSection,
      featureCard1,
      featureCard2,
      featureCard3,
      featureCard4,
      benefitsSection,
      benefitItem1,
      benefitItem2,
      benefitItem3
    };
  }
};
</script>

<style scoped>
/* Base animations */
@keyframes float {
  0% {
    transform: translate(0, 0) scale(1);
  }
  10% {
    transform: translate(5px, -5px) scale(1.01);
  }
  20% {
    transform: translate(10px, -10px) scale(1.015);
  }
  30% {
    transform: translate(12px, -15px) scale(1.02);
  }
  40% {
    transform: translate(8px, -20px) scale(1.035);
  }
  50% {
    transform: translate(0, -25px) scale(1.05);
  }
  60% {
    transform: translate(-8px, -20px) scale(1.035);
  }
  70% {
    transform: translate(-12px, -15px) scale(1.02);
  }
  80% {
    transform: translate(-10px, -10px) scale(1.015);
  }
  90% {
    transform: translate(-5px, -5px) scale(1.01);
  }
  100% {
    transform: translate(0, 0) scale(1);
  }
}

@keyframes float-reverse {
  0% {
    transform: translate(0, 0) scale(1);
  }
  10% {
    transform: translate(-3px, -4px) scale(1.005);
  }
  20% {
    transform: translate(-8px, -8px) scale(1.01);
  }
  30% {
    transform: translate(-12px, -10px) scale(1.015);
  }
  40% {
    transform: translate(-8px, -15px) scale(1.03);
  }
  50% {
    transform: translate(0, -20px) scale(1.05);
  }
  60% {
    transform: translate(8px, -15px) scale(1.03);
  }
  70% {
    transform: translate(12px, -10px) scale(1.015);
  }
  80% {
    transform: translate(8px, -8px) scale(1.01);
  }
  90% {
    transform: translate(3px, -4px) scale(1.005);
  }
  100% {
    transform: translate(0, 0) scale(1);
  }
}

.floating-bubble {
  animation-name: float;
  animation-iteration-count: infinite;
  animation-timing-function: cubic-bezier(0.445, 0.05, 0.55, 0.95); /* Más suave que ease-in-out */
  opacity: 0.4;
  will-change: transform;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.light-mode .floating-bubble {
  opacity: 0.8;
  box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3), inset 0 5px 15px rgba(255, 255, 255, 0.5);
  backdrop-filter: blur(2px);
}

.bubble-reverse {
  animation-name: float-reverse;
}

/* Fade in animation */
.fade-in {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 1s ease, transform 1s ease;
}

.fade-in.active {
  opacity: 1;
  transform: translateY(0);
}

/* Slide up animation */
.slide-up {
  opacity: 0;
  transform: translateY(40px);
  transition: opacity 0.8s ease, transform 0.8s ease;
}

.slide-up.active {
  opacity: 1;
  transform: translateY(0);
}

/* Feature cards and benefit items animations */
.feature-card, .benefit-item {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}

.feature-card.active, .benefit-item.active {
  opacity: 1;
  transform: translateY(0);
}

/* Stagger animation delays */
.feature-card:nth-child(2), .benefit-item:nth-child(2) {
  transition-delay: 0.2s;
}

.feature-card:nth-child(3), .benefit-item:nth-child(3) {
  transition-delay: 0.4s;
}

.feature-card:nth-child(4) {
  transition-delay: 0.6s;
}

/* Dark mode transition */
.landing-page {
  transition: background-color 0.5s ease, color 0.5s ease;
}

/* Transiciones para todos los elementos */
.landing-page * {
  transition: background-color 0.5s ease, color 0.5s ease, border-color 0.5s ease;
}

/* Tooltip animations */
.tooltip-container {
  position: relative;
  display: inline-block;
  cursor: help;
}

.tooltip {
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  transform: translate(-50%, -100%) scale(0.9);
  pointer-events: none;
}

.tooltip-show {
  opacity: 1;
  visibility: visible;
  transform: translate(-50%, -100%) scale(1);
}

.tooltip-hide {
  opacity: 0;
  visibility: hidden;
  transform: translate(-50%, -100%) scale(0.9);
}

.tooltip-content {
  position: relative;
  backdrop-filter: blur(10px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  min-width: 150px;
  text-align: center;
}

.tooltip-arrow {
  filter: drop-shadow(0 2px 2px rgba(0, 0, 0, 0.1));
}
</style>
