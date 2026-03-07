<?php

namespace App\Domain\InductorTiempo;

use App\Domain\InductorTiempo\Enums\InductorTiempoTipoCalculoEnum;


class InductorTiempo
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $nombre,
        public readonly ?string $descripcion,
        public readonly InductorTiempoTipoCalculoEnum  $tipoCalculo, // manual | por_cantidad | por_lote
    ) {
        
    }
}
