<?php

namespace App\Application\RecursoCompartido\Dtos;

class ActualizarRecursoCompartidoDto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $nombre,
        public readonly string $tipo,
        public readonly float  $costoMensual,
    ) {}
}
