<?php

namespace App\Application\ProductoActividadValorInductor\Dtos;

class ValorInductorResultDto
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $productoActividadId,
        public readonly int    $inductorTiempoId,
        public readonly ?float $valorX,
        public readonly float  $betaMinutos,
        public readonly ?float $tamanoLote,
    ) {}
}
