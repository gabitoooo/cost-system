<?php

namespace App\Domain\Usuario\Exceptions;

use RuntimeException;

class CredencialesInvalidasException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Credenciales inválidas.');
    }
}
