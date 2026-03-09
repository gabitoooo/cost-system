<?php

namespace App\Interfaces\Resources\AsignacionRecursoGrupo;

use App\Application\AsignacionRecursoGrupo\Dtos\AsignacionRecursoGrupoResultDto;

class AsignacionRecursoGrupoResource
{
    public function __construct(private readonly AsignacionRecursoGrupoResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'               => $this->dto->id,
            'recurso_id'       => $this->dto->recursoId,
            'grupo_recursos_id' => $this->dto->grupoRecursosId,
            'porcentaje'       => $this->dto->porcentaje,
        ];
    }
}
