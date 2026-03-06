<?php

namespace App\Interfaces\Controllers;

use App\Application\AsignacionCostoCompartido\AsignarCostoCompartidoUseCase;
use App\Application\AsignacionCostoCompartido\Dtos\AsignacionItemDto;
use App\Application\AsignacionCostoCompartido\Dtos\AsignarCostoCompartidoDto;
use App\Application\AsignacionCostoCompartido\ListarAsignacionesUseCase;
use App\Domain\AsignacionCostoCompartido\Exceptions\PorcentajeInvalidoException;
use App\Domain\RecursoCompartido\Exceptions\RecursoCompartidoNoEncontradoException;
use App\Interfaces\Requests\AsignacionCostoCompartido\SyncAsignacionesRequest;
use App\Interfaces\Resources\AsignacionCostoCompartido\AsignacionCostoCompartidoResource;
use Illuminate\Http\JsonResponse;

class AsignacionCostoCompartidoController extends Controller
{
    public function __construct(
        private readonly ListarAsignacionesUseCase     $listar,
        private readonly AsignarCostoCompartidoUseCase $asignar,
    ) {}

    public function index(int $recursoCompartidoId): JsonResponse
    {
        try {
            $items = $this->listar->ejecutar($recursoCompartidoId);
            return response()->json([
                'data' => array_map(fn($a) => (new AsignacionCostoCompartidoResource($a))->toArray(), $items),
            ]);
        } catch (RecursoCompartidoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function sync(SyncAsignacionesRequest $request, int $recursoCompartidoId): JsonResponse
    {
        try {
            $asignaciones = array_map(
                fn($a) => new AsignacionItemDto(
                    grupoRecursosId: $a['grupo_recursos_id'],
                    porcentaje: $a['porcentaje'],
                ),
                $request->validated('asignaciones'),
            );

            $items = $this->asignar->ejecutar(new AsignarCostoCompartidoDto(
                recursoCompartidoId: $recursoCompartidoId,
                asignaciones: $asignaciones,
            ));

            return response()->json([
                'data' => array_map(fn($a) => (new AsignacionCostoCompartidoResource($a))->toArray(), $items),
            ]);
        } catch (RecursoCompartidoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (PorcentajeInvalidoException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
