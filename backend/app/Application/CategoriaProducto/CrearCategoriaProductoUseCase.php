<?php

namespace App\Application\CategoriaProducto;

use App\Application\CategoriaProducto\Dtos\CategoriaProductoResultDto;
use App\Application\CategoriaProducto\Dtos\CrearCategoriaProductoDto;
use App\Domain\CategoriaProducto\CategoriaProducto;
use App\Domain\CategoriaProducto\CategoriaProductoRepository;

class CrearCategoriaProductoUseCase
{
    public function __construct(
        private readonly CategoriaProductoRepository $repository,
    ) {}

    public function ejecutar(CrearCategoriaProductoDto $dto): CategoriaProductoResultDto
    {
        $categoria = new CategoriaProducto(
            id: 0,
            nombre: $dto->nombre,
            descripcion: $dto->descripcion,
        );

        $guardada = $this->repository->save($categoria);

        return new CategoriaProductoResultDto(
            id: $guardada->id,
            nombre: $guardada->nombre,
            descripcion: $guardada->descripcion,
        );
    }
}
