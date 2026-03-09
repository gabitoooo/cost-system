<template>
  <div class="p-6">
    <!-- ─── Header ──────────────────────────────────────────────────────────── -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Centros de Costo</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Grupos de recursos con su capacidad práctica y tasa de costo por minuto.
        </p>
      </div>
      <button
        @click="openCreateGrupoModal"
        :disabled="!selectedDeptId"
        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
      >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nuevo Grupo
      </button>
    </div>

    <!-- ─── Filtro departamento ─────────────────────────────────────────────── -->
    <div class="mb-5 flex items-center gap-3">
      <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
      </svg>
      <select
        v-model="selectedDeptId"
        class="rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 outline-none focus:border-brand-500 min-w-52"
      >
        <option :value="null">Selecciona un departamento...</option>
        <option v-for="dept in deptStore.departamentos" :key="dept.id" :value="dept.id">
          {{ dept.nombre }}
        </option>
      </select>
      <span v-if="loadingDepts" class="text-xs text-gray-400">Cargando...</span>
    </div>

    <!-- ─── Layout principal ────────────────────────────────────────────────── -->
    <div class="flex gap-6">

      <!-- ── Panel izquierdo: lista de grupos ─────────────────────────────── -->
      <div class="w-80 shrink-0 space-y-3">
        <div v-if="!selectedDeptId" class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-10 text-center">
          <svg class="h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          <p class="mt-3 text-sm text-gray-400 dark:text-gray-500">Selecciona un departamento para ver sus grupos.</p>
        </div>

        <template v-else-if="loadingGrupos">
          <div v-for="n in 4" :key="n" class="h-28 animate-pulse rounded-xl bg-gray-100 dark:bg-gray-800" />
        </template>

        <div v-else-if="deptStore.gruposRecursos.length === 0" class="rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-8 text-center">
          <p class="text-sm text-gray-500 dark:text-gray-400">Este departamento no tiene grupos aún.</p>
        </div>

        <template v-else>
          <div
            v-for="grupo in deptStore.gruposRecursos"
            :key="grupo.id"
            :class="[
              'rounded-xl border p-4 transition-all',
              grupoStore.selectedGrupo?.id === grupo.id
                ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10 shadow-sm ring-1 ring-brand-500'
                : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-brand-300 hover:shadow-sm',
            ]"
          >
            <div class="flex items-start justify-between gap-2">
              <div class="min-w-0 flex-1">
                <h3 class="truncate font-semibold text-gray-900 dark:text-white">{{ grupo.nombre }}</h3>
                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                  Cap: {{ grupo.capacidad_practica_minutos.toLocaleString() }} min
                </p>
              </div>
              <div class="flex shrink-0 items-center gap-1">
                <button @click.stop="openEditGrupoModal(grupo)" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 dark:hover:text-gray-200 transition-colors" title="Editar">
                  <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </button>
                <button @click.stop="handleDeleteGrupo(grupo)" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition-colors" title="Eliminar">
                  <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
              </div>
            </div>
            <div class="mt-2 flex items-center justify-between">
              <span class="text-xs text-gray-500 dark:text-gray-400">Tasa:</span>
              <span class="rounded-full bg-green-50 dark:bg-green-500/10 px-2 py-0.5 text-xs font-semibold text-green-700 dark:text-green-400">
                ${{ (grupo.tasa_costo_por_minuto ?? 0).toFixed(4) }}/min
              </span>
            </div>
            <button
              @click="handleSelectGrupo(grupo)"
              :class="[
                'mt-3 w-full rounded-lg px-3 py-1.5 text-xs font-medium transition-colors',
                grupoStore.selectedGrupo?.id === grupo.id
                  ? 'bg-brand-500 text-white hover:bg-brand-600'
                  : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600',
              ]"
            >
              <span class="flex items-center justify-center gap-1.5">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                Ver detalle
              </span>
            </button>
          </div>
        </template>
      </div>

      <!-- ── Panel derecho: detalle del grupo con tabs ─────────────────────── -->
      <div class="flex-1 min-w-0">
        <!-- Placeholder -->
        <div v-if="!grupoStore.selectedGrupo" class="flex h-full min-h-64 flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700">
          <svg class="h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5" />
          </svg>
          <p class="mt-3 text-sm font-medium text-gray-400 dark:text-gray-500">Selecciona un grupo</p>
          <p class="mt-1 text-xs text-gray-400 dark:text-gray-600">para gestionar sus recursos y actividades</p>
        </div>

        <div v-else>
          <!-- Header grupo -->
          <div class="mb-4 flex items-start justify-between gap-4">
            <div>
              <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ grupoStore.selectedGrupo.nombre }}</h2>
              <div class="mt-1 flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                <span>Capacidad: <strong class="text-gray-700 dark:text-gray-200">{{ grupoStore.selectedGrupo.capacidad_practica_minutos.toLocaleString() }} min</strong></span>
                <span>Tasa: <strong class="text-green-600 dark:text-green-400">${{ (grupoStore.selectedGrupo.tasa_costo_por_minuto ?? 0).toFixed(4) }}/min</strong></span>
              </div>
            </div>
            <button @click="handleClosePanel" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 transition-colors" title="Cerrar panel">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
          </div>

          <!-- ── Tabs ── -->
          <div class="mb-5 flex border-b border-gray-200 dark:border-gray-700">
            <button
              @click="activeTab = 'recursos'"
              :class="[
                'flex items-center gap-2 px-4 py-2.5 text-sm font-medium border-b-2 transition-colors -mb-px',
                activeTab === 'recursos'
                  ? 'border-brand-500 text-brand-600 dark:text-brand-400'
                  : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200',
              ]"
            >
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
              Recursos
              <span class="rounded-full bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 text-xs font-semibold text-gray-600 dark:text-gray-300">
                {{ grupoStore.recursosDelGrupo.length }}
              </span>
            </button>
            <button
              @click="handleActivarTabActividades"
              :class="[
                'flex items-center gap-2 px-4 py-2.5 text-sm font-medium border-b-2 transition-colors -mb-px',
                activeTab === 'actividades'
                  ? 'border-brand-500 text-brand-600 dark:text-brand-400'
                  : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200',
              ]"
            >
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              Actividades
              <span class="rounded-full bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 text-xs font-semibold text-gray-600 dark:text-gray-300">
                {{ actividadesStore.actividadesDelGrupo.length }}
              </span>
            </button>
          </div>

          <!-- ══════════════════════════════════════════════════════════════════ -->
          <!-- TAB: RECURSOS                                                     -->
          <!-- ══════════════════════════════════════════════════════════════════ -->
          <div v-if="activeTab === 'recursos'">
            <!-- Barra porcentaje total -->
            <div class="mb-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
              <div class="mb-2 flex items-center justify-between text-sm">
                <span class="font-medium text-gray-700 dark:text-gray-300">Porcentaje asignado</span>
                <span :class="['font-semibold', grupoStore.totalPorcentaje > 100 ? 'text-red-500' : grupoStore.totalPorcentaje === 100 ? 'text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300']">
                  {{ grupoStore.totalPorcentaje }}%
                </span>
              </div>
              <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                <div
                  :class="['h-full rounded-full transition-all duration-300', grupoStore.totalPorcentaje > 100 ? 'bg-red-500' : grupoStore.totalPorcentaje === 100 ? 'bg-green-500' : 'bg-brand-500']"
                  :style="{ width: `${Math.min(grupoStore.totalPorcentaje, 100)}%` }"
                />
              </div>
            </div>

            <div class="mb-3 flex items-center justify-between">
              <h3 class="font-medium text-gray-900 dark:text-white">Recursos del grupo</h3>
              <button @click="openAddRecursoModal" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-600 transition-colors">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Agregar Recurso
              </button>
            </div>

            <div v-if="loadingRecursos" class="space-y-2">
              <div v-for="n in 3" :key="n" class="h-14 animate-pulse rounded-xl bg-gray-100 dark:bg-gray-800" />
            </div>

            <div v-else-if="grupoStore.recursosDelGrupo.length === 0" class="rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-10 text-center">
              <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
              <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">No hay recursos asignados a este grupo.</p>
              <p class="mt-1 text-xs text-gray-400 dark:text-gray-600">Agrega recursos desde el catálogo global.</p>
            </div>

            <div v-else class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
              <table class="w-full text-sm">
                <thead>
                  <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Recurso</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Tipo</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Costo/mes</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">%</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Acciones</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                  <tr v-for="recurso in grupoStore.recursosDelGrupo" :key="recurso.recurso_id" class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ recurso.nombre }}</td>
                    <td class="px-4 py-3"><span :class="tipoBadgeClass(recurso.tipo)">{{ tipoLabel(recurso.tipo) }}</span></td>
                    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-300">${{ recurso.costo_mensual.toLocaleString() }}</td>
                    <td class="px-4 py-3 text-right"><span class="font-semibold text-brand-600 dark:text-brand-400">{{ recurso.porcentaje }}%</span></td>
                    <td class="px-4 py-3">
                      <div class="flex items-center justify-center gap-1">
                        <button @click="openEditPorcentajeModal(recurso)" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 dark:hover:text-gray-200 transition-colors" title="Editar porcentaje">
                          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                        <button @click="handleRemoveRecurso(recurso)" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition-colors" title="Quitar">
                          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- ══════════════════════════════════════════════════════════════════ -->
          <!-- TAB: ACTIVIDADES                                                  -->
          <!-- ══════════════════════════════════════════════════════════════════ -->
          <div v-else-if="activeTab === 'actividades'" class="flex gap-5">

            <!-- Mini panel izquierdo: lista de actividades -->
            <div class="w-64 shrink-0 space-y-2">
              <div class="mb-2 flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Actividades</span>
                <button @click="openCreateActividadModal" class="inline-flex items-center gap-1 rounded-lg bg-brand-500 px-2.5 py-1 text-xs font-semibold text-white hover:bg-brand-600 transition-colors">
                  <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                  Nueva
                </button>
              </div>

              <div v-if="loadingActividades" class="space-y-2">
                <div v-for="n in 3" :key="n" class="h-20 animate-pulse rounded-xl bg-gray-100 dark:bg-gray-800" />
              </div>

              <div v-else-if="actividadesStore.actividadesDelGrupo.length === 0" class="rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-6 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">Sin actividades aún.</p>
              </div>

              <template v-else>
                <div
                  v-for="actividad in actividadesStore.actividadesDelGrupo"
                  :key="actividad.id"
                  :class="[
                    'rounded-xl border p-3 transition-all',
                    actividadesStore.selectedActividad?.id === actividad.id
                      ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10 ring-1 ring-brand-500'
                      : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-brand-300',
                  ]"
                >
                  <div class="flex items-start justify-between gap-1">
                    <div class="min-w-0 flex-1">
                      <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ actividad.nombre }}</p>
                      <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ actividad.tiempo_base_minutos }} min base</p>
                    </div>
                    <div class="flex shrink-0 gap-0.5">
                      <button @click.stop="openEditActividadModal(actividad)" class="rounded p-1 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 transition-colors" title="Editar">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                      </button>
                      <button @click.stop="handleDeleteActividad(actividad)" class="rounded p-1 text-gray-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition-colors" title="Eliminar">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                      </button>
                    </div>
                  </div>
                  <button
                    @click="handleSelectActividad(actividad)"
                    :class="[
                      'mt-2 w-full rounded-lg px-2.5 py-1 text-xs font-medium transition-colors',
                      actividadesStore.selectedActividad?.id === actividad.id
                        ? 'bg-brand-500 text-white'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600',
                    ]"
                  >
                    Ver Inductores
                  </button>
                </div>
              </template>
            </div>

            <!-- Mini panel derecho: inductores de la actividad -->
            <div class="flex-1 min-w-0">
              <div v-if="!actividadesStore.selectedActividad" class="flex h-full min-h-48 flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                <svg class="h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-2 text-sm text-gray-400 dark:text-gray-500">Selecciona una actividad</p>
                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-600">para ver sus inductores</p>
              </div>

              <div v-else>
                <div class="mb-3 flex items-center justify-between">
                  <div>
                    <h3 class="font-medium text-gray-900 dark:text-white">{{ actividadesStore.selectedActividad.nombre }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ actividadesStore.selectedActividad.tiempo_base_minutos }} min base</p>
                  </div>
                  <div class="flex items-center gap-2">
                    <button @click="openAddInductorModal" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-600 transition-colors">
                      <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                      Agregar Inductor
                    </button>
                    <button @click="actividadesStore.clearSelection()" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 transition-colors">
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                  </div>
                </div>

                <div v-if="loadingInductores" class="space-y-2">
                  <div v-for="n in 3" :key="n" class="h-12 animate-pulse rounded-xl bg-gray-100 dark:bg-gray-800" />
                </div>

                <div v-else-if="actividadesStore.inductoresDeActividad.length === 0" class="rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-8 text-center">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Sin inductores asignados.</p>
                  <p class="mt-1 text-xs text-gray-400 dark:text-gray-600">Agrega inductores de tiempo para calcular el costo.</p>
                </div>

                <div v-else class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Inductor</th>
                        <!-- <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Tipo</th> -->
                        <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Acciones</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                      <tr v-for="item in actividadesStore.inductoresDeActividad" :key="item.id" class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ item.inductor_tiempo_id }}</td>
                        <!-- <td class="px-4 py-3"><span :class="tipoCalculoBadge(item.tipo_calculo)">{{ tipoCalculoLabel(item.tipo_calculo) }}</span></td> -->
                        <td class="px-4 py-3 text-center">
                          <button @click="handleRemoveInductor(item)" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-500 transition-colors" title="Quitar">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                          </button>
                        </td>
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

    <!-- ════════════════════════════════════════════════════════════════════════ -->
    <!-- MODALES                                                                 -->
    <!-- ════════════════════════════════════════════════════════════════════════ -->

    <!-- Modal: crear / editar grupo -->
    <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-if="showGrupoModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeGrupoModal" />
        <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
          <div v-if="showGrupoModal" class="relative z-10 w-full max-w-lg rounded-2xl bg-white dark:bg-gray-900 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-5">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ editingGrupoId ? 'Editar Grupo' : 'Nuevo Grupo de Recursos' }}</h2>
              <button @click="closeGrupoModal" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-600 transition-colors"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
            <div class="space-y-4 px-6 py-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nombre</label>
                <input v-model="grupoForm.nombre" type="text" placeholder="Ej: Maquinaria, Operarios..." :class="inputClass(grupoApiState.validationErrors.value, 'nombre')" />
                <p v-if="getFieldError(grupoApiState.validationErrors.value, 'nombre')" class="mt-1.5 text-xs text-red-500">{{ getFieldError(grupoApiState.validationErrors.value, 'nombre') }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Capacidad práctica (minutos)</label>
                <input v-model.number="grupoForm.capacidad_practica_minutos" type="number" min="1" placeholder="Ej: 10000" :class="inputClass(grupoApiState.validationErrors.value, 'capacidad_practica_minutos')" />
                <p v-if="getFieldError(grupoApiState.validationErrors.value, 'capacidad_practica_minutos')" class="mt-1.5 text-xs text-red-500">{{ getFieldError(grupoApiState.validationErrors.value, 'capacidad_practica_minutos') }}</p>
              </div>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 px-6 py-4">
              <button @click="closeGrupoModal" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Cancelar</button>
              <button @click="handleSubmitGrupo" :disabled="grupoApiState.loading.value" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed transition-colors">
                <svg v-if="grupoApiState.loading.value" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" /><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" /></svg>
                {{ grupoApiState.loading.value ? 'Guardando...' : (editingGrupoId ? 'Guardar cambios' : 'Crear grupo') }}
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

    <!-- Modal: agregar recurso al grupo -->
    <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-if="showAddRecursoModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeAddRecursoModal" />
        <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
          <div v-if="showAddRecursoModal" class="relative z-10 w-full max-w-lg rounded-2xl bg-white dark:bg-gray-900 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-5">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Agregar Recurso al Grupo</h2>
              <button @click="closeAddRecursoModal" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-600 transition-colors"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
            <div class="space-y-4 px-6 py-6">
              <div v-if="loadingCatalogoRecursos" class="py-8 text-center text-sm text-gray-400">Cargando catálogo...</div>
              <template v-else>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Recurso</label>
                  <select v-model="addRecursoForm.recurso_id" :class="inputClass(recursoApiState.validationErrors.value, 'recurso_id')">
                    <option :value="0" disabled>Selecciona un recurso...</option>
                    <option v-for="recurso in recursosDisponibles" :key="recurso.id" :value="recurso.id">{{ recurso.nombre }} — {{ tipoLabel(recurso.tipo) }} — ${{ recurso.costo_mensual.toLocaleString() }}</option>
                  </select>
                  <p v-if="getFieldError(recursoApiState.validationErrors.value, 'recurso_id')" class="mt-1.5 text-xs text-red-500">{{ getFieldError(recursoApiState.validationErrors.value, 'recurso_id') }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Porcentaje (%)</label>
                  <input v-model.number="addRecursoForm.porcentaje" type="number" min="1" max="100" placeholder="Ej: 60" :class="inputClass(recursoApiState.validationErrors.value, 'porcentaje')" />
                  <p v-if="getFieldError(recursoApiState.validationErrors.value, 'porcentaje')" class="mt-1.5 text-xs text-red-500">{{ getFieldError(recursoApiState.validationErrors.value, 'porcentaje') }}</p>
                  <p class="mt-1.5 text-xs text-gray-400">Porcentaje del costo del recurso que se asigna al grupo.</p>
                </div>
              </template>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 px-6 py-4">
              <button @click="closeAddRecursoModal" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Cancelar</button>
              <button @click="handleAddRecurso" :disabled="recursoApiState.loading.value || addRecursoForm.recurso_id === 0" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed transition-colors">
                <svg v-if="recursoApiState.loading.value" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" /><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" /></svg>
                {{ recursoApiState.loading.value ? 'Agregando...' : 'Agregar al grupo' }}
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

    <!-- Modal: editar porcentaje recurso -->
    <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-if="showEditPorcentajeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeEditPorcentajeModal" />
        <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
          <div v-if="showEditPorcentajeModal" class="relative z-10 w-full max-w-sm rounded-2xl bg-white dark:bg-gray-900 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-5">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Editar Porcentaje</h2>
              <button @click="closeEditPorcentajeModal" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-600 transition-colors"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
            <div class="px-6 py-6">
              <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">Recurso: <strong class="text-gray-900 dark:text-white">{{ editingRecurso?.nombre }}</strong></p>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nuevo porcentaje (%)</label>
                <input v-model.number="editPorcentaje" type="number" min="1" max="100" :class="inputClass(editApiState.validationErrors.value, 'porcentaje')" />
                <p v-if="getFieldError(editApiState.validationErrors.value, 'porcentaje')" class="mt-1.5 text-xs text-red-500">{{ getFieldError(editApiState.validationErrors.value, 'porcentaje') }}</p>
              </div>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 px-6 py-4">
              <button @click="closeEditPorcentajeModal" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Cancelar</button>
              <button @click="handleEditPorcentaje" :disabled="editApiState.loading.value" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed transition-colors">
                <svg v-if="editApiState.loading.value" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" /><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" /></svg>
                {{ editApiState.loading.value ? 'Guardando...' : 'Guardar' }}
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

    <!-- Modal: crear / editar actividad -->
    <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-if="showActividadModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeActividadModal" />
        <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
          <div v-if="showActividadModal" class="relative z-10 w-full max-w-lg rounded-2xl bg-white dark:bg-gray-900 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-5">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ editingActividadId ? 'Editar Actividad' : 'Nueva Actividad' }}</h2>
              <button @click="closeActividadModal" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-600 transition-colors"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
            <div class="space-y-4 px-6 py-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nombre</label>
                <input v-model="actividadForm.nombre" type="text" placeholder="Ej: Corte, Ensamblaje, Pintura..." :class="inputClass(actividadApiState.validationErrors.value, 'nombre')" />
                <p v-if="getFieldError(actividadApiState.validationErrors.value, 'nombre')" class="mt-1.5 text-xs text-red-500">{{ getFieldError(actividadApiState.validationErrors.value, 'nombre') }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tiempo base (minutos)</label>
                <input v-model.number="actividadForm.tiempo_base_minutos" type="number" min="0" step="0.01" placeholder="Ej: 15" :class="inputClass(actividadApiState.validationErrors.value, 'tiempo_base_minutos')" />
                <p v-if="getFieldError(actividadApiState.validationErrors.value, 'tiempo_base_minutos')" class="mt-1.5 text-xs text-red-500">{{ getFieldError(actividadApiState.validationErrors.value, 'tiempo_base_minutos') }}</p>
              </div>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 px-6 py-4">
              <button @click="closeActividadModal" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Cancelar</button>
              <button @click="handleSubmitActividad" :disabled="actividadApiState.loading.value" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed transition-colors">
                <svg v-if="actividadApiState.loading.value" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" /><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" /></svg>
                {{ actividadApiState.loading.value ? 'Guardando...' : (editingActividadId ? 'Guardar cambios' : 'Crear actividad') }}
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

    <!-- Modal: agregar / editar inductor de actividad -->
    <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-if="showInductorModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeInductorModal" />
        <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
          <div v-if="showInductorModal" class="relative z-10 w-full max-w-lg rounded-2xl bg-white dark:bg-gray-900 shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 px-6 py-5">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Agregar Inductor</h2>
              <button @click="closeInductorModal" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-600 transition-colors"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
            </div>
            <div class="space-y-4 px-6 py-6">
              <div v-if="loadingCatalogoInductores" class="py-6 text-center text-sm text-gray-400">Cargando catálogo...</div>
              <div v-else>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Inductor de tiempo</label>
                <select v-model="inductorForm.inductor_tiempo_id" :class="inputClass(inductorApiState.validationErrors.value, 'inductor_tiempo_id')">
                  <option :value="0" disabled>Selecciona un inductor...</option>
                  <option v-for="ind in inductoresCatalogoDisponibles" :key="ind.id" :value="ind.id">{{ ind.nombre }} — {{ tipoCalculoLabel(ind.tipo_calculo) }}</option>
                </select>
                <p v-if="getFieldError(inductorApiState.validationErrors.value, 'inductor_tiempo_id')" class="mt-1.5 text-xs text-red-500">{{ getFieldError(inductorApiState.validationErrors.value, 'inductor_tiempo_id') }}</p>
              </div>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 px-6 py-4">
              <button @click="closeInductorModal" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Cancelar</button>
              <button @click="handleSubmitInductor" :disabled="inductorApiState.loading.value || inductorForm.inductor_tiempo_id === 0" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-600 disabled:opacity-60 disabled:cursor-not-allowed transition-colors">
                <svg v-if="inductorApiState.loading.value" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" /><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" /></svg>
                {{ inductorApiState.loading.value ? 'Agregando...' : 'Agregar' }}
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue'
import Swal from 'sweetalert2'
import { useDepartamentosStore } from '@/modules/departamentos/store/departamentosStore'
import { useGruposRecursosStore } from '../store/gruposRecursosStore'
import { useRecursosStore } from '@/modules/recursos/store/recursosStore'
import { useActividadesStore } from '@/modules/actividades/store/actividadesStore'
import { useInductoresTiempoStore } from '@/modules/inductores-tiempo/store/inductoresTiempoStore'
import { useApi } from '@/composables/useApi'
import { getFieldError } from '@/app/api/types'
import gruposRecursosService from '../api/gruposRecursosService'
import actividadesService from '@/modules/actividades/api/actividadesService'
import type { GrupoRecurso, RecursoEnGrupo } from '../types'
import type { Actividad, ActividadInductor } from '@/modules/actividades/types'
import type { ValidationErrors } from '@/app/api/types'

const deptStore = useDepartamentosStore()
const grupoStore = useGruposRecursosStore()
const recursosStore = useRecursosStore()
const actividadesStore = useActividadesStore()
const inductoresTiempoStore = useInductoresTiempoStore()

const grupoApiState = useApi()
const recursoApiState = useApi()
const editApiState = useApi()
const actividadApiState = useApi()
const inductorApiState = useApi()

// ── Loading ───────────────────────────────────────────────────────────────────
const loadingDepts = ref(false)
const loadingGrupos = ref(false)
const loadingRecursos = ref(false)
const loadingActividades = ref(false)
const loadingInductores = ref(false)
const loadingCatalogoRecursos = ref(false)
const loadingCatalogoInductores = ref(false)

// ── Estado UI ─────────────────────────────────────────────────────────────────
const selectedDeptId = ref<number | null>(null)
const activeTab = ref<'recursos' | 'actividades'>('recursos')

// ── Modales grupo ─────────────────────────────────────────────────────────────
const showGrupoModal = ref(false)
const editingGrupoId = ref<number | null>(null)
const grupoForm = ref({ nombre: '', capacidad_practica_minutos: 0 })

// ── Modales recurso ───────────────────────────────────────────────────────────
const showAddRecursoModal = ref(false)
const addRecursoForm = ref({ recurso_id: 0, porcentaje: 0 })
const showEditPorcentajeModal = ref(false)
const editingRecurso = ref<RecursoEnGrupo | null>(null)
const editPorcentaje = ref(0)

// ── Modales actividad ─────────────────────────────────────────────────────────
const showActividadModal = ref(false)
const editingActividadId = ref<number | null>(null)
const actividadForm = ref({ nombre: '', tiempo_base_minutos: 0 })

// ── Modales inductor ──────────────────────────────────────────────────────────
const showInductorModal = ref(false)
const inductorForm = ref({ inductor_tiempo_id: 0 })

// ── Computed ──────────────────────────────────────────────────────────────────
const asignadosRecursosIds = computed(() => new Set(grupoStore.recursosDelGrupo.map((r) => r.recurso_id)))
const recursosDisponibles = computed(() => recursosStore.recursos.filter((r) => !asignadosRecursosIds.value.has(r.id)))

const asignadosInductoresIds = computed(() => new Set(actividadesStore.inductoresDeActividad.map((i) => i.inductor_tiempo_id)))
const inductoresCatalogoDisponibles = computed(() => inductoresTiempoStore.inductores.filter((i) => !asignadosInductoresIds.value.has(i.id)))

// ── Lifecycle ─────────────────────────────────────────────────────────────────
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

// ── Helpers UI ────────────────────────────────────────────────────────────────
function tipoLabel(tipo: string) {
  const map: Record<string, string> = { humano: 'Humano', maquina: 'Máquina', infraestructura: 'Infraestructura' }
  return map[tipo] ?? tipo
}

function tipoBadgeClass(tipo: string) {
  const base = 'rounded-full px-2.5 py-0.5 text-xs font-medium'
  const map: Record<string, string> = {
    humano: `${base} bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400`,
    maquina: `${base} bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400`,
    infraestructura: `${base} bg-orange-50 dark:bg-orange-500/10 text-orange-700 dark:text-orange-400`,
  }
  return map[tipo] ?? base
}

function tipoCalculoLabel(tipo: string) {
  const map: Record<string, string> = { MANUAL: 'Manual', POR_CANTIDAD: 'Por cantidad', POR_LOTE: 'Por lote' }
  return map[tipo] ?? tipo
}

function tipoCalculoBadge(tipo: string) {
  const base = 'rounded-full px-2.5 py-0.5 text-xs font-medium'
  const map: Record<string, string> = {
    MANUAL: `${base} bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300`,
    POR_CANTIDAD: `${base} bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400`,
    POR_LOTE: `${base} bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400`,
  }
  return map[tipo] ?? base
}

function inputClass(errors: ValidationErrors | null, field: string) {
  const hasError = !!getFieldError(errors, field)
  return [
    'w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-800 outline-none transition-colors',
    hasError
      ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-500/20'
      : 'border-gray-300 dark:border-gray-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20',
  ]
}

// ── Panel ─────────────────────────────────────────────────────────────────────
function handleClosePanel() {
  grupoStore.clearSelection()
  actividadesStore.clearGrupo()
  activeTab.value = 'recursos'
}

async function handleSelectGrupo(grupo: GrupoRecurso) {
  if (grupoStore.selectedGrupo?.id === grupo.id) {
    handleClosePanel()
    return
  }
  actividadesStore.clearGrupo()
  activeTab.value = 'recursos'
  loadingRecursos.value = true
  await grupoStore.selectGrupo(grupo)
  loadingRecursos.value = false
}

async function handleActivarTabActividades() {
  activeTab.value = 'actividades'
  if (!grupoStore.selectedGrupo) return
  if (actividadesStore.actividadesDelGrupo.length === 0) {
    loadingActividades.value = true
    await actividadesStore.fetchByGrupo(grupoStore.selectedGrupo.id)
    loadingActividades.value = false
  }
}

// ── CRUD Grupo ────────────────────────────────────────────────────────────────
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
      gruposRecursosService.update(editingGrupoId.value!, { nombre: grupoForm.value.nombre, capacidad_practica_minutos: grupoForm.value.capacidad_practica_minutos }),
    )
    if (result !== null) {
      if (grupoStore.selectedGrupo?.id === editingGrupoId.value) {
        Object.assign(grupoStore.selectedGrupo, (result as { data: GrupoRecurso }).data)
      }
      await deptStore.selectDepartamento(dept)
      closeGrupoModal()
    }
  } else {
    const result = await grupoApiState.execute(() =>
      gruposRecursosService.create({ departamento_id: dept.id, nombre: grupoForm.value.nombre, capacidad_practica_minutos: grupoForm.value.capacidad_practica_minutos }),
    )
    if (result !== null) {
      await deptStore.selectDepartamento(dept)
      closeGrupoModal()
    }
  }
}

