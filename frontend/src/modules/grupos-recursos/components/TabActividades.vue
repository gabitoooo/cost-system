<template>
  <div class="flex gap-5">
    <!-- Panel izquierdo: lista de actividades -->
    <div class="w-64 shrink-0 space-y-2">
      <div class="mb-2 flex items-center justify-between">
        <span class="text-sm font-medium text-body">Actividades</span>
        <button @click="openCreateModal" class="btn-base btn-primary btn-xs">
          <PlusIcon class="h-3 w-3" />
          Nueva
        </button>
      </div>

      <SkeletonList v-if="loadingActividades" :count="3" height="h-20" />

      <EmptyState
        v-else-if="actividadesStore.actividadesDelGrupo.length === 0"
        message="Sin actividades aún."
      />

      <template v-else>
        <SelectableCard
          v-for="actividad in actividadesStore.actividadesDelGrupo"
          :key="actividad.id"
          size="sm"
          :title="actividad.nombre"
          :subtitle="`${actividad.tiempo_base_minutos} min base`"
          :selected="actividadesStore.selectedActividad?.id === actividad.id"
          @edit="openEditModal(actividad)"
          @delete="handleDelete(actividad)"
          @select="handleSelect(actividad)"
        >
          <template #action>Ver Inductores</template>
        </SelectableCard>
      </template>
    </div>

    <!-- Panel derecho: inductores de la actividad seleccionada -->
    <ActividadInductoresPanel :loading="loadingInductores" />

    <!-- Modal crear / editar actividad -->
    <BaseModal
      :show="showModal"
      :title="editingId ? 'Editar Actividad' : 'Nueva Actividad'"
      :loading="apiState.loading.value"
      :confirm-label="editingId ? 'Guardar cambios' : 'Crear actividad'"
      @close="closeModal"
      @confirm="handleSubmit"
    >
      <div class="space-y-4">
        <AppInput
          label="Nombre"
          v-model="form.nombre"
          field="nombre"
          placeholder="Ej: Corte, Ensamblaje, Pintura..."
          :errors="apiState.validationErrors.value"
        />
        <AppInput
          label="Tiempo base (minutos)"
          v-model.number="form.tiempo_base_minutos"
          field="tiempo_base_minutos"
          type="number"
          min="0"
          step="0.01"
          placeholder="Ej: 15"
          :errors="apiState.validationErrors.value"
        />
      </div>
    </BaseModal>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Swal from 'sweetalert2'
import { PlusIcon } from '@/components/icons'
import ActividadInductoresPanel from '@/modules/actividades/components/ActividadInductoresPanel.vue'
import { useActividadesStore } from '@/modules/actividades/store/actividadesStore'
import { useGruposRecursosStore } from '../store/gruposRecursosStore'
import { useApi } from '@/composables/useApi'
import actividadesService from '@/modules/actividades/api/actividadesService'
import type { Actividad } from '@/modules/actividades/types'

const actividadesStore = useActividadesStore()
const grupoStore = useGruposRecursosStore()
const apiState = useApi()

const loadingActividades = ref(false)
const loadingInductores = ref(false)
const showModal = ref(false)
const editingId = ref<number | null>(null)
const form = ref({ nombre: '', tiempo_base_minutos: 0 })

onMounted(async () => {
  if (grupoStore.selectedGrupo && actividadesStore.actividadesDelGrupo.length === 0) {
    loadingActividades.value = true
    await actividadesStore.fetchByGrupo(grupoStore.selectedGrupo.id)
    loadingActividades.value = false
  }
})

function openCreateModal() {
  editingId.value = null
  form.value = { nombre: '', tiempo_base_minutos: 0 }
  showModal.value = true
}

function openEditModal(actividad: Actividad) {
  editingId.value = actividad.id
  form.value = { nombre: actividad.nombre, tiempo_base_minutos: actividad.tiempo_base_minutos }
  showModal.value = true
}

function closeModal() { showModal.value = false }

async function handleSubmit() {
  if (!grupoStore.selectedGrupo) return
  if (editingId.value) {
    const result = await apiState.execute(() =>
      actividadesService.update(editingId.value!, {
        nombre: form.value.nombre,
        tiempo_base_minutos: form.value.tiempo_base_minutos,
      }),
    )
    if (result !== null) {
      const data = (result as { data: Actividad }).data
      const idx = actividadesStore.actividadesDelGrupo.findIndex((a) => a.id === editingId.value)
      if (idx !== -1) actividadesStore.actividadesDelGrupo[idx] = data
      if (actividadesStore.selectedActividad?.id === editingId.value) Object.assign(actividadesStore.selectedActividad, data)
      closeModal()
    }
  } else {
    const result = await apiState.execute(() =>
      actividadesService.create({
        grupo_recursos_id: grupoStore.selectedGrupo!.id,
        nombre: form.value.nombre,
        tiempo_base_minutos: form.value.tiempo_base_minutos,
      }),
    )
    if (result !== null) {
      actividadesStore.actividadesDelGrupo.push((result as { data: Actividad }).data)
      closeModal()
    }
  }
}

async function handleDelete(actividad: Actividad) {
  const { isConfirmed } = await Swal.fire({
    title: '¿Eliminar actividad?',
    text: `Se eliminará "${actividad.nombre}". Esta acción no se puede deshacer.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    reverseButtons: true,
  })
  if (!isConfirmed) return
  const result = await apiState.execute(() => actividadesService.remove(actividad.id))
  if (result !== null) {
    actividadesStore.actividadesDelGrupo = actividadesStore.actividadesDelGrupo.filter((a) => a.id !== actividad.id)
    if (actividadesStore.selectedActividad?.id === actividad.id) actividadesStore.clearSelection()
  }
}

async function handleSelect(actividad: Actividad) {
  if (actividadesStore.selectedActividad?.id === actividad.id) {
    actividadesStore.clearSelection()
    return
  }
  loadingInductores.value = true
  await actividadesStore.selectActividad(actividad)
  loadingInductores.value = false
}
</script>
