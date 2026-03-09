<?php

namespace App\Domain\ProductoActividadValorInductor\Exceptions;

class ValorInductorNoEncontradoException extends \RuntimeException
{
    public function __construct(int $inductorId)
    {
        parent::__construct("Configuración de inductor con id {$inductorId} no encontrada en la asignación producto-actividad.");
    }
}
