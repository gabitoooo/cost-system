<!-- Tabla reutilizable que maneja el contenedor, thead y tbody estilizados.
     Recibe `columns` con label y alineación. El slot default recibe los <tr> de datos.
     Usa las clases exportadas TR_CLASS y TD_CLASS en el padre para mantener estilos uniformes. -->
<template>
  <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
          <th
            v-for="col in columns"
            :key="col.label"
            :class="['px-4 py-3 font-semibold text-gray-600 dark:text-gray-300', alignClass(col.align)]"
          >
            {{ col.label }}
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
        <slot />
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
export interface TableColumn {
  label: string
  align?: 'left' | 'right' | 'center'
}

defineProps<{ columns: TableColumn[] }>()

function alignClass(align?: 'left' | 'right' | 'center') {
  return { left: 'text-left', right: 'text-right', center: 'text-center' }[align ?? 'left']
}
</script>

<script lang="ts">
// Clases estándar para usar en los <tr> y <td> del padre
export const TR_CLASS = 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors'
export const TD_CLASS = 'px-4 py-3'
</script>
