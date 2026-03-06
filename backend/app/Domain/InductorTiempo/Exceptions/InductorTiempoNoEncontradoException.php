<?php

namespace App\Domain\InductorTiempo\Exceptions;

class InductorTiempoNoEncontradoException extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Inductor de tiempo con id {$id} no encontrado.");
    }
}
