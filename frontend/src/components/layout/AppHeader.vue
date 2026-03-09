<template>
  <header
    class="sticky top-0 z-99999 flex w-full bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800"
  >
    <div class="flex items-center justify-between w-full px-4 py-3 lg:px-6 lg:py-3.5">

      <!-- Izquierda: hamburger + logo (mobile) / hamburger (desktop) -->
      <div class="flex items-center gap-3">
        <!-- Hamburger -->
        <button
          @click="handleToggle"
          class="flex items-center justify-center w-10 h-10 rounded-lg border border-gray-200 dark:border-gray-800
                 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
        >
          <!-- X en mobile si está abierto -->
          <svg v-if="isMobileOpen" class="fill-current" width="20" height="20" viewBox="0 0 24 24">
            <path fill-rule="evenodd" clip-rule="evenodd"
              d="M6.22 7.28a1 1 0 0 1 1.41-1.41L12 10.59l4.36-4.72a1 1 0 1 1 1.46 1.36L13.06 12l4.76 4.72a1 1 0 0 1-1.41 1.41L12 13.41l-4.36 4.72a1 1 0 0 1-1.46-1.36L10.94 12 6.22 7.28z"/>
          </svg>
          <!-- Hamburger normal -->
          <svg v-else width="18" height="14" viewBox="0 0 18 14" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
              d="M0 1a1 1 0 0 1 1-1h16a1 1 0 1 1 0 2H1a1 1 0 0 1-1-1zm0 12a1 1 0 0 1 1-1h16a1 1 0 1 1 0 2H1a1 1 0 0 1-1-1zm1-7a1 1 0 0 0 0 2h9a1 1 0 1 0 0-2H1z"/>
          </svg>
        </button>

        <!-- Nombre app solo en mobile (el sidebar está oculto) -->
        <span class="lg:hidden font-semibold text-gray-800 dark:text-white text-sm">
          Sistema de Costos
        </span>
      </div>

      <!-- Centro: buscador (desktop) -->
      <div class="hidden lg:flex flex-1 max-w-md mx-6">
        <div class="relative w-full">
          <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"
            width="16" height="16" viewBox="0 0 20 20" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
              d="M3 9.5a6.5 6.5 0 1 1 11.436 4.23l3.85 3.84a.75.75 0 0 1-1.06 1.06l-3.84-3.84A6.5 6.5 0 0 1 3 9.5zm6.5-5a5 5 0 1 0 0 10 5 5 0 0 0 0-10z"/>
          </svg>
          <input
            type="text"
            placeholder="Buscar..."
            class="w-full h-10 pl-10 pr-4 text-sm rounded-lg border border-gray-200 dark:border-gray-700
                   bg-transparent text-gray-800 dark:text-white/90 placeholder-gray-400 dark:placeholder-white/30
                   focus:outline-none focus:border-brand-300 dark:focus:border-brand-700
                   focus:ring-2 focus:ring-brand-500/10 dark:bg-gray-900/50"
          />
        </div>
      </div>

      <!-- Derecha: modo oscuro + usuario -->
      <div class="flex items-center gap-2">

        <!-- Toggle dark mode -->
        <button
          @click="toggleTheme"
          class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-200
                 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400
                 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
          :title="isDarkMode ? 'Modo claro' : 'Modo oscuro'"
        >
          <component :is="isDarkMode ? SunIcon : MoonIcon" />
        </button>

        <!-- Separador -->
        <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 hidden sm:block" />

        <!-- Usuario con iniciales -->
        <div class="relative" ref="userMenuRef">
          <button
            @click="userMenuOpen = !userMenuOpen"
            class="flex items-center gap-2.5 focus:outline-none"
          >
            <div
              class="flex items-center justify-center w-9 h-9 rounded-full bg-brand-500 text-white
                     text-sm font-semibold select-none"
            >
              {{ userInitials }}
            </div>
            <div class="hidden sm:flex flex-col text-left">
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300 leading-tight">
                {{ authStore.user?.name ?? 'Usuario' }}
              </span>
              <span class="text-xs text-gray-400 dark:text-gray-500 leading-tight">
                {{ authStore.user?.roles[0] ?? '' }}
              </span>
            </div>
            <svg class="hidden sm:block w-4 h-4 text-gray-400 transition-transform duration-200"
              :class="{ 'rotate-180': userMenuOpen }"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="6 9 12 15 18 9"/>
            </svg>
          </button>

          <!-- Dropdown usuario -->
          <div
            v-if="userMenuOpen"
            class="absolute right-0 mt-3 w-52 rounded-xl border border-gray-200 dark:border-gray-700
                   bg-white dark:bg-gray-dark shadow-theme-lg py-2 z-99999"
          >
            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800">
              <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                {{ authStore.user?.name ?? 'Usuario' }}
              </p>
              <p class="text-xs text-gray-400 dark:text-gray-500">
                {{ authStore.user?.email ?? '' }}
              </p>
            </div>
            <ul class="py-1">
              <li>
                <button
                  @click="handleLogout"
                  class="flex items-center gap-3 w-full px-4 py-2 text-sm text-gray-600 dark:text-gray-300
                         hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-800 dark:hover:text-white
                         transition-colors"
                >
                  <component :is="LogoutIcon" class="w-4 h-4" />
                  Cerrar sesión
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { ref, computed, inject, onMounted, onUnmounted } from 'vue'
import type { Ref } from 'vue'
import { useRouter } from 'vue-router'
import { useSidebar } from '@/composables/useSidebar'
import { useAuthStore } from '@/modules/auth/store/authStore'
import { MoonIcon, SunIcon, LogoutIcon } from '@/components/icons'

const { toggleSidebar, toggleMobileSidebar, isMobileOpen } = useSidebar()

const theme = inject<{ isDarkMode: Ref<boolean>; toggleTheme: () => void }>('theme')!
const { isDarkMode, toggleTheme } = theme

const router = useRouter()
const authStore = useAuthStore()

// Iniciales del nombre del usuario (ej: "Juan Pérez" → "JP")
const userInitials = computed(() => {
  const name = authStore.user?.name ?? ''
  return name
    .split(' ')
    .slice(0, 2)
    .map((w) => w[0]?.toUpperCase() ?? '')
    .join('')
})

const userMenuOpen = ref(false)
const userMenuRef = ref<HTMLElement | null>(null)

const handleToggle = () => {
  if (window.innerWidth >= 1024) {
    toggleSidebar()
  } else {
    toggleMobileSidebar()
  }
}

const handleLogout = async () => {
  userMenuOpen.value = false
  await authStore.logout()
  router.push('/login')
}

const handleClickOutside = (e: MouseEvent) => {
  if (userMenuRef.value && !userMenuRef.value.contains(e.target as Node)) {
    userMenuOpen.value = false
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>
