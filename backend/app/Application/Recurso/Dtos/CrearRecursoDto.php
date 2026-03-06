<?php

namespace App\Application\Recurso\Dtos;

class CrearRecursoDto
{
    public function __construct(
        public readonly int    $grupoRecursosId,
        public readonly string $nombre,
        public readonly string $tipo,
        public readonly float  $costoMensual,
    ) {}
}
