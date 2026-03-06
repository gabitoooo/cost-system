<?php

namespace App\Application\GrupoRecurso\Dtos;

class CrearGrupoRecursoDto
{
    public function __construct(
        public readonly int    $departamentoId,
        public readonly string $nombre,
        public readonly float  $capacidadPracticaMinutos,
    ) {}
}
