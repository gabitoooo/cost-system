<?php

namespace App\Application\ActividadInductorTiempo\Dtos;

class ActividadInductorTiempoResultDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $actividadId,
        public readonly int $inductorTiempoId,
    ) {}
}
