<?php

namespace App\Interfaces\Controllers;

use App\Application\ProductoActividad\AsignarActividadProductoUseCase;
use App\Application\ProductoActividad\Dtos\AsignarActividadProductoDto;
use App\Application\ProductoActividad\EliminarActividadProductoUseCase;
use App\Application\ProductoActividad\ListarActividadesProductoUseCase;
use App\Domain\Actividad\Exceptions\ActividadNoEncontradaException;
use App\Domain\Producto\Exceptions\ProductoNoEncontradoException;
use App\Domain\ProductoActividad\Exceptions\ActividadYaAsignadaAlProductoException;
use App\Domain\ProductoActividad\Exceptions\ProductoActividadNoEncontradaException;
use App\Interfaces\Requests\ProductoActividad\StoreProductoActividadRequest;
use App\Interfaces\Resources\ProductoActividad\ProductoActividadResource;
use Illuminate\Http\JsonResponse;

class ProductoActividadController extends Controller
{
    public function __construct(
        private readonly ListarActividadesProductoUseCase  $listar,
        private readonly AsignarActividadProductoUseCase   $asignar,
        private readonly EliminarActividadProductoUseCase  $eliminar,
    ) {}

    public function index(int $productoId): JsonResponse
    {
        try {
            $items = $this->listar->ejecutar($productoId);
            return response()->json([
                'data' => array_map(fn($pa) => (new ProductoActividadResource($pa))->toArray(), $items),
            ]);
        } catch (ProductoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(StoreProductoActividadRequest $request, int $productoId): JsonResponse
    {
        try {
            $dto = $this->asignar->ejecutar(new AsignarActividadProductoDto(
                productoId: $productoId,
                actividadId: $request->validated('actividad_id'),
            ));
            return (new ProductoActividadResource($dto))->toResponse(201);
        } catch (ProductoNoEncontradoException | ActividadNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (ActividadYaAsignadaAlProductoException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }

    public function destroy(int $productoId, int $actividadId): JsonResponse
    {
        try {
            $this->eliminar->ejecutar($productoId, $actividadId);
            return response()->json(null, 204);
        } catch (ProductoActividadNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
