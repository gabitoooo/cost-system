<?php

namespace App\Application\ProductoActividadValorInductor\Dtos;

class ActualizarValorInductorDto
{
    public function __construct(
        public readonly int    $productoActividadId,
        public readonly int    $inductorTiempoId,
        public readonly ?float $valorX,
        public readonly float  $betaMinutos,
        public readonly ?float $tamanoLote,
    ) {}
}
