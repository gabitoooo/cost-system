export function isEmail(value: string): boolean {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
}

export function isRequired(value: unknown): boolean {
  return value !== null && value !== undefined && value !== ''
}

export function minLength(value: string, min: number): boolean {
  return value.length >= min
}
