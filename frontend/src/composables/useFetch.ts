import { ref } from 'vue'
import type { Ref } from 'vue'
import httpClient from '@/core/api/httpClient'

export function useFetch<T>(url: string) {
  const data: Ref<T | null> = ref(null)
  const loading = ref(false)
  const error: Ref<string | null> = ref(null)

  async function fetch() {
    loading.value = true
    error.value = null
    try {
      const response = await httpClient.get<T>(url)
      data.value = response.data
    } catch (e: unknown) {
      error.value = e instanceof Error ? e.message : 'Error desconocido'
    } finally {
      loading.value = false
    }
  }

  return { data, loading, error, fetch }
}
