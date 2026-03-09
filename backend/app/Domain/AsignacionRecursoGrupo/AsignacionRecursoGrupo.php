<?php

namespace App\Domain\AsignacionRecursoGrupo;

class AsignacionRecursoGrupo
{
    public function __construct(
        public readonly int   $id,
        public readonly int   $recursoId,
        public readonly int   $grupoRecursosId,
        public readonly float $porcentaje,
    ) {}
}
