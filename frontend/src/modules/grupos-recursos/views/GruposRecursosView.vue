<template>
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-default">Centros de Costo</h1>
        <p class="mt-1 text-sm text-soft">
          Grupos de recursos con su capacidad práctica y tasa de costo por minuto.
        </p>
      </div>
      <button
        @click="openCreateGrupoModal"
        :disabled="!selectedDeptId"
        class="btn-base btn-primary btn-md shadow-sm"
      >
        <PlusIcon class="h-4 w-4" />
        Nuevo Grupo
      </button>
    </div>

    <!-- Filtro departamento -->
    <div class="mb-5 flex items-center gap-3">
      <FilterIcon class="h-4 w-4 shrink-0 text-gray-400" />
      <select
        v-model="selectedDeptId"
        class="rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-body outline-none focus:border-brand-500 min-w-52"
      >
        <option :value="null">Selecciona un departamento...</option>
        <option v-for="dept in deptStore.departamentos" :key="dept.id" :value="dept.id">
          {{ dept.nombre }}
        </option>
      </select>
      <span v-if="loadingDepts" class="text-xs text-gray-400">Cargando...</span>
    </div>

    <!-- Layout principal -->
    <div class="flex gap-6">

      <!-- Panel izquierdo: lista de grupos -->
      <div class="w-80 shrink-0 space-y-3">
        <EmptyState
          v-if="!selectedDeptId"
          centered
          class="min-h-48"
          message="Selecciona un departamento para ver sus grupos."
        >
          <template #icon>
            <BuildingIcon class="h-10 w-10 text-gray-300 dark:text-gray-600" />
          </template>
        </EmptyState>

        <SkeletonList v-else-if="loadingGrupos" :count="4" height="h-28" />

        <EmptyState
          v-else-if="deptStore.gruposRecursos.length === 0"
          message="Este departamento no tiene grupos aún."
        />

        <template v-else>
          <SelectableCard
            v-for="grupo in deptStore.gruposRecursos"
            :key="grupo.id"
            :title="grupo.nombre"
            :subtitle="`Cap: ${grupo.capacidad_practica_minutos.toLocaleString()} min`"
            :selected="grupoStore.selectedGrupo?.id === grupo.id"
            @edit="openEditGrupoModal(grupo)"
            @delete="handleDeleteGrupo(grupo)"
            @select="handleSelectGrupo(grupo)"
          >
            <template #extra>
              <div class="mt-2 flex items-center justify-between">
                <span class="text-xs text-soft">Tasa:</span>
                <AppBadge color="green">${{ (grupo.tasa_costo_por_minuto ?? 0).toFixed(4) }}/min</AppBadge>
              </div>
            </template>
            <template #action>
              <ListIcon class="h-3.5 w-3.5" />
              Ver detalle
            </template>
          </SelectableCard>
        </template>
      </div>

      <!-- Panel derecho: detalle del grupo con tabs -->
      <div class="flex-1 min-w-0">
        <EmptyState
          v-if="!grupoStore.selectedGrupo"
          centered
          class="h-full min-h-64"
          message="Selecciona un grupo"
          hint="para gestionar sus recursos y actividades"
        >
          <template #icon>
            <CursorArrowIcon class="h-12 w-12 text-gray-300 dark:text-gray-600" />
          </template>
        </EmptyState>

        <div v-else>
          <!-- Header grupo seleccionado -->
          <div class="mb-4 flex items-start justify-between gap-4">
            <div>
              <h2 class="text-lg font-semibold text-default">{{ grupoStore.selectedGrupo.nombre }}</h2>
              <div class="mt-1 flex items-center gap-4 text-sm text-soft">
                <span>Capacidad: <strong class="text-gray-700 dark:text-gray-200">{{ grupoStore.selectedGrupo.capacidad_practica_minutos.toLocaleString() }} min</strong></span>
                <span>Tasa: <strong class="text-green-600 dark:text-green-400">${{ (grupoStore.selectedGrupo.tasa_costo_por_minuto ?? 0).toFixed(4) }}/min</strong></span>
              </div>
            </div>
            <button @click="handleClosePanel" class="btn-icon" title="Cerrar panel">
              <XIcon class="h-5 w-5" />
            </button>
          </div>

          <!-- Tabs -->
          <AppTabs v-model="activeTab" :tabs="tabItems" />

          <!-- Contenido de tabs -->
          <TabRecursos v-if="activeTab === 'recursos'" :loading="loadingRecursos" />
          <TabActividades v-else-if="activeTab === 'actividades'" />
        </div>
      </div>
    </div>

    <!-- Modal crear / editar grupo -->
    <BaseModal
      :show="showGrupoModal"
      :title="editingGrupoId ? 'Editar Grupo' : 'Nuevo Grupo de Recursos'"
      :loading="grupoApiState.loading.value"
      :confirm-label="editingGrupoId ? 'Guardar cambios' : 'Crear grupo'"
      @close="closeGrupoModal"
      @confirm="handleSubmitGrupo"
    >
      <div class="space-y-4">
        <AppInput
          label="Nombre"
          v-model="grupoForm.nombre"
          field="nombre"
          placeholder="Ej: Maquinaria, Operarios..."
          :errors="grupoApiState.validationErrors.value"
        />
        <AppInput
          label="Capacidad práctica (minutos)"
          v-model.number="grupoForm.capacidad_practica_minutos"
          field="capacidad_practica_minutos"
          type="number"
          min="1"
          placeholder="Ej: 10000"
          :errors="grupoApiState.validationErrors.value"
        />
      </div>
    </BaseModal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue'
