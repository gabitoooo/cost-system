<?php

namespace App\Interfaces\Resources\RecursoCompartido;

use App\Application\RecursoCompartido\Dtos\RecursoCompartidoResultDto;
use Illuminate\Http\JsonResponse;

class RecursoCompartidoResource
{
    public function __construct(private readonly RecursoCompartidoResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'              => $this->dto->id,
            'departamento_id' => $this->dto->departamentoId,
            'nombre'          => $this->dto->nombre,
            'tipo'            => $this->dto->tipo,
            'costo_mensual'   => $this->dto->costoMensual,
        ];
    }

    public function toResponse(int $status = 200): JsonResponse
    {
        return response()->json(['data' => $this->toArray()], $status);
    }
}
