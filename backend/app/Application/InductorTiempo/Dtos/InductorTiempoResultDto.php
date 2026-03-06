<?php

namespace App\Application\InductorTiempo\Dtos;

class InductorTiempoResultDto
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $nombre,
        public readonly ?string $descripcion,
        public readonly string  $tipoCalculo,
    ) {}
}
