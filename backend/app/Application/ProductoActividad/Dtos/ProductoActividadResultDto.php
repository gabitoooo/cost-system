<?php

namespace App\Application\ProductoActividad\Dtos;

class ProductoActividadResultDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $productoId,
        public readonly int $actividadId,
    ) {}
}
