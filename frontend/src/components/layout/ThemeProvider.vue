<template>
  <slot />
</template>

<script setup lang="ts">
import { ref, computed, provide, watch, onMounted } from 'vue'

type Theme = 'light' | 'dark'

const theme = ref<Theme>('light')
const isDarkMode = computed(() => theme.value === 'dark')

const toggleTheme = () => {
  theme.value = theme.value === 'light' ? 'dark' : 'light'
}

onMounted(() => {
  const saved = localStorage.getItem('theme') as Theme | null
  theme.value = saved ?? 'light'
})

watch(theme, (val) => {
  localStorage.setItem('theme', val)
  document.documentElement.classList.toggle('dark', val === 'dark')
}, { immediate: false })

// Aplicar clase dark sin esperar al watch en el primer render
onMounted(() => {
  document.documentElement.classList.toggle('dark', theme.value === 'dark')
})

provide('theme', { isDarkMode, toggleTheme })
</script>
