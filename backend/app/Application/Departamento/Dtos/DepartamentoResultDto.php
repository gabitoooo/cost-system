<?php

namespace App\Application\Departamento\Dtos;

class DepartamentoResultDto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $nombre,
    ) {}
}
