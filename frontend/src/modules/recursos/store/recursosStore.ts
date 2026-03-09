import { defineStore } from 'pinia'
import { ref } from 'vue'
import recursosService from '../api/recursosService'
import type { Recurso, CreateRecursoDto, UpdateRecursoDto } from '../types'

export const useRecursosStore = defineStore('recursos', () => {
  const recursos = ref<Recurso[]>([])

  async function fetchAll() {
    const response = await recursosService.getAll()
    recursos.value = response.data
  }

  async function create(dto: CreateRecursoDto) {
    const response = await recursosService.create(dto)
    recursos.value.push(response.data)
  }

  async function update(id: number, dto: UpdateRecursoDto) {
    const response = await recursosService.update(id, dto)
    const index = recursos.value.findIndex((r) => r.id === id)
    if (index !== -1) recursos.value[index] = response.data
  }

  async function remove(id: number) {
    await recursosService.remove(id)
    recursos.value = recursos.value.filter((r) => r.id !== id)
  }

  return { recursos, fetchAll, create, update, remove }
})
