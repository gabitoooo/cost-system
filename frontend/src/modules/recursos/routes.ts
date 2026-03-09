import type { RouteRecordRaw } from 'vue-router'

const recursosRoutes: RouteRecordRaw[] = [
  {
    path: '/recursos',
    component: () => import('./views/RecursosView.vue'),
    meta: { requiresAuth: true, layout: 'default' },
  },
]

export default recursosRoutes
