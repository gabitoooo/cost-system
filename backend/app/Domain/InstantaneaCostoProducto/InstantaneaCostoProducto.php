<?php

namespace App\Domain\InstantaneaCostoProducto;

class InstantaneaCostoProducto
{
    public function __construct(
        public readonly int     $id,
        public readonly int     $productoId,
        public readonly ?int    $precioProductoId,
        public readonly int     $cantidadMinima,
        public readonly ?int    $cantidadMaxima,
        public readonly float   $costoIndirecto,
        public readonly float   $costoMaterialDirecto,
        public readonly float   $costoUnitario,
        public readonly float   $costoTotal,
        public readonly string  $calculadoEn,
    ) {}
}
