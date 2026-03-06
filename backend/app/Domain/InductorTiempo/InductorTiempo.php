<?php

namespace App\Domain\InductorTiempo;

class InductorTiempo
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $nombre,
        public readonly ?string $descripcion,
        public readonly string  $tipoCalculo, // manual | por_cantidad | por_lote
    ) {}
}
