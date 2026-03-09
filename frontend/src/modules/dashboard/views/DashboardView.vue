<template>
  <div>
    <!-- Cabecera de página -->
    <div class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Dashboard</h1>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Bienvenido al Sistema de Costos ABC.
      </p>
    </div>

    <!-- Tarjetas de bienvenida (placeholder) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
      <div
        v-for="card in placeholderCards"
        :key="card.label"
        class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5"
      >
        <div class="flex items-center justify-between mb-4">
          <span class="text-sm text-gray-500 dark:text-gray-400">{{ card.label }}</span>
          <div
            class="flex items-center justify-center w-9 h-9 rounded-lg"
            :class="card.bgClass"
          >
            <component :is="card.icon" class="w-5 h-5" :class="card.iconClass" />
          </div>
        </div>
        <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ card.value }}</p>
        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ card.sub }}</p>
      </div>
    </div>

    <!-- Área principal vacía -->
    <div
      class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700
             bg-white dark:bg-gray-900 flex flex-col items-center justify-center
             min-h-[320px] text-center p-10"
    >
      <div class="flex items-center justify-center w-14 h-14 rounded-full bg-brand-50 dark:bg-brand-500/10 mb-4">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
          class="text-brand-500">
          <line x1="18" y1="20" x2="18" y2="10"/>
          <line x1="12" y1="20" x2="12" y2="4"/>
          <line x1="6"  y1="20" x2="6"  y2="14"/>
          <line x1="2"  y1="20" x2="22" y2="20"/>
        </svg>
      </div>
      <h3 class="text-base font-semibold text-gray-800 dark:text-white/90 mb-1">
        Dashboard en construcción
      </h3>
      <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
        Aquí se mostrarán los indicadores y gráficos del sistema de costos una vez que
        se conecte con el backend.
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { SettingsIcon, BoxIcon, ChartBarIcon, CalculatorIcon } from '@/components/icons'

const placeholderCards = [
  {
    label: 'Actividades',
    value: '—',
    sub: 'Sin datos aún',
    icon: SettingsIcon,
    bgClass: 'bg-brand-50 dark:bg-brand-500/10',
    iconClass: 'text-brand-500',
  },
  {
    label: 'Recursos',
    value: '—',
    sub: 'Sin datos aún',
    icon: BoxIcon,
    bgClass: 'bg-success-50 dark:bg-success-500/10',
    iconClass: 'text-success-500',
  },
  {
    label: 'Inductores',
    value: '—',
    sub: 'Sin datos aún',
    icon: ChartBarIcon,
    bgClass: 'bg-warning-50 dark:bg-warning-500/10',
    iconClass: 'text-warning-500',
  },
  {
    label: 'Costos Asignados',
    value: '—',
    sub: 'Sin datos aún',
    icon: CalculatorIcon,
    bgClass: 'bg-error-50 dark:bg-error-500/10',
    iconClass: 'text-error-500',
  },
]
</script>
