export interface Producto {
  id: number
  categoria_id: number | null
  nombre: string
  descripcion: string | null
  costo_material_directo: number
}

export interface ProductoActividad {
  id: number          // producto_actividad_id
  producto_id: number
  actividad_id: number
  actividad_nombre?: string
  tiempo_base_minutos?: number
}

export interface ProductoActividadValorInductor {
  id: number
  producto_actividad_id: number
  inductor_tiempo_id: number
  beta_minutos: number
  valor_x: number | null
  tamano_lote: number | null
}

// ── Costo TDABC ──────────────────────────────────────────────────────────────

export interface InductorCostoDetalle {
  nombre: string
  tipo_calculo: 'MANUAL' | 'POR_CANTIDAD' | 'POR_LOTE'
  beta_minutos: number
  valor_x: number | null
  minutos_aportados: number
  tamano_lote: number | null
}

export interface ActividadCostoDetalle {
  actividad_id: number
  nombre: string
  tiempo_base_minutos: number
  tasa_costo_por_minuto: number
  inductores: InductorCostoDetalle[]
  tiempo_total_minutos: number
  costo_actividad: number
}

export interface CostoTdabcResult {
  instantanea_id: number
  producto_id: number
  cantidad_pedido: number
  costo_material_directo: number
  costo_indirecto: number
  costo_unitario: number
  costo_total: number
  detalle_actividades: ActividadCostoDetalle[]
}

// ── DTOs ─────────────────────────────────────────────────────────────────────

export interface CreateProductoDto {
  nombre: string
  descripcion?: string
  costo_material_directo: number
  categoria_id?: number
}

export interface UpdateProductoDto {
  nombre: string
  descripcion?: string
  costo_material_directo: number
  categoria_id?: number
}

export interface CreateProductoActividadValorInductorDto {
  inductor_tiempo_id: number
  beta_minutos: number
  valor_x?: number
  tamano_lote?: number
}

export interface UpdateProductoActividadValorInductorDto {
  beta_minutos: number
  valor_x?: number
  tamano_lote?: number
}
