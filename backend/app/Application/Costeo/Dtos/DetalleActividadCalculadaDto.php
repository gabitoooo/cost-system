<?php

namespace App\Application\Costeo\Dtos;

class DetalleActividadCalculadaDto
{
    public function __construct(
        public readonly int    $actividadId,
        public readonly string $nombre,
        public readonly float  $tiempoBaseMinutos,
        public readonly float  $tasaCostoPorMinuto,
        public readonly array  $inductores,
        public readonly float  $tiempoTotalMinutos,
        public readonly float  $costoActividad,
    ) {}
}
