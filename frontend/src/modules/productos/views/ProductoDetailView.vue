<template>
  <div class="p-6">
    <!-- Breadcrumb -->
    <div class="mb-5 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
      <router-link to="/productos" class="hover:text-brand-500 transition-colors">Productos</router-link>
      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
      <span class="font-medium text-gray-700 dark:text-gray-200">{{ producto?.nombre ?? '...' }}</span>
    </div>

    <!-- Loading inicial -->
    <div v-if="loadingInit" class="space-y-4">
      <div class="h-32 animate-pulse rounded-xl bg-gray-100 dark:bg-gray-800" />
      <div class="h-48 animate-pulse rounded-xl bg-gray-100 dark:bg-gray-800" />
    </div>

    <template v-else-if="producto">
      <!-- ─── Card del Producto ─── -->
      <div class="mb-6 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
        <div class="flex items-start justify-between gap-4">
          <div>
            <div class="flex items-center gap-3">
              <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ producto.nombre }}</h1>
              <span
                v-if="categoriaLabel"
                class="rounded-full bg-blue-50 dark:bg-blue-500/10 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-400"
              >
                {{ categoriaLabel }}
              </span>
            </div>
            <p v-if="producto.descripcion" class="mt-1 text-sm text-gray-500 dark:text-gray-400">
              {{ producto.descripcion }}
            </p>
            <div class="mt-3 flex items-center gap-4">
              <div>
                <p class="text-xs text-gray-400 dark:text-gray-500">Costo material directo</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">${{ producto.costo_material_directo.toFixed(2) }}</p>
              </div>
            </div>
          </div>
          <button
            @click="openEditModal"
            class="shrink-0 rounded-lg border border-gray-200 dark:border-gray-700 p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 transition-colors"
            title="Editar producto"
          >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </button>
        </div>
      </div>

      <!-- ─── Actividades del Producto ─── -->
      <div class="mb-6 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-4">
          <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">Actividades Asignadas</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Actividades que consume este producto en el proceso TDABC.</p>
          </div>
          <button
            @click="openAddActividadModal"
            class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-600 transition-colors"
          >
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Agregar actividad
          </button>
        </div>

        <!-- Sin actividades -->
        <div
          v-if="store.actividades.length === 0"
          class="px-6 py-10 text-center"
        >
          <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <p class="mt-2 text-sm text-gray-400 dark:text-gray-500">No hay actividades asignadas aún.</p>
        </div>

        <!-- Lista de actividades -->
        <div v-else class="divide-y divide-gray-100 dark:divide-gray-800">
          <div
            v-for="pa in store.actividades"
            :key="pa.id"
            class="px-6 py-4"
          >
            <!-- Cabecera actividad -->
            <div class="flex items-center justify-between gap-3">
              <div class="flex items-center gap-3">
                <button
                  @click="toggleActividadExpanded(pa.id)"
                  class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                >
                  <svg
                    class="h-4 w-4 transition-transform duration-200"
                    :class="expandedActividades.has(pa.id) ? 'rotate-90' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">
                    {{ actividadMap[pa.actividad_id]?.nombre ?? `Actividad #${pa.actividad_id}` }}
                  </p>
                  <p class="text-xs text-gray-400 dark:text-gray-500">
                    Tiempo base: {{ actividadMap[pa.actividad_id]?.tiempo_base_minutos ?? '—' }} min
                  </p>
                </div>
              </div>
              <button
                @click="handleRemoveActividad(pa)"
                class="shrink-0 rounded-lg p-1.5 text-gray-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition-colors"
                title="Quitar actividad"
              >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Panel de inductores expandido -->
            <Transition
              @enter="startTransition"
              @after-enter="endTransition"
              @before-leave="startTransition"
              @after-leave="endTransition"
            >
              <div v-show="expandedActividades.has(pa.id)" class="overflow-hidden">
                <div class="mt-4 ml-9 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-4">
                  <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                    Inductores de esta actividad para el producto
                  </p>

                  <!-- Loading -->
                  <div v-if="loadingInductores.has(pa.id)" class="space-y-2">
                    <div v-for="n in 2" :key="n" class="h-16 animate-pulse rounded-lg bg-gray-100 dark:bg-gray-800" />
                  </div>

                  <!-- Sin inductores en la actividad -->
                  <div
                    v-else-if="(inductoresPorActividad.get(pa.actividad_id) ?? []).length === 0"
                    class="text-center py-4"
                  >
                    <p class="text-xs text-gray-400 dark:text-gray-500">Esta actividad no tiene inductores de tiempo asignados.</p>
                  </div>

                  <!-- Fila por inductor -->
                  <div v-else class="space-y-2">
                    <div
                      v-for="actInd in inductoresPorActividad.get(pa.actividad_id) ?? []"
                      :key="actInd.inductor_tiempo_id"
                      class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-3"
                    >
                      <!-- Cabecera del inductor -->
                      <div class="mb-2 flex items-center gap-2">
                        <span class="font-medium text-sm text-gray-900 dark:text-white">{{ actInd.inductor_nombre }}</span>
                        <span :class="tipoBadgeClass(actInd.tipo_calculo)">{{ tipoLabel(actInd.tipo_calculo) }}</span>
                        <span
                          v-if="isInductorConfigured(pa.id, actInd.inductor_tiempo_id)"
                          class="ml-auto flex items-center gap-1 text-xs text-green-500"
                        >
                          <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                          </svg>
                          Guardado
                        </span>
                      </div>

                      <!-- Campos de configuración -->
                      <div class="flex flex-wrap items-end gap-2">
                        <!-- β minutos -->
                        <div class="min-w-22.5">
                          <label class="mb-1 block text-xs text-gray-400">β min</label>
                          <input
                            v-model.number="getRowForm(pa.id, actInd.inductor_tiempo_id).beta_minutos"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-2.5 py-1.5 text-xs text-gray-900 dark:text-white outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/20"
                          />
                        </div>
                        <!-- Valor X (solo MANUAL) -->
                        <div v-if="actInd.tipo_calculo === 'MANUAL'" class="min-w-22.5">
                          <label class="mb-1 block text-xs text-gray-400">Valor X</label>
                          <input
                            v-model.number="getRowForm(pa.id, actInd.inductor_tiempo_id).valor_x"
                            type="number"
                            step="0.01"
                            placeholder="0.00"
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-2.5 py-1.5 text-xs text-gray-900 dark:text-white outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/20"
                          />
                        </div>
                        <!-- Tamaño de lote (solo POR_LOTE) -->
                        <div v-if="actInd.tipo_calculo === 'POR_LOTE'" class="min-w-22.5">
                          <label class="mb-1 block text-xs text-gray-400">Tam. lote</label>
                          <input
                            v-model.number="getRowForm(pa.id, actInd.inductor_tiempo_id).tamano_lote"
                            type="number"
                            step="1"
                            min="1"
                            placeholder="100"
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-2.5 py-1.5 text-xs text-gray-900 dark:text-white outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/20"
                          />
                        </div>
                        <!-- Botón guardar/actualizar -->
                        <button
                          @click="saveInductorRow(pa.id, actInd)"
                          :disabled="getRowForm(pa.id, actInd.inductor_tiempo_id).saving"
                          class="inline-flex items-center gap-1 rounded-md bg-brand-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed transition-colors"
                        >
                          <svg v-if="getRowForm(pa.id, actInd.inductor_tiempo_id).saving" class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                          </svg>
                          {{ isInductorConfigured(pa.id, actInd.inductor_tiempo_id) ? 'Actualizar' : 'Guardar' }}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </Transition>
          </div>
        </div>
      </div>

      <!-- ─── Calcular Costo TDABC ─── -->
      <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        <div class="border-b border-gray-100 dark:border-gray-800 px-6 py-4">
          <h2 class="text-base font-semibold text-gray-900 dark:text-white">Calcular Costo TDABC</h2>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Ingresa la cantidad de pedido para calcular el costo unitario y total.</p>
        </div>
        <div class="px-6 py-5">
          <div class="flex items-end gap-3 max-w-sm">
            <div class="flex-1">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Cantidad de pedido</label>
              <input
                v-model.number="cantidadPedido"
                type="number"
                min="1"
                step="1"
                placeholder="Ej: 50"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
              />
            </div>
            <button
              @click="handleCalcularCosto"
              :disabled="calculandoCosto || !cantidadPedido"
              class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed transition-colors"
            >
              <svg v-if="calculandoCosto" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
              </svg>
              <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
              {{ calculandoCosto ? 'Calculando...' : 'Calcular' }}
            </button>
          </div>

          <!-- Resultado del costo -->
          <div v-if="store.costoResult" class="mt-6">
            <!-- Resumen -->
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 mb-5">
              <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-4 text-center">
                <p class="text-xs text-gray-400 dark:text-gray-500">Material Directo</p>
                <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">${{ store.costoResult.costo_material_directo.toFixed(2) }}</p>
              </div>
              <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-4 text-center">
                <p class="text-xs text-gray-400 dark:text-gray-500">Costo Indirecto</p>
                <p class="mt-1 text-xl font-bold text-gray-900 dark:text-white">${{ store.costoResult.costo_indirecto.toFixed(2) }}</p>
              </div>
              <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-brand-50 dark:bg-brand-500/10 border-brand-200 dark:border-brand-500/30 p-4 text-center">
                <p class="text-xs text-brand-500">Costo Unitario</p>
                <p class="mt-1 text-xl font-bold text-brand-600 dark:text-brand-400">${{ store.costoResult.costo_unitario.toFixed(2) }}</p>
              </div>
              <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-green-50 dark:bg-green-500/10 border-green-200 dark:border-green-500/30 p-4 text-center">
                <p class="text-xs text-green-600 dark:text-green-400">Costo Total (×{{ store.costoResult.cantidad_pedido }})</p>
                <p class="mt-1 text-xl font-bold text-green-700 dark:text-green-400">${{ store.costoResult.costo_total.toFixed(2) }}</p>
              </div>
            </div>

            <!-- Detalle por actividad -->
            <div v-if="store.costoResult.detalle_actividades.length > 0">
              <h3 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Desglose por actividad</h3>
              <div class="space-y-3">
                <div
                  v-for="act in store.costoResult.detalle_actividades"
                  :key="act.actividad_id"
                  class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden"
                >
                  <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-900 px-4 py-3">
                    <div>
                      <p class="font-medium text-gray-900 dark:text-white text-sm">{{ act.nombre }}</p>
                      <p class="text-xs text-gray-400 dark:text-gray-500">
                        Tiempo total: {{ act.tiempo_total_minutos.toFixed(2) }} min · Tasa: ${{ act.tasa_costo_por_minuto.toFixed(4) }}/min
                      </p>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white text-sm">${{ act.costo_actividad.toFixed(2) }}</span>
                  </div>
                  <div v-if="act.inductores.length > 0" class="px-4 py-3">
                    <table class="w-full text-xs">
                      <thead>
                        <tr class="text-gray-400 dark:text-gray-500">
                          <th class="pb-1.5 text-left font-medium">Inductor</th>
                          <th class="pb-1.5 text-center font-medium">Tipo</th>
                          <th class="pb-1.5 text-right font-medium">β</th>
                          <th class="pb-1.5 text-right font-medium">X</th>
                          <th class="pb-1.5 text-right font-medium">Minutos</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        <tr v-for="ind in act.inductores" :key="ind.nombre">
                          <td class="py-1 text-gray-700 dark:text-gray-300">{{ ind.nombre }}</td>
                          <td class="py-1 text-center">
                            <span :class="tipoBadgeClass(ind.tipo_calculo)">{{ tipoLabel(ind.tipo_calculo) }}</span>
                          </td>
                          <td class="py-1 text-right text-gray-600 dark:text-gray-400">{{ ind.beta_minutos }}</td>
                          <td class="py-1 text-right text-gray-600 dark:text-gray-400">{{ ind.valor_x ?? '—' }}</td>
                          <td class="py-1 text-right font-medium text-gray-700 dark:text-gray-300">{{ ind.minutos_aportados.toFixed(2) }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>

    <!-- ─── Modal Editar Producto ─── -->
    <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showEditModal = false" />
        <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
          <div v-if="showEditModal" class="relative z-10 w-full max-w-lg rounded-2xl bg-white dark:bg-gray-900 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-5">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Editar Producto</h2>
              <button @click="showEditModal = false" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-600 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
              </button>
            </div>
            <div class="space-y-4 px-6 py-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nombre</label>
                <input v-model="editForm.nombre" type="text" :class="modalInputClass('nombre')" />
                <p v-if="getFieldError(editApiState.validationErrors.value, 'nombre')" class="mt-1.5 text-xs text-red-500">
                  {{ getFieldError(editApiState.validationErrors.value, 'nombre') }}
                </p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Descripción <span class="font-normal text-gray-400">(opcional)</span></label>
                <input v-model="editForm.descripcion" type="text" :class="modalInputClass('descripcion')" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Costo Material Directo ($)</label>
                <input v-model.number="editForm.costo_material_directo" type="number" step="0.01" min="0" :class="modalInputClass('costo_material_directo')" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Categoría <span class="font-normal text-gray-400">(opcional)</span></label>
                <select v-model="editForm.categoria_id" :class="modalInputClass('categoria_id')">
                  <option :value="null">Sin categoría</option>
                  <option v-for="cat in categoriasStore.categorias" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
                </select>
              </div>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 px-6 py-4">
              <button @click="showEditModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Cancelar</button>
              <button @click="handleEditSubmit" :disabled="editApiState.loading.value" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed transition-colors">
                <svg v-if="editApiState.loading.value" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" /><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" /></svg>
                {{ editApiState.loading.value ? 'Guardando...' : 'Guardar cambios' }}
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

    <!-- ─── Modal Agregar Actividad ─── -->
    <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-if="showAddActividadModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showAddActividadModal = false" />
        <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
          <div v-if="showAddActividadModal" class="relative z-10 w-full max-w-md rounded-2xl bg-white dark:bg-gray-900 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-5">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Agregar Actividad</h2>
              <button @click="showAddActividadModal = false" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-600 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
              </button>
            </div>
            <div class="px-6 py-6">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Selecciona una actividad</label>
              <select v-model="selectedActividadId" class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm text-gray-900 dark:text-white outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
                <option :value="null" disabled>-- Elige una actividad --</option>
                <option
                  v-for="act in actividadesDisponibles"
                  :key="act.id"
                  :value="act.id"
                >
                  {{ act.nombre }} ({{ act.tiempo_base_minutos }} min)
                </option>
              </select>
              <p v-if="actividadesDisponibles.length === 0" class="mt-2 text-xs text-gray-400">
                Todas las actividades ya están asignadas.
              </p>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 px-6 py-4">
              <button @click="showAddActividadModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Cancelar</button>
              <button
                @click="handleAddActividad"
                :disabled="!selectedActividadId || addingActividad"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed transition-colors"
              >
                <svg v-if="addingActividad" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" /><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" /></svg>
                {{ addingActividad ? 'Asignando...' : 'Asignar actividad' }}
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import Swal from 'sweetalert2'
import { useProductosStore } from '../store/productosStore'
import { useCategoriasProductoStore } from '@/modules/categorias-producto/store/categoriaProductoStore'
import { useApi } from '@/composables/useApi'
import { getFieldError } from '@/app/api/types'
import actividadesService from '@/modules/actividades/api/actividadesService'
import type { Actividad, ActividadInductor } from '@/modules/actividades/types'
import type { ProductoActividad } from '../types'

const route = useRoute()
const productoId = Number(route.params.id)

const store = useProductosStore()
const categoriasStore = useCategoriasProductoStore()
const editApiState = useApi()

// ── Estado inicial ────────────────────────────────────────────────────────────
const loadingInit = ref(true)
const producto = computed(() => store.selectedProducto)

// Mapa de actividades del sistema (para mostrar nombres)
const todasActividades = ref<Actividad[]>([])
const actividadMap = computed(() => {
  const m: Record<number, Actividad> = {}
  for (const a of todasActividades.value) m[a.id] = a
  return m
})

// Mapa de inductores del sistema (cargados por actividad)
const inductoresPorActividad = ref<Map<number, ActividadInductor[]>>(new Map())
const inductorMap = computed(() => {
  const m: Record<number, ActividadInductor> = {}
  for (const [, inductores] of inductoresPorActividad.value) {
    for (const ind of inductores) m[ind.inductor_tiempo_id] = ind
  }
  return m
})

// Categoría del producto
const categoriaLabel = computed(() => {
  if (!producto.value?.categoria_id) return null
  return categoriasStore.categorias.find((c) => c.id === producto.value!.categoria_id)?.nombre ?? null
})

onMounted(async () => {
  loadingInit.value = true
  await Promise.all([
    store.fetchById(productoId),
    store.fetchActividades(productoId),
    categoriasStore.fetchAll(),
    loadTodasActividades(),
  ])
  // Cargar inductores de cada actividad asignada
  await Promise.all(store.actividades.map((pa) => loadInductoresDeActividad(pa)))
  loadingInit.value = false
})

onUnmounted(() => {
  store.clearDetalle()
})

async function loadTodasActividades() {
  const response = await actividadesService.getAll()
  todasActividades.value = response.data
}

async function loadInductoresDeActividad(pa: ProductoActividad) {
  try {
    const [inductoresAct] = await Promise.all([
      actividadesService.getInductores(pa.actividad_id),
      store.fetchValoresInductor(pa.id),
    ])
    inductoresPorActividad.value.set(pa.actividad_id, inductoresAct.data)
    initRowForms(pa)
  } catch {
    // silenciar
  }
}

// ── Actividades expandidas ───────────────────────────────────────────────────
const expandedActividades = ref<Set<number>>(new Set())
const loadingInductores = ref<Set<number>>(new Set())

function toggleActividadExpanded(paId: number) {
  if (expandedActividades.value.has(paId)) {
    expandedActividades.value.delete(paId)
    expandedActividades.value = new Set(expandedActividades.value)
  } else {
    expandedActividades.value.add(paId)
    expandedActividades.value = new Set(expandedActividades.value)
  }
}

// ── Editar Producto ──────────────────────────────────────────────────────────
const showEditModal = ref(false)
const editForm = ref({ nombre: '', descripcion: '', costo_material_directo: 0, categoria_id: null as number | null })

function openEditModal() {
  if (!producto.value) return
  editForm.value = {
    nombre: producto.value.nombre,
    descripcion: producto.value.descripcion ?? '',
    costo_material_directo: producto.value.costo_material_directo,
    categoria_id: producto.value.categoria_id,
  }
  showEditModal.value = true
}

function modalInputClass(field: string) {
  const hasError = !!getFieldError(editApiState.validationErrors.value, field)
  return [
    'w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-800 outline-none transition-colors',
    hasError
      ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-500/20'
      : 'border-gray-300 dark:border-gray-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20',
  ]
}

async function handleEditSubmit() {
  const dto = {
    nombre: editForm.value.nombre,
    descripcion: editForm.value.descripcion || undefined,
    costo_material_directo: editForm.value.costo_material_directo,
    categoria_id: editForm.value.categoria_id ?? undefined,
  }
  const result = await editApiState.execute(() => store.update(productoId, dto))
  if (result !== null) showEditModal.value = false
}

// ── Agregar Actividad ────────────────────────────────────────────────────────
const showAddActividadModal = ref(false)
const selectedActividadId = ref<number | null>(null)
const addingActividad = ref(false)

const actividadesDisponibles = computed(() => {
  const asignadasIds = new Set(store.actividades.map((a) => a.actividad_id))
  return todasActividades.value.filter((a) => !asignadasIds.has(a.id))
})

function openAddActividadModal() {
  selectedActividadId.value = null
  showAddActividadModal.value = true
}

async function handleAddActividad() {
  if (!selectedActividadId.value) return
  addingActividad.value = true
  try {
    const pa = await store.addActividad(productoId, selectedActividadId.value)
    // Cargar inductores de la nueva actividad
    await loadInductoresDeActividad(pa)
    showAddActividadModal.value = false
  } finally {
    addingActividad.value = false
  }
}

async function handleRemoveActividad(pa: ProductoActividad) {
  const nombre = actividadMap.value[pa.actividad_id]?.nombre ?? `Actividad #${pa.actividad_id}`
  const { isConfirmed } = await Swal.fire({
    title: '¿Quitar actividad?',
    text: `Se quitará "${nombre}" de este producto.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, quitar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#6b7280',
    reverseButtons: true,
  })
  if (!isConfirmed) return
  await store.removeActividad(productoId, pa.actividad_id)
  expandedActividades.value.delete(pa.id)
  expandedActividades.value = new Set(expandedActividades.value)
}

// ── Inductores inline (filas por inductor de la actividad) ───────────────────
interface RowForm {
  beta_minutos: number
  valor_x: number | null
  tamano_lote: number | null
  saving: boolean
}

const rowForms = ref<Map<string, RowForm>>(new Map())

function rowKey(paId: number, inductorTiempoId: number) {
  return `${paId}-${inductorTiempoId}`
}

function getRowForm(paId: number, inductorTiempoId: number): RowForm {
  const key = rowKey(paId, inductorTiempoId)
  if (!rowForms.value.has(key)) {
    rowForms.value.set(key, { beta_minutos: 0, valor_x: null, tamano_lote: null, saving: false })
  }
  return rowForms.value.get(key)!
}

function initRowForms(pa: ProductoActividad) {
  const configured = store.valoresInductor.get(pa.id) ?? []
  for (const val of configured) {
    const key = rowKey(pa.id, val.inductor_tiempo_id)
    rowForms.value.set(key, {
      beta_minutos: val.beta_minutos,
      valor_x: val.valor_x,
      tamano_lote: val.tamano_lote,
      saving: false,
    })
  }
  rowForms.value = new Map(rowForms.value)
}

function isInductorConfigured(paId: number, inductorTiempoId: number) {
  return (store.valoresInductor.get(paId) ?? []).some((v) => v.inductor_tiempo_id === inductorTiempoId)
}

function getConfiguredVal(paId: number, inductorTiempoId: number) {
  return (store.valoresInductor.get(paId) ?? []).find((v) => v.inductor_tiempo_id === inductorTiempoId) ?? null
}

async function saveInductorRow(paId: number, actInd: ActividadInductor) {
  const form = getRowForm(paId, actInd.inductor_tiempo_id)
  form.saving = true
  try {
    const configured = getConfiguredVal(paId, actInd.inductor_tiempo_id)
    const dto = {
      beta_minutos: form.beta_minutos,
      valor_x: actInd.tipo_calculo === 'MANUAL' ? form.valor_x ?? undefined : undefined,
      tamano_lote: actInd.tipo_calculo === 'POR_LOTE' ? form.tamano_lote ?? undefined : undefined,
    }
    if (configured) {
      await store.updateValorInductor(paId, configured.id, dto)
    } else {
      await store.createValorInductor(paId, { inductor_tiempo_id: actInd.inductor_tiempo_id, ...dto })
    }
  } finally {
    form.saving = false
  }
}

// ── Calcular Costo ────────────────────────────────────────────────────────────
const cantidadPedido = ref<number>(1)
const calculandoCosto = ref(false)

async function handleCalcularCosto() {
  if (!cantidadPedido.value) return
  calculandoCosto.value = true
  try {
    await store.calcularCosto(productoId, cantidadPedido.value)
  } finally {
    calculandoCosto.value = false
  }
}

// ── Helpers ──────────────────────────────────────────────────────────────────
function tipoLabel(tipo: string | undefined) {
  if (!tipo) return '—'
  const map: Record<string, string> = {
    MANUAL: 'Manual',
    POR_CANTIDAD: 'Por cantidad',
    POR_LOTE: 'Por lote',
  }
  return map[tipo] ?? tipo
}

function tipoBadgeClass(tipo: string | undefined) {
  const base = 'rounded-full px-2 py-0.5 text-xs font-medium'
  if (!tipo) return base
  const map: Record<string, string> = {
    MANUAL: `${base} bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300`,
    POR_CANTIDAD: `${base} bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400`,
    POR_LOTE: `${base} bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400`,
  }
  return map[tipo] ?? base
}

// Animación altura subitems
const startTransition = (el: Element) => {
  const htmlEl = el as HTMLElement
  htmlEl.style.height = 'auto'
  const h = htmlEl.scrollHeight
  htmlEl.style.height = '0px'
  void htmlEl.offsetHeight
  htmlEl.style.height = h + 'px'
}
const endTransition = (el: Element) => {
  ;(el as HTMLElement).style.height = ''
}
</script>
