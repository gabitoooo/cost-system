<?php

namespace App\Domain\Producto\Exceptions;

class ProductoNoEncontradoException extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Producto con id {$id} no encontrado.");
    }
}
