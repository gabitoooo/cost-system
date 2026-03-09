<?php

namespace App\Application\Producto;

use App\Application\Producto\Dtos\ProductoResultDto;
use App\Domain\Producto\Exceptions\ProductoNoEncontradoException;
use App\Domain\Producto\ProductoRepository;

class MostrarProductoUseCase
{
    public function __construct(
        private readonly ProductoRepository $repository,
    ) {}

    /** @throws ProductoNoEncontradoException */
    public function ejecutar(int $id): ProductoResultDto
    {
        $producto = $this->repository->findById($id)
            ?? throw new ProductoNoEncontradoException($id);

        return new ProductoResultDto(
            id: $producto->id,
            categoriaId: $producto->categoriaId,
            nombre: $producto->nombre,
            descripcion: $producto->descripcion,
            costoMaterialDirecto: $producto->costoMaterialDirecto,
        );
    }
}
