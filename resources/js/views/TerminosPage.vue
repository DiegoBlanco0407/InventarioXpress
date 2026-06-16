<template>
  <ScrollToTop />
  <div id="top" class="min-h-screen bg-gradient-to-br from-blue-900 to-cyan-700 dark:from-gray-900 dark:to-blue-900 text-white py-16">
    <div class="container mx-auto px-6">
      <div class="max-w-4xl mx-auto bg-white/10 dark:bg-white/5 backdrop-blur-sm rounded-xl p-8 shadow-xl border border-white/20">
        <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center">Términos y Condiciones</h1>
        
        <div class="prose prose-lg prose-invert max-w-none">
          <p class="text-blue-100">
            Última actualización: 13 de noviembre de 2025
          </p>
          
          <h2 class="text-2xl font-semibold mt-8 mb-4 text-orange-300">1. Introducción</h2>
          <p>
            Bienvenido a InventarioXpress. Estos Términos y Condiciones rigen el uso de nuestra plataforma de gestión de inventarios y establecen las reglas y regulaciones para el uso de nuestros servicios.
          </p>
          <p>
            Al registrarse y utilizar nuestra plataforma, usted acepta estos términos en su totalidad. Si no está de acuerdo con estos términos, debe abstenerse de utilizar este sitio web y servicios.
          </p>
          
          <h2 class="text-2xl font-semibold mt-8 mb-4 text-orange-300">2. Definiciones</h2>
          <ul class="list-disc pl-6 space-y-2">
            <li>"Usuario", "Usted" y "Su" se refiere a la persona que accede a esta plataforma y acepta los términos y condiciones de la empresa.</li>
            <li>"La Empresa", "Nosotros", "Nuestro" y "Nos" se refiere a InventarioXpress.</li>
            <li>"Plataforma" se refiere al sitio web y servicios ofrecidos por InventarioXpress.</li>
            <li>"Servicio" se refiere a la plataforma de gestión de inventarios y todas sus funcionalidades.</li>
            <li>"Contenido" se refiere a todos los datos, información, textos, gráficos, imágenes, y otros materiales que pueden ser accedidos a través de nuestra plataforma.</li>
          </ul>
          
          <h2 class="text-2xl font-semibold mt-8 mb-4 text-orange-300">3. Uso del Servicio</h2>
          <p>
            InventarioXpress proporciona una plataforma para la gestión de inventarios, almacenes y stock. El uso de nuestros servicios está sujeto a las siguientes condiciones:
          </p>
          <ul class="list-disc pl-6 space-y-2">
            <li>Debe tener al menos 18 años o la mayoría de edad legal en su jurisdicción para utilizar este servicio.</li>
            <li>Debe proporcionar información precisa, actualizada y completa durante el proceso de registro.</li>
            <li>Es responsable de mantener la confidencialidad de su cuenta y contraseña.</li>
            <li>No debe utilizar el servicio para fines ilegales o no autorizados.</li>
            <li>No debe intentar acceder, utilizar, alterar o dañar los sistemas o datos de otros usuarios.</li>
          </ul>
          
          <h2 class="text-2xl font-semibold mt-8 mb-4 text-orange-300">4. Suscripciones y Pagos</h2>
          <p>
            Algunos aspectos de nuestro servicio pueden requerir una suscripción de pago. Al suscribirse a un plan de pago:
          </p>
          <ul class="list-disc pl-6 space-y-2">
            <li>Acepta pagar todas las tarifas aplicables según el plan seleccionado.</li>
            <li>Las suscripciones se renuevan automáticamente a menos que cancele antes del período de renovación.</li>
            <li>Puede cancelar su suscripción en cualquier momento desde la configuración de su cuenta.</li>
            <li>No ofrecemos reembolsos por períodos parciales de servicio.</li>
          </ul>
          
          <h2 class="text-2xl font-semibold mt-8 mb-4 text-orange-300">5. Propiedad Intelectual</h2>
          <p>
            El contenido, características y funcionalidad de nuestra plataforma son propiedad de InventarioXpress y están protegidos por leyes de propiedad intelectual. No puede reproducir, distribuir, modificar, crear trabajos derivados, mostrar públicamente, o utilizar cualquier contenido de nuestra plataforma sin nuestro consentimiento expreso por escrito.
          </p>
          
          <h2 class="text-2xl font-semibold mt-8 mb-4 text-orange-300">6. Limitación de Responsabilidad</h2>
          <p>
            En ningún caso InventarioXpress, sus directores, empleados o agentes serán responsables por cualquier daño indirecto, incidental, especial, consecuente o punitivo, incluyendo sin limitación, pérdida de beneficios, datos, uso, buena voluntad, u otras pérdidas intangibles, resultantes de:
          </p>
          <ul class="list-disc pl-6 space-y-2">
            <li>El uso o la imposibilidad de usar el servicio.</li>
            <li>Cualquier cambio realizado en el servicio.</li>
            <li>Acceso no autorizado o alteración de sus transmisiones o datos.</li>
            <li>Declaraciones o conducta de terceros en el servicio.</li>
            <li>Cualquier otra cuestión relacionada con el servicio.</li>
          </ul>
          
          <h2 class="text-2xl font-semibold mt-8 mb-4 text-orange-300">7. Modificaciones</h2>
          <p>
            Nos reservamos el derecho de modificar estos términos en cualquier momento. Las modificaciones entrarán en vigor inmediatamente después de su publicación en nuestra plataforma. Es su responsabilidad revisar periódicamente estos términos para estar informado de las actualizaciones.
          </p>
          
          <h2 class="text-2xl font-semibold mt-8 mb-4 text-orange-300">8. Contacto</h2>
          <p>
            Si tiene alguna pregunta sobre estos Términos y Condiciones, por favor contáctenos en:
          </p>
          <p class="mt-2">
            Email: legal@inventarioxpress.com<br>
            Teléfono: 657 70 91 64<br>
            Dirección: Calle Brasil 9A, 1º, Cuéllar, Segovia, España
          </p>
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
  name: 'TerminosPage',
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
