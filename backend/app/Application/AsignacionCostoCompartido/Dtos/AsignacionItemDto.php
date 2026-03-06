<?php

namespace App\Application\AsignacionCostoCompartido\Dtos;

class AsignacionItemDto
{
    public function __construct(
        public readonly int   $grupoRecursosId,
        public readonly float $porcentaje,
    ) {}
}
