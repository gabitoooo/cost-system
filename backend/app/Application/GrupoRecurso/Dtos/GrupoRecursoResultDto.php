<?php

namespace App\Application\GrupoRecurso\Dtos;

class GrupoRecursoResultDto
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $departamentoId,
        public readonly string $nombre,
        public readonly float  $capacidadPracticaMinutos,
        public readonly ?float $tasaCostoPorMinuto,
    ) {}
}
