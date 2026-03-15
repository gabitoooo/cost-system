<!-- Modal reutilizable con overlay, animaciones y footer estándar (Cancelar + Confirmar con spinner).
     Props: show, title, size (sm|md|lg), loading, confirmLabel, loadingLabel, confirmDisabled.
     Emits: close, confirm. Slot `footer` permite reemplazar los botones por completo. -->
<template>
  <Transition
    enter-active-class="transition ease-out duration-200"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="$emit('close')" />
      <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
      >
        <div v-if="show" :class="['relative z-10 w-full rounded-2xl bg-white dark:bg-gray-900 shadow-2xl', sizeClass]">
          <!-- Header -->
          <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-5">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ title }}</h2>
            <button @click="$emit('close')" class="btn-icon">
              <XIcon class="h-5 w-5" />
            </button>
          </div>

          <!-- Body -->
          <div class="px-6 py-6">
            <slot />
          </div>

          <!-- Footer -->
          <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 px-6 py-4">
            <slot name="footer">
              <button @click="$emit('close')" class="btn-base btn-ghost btn-md">
                Cancelar
              </button>
              <button
                @click="$emit('confirm')"
                :disabled="loading || confirmDisabled"
                class="btn-base btn-primary btn-md"
              >
                <svg v-if="loading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                </svg>
                {{ loading ? loadingLabel : confirmLabel }}
              </button>
            </slot>
          </div>
        </div>
      </Transition>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { XIcon } from '@/components/icons'

const props = withDefaults(defineProps<{
  show: boolean
  title: string
  size?: 'sm' | 'md' | 'lg'
  loading?: boolean
  confirmLabel?: string
  loadingLabel?: string
  confirmDisabled?: boolean
}>(), {
  size: 'lg',
  loading: false,
  confirmLabel: 'Guardar',
  loadingLabel: 'Guardando...',
  confirmDisabled: false,
})

defineEmits<{ close: []; confirm: [] }>()

const sizeClass = computed(() => ({ sm: 'max-w-sm', md: 'max-w-md', lg: 'max-w-lg' }[props.size]))
</script>
