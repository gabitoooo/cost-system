<template>
  <!-- Teleport renderiza el toast directo en el <body>, evitando que quede
       tapado por overflow:hidden o z-index de componentes padres -->
  <Teleport to="body">
    <!-- Contenedor fijo en esquina inferior derecha.
         pointer-events-none para no bloquear clicks en la página,
         se revierte en cada toast individual con pointer-events-auto -->
    <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 pointer-events-none">

      <!-- TransitionGroup anima entradas y salidas de la lista de toasts.
           name="toast" define el prefijo de las clases CSS: .toast-enter-from, .toast-leave-to, etc. -->
      <TransitionGroup name="toast">
        <div
          v-for="toast in toasts"
          :key="toast.id"
          class="flex items-start gap-3 min-w-72 max-w-sm px-4 py-3 rounded-xl shadow-lg pointer-events-auto"
          :class="toastClass(toast.type)"
        >
          <!-- Icono SVG que cambia según el tipo de toast -->
          <svg class="shrink-0 mt-0.5" width="18" height="18" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <template v-if="toast.type === 'success'">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
              <polyline points="22 4 12 14.01 9 11.01"/>
            </template>
            <template v-else-if="toast.type === 'error'">
              <circle cx="12" cy="12" r="10"/>
              <line x1="15" y1="9" x2="9" y2="15"/>
              <line x1="9" y1="9" x2="15" y2="15"/>
            </template>
            <template v-else>
              <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
              <line x1="12" y1="9" x2="12" y2="13"/>
              <line x1="12" y1="17" x2="12.01" y2="17"/>
            </template>
          </svg>

          <p class="text-sm font-medium leading-snug">{{ toast.message }}</p>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { useToast } from '@/composables/useToast'
import type { ToastType } from '@/composables/useToast'

// Obtiene la lista reactiva de toasts desde el provider
const { toasts } = useToast()

// Devuelve las clases de color según el tipo
function toastClass(type: ToastType) {
  if (type === 'success') return 'bg-green-500 text-white'
  if (type === 'error')   return 'bg-red-500 text-white'
  return 'bg-yellow-400 text-gray-900'
}
</script>

<style scoped>
/* Durante la animación de entrada y salida */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

/* Estado inicial al entrar — aparece desde abajo */
.toast-enter-from {
  opacity: 0;
  transform: translateY(12px);
}

/* Estado final al salir — se va hacia la derecha */
.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
</style>
