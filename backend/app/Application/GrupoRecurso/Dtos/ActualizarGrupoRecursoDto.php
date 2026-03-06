<?php

namespace App\Application\GrupoRecurso\Dtos;

class ActualizarGrupoRecursoDto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $nombre,
        public readonly float  $capacidadPracticaMinutos,
    ) {}
}
