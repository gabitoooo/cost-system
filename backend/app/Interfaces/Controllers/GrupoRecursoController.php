<?php

namespace App\Interfaces\Controllers;

use App\Application\GrupoRecurso\ActualizarGrupoRecursoUseCase;
use App\Application\GrupoRecurso\CrearGrupoRecursoUseCase;
use App\Application\GrupoRecurso\Dtos\ActualizarGrupoRecursoDto;
use App\Application\GrupoRecurso\Dtos\CrearGrupoRecursoDto;
use App\Application\GrupoRecurso\EliminarGrupoRecursoUseCase;
use App\Application\GrupoRecurso\ListarGruposRecursoUseCase;
use App\Application\GrupoRecurso\MostrarGrupoRecursoUseCase;
use App\Domain\Departamento\Exceptions\DepartamentoNoEncontradoException;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Interfaces\Requests\GrupoRecurso\StoreGrupoRecursoRequest;
use App\Interfaces\Requests\GrupoRecurso\UpdateGrupoRecursoRequest;
use App\Interfaces\Resources\GrupoRecurso\GrupoRecursoResource;
use Illuminate\Http\JsonResponse;

class GrupoRecursoController extends Controller
{
    public function __construct(
        private readonly ListarGruposRecursoUseCase   $listar,
        private readonly MostrarGrupoRecursoUseCase   $mostrar,
        private readonly CrearGrupoRecursoUseCase     $crear,
        private readonly ActualizarGrupoRecursoUseCase $actualizar,
        private readonly EliminarGrupoRecursoUseCase  $eliminar,
    ) {}

    public function index(int $departamentoId): JsonResponse
    {
        try {
            $items = $this->listar->ejecutar($departamentoId);
            return response()->json([
                'data' => array_map(fn($g) => (new GrupoRecursoResource($g))->toArray(), $items),
            ]);
        } catch (DepartamentoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $dto = $this->mostrar->ejecutar($id);
            return (new GrupoRecursoResource($dto))->toResponse();
        } catch (GrupoRecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(StoreGrupoRecursoRequest $request): JsonResponse
    {
        try {
            $dto = $this->crear->ejecutar(new CrearGrupoRecursoDto(
                departamentoId: $request->validated('departamento_id'),
                nombre: $request->validated('nombre'),
                capacidadPracticaMinutos: $request->validated('capacidad_practica_minutos'),
            ));
            return (new GrupoRecursoResource($dto))->toResponse(201);
        } catch (DepartamentoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(UpdateGrupoRecursoRequest $request, int $id): JsonResponse
    {
        try {
            $dto = $this->actualizar->ejecutar(new ActualizarGrupoRecursoDto(
                id: $id,
                nombre: $request->validated('nombre'),
                capacidadPracticaMinutos: $request->validated('capacidad_practica_minutos'),
            ));
            return (new GrupoRecursoResource($dto))->toResponse();
        } catch (GrupoRecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->eliminar->ejecutar($id);
            return response()->json(null, 204);
        } catch (GrupoRecursoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
