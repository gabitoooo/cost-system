import type { RouteRecordRaw } from 'vue-router'

const categoriasProductoRoutes: RouteRecordRaw[] = [
  {
    path: '/categorias-producto',
    component: () => import('./views/CategoriasProductoView.vue'),
    meta: { requiresAuth: true, layout: 'default' },
  },
]

export default categoriasProductoRoutes
