<template>
  <div class="p-6">
    <!-- ─── Header ──────────────────────────────────────────────────────────── -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-default">Recursos</h1>
        <p class="mt-1 text-sm text-soft">
          Catálogo global de recursos: humanos, maquinaria e infraestructura.
        </p>
      </div>
      <button
        @click="openCreateModal"
        class="btn-base btn-primary btn-md shadow-sm"
      >
        <PlusIcon class="h-4 w-4" />
        Nuevo Recurso
      </button>
    </div>

    <!-- ─── Filtros ─────────────────────────────────────────────────────────── -->
    <div class="mb-4 flex items-center gap-3">
      <div class="relative flex-1 max-w-xs">
        <SearchIcon class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
        <input
          v-model="search"
          type="text"
          placeholder="Buscar recurso..."
          class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 pl-9 pr-4 py-2 text-sm text-default placeholder-gray-400 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
        />
      </div>
      <select
        v-model="filterTipo"
        class="rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-body outline-none focus:border-brand-500"
      >
        <option value="">Todos los tipos</option>
        <option value="humano">Humano</option>
        <option value="maquina">Máquina</option>
        <option value="infraestructura">Infraestructura</option>
      </select>
    </div>

    <!-- ─── Tabla ───────────────────────────────────────────────────────────── -->
    <AppTable :columns="recursosColumns">
      <!-- Skeletons -->
      <template v-if="loadingList">
        <tr v-for="n in 5" :key="n" :class="TR_CLASS">
          <td :class="TD_CLASS"><div class="h-4 w-40 animate-pulse rounded bg-gray-100 dark:bg-gray-700" /></td>
          <td :class="TD_CLASS"><div class="h-5 w-24 animate-pulse rounded-full bg-gray-100 dark:bg-gray-700" /></td>
          <td :class="TD_CLASS"><div class="ml-auto h-4 w-20 animate-pulse rounded bg-gray-100 dark:bg-gray-700" /></td>
          <td :class="TD_CLASS"><div class="mx-auto h-4 w-16 animate-pulse rounded bg-gray-100 dark:bg-gray-700" /></td>
        </tr>
      </template>

      <!-- Estado vacío -->
      <tr v-else-if="recursosFiltrados.length === 0">
        <td colspan="4" class="px-4 py-16 text-center">
          <BoxIcon class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" />
          <p class="mt-3 text-sm font-medium text-gray-400 dark:text-gray-500">
            {{ search || filterTipo ? 'Sin resultados para la búsqueda' : 'No hay recursos registrados' }}
          </p>
          <p class="mt-1 text-xs text-gray-400 dark:text-gray-600">
            {{ search || filterTipo ? 'Prueba con otros filtros.' : 'Comienza creando el primer recurso.' }}
          </p>
        </td>
      </tr>

      <!-- Filas -->
      <tr
        v-else
        v-for="recurso in recursosFiltrados"
        :key="recurso.id"
        :class="TR_CLASS"
      >
        <td :class="[TD_CLASS, 'font-medium text-default']">{{ recurso.nombre }}</td>
        <td :class="TD_CLASS">
          <AppBadge :color="tipoBadgeColor(recurso.tipo)">{{ tipoLabel(recurso.tipo) }}</AppBadge>
        </td>
        <td :class="[TD_CLASS, 'text-right text-body']">
          ${{ recurso.costo_mensual.toLocaleString() }}
        </td>
        <td :class="TD_CLASS">
          <div class="flex items-center justify-center">
            <RowActions @edit="openEditModal(recurso)" @delete="handleDelete(recurso)" />
          </div>
        </td>
      </tr>
    </AppTable>

    <!-- Modal crear / editar -->
    <BaseModal
      :show="showModal"
      :title="editingId ? 'Editar Recurso' : 'Nuevo Recurso'"
      :loading="apiState.loading.value"
      :confirm-label="editingId ? 'Guardar cambios' : 'Crear recurso'"
      @close="closeModal"
      @confirm="handleSubmit"
    >
      <div class="space-y-4">
        <AppInput label="Nombre" v-model="form.nombre" field="nombre" :errors="apiState.validationErrors.value" placeholder="Ej: Torno CNC, Operario de planta..." />
        <AppField label="Tipo" field="tipo" :errors="apiState.validationErrors.value" v-slot="{ inputClass }">
          <select v-model="form.tipo" :class="inputClass">
            <option value="" disabled>Selecciona un tipo...</option>
            <option value="humano">Humano</option>
            <option value="maquina">Máquina</option>
            <option value="infraestructura">Infraestructura</option>
          </select>
        </AppField>
        <AppInput label="Costo mensual ($)" v-model.number="form.costo_mensual" field="costo_mensual" type="number" min="0" step="0.01" placeholder="Ej: 3000" :errors="apiState.validationErrors.value" />
      </div>
    </BaseModal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import Swal from 'sweetalert2'
