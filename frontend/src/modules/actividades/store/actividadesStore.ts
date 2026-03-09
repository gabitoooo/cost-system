import { defineStore } from 'pinia'
import { ref } from 'vue'
import actividadesService from '../api/actividadesService'
import type { Actividad, ActividadInductor, AddInductorDto } from '../types'

export const useActividadesStore = defineStore('actividades', () => {
  const actividadesDelGrupo = ref<Actividad[]>([])
  const selectedActividad = ref<Actividad | null>(null)
  const inductoresDeActividad = ref<ActividadInductor[]>([])

  async function fetchByGrupo(grupoId: number) {
    const response = await actividadesService.getByGrupo(grupoId)
    actividadesDelGrupo.value = response.data
  }

  async function selectActividad(actividad: Actividad) {
    selectedActividad.value = actividad
    inductoresDeActividad.value = []
    const response = await actividadesService.getInductores(actividad.id)
    inductoresDeActividad.value = response.data
  }

  async function addInductor(dto: AddInductorDto) {
    if (!selectedActividad.value) return
    const response = await actividadesService.addInductor(selectedActividad.value.id, dto)
    inductoresDeActividad.value.push(response.data)
  }

  async function removeInductor(inductorId: number) {
    if (!selectedActividad.value) return
    await actividadesService.removeInductor(selectedActividad.value.id, inductorId)
    inductoresDeActividad.value = inductoresDeActividad.value.filter((i) => i.id !== inductorId)
  }

  function clearSelection() {
    selectedActividad.value = null
    inductoresDeActividad.value = []
  }

  function clearGrupo() {
    actividadesDelGrupo.value = []
    clearSelection()
  }

  return {
    actividadesDelGrupo,
    selectedActividad,
    inductoresDeActividad,
    fetchByGrupo,
    selectActividad,
    addInductor,
    removeInductor,
    clearSelection,
    clearGrupo,
  }
})
