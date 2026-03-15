<!-- Lista de skeletons animados para estados de carga.
     Prop `count`: número de ítems. `height`: clase Tailwind de altura (ej. 'h-14', 'h-28'). -->
<template>
  <div class="space-y-3">
    <div
      v-for="n in count"
      :key="n"
      :class="['animate-pulse rounded-xl bg-gray-100 dark:bg-gray-800', height]"
    />
  </div>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
  count?: number
  height?: string
}>(), {
  count: 4,
  height: 'h-14',
})
</script>
