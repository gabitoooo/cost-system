<?php

namespace App\Domain\Actividad\Exceptions;

class ActividadNoEncontradaException extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Actividad con id {$id} no encontrada.");
    }
}
