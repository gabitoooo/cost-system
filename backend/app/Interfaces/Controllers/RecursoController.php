<?php

namespace App\Interfaces\Controllers;

use App\Application\Recurso\ActualizarRecursoUseCase;
use App\Application\Recurso\CrearRecursoUseCase;
use App\Application\Recurso\Dtos\ActualizarRecursoDto;
use App\Application\Recurso\Dtos\CrearRecursoDto;
use App\Application\Recurso\EliminarRecursoUseCase;
use App\Application\Recurso\ListarRecursosUseCase;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\Recurso\Exceptions\RecursoNoEncontradoException;
use App\Interfaces\Requests\Recurso\StoreRecursoRequest;
use App\Interfaces\Requests\Recurso\UpdateRecursoRequest;
use App\Interfaces\Resources\Recurso\RecursoResource;
use Illuminate\Http\JsonResponse;

class RecursoController extends Controller
{
    public function __construct(
        private readonly ListarRecursosUseCase    $listar,
        private readonly CrearRecursoUseCase      $crear,
        private readonly ActualizarRecursoUseCase $actualizar,
        private readonly EliminarRecursoUseCase   $eliminar,
    ) {}

    public function index(int $grupoRecursoId): JsonResponse
    {
        try {
            $items = $this->listar->ejecutar($grupoRecursoId);
            return response()->json([
                'data' => array_map(fn($r) => (new RecursoResource($r))->toArray(), $items),
            ]);
        } catch (GrupoRecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(StoreRecursoRequest $request): JsonResponse
    {
        try {
            $dto = $this->crear->ejecutar(new CrearRecursoDto(
                grupoRecursosId: $request->validated('grupo_recursos_id'),
                nombre: $request->validated('nombre'),
                tipo: $request->validated('tipo'),
                costoMensual: $request->validated('costo_mensual'),
            ));
            return (new RecursoResource($dto))->toResponse(201);
        } catch (GrupoRecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(UpdateRecursoRequest $request, int $id): JsonResponse
    {
        try {
            $dto = $this->actualizar->ejecutar(new ActualizarRecursoDto(
                id: $id,
                nombre: $request->validated('nombre'),
                tipo: $request->validated('tipo'),
                costoMensual: $request->validated('costo_mensual'),
            ));
            return (new RecursoResource($dto))->toResponse();
        } catch (RecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->eliminar->ejecutar($id);
            return response()->json(null, 204);
        } catch (RecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
