<!-- Muestra un estado vacío con borde punteado, ícono (slot), mensaje y hint opcionales.
     Prop `centered` activa layout flex columna para usarlo como placeholder de panel completo. -->
<template>
  <div :class="[
    'rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700',
    centered ? 'flex flex-col items-center justify-center' : 'p-8 text-center',
  ]">
    <slot name="icon" />
    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">{{ message }}</p>
    <p v-if="hint" class="mt-1 text-xs text-gray-400 dark:text-gray-600">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
  message: string
  hint?: string
  centered?: boolean
}>(), {
  centered: false,
})
</script>
