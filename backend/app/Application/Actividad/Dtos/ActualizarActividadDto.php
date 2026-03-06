<?php

namespace App\Application\Actividad\Dtos;

class ActualizarActividadDto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $nombre,
        public readonly float  $tiempoBaseMinutos,
    ) {}
}
