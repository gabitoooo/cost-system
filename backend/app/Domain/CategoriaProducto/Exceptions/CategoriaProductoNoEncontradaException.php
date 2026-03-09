<?php

namespace App\Domain\CategoriaProducto\Exceptions;

class CategoriaProductoNoEncontradaException extends \RuntimeException
{
    public function __construct(int $id)
    {
        parent::__construct("Categoria de producto con id {$id} no encontrada.");
    }
}
