<?php

namespace App\Interfaces\Resources\Recurso;

use App\Application\Recurso\Dtos\RecursoResultDto;
use Illuminate\Http\JsonResponse;

class RecursoResource
{
    public function __construct(private readonly RecursoResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'               => $this->dto->id,
            'grupo_recursos_id' => $this->dto->grupoRecursosId,
            'nombre'           => $this->dto->nombre,
            'tipo'             => $this->dto->tipo,
            'costo_mensual'    => $this->dto->costoMensual,
        ];
    }

    public function toResponse(int $status = 200): JsonResponse
    {
        return response()->json(['data' => $this->toArray()], $status);
    }
}
