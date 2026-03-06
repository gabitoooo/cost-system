<?php

namespace App\Domain\RecursoCompartido;

class RecursoCompartido
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $departamentoId,
        public readonly string $nombre,
        public readonly string $tipo,
        public readonly float  $costoMensual,
    ) {}
}
