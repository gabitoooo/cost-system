<?php

namespace App\Application\ActividadInductorTiempo\Dtos;

class ActualizarInductorActividadDto
{
    public function __construct(
        public readonly int    $actividadId,
        public readonly int    $inductorTiempoId,
        public readonly float  $betaMinutos,
        public readonly ?float $tamanoLote,
    ) {}
}
