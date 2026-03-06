<?php

namespace App\Interfaces\Resources\AsignacionCostoCompartido;

use App\Application\AsignacionCostoCompartido\Dtos\AsignacionCostoCompartidoResultDto;

class AsignacionCostoCompartidoResource
{
    public function __construct(private readonly AsignacionCostoCompartidoResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'                    => $this->dto->id,
            'recurso_compartido_id' => $this->dto->recursoCompartidoId,
            'grupo_recursos_id'     => $this->dto->grupoRecursosId,
            'porcentaje'            => $this->dto->porcentaje,
        ];
    }
}
