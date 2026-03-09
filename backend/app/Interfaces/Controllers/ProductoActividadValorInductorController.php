<?php

namespace App\Interfaces\Controllers;

use App\Application\ProductoActividadValorInductor\ActualizarValorInductorUseCase;
use App\Application\ProductoActividadValorInductor\CrearValorInductorUseCase;
use App\Application\ProductoActividadValorInductor\Dtos\ActualizarValorInductorDto;
use App\Application\ProductoActividadValorInductor\Dtos\CrearValorInductorDto;
use App\Application\ProductoActividadValorInductor\EliminarValorInductorUseCase;
use App\Application\ProductoActividadValorInductor\ListarValoresInductorUseCase;
use App\Domain\InductorTiempo\Exceptions\InductorTiempoNoEncontradoException;
use App\Domain\ProductoActividad\Exceptions\ProductoActividadNoEncontradaException;
use App\Domain\ProductoActividadValorInductor\Exceptions\InductorYaConfiguradoException;
use App\Domain\ProductoActividadValorInductor\Exceptions\ValorInductorNoEncontradoException;
use App\Interfaces\Requests\ProductoActividadValorInductor\StoreValorInductorRequest;
use App\Interfaces\Requests\ProductoActividadValorInductor\UpdateValorInductorRequest;
use App\Interfaces\Resources\ProductoActividadValorInductor\ValorInductorResource;
use Illuminate\Http\JsonResponse;

class ProductoActividadValorInductorController extends Controller
{
    public function __construct(
        private readonly ListarValoresInductorUseCase    $listar,
        private readonly CrearValorInductorUseCase       $crear,
        private readonly ActualizarValorInductorUseCase  $actualizar,
        private readonly EliminarValorInductorUseCase    $eliminar,
    ) {}

    public function index(int $productoActividadId): JsonResponse
    {
        $items = $this->listar->ejecutar($productoActividadId);
        return response()->json([
            'data' => array_map(fn($v) => (new ValorInductorResource($v))->toArray(), $items),
        ]);
    }

    public function store(StoreValorInductorRequest $request, int $productoActividadId): JsonResponse
    {
        try {
            $dto = $this->crear->ejecutar(new CrearValorInductorDto(
                productoActividadId: $productoActividadId,
                inductorTiempoId: $request->validated('inductor_tiempo_id'),
                valorX: $request->validated('valor_x') !== null ? (float) $request->validated('valor_x') : null,
                betaMinutos: (float) $request->validated('beta_minutos'),
                tamanoLote: $request->validated('tamano_lote') !== null ? (float) $request->validated('tamano_lote') : null,
            ));
            return (new ValorInductorResource($dto))->toResponse(201);
        } catch (ProductoActividadNoEncontradaException | InductorTiempoNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (InductorYaConfiguradoException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }

    public function update(UpdateValorInductorRequest $request, int $productoActividadId, int $inductorId): JsonResponse
    {
        try {
            $dto = $this->actualizar->ejecutar(new ActualizarValorInductorDto(
                productoActividadId: $productoActividadId,
                inductorTiempoId: $inductorId,
                valorX: $request->validated('valor_x') !== null ? (float) $request->validated('valor_x') : null,
                betaMinutos: (float) $request->validated('beta_minutos'),
                tamanoLote: $request->validated('tamano_lote') !== null ? (float) $request->validated('tamano_lote') : null,
            ));
            return (new ValorInductorResource($dto))->toResponse();
        } catch (ValorInductorNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $productoActividadId, int $inductorId): JsonResponse
    {
        try {
            $this->eliminar->ejecutar($productoActividadId, $inductorId);
            return response()->json(null, 204);
        } catch (ValorInductorNoEncontradoException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
