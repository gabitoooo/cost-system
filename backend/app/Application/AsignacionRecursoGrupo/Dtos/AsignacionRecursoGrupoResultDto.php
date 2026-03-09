<?php

namespace App\Application\AsignacionRecursoGrupo\Dtos;

class AsignacionRecursoGrupoResultDto
{
    public function __construct(
        public readonly int   $id,
        public readonly int   $recursoId,
        public readonly int   $grupoRecursosId,
        public readonly float $porcentaje,
    ) {}
}
