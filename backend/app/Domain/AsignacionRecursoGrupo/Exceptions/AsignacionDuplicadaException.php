<?php

namespace App\Domain\AsignacionRecursoGrupo\Exceptions;

class AsignacionDuplicadaException extends \RuntimeException
{
    public function __construct(int $recursoId, int $grupoId)
    {
        parent::__construct(
            "El recurso {$recursoId} ya está asignado al grupo {$grupoId}."
        );
    }
}
