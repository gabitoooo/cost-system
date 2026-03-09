<template>
  <div class="p-6">
    <!-- Encabezado -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Departamentos</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Gestiona los departamentos y consulta sus grupos de recursos.
        </p>
      </div>
      <button
        @click="openCreateModal"
        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition-colors"
      >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nuevo Departamento
      </button>
    </div>

    <!-- Layout maestro-detalle -->
    <div class="flex gap-6">
      <!-- Panel izquierdo: lista de cards -->
      <div class="w-80 shrink-0 space-y-3">
        <!-- Loading skeleton -->
        <template v-if="loadingList">
          <div
            v-for="n in 4"
            :key="n"
            class="h-24 animate-pulse rounded-xl bg-gray-100 dark:bg-gray-800"
          />
        </template>

        <!-- Sin departamentos -->
        <div
          v-else-if="store.departamentos.length === 0"
          class="rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-8 text-center"
        >
          <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">No hay departamentos aún.</p>
        </div>

        <!-- Cards -->
        <template v-else>
        <div
          v-for="dep in store.departamentos"
          :key="dep.id"
          :class="[
            'rounded-xl border p-4 transition-all cursor-pointer',
            store.selectedDepartamento?.id === dep.id
              ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10 shadow-sm ring-1 ring-brand-500'
              : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-brand-300 hover:shadow-sm',
          ]"
        >
          <div class="flex items-start justify-between gap-2">
            <div class="min-w-0 flex-1">
              <h3 class="truncate font-semibold text-gray-900 dark:text-white">{{ dep.nombre }}</h3>
              <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">ID #{{ dep.id }}</p>
            </div>
            <!-- Acciones -->
            <div class="flex shrink-0 items-center gap-1">
              <button
                @click.stop="openEditModal(dep)"
                class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"
                title="Editar"
              >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button
                @click.stop="handleDelete(dep)"
                class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition-colors"
                title="Eliminar"
              >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Botón ver grupos -->
          <button
            @click="handleSelectDepartamento(dep)"
            :class="[
              'mt-3 w-full rounded-lg px-3 py-1.5 text-xs font-medium transition-colors',
              store.selectedDepartamento?.id === dep.id
                ? 'bg-brand-500 text-white hover:bg-brand-600'
                : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600',
            ]"
          >
            <span class="flex items-center justify-center gap-1.5">
              <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Ver grupos de recursos
            </span>
          </button>
        </div>
        </template>
      </div>

      <!-- Panel derecho: grupos de recursos -->
      <div class="flex-1">
        <!-- Placeholder -->
        <div
          v-if="!store.selectedDepartamento"
          class="flex h-full min-h-64 flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700"
        >
          <svg class="h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5" />
          </svg>
          <p class="mt-3 text-sm font-medium text-gray-400 dark:text-gray-500">Selecciona un departamento</p>
          <p class="mt-1 text-xs text-gray-400 dark:text-gray-600">para ver sus grupos de recursos</p>
        </div>

        <!-- Panel con grupos -->
        <div v-else>
          <div class="mb-4 flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Grupos de Recursos
              </h2>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Departamento: <span class="font-medium text-brand-500">{{ store.selectedDepartamento.nombre }}</span>
              </p>
            </div>
            <button
              @click="store.clearSelection()"
              class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 transition-colors"
              title="Cerrar panel"
            >
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Loading grupos -->
          <div v-if="loadingGrupos" class="space-y-3">
            <div v-for="n in 3" :key="n" class="h-16 animate-pulse rounded-xl bg-gray-100 dark:bg-gray-800" />
          </div>

          <!-- Sin grupos -->
          <div
            v-else-if="store.gruposRecursos.length === 0"
            class="rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-10 text-center"
          >
            <p class="text-sm text-gray-500 dark:text-gray-400">Este departamento no tiene grupos de recursos aún.</p>
          </div>

          <!-- Tabla de grupos -->
          <div v-else class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                  <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Nombre</th>
                  <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Capacidad (min)</th>
                  <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Tasa $/min</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                <tr
                  v-for="grupo in store.gruposRecursos"
                  :key="grupo.id"
                  class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
                >
                  <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ grupo.nombre }}</td>
                  <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-300">
                    {{ grupo.capacidad_practica_minutos.toLocaleString() }}
                  </td>
                  <td class="px-4 py-3 text-right">
                    <span class="rounded-full bg-green-50 dark:bg-green-500/10 px-2.5 py-0.5 text-xs font-semibold text-green-700 dark:text-green-400">
                      ${{ grupo.tasa_costo_por_minuto.toFixed(4) }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- ─── Modal crear / editar ─── -->
    <Transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="showModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
      >
        <!-- Overlay -->
        <div
          class="absolute inset-0 bg-black/50 backdrop-blur-sm"
          @click="closeModal"
        />

        <!-- Panel del modal -->
        <Transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 scale-95"
          enter-to-class="opacity-100 scale-100"
          leave-active-class="transition ease-in duration-150"
          leave-from-class="opacity-100 scale-100"
          leave-to-class="opacity-0 scale-95"
        >
          <div
            v-if="showModal"
            class="relative z-10 w-full max-w-lg rounded-2xl bg-white dark:bg-gray-900 shadow-2xl"
          >
            <!-- Header modal -->
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-5">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ editingId ? 'Editar Departamento' : 'Nuevo Departamento' }}
              </h2>
              <button
                @click="closeModal"
                class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-600 transition-colors"
              >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Cuerpo modal -->
            <div class="px-6 py-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                  Nombre del departamento
                </label>
                <input
                  v-model="form.nombre"
                  type="text"
                  placeholder="Ej: Producción, Ventas, Administración..."
                  :class="[
                    'w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-800 outline-none transition-colors',
                    fieldError
                      ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-500/20'
                      : 'border-gray-300 dark:border-gray-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20',
                  ]"
                  @keydown.enter="handleSubmit"
                />
                <p v-if="fieldError" class="mt-1.5 text-xs text-red-500">{{ fieldError }}</p>
              </div>
            </div>

            <!-- Footer modal -->
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 px-6 py-4">
              <button
                @click="closeModal"
                class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
              >
                Cancelar
              </button>
              <button
                @click="handleSubmit"
                :disabled="apiState.loading.value"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed transition-colors"
              >
                <svg
                  v-if="apiState.loading.value"
                  class="h-4 w-4 animate-spin"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                </svg>
                {{ apiState.loading.value ? 'Guardando...' : (editingId ? 'Guardar cambios' : 'Crear departamento') }}
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import Swal from 'sweetalert2'
import { useDepartamentosStore } from '../store/departamentosStore'
import { useApi } from '@/composables/useApi'
import { getFieldError } from '@/app/api/types'
import type { Departamento } from '../types'

const store = useDepartamentosStore()
const apiState = useApi()

const loadingList = ref(false)
const loadingGrupos = ref(false)
const showModal = ref(false)
const editingId = ref<number | null>(null)
const form = ref({ nombre: '' })

const fieldError = computed(() => getFieldError(apiState.validationErrors.value, 'nombre'))

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
