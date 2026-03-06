<?php

namespace App\Application\Actividad\Dtos;

class ActividadResultDto
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $grupoRecursosId,
        public readonly string $nombre,
        public readonly float  $tiempoBaseMinutos,
    ) {}
}
