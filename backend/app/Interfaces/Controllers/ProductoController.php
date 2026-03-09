<?php

namespace App\Interfaces\Controllers;

use App\Application\Costeo\CalcularCostoProductoUseCase;
use App\Application\Costeo\Dtos\CalcularCostoProductoDto;
use App\Application\Producto\ActualizarProductoUseCase;
use App\Application\Producto\CrearProductoUseCase;
use App\Application\Producto\Dtos\ActualizarProductoDto;
use App\Application\Producto\Dtos\CrearProductoDto;
use App\Application\Producto\EliminarProductoUseCase;
use App\Application\Producto\ListarProductosUseCase;
use App\Application\Producto\MostrarProductoUseCase;
use App\Domain\CategoriaProducto\Exceptions\CategoriaProductoNoEncontradaException;
use App\Domain\Producto\Exceptions\ProductoNoEncontradoException;
use App\Interfaces\Requests\Costeo\CalcularCostoProductoRequest;
use App\Interfaces\Requests\Producto\StoreProductoRequest;
use App\Interfaces\Requests\Producto\UpdateProductoRequest;
use App\Interfaces\Resources\Costeo\CostoProductoResource;
use App\Interfaces\Resources\Producto\ProductoResource;
use Illuminate\Http\JsonResponse;

class ProductoController extends Controller
{
    public function __construct(
        private readonly ListarProductosUseCase       $listar,
        private readonly MostrarProductoUseCase       $mostrar,
        private readonly CrearProductoUseCase         $crear,
        private readonly ActualizarProductoUseCase    $actualizar,
        private readonly EliminarProductoUseCase      $eliminar,
        private readonly CalcularCostoProductoUseCase $calcularCosto,
    ) {}

    public function index(): JsonResponse
    {
        $items = $this->listar->ejecutar();
        return response()->json([
            'data' => array_map(fn($p) => (new ProductoResource($p))->toArray(), $items),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $dto = $this->mostrar->ejecutar($id);
            return (new ProductoResource($dto))->toResponse();
        } catch (ProductoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(StoreProductoRequest $request): JsonResponse
    {
        try {
            $dto = $this->crear->ejecutar(new CrearProductoDto(
                categoriaId: $request->validated('categoria_id'),
                nombre: $request->validated('nombre'),
                descripcion: $request->validated('descripcion'),
                costoMaterialDirecto: (float) $request->validated('costo_material_directo'),
            ));
            return (new ProductoResource($dto))->toResponse(201);
        } catch (CategoriaProductoNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(UpdateProductoRequest $request, int $id): JsonResponse
    {
        try {
            $dto = $this->actualizar->ejecutar(new ActualizarProductoDto(
                id: $id,
                categoriaId: $request->validated('categoria_id'),
                nombre: $request->validated('nombre'),
                descripcion: $request->validated('descripcion'),
                costoMaterialDirecto: (float) $request->validated('costo_material_directo'),
            ));
            return (new ProductoResource($dto))->toResponse();
        } catch (ProductoNoEncontradoException | CategoriaProductoNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->eliminar->ejecutar($id);
            return response()->json(null, 204);
        } catch (ProductoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function calcularCosto(CalcularCostoProductoRequest $request, int $id): JsonResponse
    {
        try {
            $resultado = $this->calcularCosto->ejecutar(new CalcularCostoProductoDto(
                productoId: $id,
                cantidadPedido: (int) $request->validated('cantidad_pedido'),
            ));
            return (new CostoProductoResource($resultado))->toResponse();
        } catch (ProductoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
