import { http } from '@/app/api/http'
import type {
  Departamento,
  GrupoRecurso,
  CreateDepartamentoDto,
  UpdateDepartamentoDto,
} from '../types'

const departamentosService = {
  getAll(): Promise<{ data: Departamento[] }> {
    return http.get('/departamentos')
  },

  getById(id: number): Promise<{ data: Departamento }> {
    return http.get(`/departamentos/${id}`)
  },

  create(dto: CreateDepartamentoDto): Promise<{ data: Departamento }> {
    return http.post('/departamentos', dto)
  },

  update(id: number, dto: UpdateDepartamentoDto): Promise<{ data: Departamento }> {
    return http.put(`/departamentos/${id}`, dto)
  },

  remove(id: number): Promise<void> {
    return http.delete(`/departamentos/${id}`)
  },

  getGruposRecursos(id: number): Promise<{ data: GrupoRecurso[] }> {
    return http.get(`/departamentos/${id}/grupos-recursos`)
  },
}

export default departamentosService
