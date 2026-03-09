<?php

namespace App\Interfaces\Resources\Producto;

use App\Application\Producto\Dtos\ProductoResultDto;
use Illuminate\Http\JsonResponse;

class ProductoResource
{
    public function __construct(private readonly ProductoResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'                      => $this->dto->id,
            'categoria_id'            => $this->dto->categoriaId,
            'nombre'                  => $this->dto->nombre,
            'descripcion'             => $this->dto->descripcion,
            'costo_material_directo'  => $this->dto->costoMaterialDirecto,
        ];
    }

    public function toResponse(int $status = 200): JsonResponse
    {
        return response()->json(['data' => $this->toArray()], $status);
    }
}
