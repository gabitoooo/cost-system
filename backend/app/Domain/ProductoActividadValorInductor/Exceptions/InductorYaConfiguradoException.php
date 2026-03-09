<?php

namespace App\Domain\ProductoActividadValorInductor\Exceptions;

class InductorYaConfiguradoException extends \RuntimeException
{
    public function __construct(int $inductorId)
    {
        parent::__construct("El inductor con id {$inductorId} ya está configurado para esta actividad del producto.");
    }
}
