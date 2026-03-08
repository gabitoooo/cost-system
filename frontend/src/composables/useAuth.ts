import { computed } from 'vue'
import { storageService } from '@/core/services/storageService'

export function useAuth() {
  const isAuthenticated = computed(() => !!storageService.get('token'))

  function logout() {
    storageService.remove('token')
    window.location.href = '/login'
  }

  return { isAuthenticated, logout }
}
