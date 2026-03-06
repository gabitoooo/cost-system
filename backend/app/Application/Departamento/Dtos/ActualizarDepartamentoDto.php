<?php

namespace App\Application\Departamento\Dtos;

class ActualizarDepartamentoDto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $nombre,
    ) {}
}