import Swal from 'sweetalert2'
import { BoxIcon, ClipboardIcon } from '@/components/icons'
import TabRecursos from '../components/TabRecursos.vue'
import TabActividades from '../components/TabActividades.vue'
import { useDepartamentosStore } from '@/modules/departamentos/store/departamentosStore'
import { useGruposRecursosStore } from '../store/gruposRecursosStore'
import { useActividadesStore } from '@/modules/actividades/store/actividadesStore'
import { useApi } from '@/composables/useApi'
import gruposRecursosService from '../api/gruposRecursosService'
import type { GrupoRecurso } from '../types'

const deptStore = useDepartamentosStore()
const grupoStore = useGruposRecursosStore()
const actividadesStore = useActividadesStore()
const grupoApiState = useApi()

const loadingDepts = ref(false)
const loadingGrupos = ref(false)
const loadingRecursos = ref(false)

const selectedDeptId = ref<number | null>(null)
const activeTab = ref<'recursos' | 'actividades'>('recursos')

const showGrupoModal = ref(false)
const editingGrupoId = ref<number | null>(null)
const grupoForm = ref({ nombre: '', capacidad_practica_minutos: 0 })

const tabItems = computed(() => [
  { value: 'recursos',    label: 'Recursos',    icon: BoxIcon,       count: grupoStore.recursosDelGrupo.length },
  { value: 'actividades', label: 'Actividades', icon: ClipboardIcon, count: actividadesStore.actividadesDelGrupo.length },
])

onMounted(async () => {
  loadingDepts.value = true
  await deptStore.fetchAll()
  loadingDepts.value = false
})

watch(selectedDeptId, async (newId) => {
  handleClosePanel()
  if (newId) {
    const dept = deptStore.departamentos.find((d) => d.id === newId)
    if (dept) {
      loadingGrupos.value = true
      await deptStore.selectDepartamento(dept)
      loadingGrupos.value = false
    }
  } else {
    deptStore.clearSelection()
  }
})

function handleClosePanel() {
  grupoStore.clearSelection()
  actividadesStore.clearGrupo()
  activeTab.value = 'recursos'
}

async function handleSelectGrupo(grupo: GrupoRecurso) {
  if (grupoStore.selectedGrupo?.id === grupo.id) { handleClosePanel(); return }
  actividadesStore.clearGrupo()
  activeTab.value = 'recursos'
  loadingRecursos.value = true
  await grupoStore.selectGrupo(grupo)
  loadingRecursos.value = false
}

function openCreateGrupoModal() {
  editingGrupoId.value = null
  grupoForm.value = { nombre: '', capacidad_practica_minutos: 0 }
  showGrupoModal.value = true
}

function openEditGrupoModal(grupo: GrupoRecurso) {
  editingGrupoId.value = grupo.id
  grupoForm.value = { nombre: grupo.nombre, capacidad_practica_minutos: grupo.capacidad_practica_minutos }
  showGrupoModal.value = true
}

function closeGrupoModal() { showGrupoModal.value = false }

async function handleSubmitGrupo() {
  const dept = deptStore.departamentos.find((d) => d.id === selectedDeptId.value)
  if (!dept) return
  if (editingGrupoId.value) {
    const result = await grupoApiState.execute(() =>
      gruposRecursosService.update(editingGrupoId.value!, {
        nombre: grupoForm.value.nombre,
        capacidad_practica_minutos: grupoForm.value.capacidad_practica_minutos,
      }),
    )
    if (result !== null) {
      if (grupoStore.selectedGrupo?.id === editingGrupoId.value)
        Object.assign(grupoStore.selectedGrupo, (result as { data: GrupoRecurso }).data)
      await deptStore.selectDepartamento(dept)
      closeGrupoModal()
    }
  } else {
    const result = await grupoApiState.execute(() =>
      gruposRecursosService.create({
        departamento_id: dept.id,
        nombre: grupoForm.value.nombre,
        capacidad_practica_minutos: grupoForm.value.capacidad_practica_minutos,
      }),
    )
    if (result !== null) { await deptStore.selectDepartamento(dept); closeGrupoModal() }
  }
}

async function handleDeleteGrupo(grupo: GrupoRecurso) {
  const { isConfirmed } = await Swal.fire({
    title: '¿Eliminar grupo?',
    text: `Se eliminará "${grupo.nombre}". Esta acción no se puede deshacer.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    reverseButtons: true,
  })
  if (!isConfirmed) return
  const dept = deptStore.departamentos.find((d) => d.id === selectedDeptId.value)
  await grupoApiState.execute(() => gruposRecursosService.remove(grupo.id))
  if (grupoStore.selectedGrupo?.id === grupo.id) handleClosePanel()
  if (dept) await deptStore.selectDepartamento(dept)
}
</script>
