<?php

namespace App\Domain\AsignacionRecursoGrupo\Exceptions;

class AsignacionNoEncontradaException extends \RuntimeException
{
    public function __construct(int $recursoId, int $grupoId)
    {
        parent::__construct(
            "No existe asignación del recurso {$recursoId} al grupo {$grupoId}."
        );
    }
}
