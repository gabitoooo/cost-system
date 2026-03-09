import type { RouteRecordRaw } from 'vue-router'

const actividadesRoutes: RouteRecordRaw[] = [
  {
    path: '/actividades',
    component: () => import('./views/ActividadesView.vue'),
    meta: { requiresAuth: true, layout: 'default' },
  },
]

export default actividadesRoutes
