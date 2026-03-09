<?php

namespace App\Domain\ProductoActividad\Exceptions;

class ProductoActividadNoEncontradaException extends \RuntimeException
{
    public function __construct(int $actividadId, int $productoId)
    {
        parent::__construct("Actividad con id {$actividadId} no asignada al producto {$productoId}.");
    }
}
