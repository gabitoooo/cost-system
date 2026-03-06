<?php

namespace App\Domain\Recurso\Exceptions;

class RecursoNoEncontradoException extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Recurso con id {$id} no encontrado.");
    }
}
