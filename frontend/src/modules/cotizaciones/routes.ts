import type { RouteRecordRaw } from 'vue-router'

const cotizacionesRoutes: RouteRecordRaw[] = [
  {
    path: '/cotizaciones',
    component: () => import('./views/CotizacionesView.vue'),
    meta: { requiresAuth: true, layout: 'default' },
  },
]

export default cotizacionesRoutes
