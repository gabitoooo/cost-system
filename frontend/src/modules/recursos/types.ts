export type TipoRecurso = 'humano' | 'maquina' | 'infraestructura'

export interface Recurso {
  id: number
  nombre: string
  tipo: TipoRecurso
  costo_mensual: number
}

export interface CreateRecursoDto {
  nombre: string
  tipo: TipoRecurso
  costo_mensual: number
}

export interface UpdateRecursoDto {
  nombre: string
  tipo: TipoRecurso
  costo_mensual: number
}
