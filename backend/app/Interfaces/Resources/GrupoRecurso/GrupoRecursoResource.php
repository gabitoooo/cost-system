<?php

namespace App\Interfaces\Resources\GrupoRecurso;

use App\Application\GrupoRecurso\Dtos\GrupoRecursoResultDto;
use Illuminate\Http\JsonResponse;

class GrupoRecursoResource
{
    public function __construct(private readonly GrupoRecursoResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'                        => $this->dto->id,
            'departamento_id'           => $this->dto->departamentoId,
            'nombre'                    => $this->dto->nombre,
            'capacidad_practica_minutos' => $this->dto->capacidadPracticaMinutos,
            'tasa_costo_por_minuto'     => $this->dto->tasaCostoPorMinuto,
        ];
    }

    public function toResponse(int $status = 200): JsonResponse
    {
        return response()->json(['data' => $this->toArray()], $status);
    }
}
