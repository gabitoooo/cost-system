<?php

namespace App\Domain\AsignacionCostoCompartido\Exceptions;

class PorcentajeInvalidoException extends \RuntimeException
{
    public function __construct(float $total)
    {
        $faltante = round(100 - $total, 2);
        parent::__construct(
            "La suma de porcentajes es {$total}%. " .
            ($faltante > 0
                ? "Faltan {$faltante}% por asignar."
                : "Se excede en " . abs($faltante) . "%.")
        );
    }
}
