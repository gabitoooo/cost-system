import { createApp } from 'vue'
import App from './app/App.vue'
import router from './app/router'
import pinia from './app/store'
import axiosPlugin from './app/plugins/axios'
import { useAuthStore } from './modules/auth/store/authStore'
import './assets/styles/main.css'

async function bootstrap() {
  const app = createApp(App)
  app.use(pinia)
  app.use(router)
  app.use(axiosPlugin)

  // Si hay token guardado, recupera el usuario UNA SOLA VEZ antes de montar la app.
  // El interceptor de http.ts ya maneja el 401 (limpia token + redirige).
  const token = localStorage.getItem('token')
  if (token) {
    const authStore = useAuthStore()
    await authStore.fetchMe().catch(() => {})
  }

  app.mount('#app')
}

bootstrap()
