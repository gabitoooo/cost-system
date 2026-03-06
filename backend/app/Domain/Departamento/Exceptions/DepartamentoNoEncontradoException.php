<?php

namespace App\Domain\Departamento\Exceptions;

class DepartamentoNoEncontradoException extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Departamento con id {$id} no encontrado.");
    }
}
