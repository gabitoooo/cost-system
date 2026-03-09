<?php

namespace App\Interfaces\Resources\CategoriaProducto;

use App\Application\CategoriaProducto\Dtos\CategoriaProductoResultDto;
use Illuminate\Http\JsonResponse;

class CategoriaProductoResource
{
    public function __construct(private readonly CategoriaProductoResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'          => $this->dto->id,
            'nombre'      => $this->dto->nombre,
            'descripcion' => $this->dto->descripcion,
        ];
    }

    public function toResponse(int $status = 200): JsonResponse
    {
        return response()->json(['data' => $this->toArray()], $status);
    }
}
