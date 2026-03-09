<?php

namespace App\Application\Costeo\Dtos;

class CalcularCostoProductoDto
{
    public function __construct(
        public readonly int $productoId,
        public readonly int $cantidadPedido,
    ) {}
}
