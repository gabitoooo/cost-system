export interface Actividad {
  id: number
  grupo_recursos_id: number
  nombre: string
  tiempo_base_minutos: number
}

export interface ActividadInductor {
  id: number
  actividad_id: number
  inductor_tiempo_id: number
  inductor_nombre: string
  tipo_calculo: 'MANUAL' | 'POR_CANTIDAD' | 'POR_LOTE'
}

export interface CreateActividadDto {
  grupo_recursos_id: number
  nombre: string
  tiempo_base_minutos: number
}

export interface UpdateActividadDto {
  nombre: string
  tiempo_base_minutos: number
}

export interface AddInductorDto {
  inductor_tiempo_id: number
}
