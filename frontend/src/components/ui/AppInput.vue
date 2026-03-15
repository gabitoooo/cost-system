<!-- Wrapper de conveniencia para el caso más común: AppField + <input>.
     Para select, textarea o componentes custom usa AppField directamente con v-slot.
     Soporta v-model, type, placeholder, hint y atributos numéricos (min, max, step). -->
<template>
  <AppField :label="label" :errors="errors" :field="field" :hint="hint" v-slot="{ inputClass }">
    <input
      :value="modelValue"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      :type="type"
      :placeholder="placeholder"
      :min="min"
      :max="max"
      :step="step"
      :class="inputClass"
    />
  </AppField>
</template>

<script setup lang="ts">
import AppField from './AppField.vue'
import type { ValidationErrors } from '@/app/api/types'

withDefaults(defineProps<{
  modelValue: string | number
  label?: string
  errors?: ValidationErrors | null
  field: string
  type?: string
  placeholder?: string
  hint?: string
  min?: string | number
  max?: string | number
  step?: string | number
}>(), {
  type: 'text',
})

defineEmits<{ 'update:modelValue': [value: string] }>()
</script>
