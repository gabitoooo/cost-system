<?php

namespace App\Interfaces\Resources\ProductoActividadValorInductor;

use App\Application\ProductoActividadValorInductor\Dtos\ValorInductorResultDto;
use Illuminate\Http\JsonResponse;

class ValorInductorResource
{
    public function __construct(private readonly ValorInductorResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'                    => $this->dto->id,
            'producto_actividad_id' => $this->dto->productoActividadId,
            'inductor_tiempo_id'    => $this->dto->inductorTiempoId,
            'valor_x'               => $this->dto->valorX,
            'beta_minutos'          => $this->dto->betaMinutos,
            'tamano_lote'           => $this->dto->tamanoLote,
        ];
    }

    public function toResponse(int $status = 200): JsonResponse
    {
        return response()->json(['data' => $this->toArray()], $status);
    }
}
