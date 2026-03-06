<?php

namespace App\Domain\Actividad;

class Actividad
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $grupoRecursosId,
        public readonly string $nombre,
        public readonly float  $tiempoBaseMinutos,
    ) {}
}
