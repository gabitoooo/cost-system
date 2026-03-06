<?php

namespace App\Interfaces\Controllers;

use App\Application\RecursoCompartido\ActualizarRecursoCompartidoUseCase;
use App\Application\RecursoCompartido\CrearRecursoCompartidoUseCase;
use App\Application\RecursoCompartido\Dtos\ActualizarRecursoCompartidoDto;
use App\Application\RecursoCompartido\Dtos\CrearRecursoCompartidoDto;
use App\Application\RecursoCompartido\EliminarRecursoCompartidoUseCase;
use App\Application\RecursoCompartido\ListarRecursosCompartidosUseCase;
use App\Domain\Departamento\Exceptions\DepartamentoNoEncontradoException;
use App\Domain\RecursoCompartido\Exceptions\RecursoCompartidoNoEncontradoException;
use App\Interfaces\Requests\RecursoCompartido\StoreRecursoCompartidoRequest;
use App\Interfaces\Requests\RecursoCompartido\UpdateRecursoCompartidoRequest;
use App\Interfaces\Resources\RecursoCompartido\RecursoCompartidoResource;
use Illuminate\Http\JsonResponse;

class RecursoCompartidoController extends Controller
{
    public function __construct(
        private readonly ListarRecursosCompartidosUseCase    $listar,
        private readonly CrearRecursoCompartidoUseCase       $crear,
        private readonly ActualizarRecursoCompartidoUseCase  $actualizar,
        private readonly EliminarRecursoCompartidoUseCase    $eliminar,
    ) {}

    public function index(int $departamentoId): JsonResponse
    {
        try {
            $items = $this->listar->ejecutar($departamentoId);
            return response()->json([
                'data' => array_map(fn($r) => (new RecursoCompartidoResource($r))->toArray(), $items),
            ]);
        } catch (DepartamentoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(StoreRecursoCompartidoRequest $request): JsonResponse
    {
        try {
            $dto = $this->crear->ejecutar(new CrearRecursoCompartidoDto(
                departamentoId: $request->validated('departamento_id'),
                nombre: $request->validated('nombre'),
                tipo: $request->validated('tipo'),
                costoMensual: $request->validated('costo_mensual'),
            ));
            return (new RecursoCompartidoResource($dto))->toResponse(201);
        } catch (DepartamentoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(UpdateRecursoCompartidoRequest $request, int $id): JsonResponse
    {
        try {
            $dto = $this->actualizar->ejecutar(new ActualizarRecursoCompartidoDto(
                id: $id,
                nombre: $request->validated('nombre'),
                tipo: $request->validated('tipo'),
                costoMensual: $request->validated('costo_mensual'),
            ));
            return (new RecursoCompartidoResource($dto))->toResponse();
        } catch (RecursoCompartidoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->eliminar->ejecutar($id);
            return response()->json(null, 204);
        } catch (RecursoCompartidoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
