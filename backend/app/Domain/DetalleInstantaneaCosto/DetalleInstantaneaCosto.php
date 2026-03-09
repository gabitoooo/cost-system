<?php

namespace App\Domain\DetalleInstantaneaCosto;

class DetalleInstantaneaCosto
{
    public function __construct(
        public readonly int   $id,
        public readonly int   $instantaneaId,
        public readonly int   $actividadId,
        public readonly float $tiempoConsumidoMin,
        public readonly float $tasaCostoPorMinuto,
        public readonly float $costoActividad,
    ) {}
}
