<template>
  <div>
    <!-- Barra de progreso de porcentaje asignado -->
    <div class="mb-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
      <div class="mb-2 flex items-center justify-between text-sm">
        <span class="font-medium text-body">Porcentaje asignado</span>
        <span
          :class="[
            'font-semibold',
            grupoStore.totalPorcentaje > 100
              ? 'text-blue-500'
              : grupoStore.totalPorcentaje === 100
              ? 'text-green-600 dark:text-green-400'
              : 'text-body',
          ]"
        >
          {{ grupoStore.totalPorcentaje }}%
        </span>
      </div>
      <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
        <div
          :class="[
            'h-full rounded-full transition-all duration-300',
            grupoStore.totalPorcentaje > 100
              ? 'bg-blue-500'
              : grupoStore.totalPorcentaje === 100
              ? 'bg-green-500'
              : 'bg-brand-500',
          ]"
          :style="{ width: `${Math.min(grupoStore.totalPorcentaje, 100)}%` }"
        />
      </div>
    </div>

    <!-- Header lista -->
    <div class="mb-3 flex items-center justify-between">
      <h3 class="font-medium text-default">Recursos del grupo</h3>
      <button @click="openAddRecursoModal" class="btn-base btn-primary btn-sm">
        <PlusIcon class="h-3.5 w-3.5" />
        Agregar Recurso
      </button>
    </div>

    <SkeletonList v-if="loading" :count="3" height="h-14" />

    <EmptyState
      v-else-if="grupoStore.recursosDelGrupo.length === 0"
      message="No hay recursos asignados a este grupo."
      hint="Agrega recursos desde el catálogo global."
    >
      <template #icon>
        <BoxIcon class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" />
      </template>
    </EmptyState>

    <AppTable v-else :columns="recursosColumns">
      <tr v-for="recurso in grupoStore.recursosDelGrupo" :key="recurso.recurso_id" :class="TR_CLASS">
        <td :class="[TD_CLASS, 'font-medium text-default']">{{ recurso.nombre }}</td>
        <td :class="TD_CLASS">
          <AppBadge :color="tipoBadgeColor(recurso.tipo)">{{ tipoLabel(recurso.tipo) }}</AppBadge>
        </td>
        <td :class="[TD_CLASS, 'text-right text-gray-600 dark:text-gray-300']">
          ${{ recurso.costo_mensual.toLocaleString() }}
        </td>
        <td :class="[TD_CLASS, 'text-right']">
          <span class="font-semibold text-brand-600 dark:text-brand-400">{{ recurso.porcentaje }}%</span>
        </td>
        <td :class="TD_CLASS">
          <div class="flex justify-center">
            <RowActions @edit="openEditPorcentajeModal(recurso)" @delete="handleRemoveRecurso(recurso)" />
          </div>
        </td>
      </tr>
    </AppTable>

    <!-- Modal: agregar recurso -->
    <BaseModal
      :show="showAddModal"
      title="Agregar Recurso al Grupo"
      :loading="addApiState.loading.value"
      confirm-label="Agregar al grupo"
      loading-label="Agregando..."
      :confirm-disabled="addForm.recurso_id === 0"
      @close="closeAddModal"
      @confirm="handleAddRecurso"
    >
      <div class="space-y-4">
        <div v-if="loadingCatalogo" class="py-8 text-center text-sm text-gray-400">Cargando catálogo...</div>
        <template v-else>
          <AppField
            label="Recurso"
            field="recurso_id"
            :errors="addApiState.validationErrors.value"
            v-slot="{ inputClass }"
          >
            <select v-model="addForm.recurso_id" :class="inputClass">
              <option :value="0" disabled>Selecciona un recurso...</option>
              <option v-for="recurso in recursosDisponibles" :key="recurso.id" :value="recurso.id">
                {{ recurso.nombre }} — {{ tipoLabel(recurso.tipo) }} — ${{ recurso.costo_mensual.toLocaleString() }}
              </option>
            </select>
          </AppField>
          <AppInput
            label="Porcentaje (%)"
            v-model.number="addForm.porcentaje"
            field="porcentaje"
            type="number"
            min="1"
            max="100"
            placeholder="Ej: 60"
            hint="Porcentaje del costo del recurso que se asigna al grupo."
            :errors="addApiState.validationErrors.value"
          />
        </template>
      </div>
    </BaseModal>

    <!-- Modal: editar porcentaje -->
    <BaseModal
      :show="showEditModal"
      title="Editar Porcentaje"
      size="sm"
      :loading="editApiState.loading.value"
      confirm-label="Guardar"
      @close="closeEditModal"
      @confirm="handleEditPorcentaje"
    >
      <div>
        <p class="mb-3 text-sm text-soft">
          Recurso: <strong class="text-default">{{ editingRecurso?.nombre }}</strong>
        </p>
        <AppInput
          label="Nuevo porcentaje (%)"
          v-model.number="editPorcentaje"
          field="porcentaje"
          type="number"
          min="1"
          max="100"
          :errors="editApiState.validationErrors.value"
        />
      </div>
    </BaseModal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import Swal from 'sweetalert2'
