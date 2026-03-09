import { defineStore } from 'pinia'
import { ref } from 'vue'
import inductoresTiempoService from '../api/inductoresTiempoService'
import type { InductorTiempo, CreateInductorTiempoDto, UpdateInductorTiempoDto } from '../types'

export const useInductoresTiempoStore = defineStore('inductoresTiempo', () => {
  const inductores = ref<InductorTiempo[]>([])

  async function fetchAll() {
    const response = await inductoresTiempoService.getAll()
    inductores.value = response.data
  }

  async function create(dto: CreateInductorTiempoDto) {
    const response = await inductoresTiempoService.create(dto)
    inductores.value.push(response.data)
  }

  async function update(id: number, dto: UpdateInductorTiempoDto) {
    const response = await inductoresTiempoService.update(id, dto)
    const index = inductores.value.findIndex((i) => i.id === id)
    if (index !== -1) inductores.value[index] = response.data
  }

  async function remove(id: number) {
    await inductoresTiempoService.remove(id)
    inductores.value = inductores.value.filter((i) => i.id !== id)
  }

  return { inductores, fetchAll, create, update, remove }
})
