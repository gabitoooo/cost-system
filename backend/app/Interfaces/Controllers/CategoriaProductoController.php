<?php

namespace App\Interfaces\Controllers;

use App\Application\CategoriaProducto\ActualizarCategoriaProductoUseCase;
use App\Application\CategoriaProducto\CrearCategoriaProductoUseCase;
use App\Application\CategoriaProducto\Dtos\ActualizarCategoriaProductoDto;
use App\Application\CategoriaProducto\Dtos\CrearCategoriaProductoDto;
use App\Application\CategoriaProducto\EliminarCategoriaProductoUseCase;
use App\Application\CategoriaProducto\ListarCategoriasProductoUseCase;
use App\Application\CategoriaProducto\MostrarCategoriaProductoUseCase;
use App\Domain\CategoriaProducto\Exceptions\CategoriaProductoNoEncontradaException;
use App\Interfaces\Requests\CategoriaProducto\StoreCategoriaProductoRequest;
use App\Interfaces\Requests\CategoriaProducto\UpdateCategoriaProductoRequest;
use App\Interfaces\Resources\CategoriaProducto\CategoriaProductoResource;
use Illuminate\Http\JsonResponse;

class CategoriaProductoController extends Controller
{
    public function __construct(
        private readonly ListarCategoriasProductoUseCase   $listar,
        private readonly MostrarCategoriaProductoUseCase   $mostrar,
        private readonly CrearCategoriaProductoUseCase     $crear,
        private readonly ActualizarCategoriaProductoUseCase $actualizar,
        private readonly EliminarCategoriaProductoUseCase  $eliminar,
    ) {}

    public function index(): JsonResponse
    {
        $items = $this->listar->ejecutar();
        return response()->json([
            'data' => array_map(fn($c) => (new CategoriaProductoResource($c))->toArray(), $items),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $dto = $this->mostrar->ejecutar($id);
            return (new CategoriaProductoResource($dto))->toResponse();
        } catch (CategoriaProductoNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(StoreCategoriaProductoRequest $request): JsonResponse
    {
        $dto = $this->crear->ejecutar(new CrearCategoriaProductoDto(
            nombre: $request->validated('nombre'),
            descripcion: $request->validated('descripcion'),
        ));
        return (new CategoriaProductoResource($dto))->toResponse(201);
    }

    public function update(UpdateCategoriaProductoRequest $request, int $id): JsonResponse
    {
        try {
            $dto = $this->actualizar->ejecutar(new ActualizarCategoriaProductoDto(
                id: $id,
                nombre: $request->validated('nombre'),
                descripcion: $request->validated('descripcion'),
            ));
            return (new CategoriaProductoResource($dto))->toResponse();
        } catch (CategoriaProductoNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->eliminar->ejecutar($id);
            return response()->json(null, 204);
        } catch (CategoriaProductoNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