async function handleDeleteGrupo(grupo: GrupoRecurso) {
  const { isConfirmed } = await Swal.fire({ title: '¿Eliminar grupo?', text: `Se eliminará "${grupo.nombre}". Esta acción no se puede deshacer.`, icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar', confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', reverseButtons: true })
  if (!isConfirmed) return
  const dept = deptStore.departamentos.find((d) => d.id === selectedDeptId.value)
  await grupoApiState.execute(() => gruposRecursosService.remove(grupo.id))
  if (grupoStore.selectedGrupo?.id === grupo.id) handleClosePanel()
  if (dept) await deptStore.selectDepartamento(dept)
}

// ── Recursos ──────────────────────────────────────────────────────────────────
async function openAddRecursoModal() {
  addRecursoForm.value = { recurso_id: 0, porcentaje: 0 }
  showAddRecursoModal.value = true
  if (recursosStore.recursos.length === 0) {
    loadingCatalogoRecursos.value = true
    await recursosStore.fetchAll()
    loadingCatalogoRecursos.value = false
  }
}
function closeAddRecursoModal() { showAddRecursoModal.value = false }
async function handleAddRecurso() {
  const result = await recursoApiState.execute(() => grupoStore.addRecurso(addRecursoForm.value))
  if (result !== null) closeAddRecursoModal()
}

