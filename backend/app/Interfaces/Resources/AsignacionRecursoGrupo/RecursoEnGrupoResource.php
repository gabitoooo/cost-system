<?php

namespace App\Interfaces\Resources\AsignacionRecursoGrupo;

use App\Application\AsignacionRecursoGrupo\Dtos\RecursoEnGrupoResultDto;

class RecursoEnGrupoResource
{
    public function __construct(private readonly RecursoEnGrupoResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'id'               => $this->dto->id,
            'recurso_id'       => $this->dto->recursoId,
            'nombre'           => $this->dto->nombre,
            'tipo'             => $this->dto->tipo,
            'costo_mensual'    => $this->dto->costoMensual,
            'grupo_recursos_id' => $this->dto->grupoRecursosId,
            'porcentaje'       => $this->dto->porcentaje,
        ];
    }
}
