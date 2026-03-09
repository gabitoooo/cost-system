<?php

namespace App\Application\AsignacionRecursoGrupo\Dtos;

class AsignacionItemDto
{
    public function __construct(
        public readonly int   $grupoRecursosId,
        public readonly float $porcentaje,
    ) {}
}
