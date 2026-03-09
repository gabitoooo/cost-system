<?php

namespace App\Application\CategoriaProducto;

use App\Domain\CategoriaProducto\CategoriaProductoRepository;
use App\Domain\CategoriaProducto\Exceptions\CategoriaProductoNoEncontradaException;

class EliminarCategoriaProductoUseCase
{
    public function __construct(
        private readonly CategoriaProductoRepository $repository,
    ) {}

    /** @throws CategoriaProductoNoEncontradaException */
    public function ejecutar(int $id): void
    {
        $this->repository->findById($id)
            ?? throw new CategoriaProductoNoEncontradaException($id);

        $this->repository->delete($id);
    }
}
