<!-- Badge de colores reutilizable. Prop `color` define la variante visual.
     Colores disponibles: green, blue, purple, orange, amber, red, pink, teal, gray. -->
<template>
  <span :class="['rounded-full px-2.5 py-0.5 text-xs font-medium', colorClass]">
    <slot />
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(defineProps<{
  color?: 'green' | 'blue' | 'purple' | 'orange' | 'amber' | 'red' | 'pink' | 'teal' | 'gray'
}>(), {
  color: 'gray',
})

const colorClass = computed(() => ({
  green:  'bg-green-50  dark:bg-green-500/10  text-green-700  dark:text-green-400',
  blue:   'bg-blue-50   dark:bg-blue-500/10   text-blue-700   dark:text-blue-400',
  purple: 'bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400',
  orange: 'bg-orange-50 dark:bg-orange-500/10 text-orange-700 dark:text-orange-400',
  amber:  'bg-amber-50  dark:bg-amber-500/10  text-amber-700  dark:text-amber-400',
  red:    'bg-red-50    dark:bg-red-500/10    text-red-700    dark:text-red-400',
  pink:   'bg-pink-50   dark:bg-pink-500/10   text-pink-700   dark:text-pink-400',
  teal:   'bg-teal-50   dark:bg-teal-500/10   text-teal-700   dark:text-teal-400',
  gray:   'bg-gray-100  dark:bg-gray-700      text-gray-700   dark:text-gray-300',
}[props.color]))
</script>