function openEditPorcentajeModal(recurso: RecursoEnGrupo) {
  editingRecurso.value = recurso
  editPorcentaje.value = recurso.porcentaje
  showEditPorcentajeModal.value = true
}
function closeEditPorcentajeModal() { showEditPorcentajeModal.value = false; editingRecurso.value = null }
async function handleEditPorcentaje() {
  if (!editingRecurso.value) return
  const result = await editApiState.execute(() => grupoStore.updateRecurso(editingRecurso.value!.recurso_id, { porcentaje: editPorcentaje.value }))
  if (result !== null) closeEditPorcentajeModal()
}

async function handleRemoveRecurso(recurso: RecursoEnGrupo) {
  const { isConfirmed } = await Swal.fire({ title: '¿Quitar recurso?', text: `Se quitará "${recurso.nombre}" de este grupo.`, icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, quitar', cancelButtonText: 'Cancelar', confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', reverseButtons: true })
  if (!isConfirmed) return
  await grupoStore.removeRecurso(recurso.recurso_id)
}

// ── Actividades ───────────────────────────────────────────────────────────────
function openCreateActividadModal() {
  editingActividadId.value = null
  actividadForm.value = { nombre: '', tiempo_base_minutos: 0 }
  showActividadModal.value = true
}
function openEditActividadModal(actividad: Actividad) {
  editingActividadId.value = actividad.id
  actividadForm.value = { nombre: actividad.nombre, tiempo_base_minutos: actividad.tiempo_base_minutos }
  showActividadModal.value = true
}
function closeActividadModal() { showActividadModal.value = false }

