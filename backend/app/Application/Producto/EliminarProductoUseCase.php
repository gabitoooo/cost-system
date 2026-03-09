<?php

namespace App\Application\Producto;

use App\Domain\Producto\Exceptions\ProductoNoEncontradoException;
use App\Domain\Producto\ProductoRepository;

class EliminarProductoUseCase
{
    public function __construct(
        private readonly ProductoRepository $repository,
    ) {}

    /** @throws ProductoNoEncontradoException */
    public function ejecutar(int $id): void
    {
        $this->repository->findById($id)
            ?? throw new ProductoNoEncontradoException($id);

        $this->repository->delete($id);
    }
}
