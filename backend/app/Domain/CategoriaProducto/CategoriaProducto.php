<?php

namespace App\Domain\CategoriaProducto;

class CategoriaProducto
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $nombre,
        public readonly ?string $descripcion,
    ) {}
}