async function handleSubmitActividad() {
  if (!grupoStore.selectedGrupo) return
  if (editingActividadId.value) {
    const result = await actividadApiState.execute(() =>
      actividadesService.update(editingActividadId.value!, { nombre: actividadForm.value.nombre, tiempo_base_minutos: actividadForm.value.tiempo_base_minutos }),
    )
    if (result !== null) {
      const data = (result as { data: Actividad }).data
      const idx = actividadesStore.actividadesDelGrupo.findIndex((a) => a.id === editingActividadId.value)
      if (idx !== -1) actividadesStore.actividadesDelGrupo[idx] = data
      if (actividadesStore.selectedActividad?.id === editingActividadId.value) Object.assign(actividadesStore.selectedActividad, data)
      closeActividadModal()
    }
  } else {
    const result = await actividadApiState.execute(() =>
      actividadesService.create({ grupo_recursos_id: grupoStore.selectedGrupo!.id, nombre: actividadForm.value.nombre, tiempo_base_minutos: actividadForm.value.tiempo_base_minutos }),
    )
    if (result !== null) {
      actividadesStore.actividadesDelGrupo.push((result as { data: Actividad }).data)
      closeActividadModal()
    }
  }
}

async function handleDeleteActividad(actividad: Actividad) {
  const { isConfirmed } = await Swal.fire({ title: '¿Eliminar actividad?', text: `Se eliminará "${actividad.nombre}". Esta acción no se puede deshacer.`, icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar', confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', reverseButtons: true })
  if (!isConfirmed) return
  const result = await actividadApiState.execute(() => actividadesService.remove(actividad.id))
  if (result !== null) {
    actividadesStore.actividadesDelGrupo = actividadesStore.actividadesDelGrupo.filter((a) => a.id !== actividad.id)
    if (actividadesStore.selectedActividad?.id === actividad.id) actividadesStore.clearSelection()
  }
}

