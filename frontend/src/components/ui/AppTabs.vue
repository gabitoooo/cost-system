<!-- Barra de tabs con estilo brand. Acepta array `tabs` con value, label, icon (Component Vue) y count opcional.
     Usa v-model para el tab activo. Emite `update:modelValue` al hacer click en un tab.
     Importa `TabItem` para tipar el array desde el consumidor. -->

<script lang="ts">
import type { Component } from 'vue'

export interface TabItem {
  value: string
  label: string
  icon?: Component
  count?: number
}
</script>

<script setup lang="ts">
defineProps<{
  tabs: TabItem[]
  modelValue: string
}>()

defineEmits<{ 'update:modelValue': [value: string] }>()
</script>

<template>
  <div class="mb-5 flex border-b border-gray-200 dark:border-gray-700">
    <button
      v-for="tab in tabs"
      :key="tab.value"
      @click="$emit('update:modelValue', tab.value)"
      :class="[
        'flex items-center gap-2 px-4 py-2.5 text-sm font-medium border-b-2 transition-colors -mb-px',
        modelValue === tab.value
          ? 'border-brand-500 text-brand-600 dark:text-brand-400'
          : 'border-transparent text-soft hover:text-gray-700 dark:hover:text-gray-200',
      ]"
    >
      <component :is="tab.icon" v-if="tab.icon" class="h-4 w-4" />
      {{ tab.label }}
      <span
        v-if="tab.count !== undefined"
        class="rounded-full bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 text-xs font-semibold text-gray-600 dark:text-gray-300"
      >
        {{ tab.count }}
      </span>
    </button>
  </div>
</template>
