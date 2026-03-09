<?php

namespace App\Application\Costeo\Dtos;

class CalcularCostoProductoResultDto
{
    public function __construct(
        public readonly int   $instantaneaId,
        public readonly int   $productoId,
        public readonly int   $cantidadPedido,
        public readonly float $costoMaterialDirecto,
        public readonly float $costoIndirecto,
        public readonly float $costoUnitario,
        public readonly float $costoTotal,
        public readonly array $detalleActividades,
    ) {}
}
