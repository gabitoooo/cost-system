import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import authService from '../api/authService'
import type { User, LoginRequest } from '../types'

export const useAuthStore = defineStore('auth', () => {
  // Estado
  const token = ref<string | null>(localStorage.getItem('token'))
  const user = ref<User | null>(null)

  // Getters
  const isAuthenticated = computed(() => !!token.value)

  // Guarda el token en el estado y en localStorage
  function setToken(value: string) {
    token.value = value
    localStorage.setItem('token', value)
  }

  // Limpia el estado y el localStorage
  function clearSession() {
    token.value = null
    user.value = null
    localStorage.removeItem('token')
  }

  async function login(credentials: LoginRequest) {
    const response = await authService.login(credentials)
    setToken(response.data.token)
    user.value = response.data.user
  }

  async function logout() {
    await authService.logout()
    clearSession()
  }

  // Recupera el usuario autenticado desde el backend (útil al recargar la página)
  async function fetchMe() {
    const response = await authService.me()
    user.value = response.data.user
  }

  return { token, user, isAuthenticated, login, logout, fetchMe }
})
