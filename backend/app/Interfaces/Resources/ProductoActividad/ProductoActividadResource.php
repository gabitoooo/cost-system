<?php

namespace App\Interfaces\Resources\ProductoActividad;

use App\Application\ProductoActividad\Dtos\ProductoActividadResultDto;
use Illuminate\Http\JsonResponse;

class ProductoActividadResource
{
    public function __construct(private readonly ProductoActividadResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'           => $this->dto->id,
            'producto_id'  => $this->dto->productoId,
            'actividad_id' => $this->dto->actividadId,
        ];
    }

    public function toResponse(int $status = 200): JsonResponse
    {
        return response()->json(['data' => $this->toArray()], $status);
    }
}
