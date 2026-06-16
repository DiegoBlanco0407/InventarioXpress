import '../css/app.css'
import './bootstrap'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './components/App.vue'
import router from './router'
import './stores/auth' // Importar para configurar interceptors de axios

const app = createApp(App)

// Global directive: v-click-outside
app.directive('click-outside', {
  beforeMount(el, binding) {
    el.__clickOutside__ = (e) => {
      if (!(el === e.target || el.contains(e.target))) {
        if (typeof binding.value === 'function') binding.value(e)
      }
    }
    document.addEventListener('mousedown', el.__clickOutside__)
  },
  unmounted(el) {
    document.removeEventListener('mousedown', el.__clickOutside__)
    delete el.__clickOutside__
  }
})

app
  .use(createPinia())
  .use(router)
  .mount('#app')
