<?php

namespace App\Application\AsignacionCostoCompartido\Dtos;

class AsignacionCostoCompartidoResultDto
{
    public function __construct(
        public readonly int   $id,
        public readonly int   $recursoCompartidoId,
        public readonly int   $grupoRecursosId,
        public readonly float $porcentaje,
    ) {}
}
