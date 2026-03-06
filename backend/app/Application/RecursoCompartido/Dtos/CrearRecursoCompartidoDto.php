<?php

namespace App\Application\RecursoCompartido\Dtos;

class CrearRecursoCompartidoDto
{
    public function __construct(
        public readonly int    $departamentoId,
        public readonly string $nombre,
        public readonly string $tipo,
        public readonly float  $costoMensual,
    ) {}
}
