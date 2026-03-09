import { http } from '@/app/api/http'
import type { Recurso, CreateRecursoDto, UpdateRecursoDto } from '../types'

const recursosService = {
  getAll(): Promise<{ data: Recurso[] }> {
    return http.get('/recursos')
  },

  create(dto: CreateRecursoDto): Promise<{ data: Recurso }> {
    return http.post('/recursos', dto)
  },

  update(id: number, dto: UpdateRecursoDto): Promise<{ data: Recurso }> {
    return http.put(`/recursos/${id}`, dto)
  },

  remove(id: number): Promise<void> {
    return http.delete(`/recursos/${id}`)
  },
}

export default recursosService
