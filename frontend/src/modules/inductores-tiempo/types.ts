export type TipoCalculo = 'MANUAL' | 'POR_CANTIDAD' | 'POR_LOTE'

export interface InductorTiempo {
  id: number
  nombre: string
  descripcion: string | null
  tipo_calculo: TipoCalculo
}

export interface CreateInductorTiempoDto {
  nombre: string
  descripcion?: string
  tipo_calculo: TipoCalculo
}

export interface UpdateInductorTiempoDto {
  nombre: string
  descripcion?: string
  tipo_calculo: TipoCalculo
}
