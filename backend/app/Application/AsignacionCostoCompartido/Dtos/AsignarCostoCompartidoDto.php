<?php

namespace App\Application\AsignacionCostoCompartido\Dtos;

class AsignarCostoCompartidoDto
{
    public function __construct(
        public readonly int   $recursoCompartidoId,
        /** @var AsignacionItemDto[] */
        public readonly array $asignaciones,
    ) {}
}
