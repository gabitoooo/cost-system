<?php

namespace App\Domain\RecursoCompartido\Exceptions;

class RecursoCompartidoNoEncontradoException extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Recurso compartido con id {$id} no encontrado.");
    }
}
