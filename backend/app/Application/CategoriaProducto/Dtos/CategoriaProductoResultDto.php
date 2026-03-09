<?php

namespace App\Application\CategoriaProducto\Dtos;

class CategoriaProductoResultDto
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $nombre,
        public readonly ?string $descripcion,
    ) {}
}
