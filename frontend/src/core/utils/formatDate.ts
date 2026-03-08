export function formatDate(date: string | Date, locale = 'es-ES'): string {
  return new Date(date).toLocaleDateString(locale, {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

export function formatDateTime(date: string | Date, locale = 'es-ES'): string {
  return new Date(date).toLocaleString(locale)
}
