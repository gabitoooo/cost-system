<?php

namespace App\Application\AsignacionRecursoGrupo\Dtos;

class RecursoEnGrupoResultDto
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $recursoId,
        public readonly string $nombre,
        public readonly string $tipo,
        public readonly float  $costoMensual,
        public readonly int    $grupoRecursosId,
        public readonly float  $porcentaje,
    ) {}
}
