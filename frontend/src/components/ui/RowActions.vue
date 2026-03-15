<!-- Par de botones ícono editar/eliminar reutilizables. Siempre detiene la propagación del click.
     Prop `size`: 'sm' (h-3.5) para cards compactas, 'md' (h-4, default) para filas de tabla.
     Emits: edit, delete. -->
<template>
  <div class="flex items-center gap-1">
    <button @click.stop="$emit('edit')" class="btn-icon" title="Editar">
      <EditIcon :class="iconClass" />
    </button>
    <button
      @click.stop="$emit('delete')"
      class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition-colors"
      title="Eliminar"
    >
      <TrashIcon :class="iconClass" />
    </button>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { EditIcon, TrashIcon } from '@/components/icons'

const props = withDefaults(defineProps<{ size?: 'sm' | 'md' }>(), { size: 'md' })
defineEmits<{ edit: []; delete: [] }>()

const iconClass = computed(() => props.size === 'sm' ? 'h-3.5 w-3.5' : 'h-4 w-4')
</script>
