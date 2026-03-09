import axios from 'axios'
import type { ValidationError, ApiError } from './types'
import type { ToastType } from '@/composables/useToast'

// Referencia al showToast del provider — se asigna una vez al inicializar la app
// (http.ts es un módulo TS puro, no puede usar inject() de Vue directamente)
type ToastHandler = (message: string, type: ToastType) => void
let _showToast: ToastHandler | null = null

type RedirectHandler = (path: string) => void
let _redirect: RedirectHandler | null = null

export function setToastHandler(fn: ToastHandler) {
  _showToast = fn
}

export function setRedirectHandler(fn: RedirectHandler) {
  _redirect = fn
}

export const http = axios.create({
  baseURL: import.meta.env.VITE_API_URL ?? 'http://localhost:8000/api',
  headers: { 'Content-Type': 'application/json' },
})

// Request interceptor — adjunta el token JWT a cada request si existe
http.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

// Response interceptor
http.interceptors.response.use(
  // Éxito: devuelve el data directamente (sin el wrapper de axios)
  (response) => response.data,

  // Error: analiza el status y decide qué hacer
  (error) => {
    const status: number = error.response?.status ?? 0
    const data = error.response?.data

    // 422 — errores de validación: los lanza para que el componente los maneje
    if (status === 422) {
      const validationError: ValidationError = {
        status: 422,
        errors: data?.errors ?? {},
      }
      return Promise.reject(validationError)
    }

    // Otros errores: dispara el toast y lanza el error
    const apiError: ApiError = {
      status,
      message: data?.message ?? defaultMessage(status),
    }
    _showToast?.(apiError.message, 'error')

    // 401 — sesión expirada en medio de la navegación → redirige al login
    if (status === 401) {
      localStorage.removeItem('token')
      _redirect?.('/login')
    }

    return Promise.reject(apiError)
  },
)

function defaultMessage(status: number): string {
  if (status === 401) return 'Sesión expirada. Por favor inicia sesión nuevamente.'
  if (status === 403) return 'No tienes permisos para realizar esta acción.'
  if (status === 404) return 'El recurso solicitado no existe.'
  if (status >= 500) return 'Error del servidor. Intenta nuevamente más tarde.'
  if (status === 0)  return 'Sin conexión. Verifica tu red.'
  return 'Ocurrió un error inesperado.'
}
