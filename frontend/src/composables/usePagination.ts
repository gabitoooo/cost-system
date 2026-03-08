import { ref, computed } from 'vue'

export function usePagination(totalItems: number, perPage = 10) {
  const currentPage = ref(1)

  const totalPages = computed(() => Math.ceil(totalItems / perPage))

  function nextPage() {
    if (currentPage.value < totalPages.value) currentPage.value++
  }

  function prevPage() {
    if (currentPage.value > 1) currentPage.value--
  }

  function goToPage(page: number) {
    if (page >= 1 && page <= totalPages.value) currentPage.value = page
  }

  return { currentPage, totalPages, nextPage, prevPage, goToPage }
}
