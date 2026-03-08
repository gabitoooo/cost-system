import type { AxiosInstance } from 'axios'
import { storageService } from '@/core/services/storageService'

export function setupInterceptors(client: AxiosInstance): void {
  // Request: adjuntar token
  client.interceptors.request.use((config) => {
    const token = storageService.get('token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  })

  // Response: manejo global de errores
  client.interceptors.response.use(
    (response) => response,
    (error) => {
      if (error.response?.status === 401) {
        storageService.remove('token')
        window.location.href = '/login'
      }
      return Promise.reject(error)
    },
  )
}
