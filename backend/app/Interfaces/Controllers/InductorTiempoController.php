<?php

namespace App\Interfaces\Controllers;

use App\Application\InductorTiempo\ActualizarInductorTiempoUseCase;
use App\Application\InductorTiempo\CrearInductorTiempoUseCase;
use App\Application\InductorTiempo\Dtos\ActualizarInductorTiempoDto;
use App\Application\InductorTiempo\Dtos\CrearInductorTiempoDto;
use App\Application\InductorTiempo\EliminarInductorTiempoUseCase;
use App\Application\InductorTiempo\ListarInductoresTiempoUseCase;
use App\Application\InductorTiempo\MostrarInductorTiempoUseCase;
use App\Domain\InductorTiempo\Exceptions\InductorTiempoNoEncontradoException;
use App\Interfaces\Requests\InductorTiempo\StoreInductorTiempoRequest;
use App\Interfaces\Requests\InductorTiempo\UpdateInductorTiempoRequest;
use App\Interfaces\Resources\InductorTiempo\InductorTiempoResource;
use Illuminate\Http\JsonResponse;

class InductorTiempoController extends Controller
{
    public function __construct(
        private readonly ListarInductoresTiempoUseCase   $listar,
        private readonly MostrarInductorTiempoUseCase    $mostrar,
        private readonly CrearInductorTiempoUseCase      $crear,
        private readonly ActualizarInductorTiempoUseCase $actualizar,
        private readonly EliminarInductorTiempoUseCase   $eliminar,
    ) {}

    public function index(): JsonResponse
    {
        $items = $this->listar->ejecutar();

        return response()->json([
            'data' => array_map(fn($i) => (new InductorTiempoResource($i))->toArray(), $items),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $dto = $this->mostrar->ejecutar($id);
            return (new InductorTiempoResource($dto))->toResponse();
        } catch (InductorTiempoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(StoreInductorTiempoRequest $request): JsonResponse
    {
        $dto = $this->crear->ejecutar(new CrearInductorTiempoDto(
            nombre: $request->validated('nombre'),
            descripcion: $request->validated('descripcion'),
            tipoCalculo: $request->validated('tipo_calculo'),
        ));

        return (new InductorTiempoResource($dto))->toResponse(201);
    }

    public function update(UpdateInductorTiempoRequest $request, int $id): JsonResponse
    {
        try {
            $dto = $this->actualizar->ejecutar(new ActualizarInductorTiempoDto(
                id: $id,
                nombre: $request->validated('nombre'),
                descripcion: $request->validated('descripcion'),
                tipoCalculo: $request->validated('tipo_calculo'),
            ));
            return (new InductorTiempoResource($dto))->toResponse();
        } catch (InductorTiempoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->eliminar->ejecutar($id);
            return response()->json(null, 204);
        } catch (InductorTiempoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
