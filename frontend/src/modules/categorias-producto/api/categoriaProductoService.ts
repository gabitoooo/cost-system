import { http } from '@/app/api/http'
import type {
  CategoriaProducto,
  CreateCategoriaProductoDto,
  UpdateCategoriaProductoDto,
} from '../types'

const categoriaProductoService = {
  getAll(): Promise<{ data: CategoriaProducto[] }> {
    return http.get('/categorias-producto')
  },

  create(dto: CreateCategoriaProductoDto): Promise<{ data: CategoriaProducto }> {
    return http.post('/categorias-producto', dto)
  },

  update(id: number, dto: UpdateCategoriaProductoDto): Promise<{ data: CategoriaProducto }> {
    return http.put(`/categorias-producto/${id}`, dto)
  },

  remove(id: number): Promise<void> {
    return http.delete(`/categorias-producto/${id}`)
  },
}

export default categoriaProductoService
