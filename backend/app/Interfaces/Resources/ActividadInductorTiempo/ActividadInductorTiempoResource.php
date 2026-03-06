<?php

namespace App\Interfaces\Resources\ActividadInductorTiempo;

use App\Application\ActividadInductorTiempo\Dtos\ActividadInductorTiempoResultDto;
use Illuminate\Http\JsonResponse;

class ActividadInductorTiempoResource
{
    public function __construct(private readonly ActividadInductorTiempoResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'                => $this->dto->id,
            'actividad_id'      => $this->dto->actividadId,
            'inductor_tiempo_id' => $this->dto->inductorTiempoId,
            'beta_minutos'      => $this->dto->betaMinutos,
            'tamano_lote'       => $this->dto->tamanoLote,
        ];
    }

    public function toResponse(int $status = 200): JsonResponse
    {
        return response()->json(['data' => $this->toArray()], $status);
    }
}
