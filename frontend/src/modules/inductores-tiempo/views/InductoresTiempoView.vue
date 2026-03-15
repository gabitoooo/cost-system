<template>
  <div class="p-6">
    <!-- ─── Header ──────────────────────────────────────────────────────────── -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-default">Inductores de Tiempo</h1>
        <p class="mt-1 text-sm text-soft">
          Catálogo de inductores de costo por tiempo usados en las actividades.
        </p>
      </div>
      <button
        @click="openCreateModal"
        class="btn-base btn-primary btn-md shadow-sm"
      >
        <PlusIcon class="h-4 w-4" />
        Nuevo Inductor
      </button>
    </div>

    <!-- ─── Filtro búsqueda ────────────────────────────────────────────────── -->
    <div class="mb-4 flex items-center gap-3">
      <div class="relative max-w-xs flex-1">
        <SearchIcon class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
        <input
          v-model="search"
          type="text"
          placeholder="Buscar inductor..."
          class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 pl-9 pr-4 py-2 text-sm text-default placeholder-gray-400 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
        />
      </div>
      <select
        v-model="filterTipo"
        class="rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-body outline-none focus:border-brand-500"
      >
        <option value="">Todos los tipos</option>
        <option value="MANUAL">Manual</option>
        <option value="POR_CANTIDAD">Por cantidad</option>
        <option value="POR_LOTE">Por lote</option>
      </select>
    </div>

    <!-- ─── Tabla ───────────────────────────────────────────────────────────── -->
    <AppTable :columns="inductoresColumns">
      <!-- Skeletons -->
      <template v-if="loadingList">
        <tr v-for="n in 4" :key="n" :class="TR_CLASS">
          <td :class="TD_CLASS"><div class="h-4 w-36 animate-pulse rounded bg-gray-100 dark:bg-gray-700" /></td>
          <td :class="TD_CLASS"><div class="h-5 w-24 animate-pulse rounded-full bg-gray-100 dark:bg-gray-700" /></td>
          <td :class="TD_CLASS"><div class="h-4 w-48 animate-pulse rounded bg-gray-100 dark:bg-gray-700" /></td>
          <td :class="TD_CLASS"><div class="mx-auto h-4 w-16 animate-pulse rounded bg-gray-100 dark:bg-gray-700" /></td>
        </tr>
      </template>

      <!-- Estado vacío -->
      <tr v-else-if="inductoresFiltrados.length === 0">
        <td colspan="4" class="px-4 py-16 text-center">
          <ClockIcon class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" />
          <p class="mt-3 text-sm font-medium text-gray-400 dark:text-gray-500">
            {{ search || filterTipo ? 'Sin resultados para la búsqueda' : 'No hay inductores registrados' }}
          </p>
          <p class="mt-1 text-xs text-gray-400 dark:text-gray-600">
            {{ search || filterTipo ? 'Prueba con otros filtros.' : 'Crea el primer inductor de tiempo.' }}
          </p>
        </td>
      </tr>

      <!-- Filas -->
      <tr
        v-else
        v-for="inductor in inductoresFiltrados"
        :key="inductor.id"
        :class="TR_CLASS"
      >
        <td :class="[TD_CLASS, 'font-medium text-default']">{{ inductor.nombre }}</td>
        <td :class="TD_CLASS">
          <AppBadge :color="tipoBadgeColor(inductor.tipo_calculo)">{{ tipoLabel(inductor.tipo_calculo) }}</AppBadge>
        </td>
        <td :class="[TD_CLASS, 'text-soft']">
          {{ inductor.descripcion ?? '—' }}
        </td>
        <td :class="TD_CLASS">
          <div class="flex items-center justify-center">
            <RowActions @edit="openEditModal(inductor)" @delete="handleDelete(inductor)" />
          </div>
        </td>
      </tr>
    </AppTable>

    <!-- Modal crear / editar -->
    <BaseModal
      :show="showModal"
      :title="editingId ? 'Editar Inductor' : 'Nuevo Inductor de Tiempo'"
      :loading="apiState.loading.value"
      :confirm-label="editingId ? 'Guardar cambios' : 'Crear inductor'"
      @close="closeModal"
      @confirm="handleSubmit"
    >
      <div class="space-y-4">
        <AppInput label="Nombre" v-model="form.nombre" field="nombre" :errors="apiState.validationErrors.value" placeholder="Ej: Pedidos, Horas máquina, Corridas de producción..." />
        <AppField label="Tipo de cálculo" field="tipo_calculo" :errors="apiState.validationErrors.value" v-slot="{ inputClass }">
          <select v-model="form.tipo_calculo" :class="inputClass">
            <option value="" disabled>Selecciona un tipo...</option>
            <option value="MANUAL">Manual</option>
            <option value="POR_CANTIDAD">Por cantidad</option>
            <option value="POR_LOTE">Por lote</option>
          </select>
        </AppField>
        <p v-if="form.tipo_calculo === 'POR_LOTE'" class="text-xs text-amber-600 dark:text-amber-400">
          Al asignar este inductor a una actividad será necesario especificar el tamaño de lote.
        </p>
        <AppInput label="Descripción (opcional)" v-model="form.descripcion" field="descripcion" :errors="apiState.validationErrors.value" placeholder="Ej: Por pedido procesado en el período..." />
      </div>
    </BaseModal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import Swal from 'sweetalert2'
