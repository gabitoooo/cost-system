<?php

namespace App\Domain\ProductoActividad;

class ProductoActividad
{
    public function __construct(
        public readonly int $id,
        public readonly int $productoId,
        public readonly int $actividadId,
    ) {}
}
