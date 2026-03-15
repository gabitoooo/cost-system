<template>
  <div class="flex-1 min-w-0">
    <EmptyState
      v-if="!actividadesStore.selectedActividad"
      centered
      class="h-full min-h-48"
      message="Selecciona una actividad"
      hint="para ver sus inductores"
    >
      <template #icon>
        <ClockIcon class="h-10 w-10 text-gray-300 dark:text-gray-600" />
      </template>
    </EmptyState>

    <div v-else>
      <div class="mb-3 flex items-center justify-between">
        <div>
          <h3 class="font-medium text-default">{{ actividadesStore.selectedActividad.nombre }}</h3>
          <p class="text-xs text-soft">{{ actividadesStore.selectedActividad.tiempo_base_minutos }} min base</p>
        </div>
        <div class="flex items-center gap-2">
          <button @click="openAddInductorModal" class="btn-base btn-primary btn-sm">
            <PlusIcon class="h-3.5 w-3.5" />
            Agregar Inductor
          </button>
          <button @click="actividadesStore.clearSelection()" class="btn-icon">
            <XIcon class="h-4 w-4" />
          </button>
        </div>
      </div>

      <SkeletonList v-if="loading" :count="3" height="h-12" />

      <EmptyState
        v-else-if="actividadesStore.inductoresDeActividad.length === 0"
        message="Sin inductores asignados."
        hint="Agrega inductores de tiempo para calcular el costo."
      />

      <AppTable v-else :columns="inductoresColumns">
        <tr v-for="item in actividadesStore.inductoresDeActividad" :key="item.id" :class="TR_CLASS">
          <td :class="[TD_CLASS, 'font-medium text-default']">{{ item.inductor_nombre }}</td>
          <td :class="[TD_CLASS, 'text-center']">
            <button
              @click="handleRemoveInductor(item)"
              class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition-colors"
              title="Quitar"
            >
              <TrashIcon class="h-4 w-4" />
            </button>
          </td>
        </tr>
      </AppTable>
    </div>

    <!-- Modal agregar inductor -->
    <BaseModal
      :show="showInductorModal"
      title="Agregar Inductor"
      :loading="inductorApiState.loading.value"
      confirm-label="Agregar"
      loading-label="Agregando..."
      :confirm-disabled="inductorForm.inductor_tiempo_id === 0"
      @close="closeInductorModal"
      @confirm="handleSubmitInductor"
    >
      <div>
        <div v-if="loadingCatalogo" class="py-6 text-center text-sm text-gray-400">Cargando catálogo...</div>
        <AppField
          v-else
          label="Inductor de tiempo"
          field="inductor_tiempo_id"
          :errors="inductorApiState.validationErrors.value"
          v-slot="{ inputClass }"
        >
          <select v-model="inductorForm.inductor_tiempo_id" :class="inputClass">
            <option :value="0" disabled>Selecciona un inductor...</option>
            <option v-for="ind in inductoresCatalogoDisponibles" :key="ind.id" :value="ind.id">
              {{ ind.nombre }} — {{ tipoCalculoLabel(ind.tipo_calculo) }}
            </option>
          </select>
        </AppField>
      </div>
    </BaseModal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import Swal from 'sweetalert2'
import { TR_CLASS, TD_CLASS } from '@/components/ui/AppTable.vue'
import { ClockIcon, PlusIcon, TrashIcon, XIcon } from '@/components/icons'
import { useActividadesStore } from '../store/actividadesStore'
import { useInductoresTiempoStore } from '@/modules/inductores-tiempo/store/inductoresTiempoStore'
import { useApi } from '@/composables/useApi'
import type { ActividadInductor } from '../types'

defineProps<{ loading: boolean }>()

const actividadesStore = useActividadesStore()
const inductoresTiempoStore = useInductoresTiempoStore()
const inductorApiState = useApi()

const loadingCatalogo = ref(false)
const showInductorModal = ref(false)
const inductorForm = ref({ inductor_tiempo_id: 0 })

const inductoresColumns = [
  { label: 'Inductor' },
  { label: 'Acciones', align: 'center' as const },
]

const asignadosIds = computed(() =>
  new Set(actividadesStore.inductoresDeActividad.map((i) => i.inductor_tiempo_id)),
)
const inductoresCatalogoDisponibles = computed(() =>
  inductoresTiempoStore.inductores.filter((i) => !asignadosIds.value.has(i.id)),
)

function tipoCalculoLabel(tipo: string) {
  const map: Record<string, string> = { MANUAL: 'Manual', POR_CANTIDAD: 'Por cantidad', POR_LOTE: 'Por lote' }
  return map[tipo] ?? tipo
}

async function openAddInductorModal() {
  inductorForm.value = { inductor_tiempo_id: 0 }
  showInductorModal.value = true
  if (inductoresTiempoStore.inductores.length === 0) {
    loadingCatalogo.value = true
    await inductoresTiempoStore.fetchAll()
    loadingCatalogo.value = false
  }
}

function closeInductorModal() { showInductorModal.value = false }

async function handleSubmitInductor() {
  const result = await inductorApiState.execute(() =>
    actividadesStore.addInductor({ inductor_tiempo_id: inductorForm.value.inductor_tiempo_id }),
  )
  if (result !== null) closeInductorModal()
}

async function handleRemoveInductor(item: ActividadInductor) {
  const { isConfirmed } = await Swal.fire({
    title: '¿Quitar inductor?',
    text: `Se quitará "${item.inductor_nombre}" de esta actividad.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, quitar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    reverseButtons: true,
  })
  if (!isConfirmed) return
  await actividadesStore.removeInductor(item.id)
}
</script>
