<?php

namespace App\Application\Costeo\Dtos;

class DetalleInductorCalculadoDto
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $tipoCalculo,
        public readonly float  $betaMinutos,
        public readonly float  $valorX,
        public readonly float  $minutosAportados,
        public readonly ?float $tamanoLote,
    ) {}
}
