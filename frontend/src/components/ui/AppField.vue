<!-- Frame reutilizable: label + slot para cualquier control + hint + mensaje de error.
     El slot expone `inputClass` con los estilos de borde correctos (normal / error).
     Prop `hint`: texto de ayuda gris bajo el control (opcional).
     Uso: <AppField label="..." :errors="errors" field="nombre" v-slot="{ inputClass }">
            <input v-model="..." :class="inputClass" />
          </AppField> -->
<template>
  <div>
    <label v-if="label" class="form-label">{{ label }}</label>
    <slot :inputClass="inputClass" />
    <p v-if="hint" class="mt-1.5 text-xs text-gray-400">{{ hint }}</p>
    <p v-if="errorMessage" class="field-error">{{ errorMessage }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { getFieldError } from '@/app/api/types'
import type { ValidationErrors } from '@/app/api/types'

const props = defineProps<{
  label?: string
  errors?: ValidationErrors | null
  field: string
  hint?: string
}>()

const errorMessage = computed(() => getFieldError(props.errors ?? null, props.field))

const inputClass = computed(() => [
  'w-full rounded-lg border px-4 py-2.5 text-sm text-default placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-800 outline-none transition-colors',
  errorMessage.value
    ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-500/20'
    : 'border-gray-300 dark:border-gray-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20',
])
</script>
