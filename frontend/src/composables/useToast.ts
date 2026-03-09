import { ref, provide, inject } from 'vue'
import type { Ref } from 'vue'
import { setToastHandler } from '@/app/api/http'

export type ToastType = 'success' | 'error' | 'warning'

export interface Toast {
  id: number
  type: ToastType
  message: string
}

interface ToastContext {
  toasts: Ref<Toast[]>
  showToast: (message: string, type?: ToastType) => void
}

const ToastSymbol = Symbol('toast')

export function useToastProvider() {
  const toasts = ref<Toast[]>([])
  let nextId = 0

  const showToast = (message: string, type: ToastType = 'success') => {
    const id = nextId++
    toasts.value.push({ id, type, message })
    setTimeout(() => {
      toasts.value = toasts.value.filter((t) => t.id !== id)
    }, 4000)
  }

  // Conecta showToast con http.ts para que los errores globales disparen el toast
  setToastHandler(showToast)

  provide(ToastSymbol, { toasts, showToast })
  return { toasts, showToast }
}

export function useToast(): ToastContext {
  const ctx = inject<ToastContext>(ToastSymbol)
  if (!ctx) throw new Error('useToast debe usarse dentro de ToastProvider')
  return ctx
}
