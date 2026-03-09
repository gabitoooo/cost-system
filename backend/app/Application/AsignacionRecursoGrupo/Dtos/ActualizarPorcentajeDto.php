<?php

namespace App\Application\AsignacionRecursoGrupo\Dtos;

class ActualizarPorcentajeDto
{
    public function __construct(
        public readonly int   $grupoRecursosId,
        public readonly int   $recursoId,
        public readonly float $porcentaje,
    ) {}
}
