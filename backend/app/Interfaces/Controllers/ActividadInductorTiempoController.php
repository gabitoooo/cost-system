<?php

namespace App\Interfaces\Controllers;

use App\Application\ActividadInductorTiempo\ActualizarInductorActividadUseCase;
use App\Application\ActividadInductorTiempo\AsociarInductorUseCase;
use App\Application\ActividadInductorTiempo\Dtos\ActualizarInductorActividadDto;
use App\Application\ActividadInductorTiempo\Dtos\AsociarInductorDto;
use App\Application\ActividadInductorTiempo\EliminarInductorActividadUseCase;
use App\Application\ActividadInductorTiempo\ListarInductoresActividadUseCase;
use App\Domain\Actividad\Exceptions\ActividadNoEncontradaException;
use App\Domain\ActividadInductorTiempo\Exceptions\AsociacionNoEncontradaException;
use App\Domain\ActividadInductorTiempo\Exceptions\InductorYaAsociadoException;
use App\Domain\ActividadInductorTiempo\Exceptions\TamanoLoteRequeridoException;
use App\Domain\InductorTiempo\Exceptions\InductorTiempoNoEncontradoException;
use App\Interfaces\Requests\ActividadInductorTiempo\StoreActividadInductorRequest;
use App\Interfaces\Requests\ActividadInductorTiempo\UpdateActividadInductorRequest;
use App\Interfaces\Resources\ActividadInductorTiempo\ActividadInductorTiempoResource;
use Illuminate\Http\JsonResponse;

class ActividadInductorTiempoController extends Controller
{
    public function __construct(
        private readonly ListarInductoresActividadUseCase  $listar,
        private readonly AsociarInductorUseCase            $asociar,
        private readonly ActualizarInductorActividadUseCase $actualizar,
        private readonly EliminarInductorActividadUseCase  $eliminar,
    ) {}

    public function index(int $actividadId): JsonResponse
    {
        try {
            $items = $this->listar->ejecutar($actividadId);
            return response()->json([
                'data' => array_map(fn($a) => (new ActividadInductorTiempoResource($a))->toArray(), $items),
            ]);
        } catch (ActividadNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(StoreActividadInductorRequest $request, int $actividadId): JsonResponse
    {
        try {
            $dto = $this->asociar->ejecutar(new AsociarInductorDto(
                actividadId: $actividadId,
                inductorTiempoId: $request->validated('inductor_tiempo_id'),
                betaMinutos: $request->validated('beta_minutos'),
                tamanoLote: $request->validated('tamano_lote'),
            ));
            return (new ActividadInductorTiempoResource($dto))->toResponse(201);
        } catch (ActividadNoEncontradaException | InductorTiempoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (InductorYaAsociadoException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (TamanoLoteRequeridoException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function update(UpdateActividadInductorRequest $request, int $actividadId, int $inductorId): JsonResponse
    {
        try {
            $dto = $this->actualizar->ejecutar(new ActualizarInductorActividadDto(
                actividadId: $actividadId,
                inductorTiempoId: $inductorId,
                betaMinutos: $request->validated('beta_minutos'),
                tamanoLote: $request->validated('tamano_lote'),
            ));
            return (new ActividadInductorTiempoResource($dto))->toResponse();
        } catch (AsociacionNoEncontradaException | InductorTiempoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (TamanoLoteRequeridoException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy(int $actividadId, int $inductorId): JsonResponse
    {
        try {
            $this->eliminar->ejecutar($actividadId, $inductorId);
            return response()->json(null, 204);
        } catch (AsociacionNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
