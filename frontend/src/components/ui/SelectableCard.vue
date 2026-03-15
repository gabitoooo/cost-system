<!-- Card seleccionable con borde brand cuando está activa, botones editar/eliminar y botón de acción inferior.
     Prop `size`: 'md' (p-4, default) para grupos/departamentos, 'sm' (p-3) para listas compactas.
     Slot `#extra`: contenido entre el header y el botón (ej. fila de tasa).
     Slot `#action`: contenido del botón inferior (texto + ícono opcional).
     Emits: edit, delete, select. -->
<template>
  <div
    :class="[
      'rounded-xl border transition-all',
      size === 'sm' ? 'p-3' : 'p-4',
      selected
        ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10 shadow-sm ring-1 ring-brand-500'
        : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-brand-300 hover:shadow-sm',
    ]"
  >
    <!-- Header: título + subtítulo + acciones -->
    <div class="flex items-start justify-between gap-2">
      <div class="min-w-0 flex-1">
        <h3 class="truncate font-semibold text-gray-900 dark:text-white">{{ title }}</h3>
        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ subtitle }}</p>
      </div>
      <RowActions :size="size" @edit="$emit('edit')" @delete="$emit('delete')" />
    </div>

    <!-- Contenido extra opcional (ej. fila de tasa) -->
    <slot name="extra" />

    <!-- Botón de acción -->
    <button
      @click="$emit('select')"
      :class="[
        'mt-3 w-full rounded-lg px-3 py-1.5 text-xs font-medium transition-colors',
        selected
          ? 'bg-brand-500 text-white hover:bg-brand-600'
          : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600',
      ]"
    >
      <span class="flex items-center justify-center gap-1.5">
        <slot name="action" />
      </span>
    </button>
  </div>
</template>

<script setup lang="ts">
import RowActions from './RowActions.vue'

withDefaults(defineProps<{
  title: string
  subtitle: string
  selected?: boolean
  size?: 'sm' | 'md'
}>(), {
  selected: false,
  size: 'md',
})

defineEmits<{ edit: []; delete: []; select: [] }>()
</script>
