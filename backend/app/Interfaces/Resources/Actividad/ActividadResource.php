<?php

namespace App\Interfaces\Resources\Actividad;

use App\Application\Actividad\Dtos\ActividadResultDto;
use Illuminate\Http\JsonResponse;

class ActividadResource
{
    public function __construct(private readonly ActividadResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'                  => $this->dto->id,
            'grupo_recursos_id'   => $this->dto->grupoRecursosId,
            'nombre'              => $this->dto->nombre,
            'tiempo_base_minutos' => $this->dto->tiempoBaseMinutos,
        ];
    }

    public function toResponse(int $status = 200): JsonResponse
    {
        return response()->json(['data' => $this->toArray()], $status);
    }
}
