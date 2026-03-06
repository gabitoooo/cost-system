<?php

namespace App\Interfaces\Resources\InductorTiempo;

use App\Application\InductorTiempo\Dtos\InductorTiempoResultDto;
use Illuminate\Http\JsonResponse;

class InductorTiempoResource
{
    public function __construct(private readonly InductorTiempoResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'           => $this->dto->id,
            'nombre'       => $this->dto->nombre,
            'descripcion'  => $this->dto->descripcion,
            'tipo_calculo' => $this->dto->tipoCalculo,
        ];
    }

    public function toResponse(int $status = 200): JsonResponse
    {
        return response()->json(['data' => $this->toArray()], $status);
    }
}
