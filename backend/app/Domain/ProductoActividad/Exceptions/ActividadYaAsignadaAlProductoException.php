<?php

namespace App\Domain\ProductoActividad\Exceptions;

class ActividadYaAsignadaAlProductoException extends \RuntimeException
{
    public function __construct(int $actividadId)
    {
        parent::__construct("La actividad con id {$actividadId} ya está asignada al producto.");
    }
}
