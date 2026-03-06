<?php

namespace App\Domain\AsignacionCostoCompartido;

class AsignacionCostoCompartido
{
    public function __construct(
        public readonly int   $id,
        public readonly int   $recursoCompartidoId,
        public readonly int   $grupoRecursosId,
        public readonly float $porcentaje,
    ) {}
}
