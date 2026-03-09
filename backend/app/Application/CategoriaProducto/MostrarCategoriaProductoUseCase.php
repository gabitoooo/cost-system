<?php

namespace App\Application\CategoriaProducto;

use App\Application\CategoriaProducto\Dtos\CategoriaProductoResultDto;
use App\Domain\CategoriaProducto\CategoriaProductoRepository;
use App\Domain\CategoriaProducto\Exceptions\CategoriaProductoNoEncontradaException;

class MostrarCategoriaProductoUseCase
{
    public function __construct(
        private readonly CategoriaProductoRepository $repository,
    ) {}

    /** @throws CategoriaProductoNoEncontradaException */
    public function ejecutar(int $id): CategoriaProductoResultDto
    {
        $categoria = $this->repository->findById($id)
            ?? throw new CategoriaProductoNoEncontradaException($id);

        return new CategoriaProductoResultDto(
            id: $categoria->id,
            nombre: $categoria->nombre,
            descripcion: $categoria->descripcion,
        );
    }
}
