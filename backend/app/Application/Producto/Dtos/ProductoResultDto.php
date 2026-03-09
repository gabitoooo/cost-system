<?php

namespace App\Application\Producto\Dtos;

class ProductoResultDto
{
    public function __construct(
        public readonly int     $id,
        public readonly ?int    $categoriaId,
        public readonly string  $nombre,
        public readonly ?string $descripcion,
        public readonly float   $costoMaterialDirecto,
    ) {}
}
