// Respuesta exitosa — el backend devuelve el data directo (array u objeto)
export type ApiResponse<T> = T

// Errores de validación 422 — cada campo tiene un array de mensajes
export type ValidationErrors = Record<string, string[]>

// Lo que lanza http.ts cuando el backend devuelve 422
export interface ValidationError {
  status: 422
  errors: ValidationErrors
}

// Error general (401, 403, 500, red...)
export interface ApiError {
  status: number
  message: string
}

// Type guard — para saber si un error catcheado es un ValidationError
export function isValidationError(error: unknown): error is ValidationError {
  return (
    typeof error === 'object' &&
    error !== null &&
    (error as ValidationError).status === 422
  )
}

// Helper — devuelve el primer mensaje de un campo (para mostrarlo en el input)
export function getFieldError(
  errors: ValidationErrors | null,
  field: string,
): string | null {
  return errors?.[field]?.[0] ?? null
}
