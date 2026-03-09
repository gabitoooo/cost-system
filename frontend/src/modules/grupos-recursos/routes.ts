import type { RouteRecordRaw } from 'vue-router'

const gruposRecursosRoutes: RouteRecordRaw[] = [
  {
    path: '/centros-costo',
    component: () => import('./views/GruposRecursosView.vue'),
    meta: { requiresAuth: true, layout: 'default' },
  },
]

export default gruposRecursosRoutes
