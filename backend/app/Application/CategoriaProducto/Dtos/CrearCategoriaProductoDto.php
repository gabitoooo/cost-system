<?php

namespace App\Application\CategoriaProducto\Dtos;

class CrearCategoriaProductoDto
{
    public function __construct(
        public readonly string  $nombre,
        public readonly ?string $descripcion,
    ) {}
}
