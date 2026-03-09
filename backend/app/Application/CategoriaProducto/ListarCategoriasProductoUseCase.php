<?php

namespace App\Application\CategoriaProducto;

use App\Application\CategoriaProducto\Dtos\CategoriaProductoResultDto;
use App\Domain\CategoriaProducto\CategoriaProducto;
use App\Domain\CategoriaProducto\CategoriaProductoRepository;

class ListarCategoriasProductoUseCase
{
    public function __construct(
        private readonly CategoriaProductoRepository $repository,
    ) {}

    /** @return CategoriaProductoResultDto[] */
    public function ejecutar(): array
    {
        return array_map(
            fn(CategoriaProducto $c) => new CategoriaProductoResultDto(
                id: $c->id,
                nombre: $c->nombre,
                descripcion: $c->descripcion,
            ),
            $this->repository->findAll()
        );
    }
}