async function handleSelectActividad(actividad: Actividad) {
  if (actividadesStore.selectedActividad?.id === actividad.id) {
    actividadesStore.clearSelection()
    return
  }
  loadingInductores.value = true
  await actividadesStore.selectActividad(actividad)
  loadingInductores.value = false
}

// ── Inductores ────────────────────────────────────────────────────────────────
async function openAddInductorModal() {
  inductorForm.value = { inductor_tiempo_id: 0 }
  showInductorModal.value = true
  if (inductoresTiempoStore.inductores.length === 0) {
    loadingCatalogoInductores.value = true
    await inductoresTiempoStore.fetchAll()
    loadingCatalogoInductores.value = false
  }
}
function closeInductorModal() { showInductorModal.value = false }

async function handleSubmitInductor() {
  const result = await inductorApiState.execute(() => actividadesStore.addInductor({ inductor_tiempo_id: inductorForm.value.inductor_tiempo_id }))
  if (result !== null) closeInductorModal()
}

async function handleRemoveInductor(item: ActividadInductor) {
  const { isConfirmed } = await Swal.fire({ title: '¿Quitar inductor?', text: `Se quitará "${item.inductor_nombre}" de esta actividad.`, icon: 'warning', showCancelButton: true, confirmButtonText: 'Sí, quitar', cancelButtonText: 'Cancelar', confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', reverseButtons: true })
  if (!isConfirmed) return
  await actividadesStore.removeInductor(item.id)
}
</script>
