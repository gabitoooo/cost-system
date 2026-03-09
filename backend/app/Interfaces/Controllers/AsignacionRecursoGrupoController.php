<?php

namespace App\Interfaces\Controllers;

use App\Application\AsignacionRecursoGrupo\ActualizarPorcentajeAsignacionUseCase;
use App\Application\AsignacionRecursoGrupo\AsignarRecursoAGrupoUseCase;
use App\Application\AsignacionRecursoGrupo\AsignarRecursoGrupoUseCase;
use App\Application\AsignacionRecursoGrupo\Dtos\ActualizarPorcentajeDto;
use App\Application\AsignacionRecursoGrupo\Dtos\AsignacionItemDto;
use App\Application\AsignacionRecursoGrupo\Dtos\AsignarAGrupoDto;
use App\Application\AsignacionRecursoGrupo\Dtos\AsignarRecursoGrupoDto;
use App\Application\AsignacionRecursoGrupo\ListarAsignacionesUseCase;
use App\Application\AsignacionRecursoGrupo\ListarRecursosDeGrupoUseCase;
use App\Application\AsignacionRecursoGrupo\QuitarRecursoDeGrupoUseCase;
use App\Domain\AsignacionRecursoGrupo\Exceptions\AsignacionDuplicadaException;
use App\Domain\AsignacionRecursoGrupo\Exceptions\AsignacionNoEncontradaException;
use App\Domain\AsignacionRecursoGrupo\Exceptions\PorcentajeInvalidoException;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\Recurso\Exceptions\RecursoNoEncontradoException;
use App\Interfaces\Requests\AsignacionRecursoGrupo\ActualizarPorcentajeRequest;
use App\Interfaces\Requests\AsignacionRecursoGrupo\AsignarAGrupoRequest;
use App\Interfaces\Requests\AsignacionRecursoGrupo\SyncAsignacionesRequest;
use App\Interfaces\Resources\AsignacionRecursoGrupo\AsignacionRecursoGrupoResource;
use App\Interfaces\Resources\AsignacionRecursoGrupo\RecursoEnGrupoResource;
use Illuminate\Http\JsonResponse;

class AsignacionRecursoGrupoController extends Controller
{
    public function __construct(
        // Flujo centrado en recurso
        private readonly ListarAsignacionesUseCase            $listar,
        private readonly AsignarRecursoGrupoUseCase           $syncAsignaciones,
        // Flujo centrado en grupo
        private readonly ListarRecursosDeGrupoUseCase         $listarPorGrupo,
        private readonly AsignarRecursoAGrupoUseCase          $asignarAGrupo,
        private readonly ActualizarPorcentajeAsignacionUseCase $actualizarPorcentaje,
        private readonly QuitarRecursoDeGrupoUseCase          $quitarDeGrupo,
    ) {}

    // ---------------------------------------------------------------
    // Flujo centrado en recurso: GET /recursos/{id}/asignaciones
    // ---------------------------------------------------------------

    public function index(int $recursoId): JsonResponse
    {
        try {
            $items = $this->listar->ejecutar($recursoId);
            return response()->json([
                'data' => array_map(fn($a) => (new AsignacionRecursoGrupoResource($a))->toArray(), $items),
            ]);
        } catch (RecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    // PUT /recursos/{id}/asignaciones — reemplaza todas las asignaciones del recurso
    public function sync(SyncAsignacionesRequest $request, int $recursoId): JsonResponse
    {
        try {
            $asignaciones = array_map(
                fn($a) => new AsignacionItemDto(
                    grupoRecursosId: $a['grupo_recursos_id'],
                    porcentaje: $a['porcentaje'],
                ),
                $request->validated('asignaciones'),
            );

            $items = $this->syncAsignaciones->ejecutar(new AsignarRecursoGrupoDto(
                recursoId: $recursoId,
                asignaciones: $asignaciones,
            ));

            return response()->json([
                'data' => array_map(fn($a) => (new AsignacionRecursoGrupoResource($a))->toArray(), $items),
            ]);
        } catch (RecursoNoEncontradoException | GrupoRecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (PorcentajeInvalidoException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    // ---------------------------------------------------------------
    // Flujo centrado en grupo: /grupos-recursos/{grupoId}/recursos
    // ---------------------------------------------------------------

    // GET /grupos-recursos/{grupoId}/recursos
    public function indexPorGrupo(int $grupoId): JsonResponse
    {
        try {
            $items = $this->listarPorGrupo->ejecutar($grupoId);
            return response()->json([
                'data' => array_map(fn($r) => (new RecursoEnGrupoResource($r))->toArray(), $items),
            ]);
        } catch (GrupoRecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    // POST /grupos-recursos/{grupoId}/recursos
    public function storeEnGrupo(AsignarAGrupoRequest $request, int $grupoId): JsonResponse
    {
        try {
            $result = $this->asignarAGrupo->ejecutar(new AsignarAGrupoDto(
                grupoRecursosId: $grupoId,
                recursoId: $request->validated('recurso_id'),
                porcentaje: $request->validated('porcentaje'),
            ));
            return response()->json(['data' => (new RecursoEnGrupoResource($result))->toArray()], 201);
        } catch (GrupoRecursoNoEncontradoException | RecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (AsignacionDuplicadaException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (PorcentajeInvalidoException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    // PUT /grupos-recursos/{grupoId}/recursos/{recursoId}
    public function updateEnGrupo(ActualizarPorcentajeRequest $request, int $grupoId, int $recursoId): JsonResponse
    {
        try {
            $result = $this->actualizarPorcentaje->ejecutar(new ActualizarPorcentajeDto(
                grupoRecursosId: $grupoId,
                recursoId: $recursoId,
                porcentaje: $request->validated('porcentaje'),
            ));
            return response()->json(['data' => (new RecursoEnGrupoResource($result))->toArray()]);
        } catch (GrupoRecursoNoEncontradoException | RecursoNoEncontradoException | AsignacionNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (PorcentajeInvalidoException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    // DELETE /grupos-recursos/{grupoId}/recursos/{recursoId}
    public function destroyDeGrupo(int $grupoId, int $recursoId): JsonResponse
    {
        try {
            $this->quitarDeGrupo->ejecutar($grupoId, $recursoId);
            return response()->json(null, 204);
        } catch (GrupoRecursoNoEncontradoException | AsignacionNoEncontradaException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
