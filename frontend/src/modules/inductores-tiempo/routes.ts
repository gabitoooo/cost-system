import type { RouteRecordRaw } from 'vue-router'

const inductoresTiempoRoutes: RouteRecordRaw[] = [
  {
    path: '/inductores-recursos',
    component: () => import('./views/InductoresTiempoView.vue'),
    meta: { requiresAuth: true, layout: 'default' },
  },
]

export default inductoresTiempoRoutes
