<template>
  <div class="p-6">
    <!-- Encabezado -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-default">Departamentos</h1>
        <p class="mt-1 text-sm text-soft">
          Gestiona los departamentos y consulta sus grupos de recursos.
        </p>
      </div>
      <button
        @click="openCreateModal"
        class="btn-base btn-primary btn-md shadow-sm"
      >
        <PlusIcon class="h-4 w-4" />
        Nuevo Departamento
      </button>
    </div>

    <!-- Layout maestro-detalle -->
    <div class="flex gap-6">
      <!-- Panel izquierdo: lista de cards -->
      <div class="w-80 shrink-0 space-y-3">
        <!-- Loading skeleton -->
        <SkeletonList v-if="loadingList" :count="4" height="h-24" />

        <!-- Sin departamentos -->
        <EmptyState
          v-else-if="store.departamentos.length === 0"
          message="No hay departamentos aún."
        >
          <template #icon>
            <BuildingIcon class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" />
          </template>
        </EmptyState>

        <!-- Cards -->
        <template v-else>
          <SelectableCard
            v-for="dep in store.departamentos"
            :key="dep.id"
            :title="dep.nombre"
            :subtitle="`ID #${dep.id}`"
            :selected="store.selectedDepartamento?.id === dep.id"
            @edit="openEditModal(dep)"
            @delete="handleDelete(dep)"
            @select="handleSelectDepartamento(dep)"
          >
            <template #action>
              <UsersIcon class="h-3.5 w-3.5" />
              Ver grupos de recursos
            </template>
          </SelectableCard>
        </template>
      </div>

      <!-- Panel derecho: grupos de recursos -->
      <div class="flex-1">
        <!-- Placeholder -->
        <EmptyState
          v-if="!store.selectedDepartamento"
          message="Selecciona un departamento"
          hint="para ver sus grupos de recursos"
          :centered="true"
          class="min-h-64 h-full"
        >
          <template #icon>
            <CursorArrowIcon class="h-12 w-12 text-gray-300 dark:text-gray-600" />
          </template>
        </EmptyState>

        <!-- Panel con grupos -->
        <div v-else>
          <div class="mb-4 flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-default">
                Grupos de Recursos
              </h2>
              <p class="text-sm text-soft">
                Departamento: <span class="font-medium text-brand-500">{{ store.selectedDepartamento.nombre }}</span>
              </p>
            </div>
            <button
              @click="store.clearSelection()"
              class="btn-icon"
              title="Cerrar panel"
            >
              <XIcon class="h-5 w-5" />
            </button>
          </div>

          <!-- Loading grupos -->
          <SkeletonList v-if="loadingGrupos" :count="3" height="h-16" />

          <!-- Sin grupos -->
          <EmptyState
            v-else-if="store.gruposRecursos.length === 0"
            message="Este departamento no tiene grupos de recursos aún."
          />

          <!-- Tabla de grupos -->
          <AppTable
            v-else
            :columns="gruposColumns"
          >
            <tr
              v-for="grupo in store.gruposRecursos"
              :key="grupo.id"
              :class="TR_CLASS"
            >
              <td :class="[TD_CLASS, 'font-medium text-default']">{{ grupo.nombre }}</td>
              <td :class="[TD_CLASS, 'text-right text-body']">
                {{ grupo.capacidad_practica_minutos.toLocaleString() }}
              </td>
              <td :class="[TD_CLASS, 'text-right']">
                <AppBadge color="green">
                  ${{ grupo.tasa_costo_por_minuto.toFixed(4) }}
                </AppBadge>
              </td>
            </tr>
          </AppTable>
        </div>
      </div>
    </div>

    <!-- Modal crear / editar -->
    <BaseModal
      :show="showModal"
      :title="editingId ? 'Editar Departamento' : 'Nuevo Departamento'"
      :loading="apiState.loading.value"
      :confirm-label="editingId ? 'Guardar cambios' : 'Crear departamento'"
      @close="closeModal"
      @confirm="handleSubmit"
    >
      <AppInput
        label="Nombre del departamento"
        v-model="form.nombre"
        field="nombre"
        :errors="apiState.validationErrors.value"
        placeholder="Ej: Producción, Ventas, Administración..."
        @keydown.enter="handleSubmit"
      />
    </BaseModal>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Swal from 'sweetalert2'
import { PlusIcon, XIcon, BuildingIcon, CursorArrowIcon, UsersIcon } from '@/components/icons'
import { useDepartamentosStore } from '../store/departamentosStore'
import { useApi } from '@/composables/useApi'
import type { Departamento } from '../types'
import { TR_CLASS, TD_CLASS } from '@/components/ui/AppTable.vue'

const store = useDepartamentosStore()
const apiState = useApi()

const loadingList = ref(false)
const loadingGrupos = ref(false)
const showModal = ref(false)
const editingId = ref<number | null>(null)
const form = ref({ nombre: '' })

const gruposColumns = [
  { label: 'Nombre', align: 'left' as const },
  { label: 'Capacidad (min)', align: 'right' as const },
  { label: 'Tasa $/min', align: 'right' as const },
]

onMounted(async () => {
  loadingList.value = true
  await store.fetchAll()
  loadingList.value = false
})

function openCreateModal() {
  editingId.value = null
  form.value = { nombre: '' }
  showModal.value = true
}

function openEditModal(dep: Departamento) {
  editingId.value = dep.id
  form.value = { nombre: dep.nombre }
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

async function handleSubmit() {
  const isEdit = editingId.value !== null
  const result = isEdit
    ? await apiState.execute(() => store.update(editingId.value!, form.value))
    : await apiState.execute(() => store.create(form.value))

  if (result !== null) closeModal()
}

async function handleSelectDepartamento(dep: Departamento) {
  if (store.selectedDepartamento?.id === dep.id) {
    store.clearSelection()
    return
  }
  loadingGrupos.value = true
  await store.selectDepartamento(dep)
  loadingGrupos.value = false
}

async function handleDelete(dep: Departamento) {
  const { isConfirmed } = await Swal.fire({
    title: '¿Eliminar departamento?',
    text: `Se eliminará "${dep.nombre}". Esta acción no se puede deshacer.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    reverseButtons: true,
  })

  if (!isConfirmed) return
  await apiState.execute(() => store.remove(dep.id))
}
</script>
