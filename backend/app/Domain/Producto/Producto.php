<?php

namespace App\Domain\Producto;

class Producto
{
    public function __construct(
        public readonly int     $id,
        public readonly ?int    $categoriaId,
        public readonly string  $nombre,
        public readonly ?string $descripcion,
        public readonly float   $costoMaterialDirecto,
    ) {}
}
