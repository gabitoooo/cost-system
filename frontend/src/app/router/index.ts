import { createRouter, createWebHistory } from 'vue-router'
import { h } from 'vue'
import type { RouteRecordRaw } from 'vue-router'

// Importar rutas de cada módulo
// import authRoutes from '@/modules/auth/routes'
// import usersRoutes from '@/modules/users/routes'

const routes: RouteRecordRaw[] = [
  // ...authRoutes,
  // ...usersRoutes,
  {
    path: '/',
    component: { render: () => h('h1', 'Funciona ✔') },
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router
