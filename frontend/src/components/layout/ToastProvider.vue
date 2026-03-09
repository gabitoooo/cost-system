<template>
  <slot />
</template>

<script setup lang="ts">
import { useToastProvider } from '@/composables/useToast'
useToastProvider()
</script>
