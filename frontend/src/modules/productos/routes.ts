import type { RouteRecordRaw } from 'vue-router'

const productosRoutes: RouteRecordRaw[] = [
  {
    path: '/productos',
    component: () => import('./views/ProductosView.vue'),
    meta: { requiresAuth: true, layout: 'default' },
  },
  {
    path: '/productos/:id',
    component: () => import('./views/ProductoDetailView.vue'),
    meta: { requiresAuth: true, layout: 'default' },
  },
]

export default productosRoutes
