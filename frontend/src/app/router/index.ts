import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/modules/auth/store/authStore'
import { setRedirectHandler } from '@/app/api/http'

import authRoutes from '@/modules/auth/routes'
import dashboardRoutes from '@/modules/dashboard/routes'

const routes: RouteRecordRaw[] = [
  { path: '/', redirect: '/login' },
  ...authRoutes,
  ...dashboardRoutes,
  { path: '/:pathMatch(.*)*', redirect: '/login' },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

// Registra el redirect handler para que http.ts pueda redirigir al login desde el interceptor
setRedirectHandler((path) => router.push(path))

// Guard global — se ejecuta antes de cada navegación
router.beforeEach(async (to) => {
  const token = localStorage.getItem('token')

  // Ruta protegida sin token → redirige al login
  if (to.meta.requiresAuth && !token) return '/login'

  // Ya autenticado intentando ir al login → redirige al dashboard
  if (to.path === '/login' && token) return '/dashboard'

  // Si hay token pero no hay usuario en memoria (recarga de página) → recupera el usuario
  if (token) {
    const authStore = useAuthStore()
    if (!authStore.user) {
      try {
        await authStore.fetchMe()
      } catch {
        // El interceptor ya limpió el token — cancelamos la navegación actual
        return '/login'
      }
    }
  }
})

export default router
