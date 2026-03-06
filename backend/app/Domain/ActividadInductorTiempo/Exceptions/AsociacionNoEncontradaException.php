<?php

namespace App\Domain\ActividadInductorTiempo\Exceptions;

class AsociacionNoEncontradaException extends \RuntimeException
{
    public function __construct(int $actividadId, int $inductorId)
    {
        parent::__construct("No existe asociación entre la actividad {$actividadId} y el inductor {$inductorId}.");
    }
}