import { TR_CLASS, TD_CLASS } from '@/components/ui/AppTable.vue'
import { BoxIcon, PlusIcon } from '@/components/icons'
import { useGruposRecursosStore } from '../store/gruposRecursosStore'
import { useRecursosStore } from '@/modules/recursos/store/recursosStore'
import { useApi } from '@/composables/useApi'
import type { RecursoEnGrupo } from '../types'

defineProps<{ loading: boolean }>()

const grupoStore = useGruposRecursosStore()
const recursosStore = useRecursosStore()
const addApiState = useApi()
const editApiState = useApi()

const loadingCatalogo = ref(false)
const showAddModal = ref(false)
const addForm = ref({ recurso_id: 0, porcentaje: 0 })
const showEditModal = ref(false)
const editingRecurso = ref<RecursoEnGrupo | null>(null)
const editPorcentaje = ref(0)

const recursosColumns = [
  { label: 'Recurso' },
  { label: 'Tipo' },
  { label: 'Costo/mes', align: 'right' as const },
  { label: '%', align: 'right' as const },
  { label: 'Acciones', align: 'center' as const },
]

const asignadosIds = computed(() => new Set(grupoStore.recursosDelGrupo.map((r) => r.recurso_id)))
const recursosDisponibles = computed(() => recursosStore.recursos.filter((r) => !asignadosIds.value.has(r.id)))

function tipoLabel(tipo: string) {
  const map: Record<string, string> = { humano: 'Humano', maquina: 'Máquina', infraestructura: 'Infraestructura' }
  return map[tipo] ?? tipo
}

function tipoBadgeColor(tipo: string): 'blue' | 'purple' | 'orange' | 'gray' {
  const map: Record<string, 'blue' | 'purple' | 'orange'> = { humano: 'blue', maquina: 'purple', infraestructura: 'orange' }
  return map[tipo] ?? 'gray'
}

async function openAddRecursoModal() {
  addForm.value = { recurso_id: 0, porcentaje: 0 }
  showAddModal.value = true
  if (recursosStore.recursos.length === 0) {
    loadingCatalogo.value = true
    await recursosStore.fetchAll()
    loadingCatalogo.value = false
  }
}

function closeAddModal() { showAddModal.value = false }

async function handleAddRecurso() {
  const result = await addApiState.execute(() => grupoStore.addRecurso(addForm.value))
  if (result !== null) closeAddModal()
}

function openEditPorcentajeModal(recurso: RecursoEnGrupo) {
  editingRecurso.value = recurso
  editPorcentaje.value = recurso.porcentaje
  showEditModal.value = true
}

function closeEditModal() { showEditModal.value = false; editingRecurso.value = null }

async function handleEditPorcentaje() {
  if (!editingRecurso.value) return
  const result = await editApiState.execute(() =>
    grupoStore.updateRecurso(editingRecurso.value!.recurso_id, { porcentaje: editPorcentaje.value }),
  )
  if (result !== null) closeEditModal()
}

async function handleRemoveRecurso(recurso: RecursoEnGrupo) {
  const { isConfirmed } = await Swal.fire({
    title: '¿Quitar recurso?',
    text: `Se quitará "${recurso.nombre}" de este grupo.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, quitar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    reverseButtons: true,
  })
  if (!isConfirmed) return
  await grupoStore.removeRecurso(recurso.recurso_id)
}
</script>
