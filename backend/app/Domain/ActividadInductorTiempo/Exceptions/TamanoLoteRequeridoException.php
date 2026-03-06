<?php

namespace App\Domain\ActividadInductorTiempo\Exceptions;

class TamanoLoteRequeridoException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct("El campo tamano_lote es requerido cuando el tipo de cálculo del inductor es 'por_lote'.");
    }
}
