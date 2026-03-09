<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    <AppSidebar />
    <Backdrop />

    <!-- Contenido principal — se desplaza para dar espacio al sidebar en desktop -->
    <div
      class="flex flex-col min-h-screen transition-all duration-300 ease-in-out"
      :class="isExpanded || isHovered ? 'lg:pl-[290px]' : 'lg:pl-[90px]'"
    >
      <AppHeader />

      <main class="flex-1 p-4 md:p-6 mx-auto w-full max-w-screen-2xl">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useSidebar } from '@/composables/useSidebar'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import AppHeader from '@/components/layout/AppHeader.vue'
import Backdrop from '@/components/layout/Backdrop.vue'

const { isExpanded, isHovered } = useSidebar()
</script>
