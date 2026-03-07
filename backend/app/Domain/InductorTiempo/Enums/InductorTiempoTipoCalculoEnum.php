<?php

namespace App\Domain\InductorTiempo\Enums;

enum InductorTiempoTipoCalculoEnum: string
{
    case MANUAL = 'MANUAL';
    case POR_CANTIDAD = 'POR_CANTIDAD';
    case POR_LOTE = 'POR_LOTE';
}