import { PlusIcon, SearchIcon, ClockIcon } from '@/components/icons'
import { useInductoresTiempoStore } from '../store/inductoresTiempoStore'
import { useApi } from '@/composables/useApi'
import type { InductorTiempo, TipoCalculo } from '../types'
import { TR_CLASS, TD_CLASS } from '@/components/ui/AppTable.vue'

const store = useInductoresTiempoStore()
const apiState = useApi()

const loadingList = ref(false)
const showModal = ref(false)
const editingId = ref<number | null>(null)
const search = ref('')
const filterTipo = ref('')
const form = ref<{ nombre: string; tipo_calculo: TipoCalculo | ''; descripcion: string }>({
  nombre: '',
  tipo_calculo: '',
  descripcion: '',
})

const inductoresColumns = [
  { label: 'Nombre', align: 'left' as const },
  { label: 'Tipo de cálculo', align: 'left' as const },
  { label: 'Descripción', align: 'left' as const },
  { label: 'Acciones', align: 'center' as const },
]

const inductoresFiltrados = computed(() => {
  let list = store.inductores
  if (search.value) {
    const q = search.value.toLowerCase()
    list = list.filter((i) => i.nombre.toLowerCase().includes(q))
  }
  if (filterTipo.value) {
    list = list.filter((i) => i.tipo_calculo === filterTipo.value)
  }
  return list
})

onMounted(async () => {
  loadingList.value = true
  await store.fetchAll()
  loadingList.value = false
})

function tipoLabel(tipo: TipoCalculo) {
  const map: Record<TipoCalculo, string> = {
    MANUAL: 'Manual',
    POR_CANTIDAD: 'Por cantidad',
    POR_LOTE: 'Por lote',
  }
  return map[tipo]
}

function tipoBadgeColor(tipo: TipoCalculo): 'gray' | 'blue' | 'amber' {
  const map: Record<TipoCalculo, 'gray' | 'blue' | 'amber'> = {
    MANUAL: 'gray',
    POR_CANTIDAD: 'blue',
    POR_LOTE: 'amber',
  }
  return map[tipo]
}

function openCreateModal() {
  editingId.value = null
  form.value = { nombre: '', tipo_calculo: '', descripcion: '' }
  showModal.value = true
}

function openEditModal(inductor: InductorTiempo) {
  editingId.value = inductor.id
  form.value = {
    nombre: inductor.nombre,
    tipo_calculo: inductor.tipo_calculo,
    descripcion: inductor.descripcion ?? '',
  }
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

async function handleSubmit() {
  if (!form.value.tipo_calculo) return
  const dto = {
    nombre: form.value.nombre,
    tipo_calculo: form.value.tipo_calculo as TipoCalculo,
    descripcion: form.value.descripcion || undefined,
  }
  const result = editingId.value !== null
    ? await apiState.execute(() => store.update(editingId.value!, dto))
    : await apiState.execute(() => store.create(dto))
  if (result !== null) closeModal()
}

async function handleDelete(inductor: InductorTiempo) {
  const { isConfirmed } = await Swal.fire({
    title: '¿Eliminar inductor?',
    text: `Se eliminará "${inductor.nombre}". Esta acción no se puede deshacer.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    reverseButtons: true,
  })
  if (!isConfirmed) return
  await apiState.execute(() => store.remove(inductor.id))
}
</script>
