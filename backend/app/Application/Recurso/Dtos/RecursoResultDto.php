<?php

namespace App\Application\Recurso\Dtos;

class RecursoResultDto
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $grupoRecursosId,
        public readonly string $nombre,
        public readonly string $tipo,
        public readonly float  $costoMensual,
    ) {}
}
