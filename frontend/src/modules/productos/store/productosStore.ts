import { defineStore } from 'pinia'
import { ref } from 'vue'
import productosService from '../api/productosService'
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

export const useProductosStore = defineStore('productos', () => {
  const productos = ref<Producto[]>([])
  const selectedProducto = ref<Producto | null>(null)
  const actividades = ref<ProductoActividad[]>([])
  // Map: productoActividadId → valores de inductores
  const valoresInductor = ref<Map<number, ProductoActividadValorInductor[]>>(new Map())
  const costoResult = ref<CostoTdabcResult | null>(null)

  // ── CRUD Productos ──────────────────────────────────────────────────────────
  async function fetchAll() {
    const response = await productosService.getAll()
    productos.value = response.data
  }

  async function fetchById(id: number) {
    const response = await productosService.getById(id)
    selectedProducto.value = response.data
    return response.data
  }

  async function create(dto: CreateProductoDto) {
    const response = await productosService.create(dto)
    productos.value.push(response.data)
  }

  async function update(id: number, dto: UpdateProductoDto) {
    const response = await productosService.update(id, dto)
    const index = productos.value.findIndex((p) => p.id === id)
    if (index !== -1) productos.value[index] = response.data
    if (selectedProducto.value?.id === id) selectedProducto.value = response.data
  }

  async function remove(id: number) {
    await productosService.remove(id)
    productos.value = productos.value.filter((p) => p.id !== id)
    if (selectedProducto.value?.id === id) {
      selectedProducto.value = null
      actividades.value = []
    }
  }

  // ── Actividades del producto ────────────────────────────────────────────────
  async function fetchActividades(productoId: number) {
    const response = await productosService.getActividades(productoId)
    actividades.value = response.data
  }

  async function addActividad(productoId: number, actividadId: number) {
    const response = await productosService.addActividad(productoId, actividadId)
    actividades.value.push(response.data)
    return response.data
  }

  async function removeActividad(productoId: number, actividadId: number) {
    await productosService.removeActividad(productoId, actividadId)
    actividades.value = actividades.value.filter((a) => a.actividad_id !== actividadId)
    valoresInductor.value.delete(actividadId)
  }

  // ── Inductores de una asignación producto-actividad ─────────────────────────
  async function fetchValoresInductor(productoActividadId: number) {
    const response = await productosService.getValoresInductor(productoActividadId)
    valoresInductor.value.set(productoActividadId, response.data)
    return response.data
  }

  async function createValorInductor(
    productoActividadId: number,
    dto: CreateProductoActividadValorInductorDto,
  ) {
    const response = await productosService.createValorInductor(productoActividadId, dto)
    const current = valoresInductor.value.get(productoActividadId) ?? []
    valoresInductor.value.set(productoActividadId, [...current, response.data])
    return response.data
  }

  async function updateValorInductor(
    productoActividadId: number,
    inductorId: number,
    dto: UpdateProductoActividadValorInductorDto,
  ) {
    const response = await productosService.updateValorInductor(productoActividadId, inductorId, dto)
    const current = valoresInductor.value.get(productoActividadId) ?? []
    valoresInductor.value.set(
      productoActividadId,
      current.map((v) => (v.id === inductorId ? response.data : v)),
    )
    return response.data
  }

  async function removeValorInductor(productoActividadId: number, inductorId: number) {
    await productosService.removeValorInductor(productoActividadId, inductorId)
    const current = valoresInductor.value.get(productoActividadId) ?? []
    valoresInductor.value.set(
      productoActividadId,
      current.filter((v) => v.id !== inductorId),
    )
  }

  // ── Cálculo de costo TDABC ──────────────────────────────────────────────────
  async function calcularCosto(productoId: number, cantidadPedido: number) {
    const result = await productosService.calcularCosto(productoId, cantidadPedido)
    costoResult.value = result
    return result
  }

  function clearDetalle() {
    selectedProducto.value = null
    actividades.value = []
    valoresInductor.value = new Map()
    costoResult.value = null
  }

  return {
    productos,
    selectedProducto,
    actividades,
    valoresInductor,
    costoResult,
    fetchAll,
    fetchById,
    create,
    update,
    remove,
    fetchActividades,
    addActividad,
    removeActividad,
    fetchValoresInductor,
    createValorInductor,
    updateValorInductor,
    removeValorInductor,
    calcularCosto,
    clearDetalle,
  }
})
