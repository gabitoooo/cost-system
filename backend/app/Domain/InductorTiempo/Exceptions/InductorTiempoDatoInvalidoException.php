<?php

namespace App\Domain\InductorTiempo\Exceptions;

class InductorTiempoDatoInvalidoException extends \RuntimeException
{
    public function __construct(String $valor, $error)
    {
        $message = json_encode([
            'valor' => $valor,
            'error' => $error
        ]);
        parent::__construct($message, 422);
    }
}
