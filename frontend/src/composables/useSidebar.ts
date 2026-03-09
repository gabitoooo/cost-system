import { ref, computed, onMounted, onUnmounted, provide, inject } from 'vue'
import type { Ref } from 'vue'

interface SidebarContext {
  isExpanded: Ref<boolean>
  isMobileOpen: Ref<boolean>
  isHovered: Ref<boolean>
  openSubmenu: Ref<string | null>
  toggleSidebar: () => void
  toggleMobileSidebar: () => void
}

const SidebarSymbol = Symbol('sidebar')

export function useSidebarProvider() {
  const isExpanded = ref(true)
  const isMobileOpen = ref(false)
  const isMobile = ref(false)
  const isHovered = ref(false)
  const openSubmenu = ref<string | null>(null)

  const handleResize = () => {
    isMobile.value = window.innerWidth < 1024
    if (!isMobile.value) isMobileOpen.value = false
  }

  onMounted(() => {
    handleResize()
    window.addEventListener('resize', handleResize)
  })

  onUnmounted(() => {
    window.removeEventListener('resize', handleResize)
  })

  const toggleSidebar = () => {
    if (isMobile.value) {
      isMobileOpen.value = !isMobileOpen.value
    } else {
      isExpanded.value = !isExpanded.value
    }
  }

  const toggleMobileSidebar = () => {
    isMobileOpen.value = !isMobileOpen.value
  }

  const context: SidebarContext = {
    isExpanded: computed(() => (isMobile.value ? false : isExpanded.value)),
    isMobileOpen,
    isHovered,
    openSubmenu,
    toggleSidebar,
    toggleMobileSidebar,
  }

  provide(SidebarSymbol, context)
  return context
}

export function useSidebar(): SidebarContext {
  const ctx = inject<SidebarContext>(SidebarSymbol)
  if (!ctx) throw new Error('useSidebar debe usarse dentro de SidebarProvider')
  return ctx
}
