import { defineStore } from 'pinia'
import { ref } from 'vue'
import categoriaProductoService from '../api/categoriaProductoService'
import type {
  CategoriaProducto,
  CreateCategoriaProductoDto,
  UpdateCategoriaProductoDto,
} from '../types'

export const useCategoriasProductoStore = defineStore('categoriasProducto', () => {
  const categorias = ref<CategoriaProducto[]>([])

  async function fetchAll() {
    const response = await categoriaProductoService.getAll()
    categorias.value = response.data
  }

  async function create(dto: CreateCategoriaProductoDto) {
    const response = await categoriaProductoService.create(dto)
    categorias.value.push(response.data)
  }

  async function update(id: number, dto: UpdateCategoriaProductoDto) {
    const response = await categoriaProductoService.update(id, dto)
    const index = categorias.value.findIndex((c) => c.id === id)
    if (index !== -1) categorias.value[index] = response.data
  }

  async function remove(id: number) {
    await categoriaProductoService.remove(id)
    categorias.value = categorias.value.filter((c) => c.id !== id)
  }

  return { categorias, fetchAll, create, update, remove }
})
