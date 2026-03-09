import { http } from '@/app/api/http'
import type {
  Actividad,
  ActividadInductor,
  CreateActividadDto,
  UpdateActividadDto,
  AddInductorDto,
} from '../types'

const actividadesService = {
  // ── Actividades ────────────────────────────────────────────────────────────
  getByGrupo(grupoId: number): Promise<{ data: Actividad[] }> {
    return http.get(`/grupos-recursos/${grupoId}/actividades`)
  },

  getById(id: number): Promise<{ data: Actividad }> {
    return http.get(`/actividades/${id}`)
  },

  create(dto: CreateActividadDto): Promise<{ data: Actividad }> {
    return http.post('/actividades', dto)
  },

  update(id: number, dto: UpdateActividadDto): Promise<{ data: Actividad }> {
    return http.put(`/actividades/${id}`, dto)
  },

  remove(id: number): Promise<void> {
    return http.delete(`/actividades/${id}`)
  },

  // ── Inductores de la actividad ─────────────────────────────────────────────
  getInductores(actividadId: number): Promise<{ data: ActividadInductor[] }> {
    return http.get(`/actividades/${actividadId}/inductores`)
  },

  addInductor(actividadId: number, dto: AddInductorDto): Promise<{ data: ActividadInductor }> {
    return http.post(`/actividades/${actividadId}/inductores`, dto)
  },

  removeInductor(actividadId: number, inductorId: number): Promise<void> {
    return http.delete(`/actividades/${actividadId}/inductores/${inductorId}`)
  },
}

export default actividadesService
