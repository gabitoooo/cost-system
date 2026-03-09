<?php

namespace App\Domain\ActividadInductorTiempo;

class ActividadInductorTiempo
{
    public function __construct(
        public readonly int $id,
        public readonly int $actividadId,
        public readonly int $inductorTiempoId,
    ) {}

    public static function crear(
        int $actividadId,
        int $inductorTiempoId,
    ): self {
        return new self(
            id: 0,
            actividadId: $actividadId,
            inductorTiempoId: $inductorTiempoId,
        );
    }
}
