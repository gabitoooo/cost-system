<?php

namespace App\Domain\GrupoRecurso\Exceptions;

class GrupoRecursoNoEncontradoException extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Grupo de recursos con id {$id} no encontrado.");
    }
}
