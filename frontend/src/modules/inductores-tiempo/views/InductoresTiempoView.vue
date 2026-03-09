<template>
  <div class="p-6">
    <!-- ─── Header ──────────────────────────────────────────────────────────── -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Inductores de Tiempo</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Catálogo de inductores de costo por tiempo usados en las actividades.
        </p>
      </div>
      <button
        @click="openCreateModal"
        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition-colors"
      >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nuevo Inductor
      </button>
    </div>

    <!-- ─── Filtro búsqueda ────────────────────────────────────────────────── -->
    <div class="mb-4 flex items-center gap-3">
      <div class="relative max-w-xs flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input
          v-model="search"
          type="text"
          placeholder="Buscar inductor..."
          class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 pl-9 pr-4 py-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
        />
      </div>
      <select
        v-model="filterTipo"
        class="rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 outline-none focus:border-brand-500"
      >
        <option value="">Todos los tipos</option>
        <option value="MANUAL">Manual</option>
        <option value="POR_CANTIDAD">Por cantidad</option>
        <option value="POR_LOTE">Por lote</option>
      </select>
    </div>

    <!-- ─── Tabla ───────────────────────────────────────────────────────────── -->
    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Nombre</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Tipo de cálculo</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Descripción</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
          <!-- Skeletons -->
          <template v-if="loadingList">
            <tr v-for="n in 4" :key="n" class="bg-white dark:bg-gray-800">
              <td class="px-4 py-3"><div class="h-4 w-36 animate-pulse rounded bg-gray-100 dark:bg-gray-700" /></td>
              <td class="px-4 py-3"><div class="h-5 w-24 animate-pulse rounded-full bg-gray-100 dark:bg-gray-700" /></td>
              <td class="px-4 py-3"><div class="h-4 w-48 animate-pulse rounded bg-gray-100 dark:bg-gray-700" /></td>
              <td class="px-4 py-3"><div class="mx-auto h-4 w-16 animate-pulse rounded bg-gray-100 dark:bg-gray-700" /></td>
            </tr>
          </template>

          <!-- Estado vacío -->
          <tr v-else-if="inductoresFiltrados.length === 0">
            <td colspan="4" class="px-4 py-16 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
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
            class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors"
          >
            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ inductor.nombre }}</td>
            <td class="px-4 py-3">
              <span :class="tipoBadgeClass(inductor.tipo_calculo)">{{ tipoLabel(inductor.tipo_calculo) }}</span>
            </td>
            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
              {{ inductor.descripcion ?? '—' }}
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center justify-center gap-1">
                <button
                  @click="openEditModal(inductor)"
                  class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"
                  title="Editar"
                >
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button
                  @click="handleDelete(inductor)"
                  class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition-colors"
                  title="Eliminar"
                >
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- ─── Modal crear / editar ──────────────────────────────────────────────── -->
    <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal" />
        <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
          <div v-if="showModal" class="relative z-10 w-full max-w-lg rounded-2xl bg-white dark:bg-gray-900 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-5">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ editingId ? 'Editar Inductor' : 'Nuevo Inductor de Tiempo' }}
              </h2>
              <button @click="closeModal" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-600 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
              </button>
            </div>
            <div class="space-y-4 px-6 py-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nombre</label>
                <input
                  v-model="form.nombre"
                  type="text"
                  placeholder="Ej: Pedidos, Horas máquina, Corridas de producción..."
                  :class="inputClass('nombre')"
                />
                <p v-if="getFieldError(apiState.validationErrors.value, 'nombre')" class="mt-1.5 text-xs text-red-500">
                  {{ getFieldError(apiState.validationErrors.value, 'nombre') }}
                </p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tipo de cálculo</label>
                <select v-model="form.tipo_calculo" :class="inputClass('tipo_calculo')">
                  <option value="" disabled>Selecciona un tipo...</option>
                  <option value="MANUAL">Manual</option>
                  <option value="POR_CANTIDAD">Por cantidad</option>
                  <option value="POR_LOTE">Por lote</option>
                </select>
                <p v-if="getFieldError(apiState.validationErrors.value, 'tipo_calculo')" class="mt-1.5 text-xs text-red-500">
                  {{ getFieldError(apiState.validationErrors.value, 'tipo_calculo') }}
                </p>
                <!-- Nota explicativa -->
                <p v-if="form.tipo_calculo === 'POR_LOTE'" class="mt-1.5 text-xs text-amber-600 dark:text-amber-400">
                  Al asignar este inductor a una actividad será necesario especificar el tamaño de lote.
                </p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                  Descripción <span class="text-gray-400 font-normal">(opcional)</span>
                </label>
                <input
                  v-model="form.descripcion"
                  type="text"
                  placeholder="Ej: Por pedido procesado en el período..."
                  :class="inputClass('descripcion')"
                />
              </div>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 px-6 py-4">
              <button @click="closeModal" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                Cancelar
              </button>
              <button
                @click="handleSubmit"
                :disabled="apiState.loading.value"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed transition-colors"
              >
                <svg v-if="apiState.loading.value" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                </svg>
                {{ apiState.loading.value ? 'Guardando...' : (editingId ? 'Guardar cambios' : 'Crear inductor') }}
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
import { useInductoresTiempoStore } from '../store/inductoresTiempoStore'
import { useApi } from '@/composables/useApi'
import { getFieldError } from '@/app/api/types'
import type { InductorTiempo, TipoCalculo } from '../types'

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

function tipoBadgeClass(tipo: TipoCalculo) {
  const base = 'rounded-full px-2.5 py-0.5 text-xs font-medium'
  const map: Record<TipoCalculo, string> = {
    MANUAL: `${base} bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300`,
    POR_CANTIDAD: `${base} bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400`,
    POR_LOTE: `${base} bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400`,
  }
  return map[tipo]
}

function inputClass(field: string) {
  const hasError = !!getFieldError(apiState.validationErrors.value, field)
  return [
    'w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-800 outline-none transition-colors',
    hasError
      ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-500/20'
      : 'border-gray-300 dark:border-gray-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20',
  ]
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
