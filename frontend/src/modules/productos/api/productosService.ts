import { http } from '@/app/api/http'
import type {
  Producto,
  ProductoActividad,
  ProductoActividadValorInductor,
  CostoTdabcResult,
  CreateProductoDto,
  UpdateProductoDto,
  CreateProductoActividadValorInductorDto,
  UpdateProductoActividadValorInductorDto,
} from '../types'

const productosService = {
  // ── CRUD Productos ──────────────────────────────────────────────────────────
  getAll(): Promise<{ data: Producto[] }> {
    return http.get('/productos')
  },

  getById(id: number): Promise<{ data: Producto }> {
    return http.get(`/productos/${id}`)
  },

  create(dto: CreateProductoDto): Promise<{ data: Producto }> {
    return http.post('/productos', dto)
  },

  update(id: number, dto: UpdateProductoDto): Promise<{ data: Producto }> {
    return http.put(`/productos/${id}`, dto)
  },

  remove(id: number): Promise<void> {
    return http.delete(`/productos/${id}`)
  },

  // ── Actividades del producto ────────────────────────────────────────────────
  getActividades(productoId: number): Promise<{ data: ProductoActividad[] }> {
    return http.get(`/productos/${productoId}/actividades`)
  },

  addActividad(productoId: number, actividadId: number): Promise<{ data: ProductoActividad }> {
    return http.post(`/productos/${productoId}/actividades`, { actividad_id: actividadId })
  },

  removeActividad(productoId: number, actividadId: number): Promise<void> {
    return http.delete(`/productos/${productoId}/actividades/${actividadId}`)
  },

  // ── Inductores de una asignación producto-actividad ─────────────────────────
  getValoresInductor(productoActividadId: number): Promise<{ data: ProductoActividadValorInductor[] }> {
    return http.get(`/producto-actividades/${productoActividadId}/inductores`)
  },

  createValorInductor(
    productoActividadId: number,
    dto: CreateProductoActividadValorInductorDto,
  ): Promise<{ data: ProductoActividadValorInductor }> {
    return http.post(`/producto-actividades/${productoActividadId}/inductores`, dto)
  },

  updateValorInductor(
    productoActividadId: number,
    inductorId: number,
    dto: UpdateProductoActividadValorInductorDto,
  ): Promise<{ data: ProductoActividadValorInductor }> {
    return http.put(`/producto-actividades/${productoActividadId}/inductores/${inductorId}`, dto)
  },

  removeValorInductor(productoActividadId: number, inductorId: number): Promise<void> {
    return http.delete(`/producto-actividades/${productoActividadId}/inductores/${inductorId}`)
  },

  // ── Cálculo de costo TDABC ──────────────────────────────────────────────────
  calcularCosto(productoId: number, cantidadPedido: number): Promise<CostoTdabcResult> {
    return http.post(`/productos/${productoId}/calcular-costo`, { cantidad_pedido: cantidadPedido })
  },
}

export default productosService
