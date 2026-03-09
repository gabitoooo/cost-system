<?php

namespace App\Domain\AsignacionRecursoGrupo\Exceptions;

class PorcentajeInvalidoException extends \RuntimeException
{
    public function __construct(float $total)
    {
        $exceso = round($total - 100, 2);
        parent::__construct(
            "La suma de porcentajes es {$total}%. Se excede en {$exceso}%."
        );
    }
}
