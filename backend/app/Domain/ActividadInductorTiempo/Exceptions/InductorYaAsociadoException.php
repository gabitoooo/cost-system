<?php

namespace App\Domain\ActividadInductorTiempo\Exceptions;

class InductorYaAsociadoException extends \RuntimeException
{
    public function __construct(int $actividadId, int $inductorId)
    {
        parent::__construct("El inductor {$inductorId} ya está asociado a la actividad {$actividadId}.");
    }
}
