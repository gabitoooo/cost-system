<?php

namespace App\Application\InductorTiempo\Dtos;

use App\Domain\InductorTiempo\Enums\InductorTiempoTipoCalculoEnum;

class ActualizarInductorTiempoDto
{
    public function __construct(
        public readonly int                         $id,
        public readonly string                      $nombre,
        public readonly ?string                     $descripcion,
        public readonly InductorTiempoTipoCalculoEnum $tipoCalculo,
    ) {}
}