import { PlusIcon, SearchIcon, BoxIcon } from '@/components/icons'
import { useRecursosStore } from '../store/recursosStore'
import { useApi } from '@/composables/useApi'
import type { Recurso, TipoRecurso } from '../types'
import { TR_CLASS, TD_CLASS } from '@/components/ui/AppTable.vue'

const store = useRecursosStore()
const apiState = useApi()

const loadingList = ref(false)
const showModal = ref(false)
const editingId = ref<number | null>(null)
const search = ref('')
const filterTipo = ref('')
const form = ref<{ nombre: string; tipo: TipoRecurso | ''; costo_mensual: number }>({
  nombre: '',
  tipo: '',
  costo_mensual: 0,
})

const recursosColumns = [
  { label: 'Nombre', align: 'left' as const },
  { label: 'Tipo', align: 'left' as const },
  { label: 'Costo Mensual', align: 'right' as const },
  { label: 'Acciones', align: 'center' as const },
]

const recursosFiltrados = computed(() => {
  let list = store.recursos
  if (search.value) {
    const q = search.value.toLowerCase()
    list = list.filter((r) => r.nombre.toLowerCase().includes(q))
  }
  if (filterTipo.value) {
    list = list.filter((r) => r.tipo === filterTipo.value)
  }
  return list
})

onMounted(async () => {
  loadingList.value = true
  await store.fetchAll()
  loadingList.value = false
})

function tipoLabel(tipo: string) {
  const map: Record<string, string> = { humano: 'Humano', maquina: 'Máquina', infraestructura: 'Infraestructura' }
  return map[tipo] ?? tipo
}

function tipoBadgeColor(tipo: string): 'blue' | 'purple' | 'orange' | 'gray' {
  const map: Record<string, 'blue' | 'purple' | 'orange'> = {
    humano: 'blue',
    maquina: 'purple',
    infraestructura: 'orange',
  }
  return map[tipo] ?? 'gray'
}

function openCreateModal() {
  editingId.value = null
  form.value = { nombre: '', tipo: '', costo_mensual: 0 }
  showModal.value = true
}

function openEditModal(recurso: Recurso) {
  editingId.value = recurso.id
  form.value = { nombre: recurso.nombre, tipo: recurso.tipo, costo_mensual: recurso.costo_mensual }
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

async function handleSubmit() {
  if (!form.value.tipo) return
  const dto = { nombre: form.value.nombre, tipo: form.value.tipo as TipoRecurso, costo_mensual: form.value.costo_mensual }
  const result = editingId.value !== null
    ? await apiState.execute(() => store.update(editingId.value!, dto))
    : await apiState.execute(() => store.create(dto))
  if (result !== null) closeModal()
}

async function handleDelete(recurso: Recurso) {
  const { isConfirmed } = await Swal.fire({
    title: '¿Eliminar recurso?',
    text: `Se eliminará "${recurso.nombre}". Esta acción no se puede deshacer.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    reverseButtons: true,
  })
  if (!isConfirmed) return
  await apiState.execute(() => store.remove(recurso.id))
}
</script>
