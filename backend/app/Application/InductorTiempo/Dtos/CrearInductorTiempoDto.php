<?php

namespace App\Application\InductorTiempo\Dtos;

use App\Domain\InductorTiempo\Enums\InductorTiempoTipoCalculoEnum;

class CrearInductorTiempoDto
{
    public function __construct(
        public readonly string                      $nombre,
        public readonly ?string                     $descripcion,
        public readonly InductorTiempoTipoCalculoEnum $tipoCalculo,
    ) {}
}
