import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import gruposRecursosService from '../api/gruposRecursosService'
import type {
  GrupoRecurso,
  RecursoEnGrupo,
  AddRecursoAlGrupoDto,
  UpdateRecursoEnGrupoDto,
} from '../types'

export const useGruposRecursosStore = defineStore('gruposRecursos', () => {
  const selectedGrupo = ref<GrupoRecurso | null>(null)
  const recursosDelGrupo = ref<RecursoEnGrupo[]>([])

  const totalPorcentaje = computed(() =>
    recursosDelGrupo.value.reduce((sum, r) => sum + r.porcentaje, 0),
  )

  async function selectGrupo(grupo: GrupoRecurso) {
    selectedGrupo.value = grupo
    recursosDelGrupo.value = []
    const response = await gruposRecursosService.getRecursos(grupo.id)
    recursosDelGrupo.value = response.data
  }

  async function refreshSelectedGrupo() {
    if (!selectedGrupo.value) return
    const response = await gruposRecursosService.getById(selectedGrupo.value.id)
    selectedGrupo.value = response.data
  }

  async function addRecurso(dto: AddRecursoAlGrupoDto) {
    if (!selectedGrupo.value) return
    const response = await gruposRecursosService.addRecurso(selectedGrupo.value.id, dto)
    recursosDelGrupo.value.push(response.data)
    await refreshSelectedGrupo()
  }

  async function updateRecurso(recursoId: number, dto: UpdateRecursoEnGrupoDto) {
    if (!selectedGrupo.value) return
    const response = await gruposRecursosService.updateRecurso(
      selectedGrupo.value.id,
      recursoId,
      dto,
    )
    const index = recursosDelGrupo.value.findIndex((r) => r.recurso_id === recursoId)
    if (index !== -1) recursosDelGrupo.value[index] = response.data
    await refreshSelectedGrupo()
  }

  async function removeRecurso(recursoId: number) {
    if (!selectedGrupo.value) return
    await gruposRecursosService.removeRecurso(selectedGrupo.value.id, recursoId)
    recursosDelGrupo.value = recursosDelGrupo.value.filter((r) => r.recurso_id !== recursoId)
    await refreshSelectedGrupo()
  }

  function clearSelection() {
    selectedGrupo.value = null
    recursosDelGrupo.value = []
  }

  return {
    selectedGrupo,
    recursosDelGrupo,
    totalPorcentaje,
    selectGrupo,
    addRecurso,
    updateRecurso,
    removeRecurso,
    clearSelection,
  }
})
