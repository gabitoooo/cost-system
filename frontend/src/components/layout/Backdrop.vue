<template>
  <div
    v-if="isMobileOpen"
    class="fixed inset-0 z-99 bg-gray-900/50 lg:hidden"
    @click="toggleMobileSidebar"
  />
</template>

<script setup lang="ts">
import { useSidebar } from '@/composables/useSidebar'
const { isMobileOpen, toggleMobileSidebar } = useSidebar()
</script>
