<template>
  <ThemeProvider>
    <SidebarProvider>
      <!-- ToastProvider envuelve todo para que cualquier componente pueda disparar toasts -->
      <ToastProvider>
        <component :is="currentLayout">
          <RouterView />
        </component>
        <!-- AppToast se renderiza aquí pero via Teleport aparece en el <body> -->
        <AppToast />
      </ToastProvider>
    </SidebarProvider>
  </ThemeProvider>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import ThemeProvider from '@/components/layout/ThemeProvider.vue'
import SidebarProvider from '@/components/layout/SidebarProvider.vue'
import ToastProvider from '@/components/layout/ToastProvider.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

const route = useRoute()

const currentLayout = computed(() => {
  if (route.meta.layout === 'auth') return AuthLayout
  return DefaultLayout
})
</script>
