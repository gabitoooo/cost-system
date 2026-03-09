import type { RouteRecordRaw } from 'vue-router'

const departamentosRoutes: RouteRecordRaw[] = [
  {
    path: '/departamentos',
    component: () => import('./views/DepartamentosView.vue'),
    meta: { requiresAuth: true, layout: 'default' },
  },
]

export default departamentosRoutes
