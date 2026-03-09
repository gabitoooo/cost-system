import { http } from '@/app/api/http'
import type {
  GrupoRecurso,
  RecursoEnGrupo,
  CreateGrupoDto,
  UpdateGrupoDto,
  AddRecursoAlGrupoDto,
  UpdateRecursoEnGrupoDto,
} from '../types'

const gruposRecursosService = {
  getById(id: number): Promise<{ data: GrupoRecurso }> {
    return http.get(`/grupos-recursos/${id}`)
  },

  create(dto: CreateGrupoDto): Promise<{ data: GrupoRecurso }> {
    return http.post('/grupos-recursos', dto)
  },

  update(id: number, dto: UpdateGrupoDto): Promise<{ data: GrupoRecurso }> {
    return http.put(`/grupos-recursos/${id}`, dto)
  },

  remove(id: number): Promise<void> {
    return http.delete(`/grupos-recursos/${id}`)
  },

  // ── Recursos dentro del grupo ──────────────────────────────────────────────
  getRecursos(grupoId: number): Promise<{ data: RecursoEnGrupo[] }> {
    return http.get(`/grupos-recursos/${grupoId}/recursos`)
  },

  addRecurso(grupoId: number, dto: AddRecursoAlGrupoDto): Promise<{ data: RecursoEnGrupo }> {
    return http.post(`/grupos-recursos/${grupoId}/recursos`, dto)
  },

  updateRecurso(
    grupoId: number,
    recursoId: number,
    dto: UpdateRecursoEnGrupoDto,
  ): Promise<{ data: RecursoEnGrupo }> {
    return http.put(`/grupos-recursos/${grupoId}/recursos/${recursoId}`, dto)
  },

  removeRecurso(grupoId: number, recursoId: number): Promise<void> {
    return http.delete(`/grupos-recursos/${grupoId}/recursos/${recursoId}`)
  },
}

export default gruposRecursosService
