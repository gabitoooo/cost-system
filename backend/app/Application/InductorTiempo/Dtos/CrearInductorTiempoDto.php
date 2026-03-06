<?php

namespace App\Application\InductorTiempo\Dtos;

class CrearInductorTiempoDto
{
    public function __construct(
        public readonly string  $nombre,
        public readonly ?string $descripcion,
        public readonly string  $tipoCalculo,
    ) {}
}
