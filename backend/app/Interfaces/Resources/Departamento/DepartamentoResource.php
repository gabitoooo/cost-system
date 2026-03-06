<?php

namespace App\Interfaces\Resources\Departamento;

use App\Application\Departamento\Dtos\DepartamentoResultDto;
use Illuminate\Http\JsonResponse;

class DepartamentoResource
{
    public function __construct(private readonly DepartamentoResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'     => $this->dto->id,
            'nombre' => $this->dto->nombre,
        ];
    }

    public function toResponse(int $status = 200): JsonResponse
    {
        return response()->json(['data' => $this->toArray()], $status);
    }
}
