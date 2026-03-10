export interface CategoriaProducto {
  id: number
  nombre: string
  descripcion: string | null
}

export interface CreateCategoriaProductoDto {
  nombre: string
  descripcion?: string
}

export interface UpdateCategoriaProductoDto {
  nombre: string
  descripcion?: string
}
