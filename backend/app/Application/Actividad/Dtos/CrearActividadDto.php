<?php

namespace App\Application\Actividad\Dtos;

class CrearActividadDto
{
    public function __construct(
        public readonly int    $grupoRecursosId,
        public readonly string $nombre,
        public readonly float  $tiempoBaseMinutos,
    ) {}
}
