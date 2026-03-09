<?php

namespace App\Application\AsignacionRecursoGrupo\Dtos;

class AsignarRecursoGrupoDto
{
    public function __construct(
        public readonly int   $recursoId,
        /** @var AsignacionItemDto[] */
        public readonly array $asignaciones,
    ) {}
}
