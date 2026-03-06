<?php

namespace App\Application\RecursoCompartido\Dtos;

class RecursoCompartidoResultDto
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $departamentoId,
        public readonly string $nombre,
        public readonly string $tipo,
        public readonly float  $costoMensual,
    ) {}
}
