import { ref } from 'vue'
import { isValidationError } from '@/app/api/types'
import type { ValidationErrors } from '@/app/api/types'

export function useApi() {
  const loading = ref(false)
  const validationErrors = ref<ValidationErrors | null>(null)

  // Ejecuta una llamada al servicio y maneja loading y errores 422
  // Retorna el data si fue exitoso, null si hubo error
  async function execute<T>(call: () => Promise<T>): Promise<T | null> {
    loading.value = true
    validationErrors.value = null

    try {
      const data = await call()
      return data
    } catch (error) {
      // 422 — guarda los errores de campo para que el componente los muestre
      if (isValidationError(error)) {
        validationErrors.value = error.errors
        return null
      }
      // Otros errores ya fueron manejados por el interceptor (toast)
      return null
    } finally {
      // Se ejecuta siempre, haya éxito o error
      loading.value = false
    }
  }

  return { execute, loading, validationErrors }
}
