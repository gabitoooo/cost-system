<?php

namespace App\Application\ProductoActividad\Dtos;

class AsignarActividadProductoDto
{
    public function __construct(
        public readonly int $productoId,
        public readonly int $actividadId,
    ) {}
}
