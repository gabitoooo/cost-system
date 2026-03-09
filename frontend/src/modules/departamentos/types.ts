export interface Departamento {
  id: number
  nombre: string
}

export interface GrupoRecurso {
  id: number
  departamento_id: number
  nombre: string
  capacidad_practica_minutos: number
  tasa_costo_por_minuto: number
}

export interface CreateDepartamentoDto {
  nombre: string
}

export interface UpdateDepartamentoDto {
  nombre: string
}
