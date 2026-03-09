import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import { setRedirectHandler } from '@/app/api/http'

import authRoutes from '@/modules/auth/routes'
import dashboardRoutes from '@/modules/dashboard/routes'
import departamentosRoutes from '@/modules/departamentos/routes'
import gruposRecursosRoutes from '@/modules/grupos-recursos/routes'
import recursosRoutes from '@/modules/recursos/routes'
import actividadesRoutes from '@/modules/actividades/routes'
import inductoresTiempoRoutes from '@/modules/inductores-tiempo/routes'
import productosRoutes from '@/modules/productos/routes'
import cotizacionesRoutes from '@/modules/cotizaciones/routes'

const routes: RouteRecordRaw[] = [
  { path: '/', redirect: '/login' },
  ...authRoutes,
  ...dashboardRoutes,
  ...departamentosRoutes,
  ...gruposRecursosRoutes,
  ...recursosRoutes,
  ...actividadesRoutes,
  ...inductoresTiempoRoutes,
  ...productosRoutes,
  ...cotizacionesRoutes,
  { path: '/:pathMatch(.*)*', redirect: '/login' },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

// Registra el redirect handler para que http.ts pueda redirigir al login desde el interceptor
setRedirectHandler((path) => router.push(path))

// Guard global — checks sincrónicos únicamente.
// fetchMe() se ejecuta una sola vez en main.ts antes de montar la app.
router.beforeEach((to) => {
  const token = localStorage.getItem('token')

  // Ruta protegida sin token → redirige al login
  if (to.meta.requiresAuth && !token) return '/login'

  // Ya autenticado intentando ir al login → redirige al dashboard
  if (to.path === '/login' && token) return '/dashboard'
})

export default router
