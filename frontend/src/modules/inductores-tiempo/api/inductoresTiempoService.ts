import { http } from '@/app/api/http'
import type { InductorTiempo, CreateInductorTiempoDto, UpdateInductorTiempoDto } from '../types'

const inductoresTiempoService = {
  getAll(): Promise<{ data: InductorTiempo[] }> {
    return http.get('/inductores-tiempo')
  },

  getById(id: number): Promise<{ data: InductorTiempo }> {
    return http.get(`/inductores-tiempo/${id}`)
  },

  create(dto: CreateInductorTiempoDto): Promise<{ data: InductorTiempo }> {
    return http.post('/inductores-tiempo', dto)
  },

  update(id: number, dto: UpdateInductorTiempoDto): Promise<{ data: InductorTiempo }> {
    return http.put(`/inductores-tiempo/${id}`, dto)
  },

  remove(id: number): Promise<void> {
    return http.delete(`/inductores-tiempo/${id}`)
  },
}

export default inductoresTiempoService
