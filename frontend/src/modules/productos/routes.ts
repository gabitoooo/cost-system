import type { RouteRecordRaw } from 'vue-router'

const productosRoutes: RouteRecordRaw[] = [
  {
    path: '/productos',
    component: () => import('./views/ProductosView.vue'),
    meta: { requiresAuth: true, layout: 'default' },
  },
]

export default productosRoutes
