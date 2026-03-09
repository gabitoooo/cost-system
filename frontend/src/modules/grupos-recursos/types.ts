export interface GrupoRecurso {
  id: number
  departamento_id: number
  nombre: string
  capacidad_practica_minutos: number
  tasa_costo_por_minuto: number
}

export interface RecursoEnGrupo {
  id: number
  recurso_id: number
  nombre: string
  tipo: 'humano' | 'maquina' | 'infraestructura'
  costo_mensual: number
  grupo_recursos_id: number
  porcentaje: number
}

export interface CreateGrupoDto {
  departamento_id: number
  nombre: string
  capacidad_practica_minutos: number
}

export interface UpdateGrupoDto {
  nombre: string
  capacidad_practica_minutos: number
}

export interface AddRecursoAlGrupoDto {
  recurso_id: number
  porcentaje: number
}

export interface UpdateRecursoEnGrupoDto {
  porcentaje: number
}
