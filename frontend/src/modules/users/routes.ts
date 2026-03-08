import type { RouteRecordRaw } from 'vue-router'

const usersRoutes: RouteRecordRaw[] = [
  {
    path: '/users',
    name: 'users',
    component: () => import('./views/UserListView.vue'),
    meta: { requiresAuth: true },
  },
]

export default usersRoutes
