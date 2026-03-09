import { defineStore } from 'pinia'
import { ref } from 'vue'
import departamentosService from '../api/departamentosService'
import type { Departamento, GrupoRecurso, CreateDepartamentoDto, UpdateDepartamentoDto } from '../types'

export const useDepartamentosStore = defineStore('departamentos', () => {
  const departamentos = ref<Departamento[]>([])
  const gruposRecursos = ref<GrupoRecurso[]>([])
  const selectedDepartamento = ref<Departamento | null>(null)

  async function fetchAll() {
    const response = await departamentosService.getAll()
    departamentos.value = response.data
  }

  async function create(dto: CreateDepartamentoDto) {
    const response = await departamentosService.create(dto)
    departamentos.value.push(response.data)
  }

  async function update(id: number, dto: UpdateDepartamentoDto) {
    const response = await departamentosService.update(id, dto)
    const index = departamentos.value.findIndex((d) => d.id === id)
    if (index !== -1) departamentos.value[index] = response.data
    if (selectedDepartamento.value?.id === id) selectedDepartamento.value = response.data
  }

  async function remove(id: number) {
    await departamentosService.remove(id)
    departamentos.value = departamentos.value.filter((d) => d.id !== id)
    if (selectedDepartamento.value?.id === id) {
      selectedDepartamento.value = null
      gruposRecursos.value = []
    }
  }

  async function selectDepartamento(departamento: Departamento) {
    selectedDepartamento.value = departamento
    gruposRecursos.value = []
    const response = await departamentosService.getGruposRecursos(departamento.id)
    gruposRecursos.value = response.data
  }

  function clearSelection() {
    selectedDepartamento.value = null
    gruposRecursos.value = []
  }

  return {
    departamentos,
    gruposRecursos,
    selectedDepartamento,
    fetchAll,
    create,
    update,
    remove,
    selectDepartamento,
    clearSelection,
  }
})
