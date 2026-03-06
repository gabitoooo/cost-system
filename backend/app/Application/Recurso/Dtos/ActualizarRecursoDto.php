<?php

namespace App\Application\Recurso\Dtos;

class ActualizarRecursoDto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $nombre,
        public readonly string $tipo,
        public readonly float  $costoMensual,
    ) {}
}
