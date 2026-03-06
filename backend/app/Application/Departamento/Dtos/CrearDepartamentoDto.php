<?php

namespace App\Application\Departamento\Dtos;

class CrearDepartamentoDto
{
    public function __construct(
        public readonly string $nombre,
    ) {}
}
