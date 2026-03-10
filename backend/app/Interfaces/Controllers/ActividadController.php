<?php

namespace App\Interfaces\Controllers;

use App\Application\Actividad\ActualizarActividadUseCase;
use App\Application\Actividad\CrearActividadUseCase;
use App\Application\Actividad\Dtos\ActualizarActividadDto;
use App\Application\Actividad\Dtos\CrearActividadDto;
use App\Application\Actividad\EliminarActividadUseCase;
use App\Application\Actividad\ListarActividadesUseCase;
use App\Application\Actividad\ListarTodasActividadesUseCase;
use App\Application\Actividad\MostrarActividadUseCase;
use App\Domain\Actividad\Exceptions\ActividadNoEncontradaException;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Interfaces\Requests\Actividad\StoreActividadRequest;
use App\Interfaces\Requests\Actividad\UpdateActividadRequest;
use App\Interfaces\Resources\Actividad\ActividadResource;
use Illuminate\Http\JsonResponse;

class ActividadController extends Controller
{
    public function __construct(
        private readonly ListarActividadesUseCase     $listar,
        private readonly ListarTodasActividadesUseCase $listarTodas,
        private readonly MostrarActividadUseCase      $mostrar,
        private readonly CrearActividadUseCase        $crear,
        private readonly ActualizarActividadUseCase   $actualizar,
        private readonly EliminarActividadUseCase     $eliminar,
    ) {}

    public function indexAll(): JsonResponse
    {
        $items = $this->listarTodas->ejecutar();
        return response()->json([
            'data' => array_map(fn($a) => (new ActividadResource($a))->toArray(), $items),
        ]);
    }

    public function index(int $grupoRecursoId): JsonResponse
    {
        try {
            $items = $this->listar->ejecutar($grupoRecursoId);
            return response()->json([
                'data' => array_map(fn($a) => (new ActividadResource($a))->toArray(), $items),
            ]);
        } catch (GrupoRecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $dto = $this->mostrar->ejecutar($id);
            return (new ActividadResource($dto))->toResponse();
        } catch (ActividadNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(StoreActividadRequest $request): JsonResponse
    {
        try {
            $dto = $this->crear->ejecutar(new CrearActividadDto(
                grupoRecursosId: $request->validated('grupo_recursos_id'),
                nombre: $request->validated('nombre'),
                tiempoBaseMinutos: $request->validated('tiempo_base_minutos'),
            ));
            return (new ActividadResource($dto))->toResponse(201);
        } catch (GrupoRecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(UpdateActividadRequest $request, int $id): JsonResponse
    {
        try {
            $dto = $this->actualizar->ejecutar(new ActualizarActividadDto(
                id: $id,
                nombre: $request->validated('nombre'),
                tiempoBaseMinutos: $request->validated('tiempo_base_minutos'),
            ));
            return (new ActividadResource($dto))->toResponse();
        } catch (ActividadNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->eliminar->ejecutar($id);
            return response()->json(null, 204);
        } catch (ActividadNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
