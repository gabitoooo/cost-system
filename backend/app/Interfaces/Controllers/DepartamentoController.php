<?php

namespace App\Interfaces\Controllers;

use App\Application\Departamento\ActualizarDepartamentoUseCase;
use App\Application\Departamento\CrearDepartamentoUseCase;
use App\Application\Departamento\Dtos\ActualizarDepartamentoDto;
use App\Application\Departamento\Dtos\CrearDepartamentoDto;
use App\Application\Departamento\EliminarDepartamentoUseCase;
use App\Application\Departamento\ListarDepartamentosUseCase;
use App\Application\Departamento\MostrarDepartamentoUseCase;
use App\Domain\Departamento\Exceptions\DepartamentoNoEncontradoException;
use App\Interfaces\Requests\Departamento\StoreDepartamentoRequest;
use App\Interfaces\Requests\Departamento\UpdateDepartamentoRequest;
use App\Interfaces\Resources\Departamento\DepartamentoResource;
use Illuminate\Http\JsonResponse;

class DepartamentoController extends Controller
{
    public function __construct(
        private readonly ListarDepartamentosUseCase  $listar,
        private readonly MostrarDepartamentoUseCase  $mostrar,
        private readonly CrearDepartamentoUseCase    $crear,
        private readonly ActualizarDepartamentoUseCase $actualizar,
        private readonly EliminarDepartamentoUseCase $eliminar,
    ) {}

    public function index(): JsonResponse
    {
        $items = $this->listar->ejecutar();

        return response()->json([
            'data' => array_map(fn($d) => (new DepartamentoResource($d))->toArray(), $items),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $dto = $this->mostrar->ejecutar($id);
            return (new DepartamentoResource($dto))->toResponse();
        } catch (DepartamentoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(StoreDepartamentoRequest $request): JsonResponse
    {
        $dto = $this->crear->ejecutar(
            new CrearDepartamentoDto(nombre: $request->validated('nombre'))
        );

        return (new DepartamentoResource($dto))->toResponse(201);
    }

    public function update(UpdateDepartamentoRequest $request, int $id): JsonResponse
    {
        try {
            $dto = $this->actualizar->ejecutar(
                new ActualizarDepartamentoDto(id: $id, nombre: $request->validated('nombre'))
            );
            return (new DepartamentoResource($dto))->toResponse();
        } catch (DepartamentoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->eliminar->ejecutar($id);
            return response()->json(null, 204);
        } catch (DepartamentoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
