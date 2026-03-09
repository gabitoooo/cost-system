<?php

namespace App\Application\CategoriaProducto;

use App\Application\CategoriaProducto\Dtos\ActualizarCategoriaProductoDto;
use App\Application\CategoriaProducto\Dtos\CategoriaProductoResultDto;
use App\Domain\CategoriaProducto\CategoriaProducto;
use App\Domain\CategoriaProducto\CategoriaProductoRepository;
use App\Domain\CategoriaProducto\Exceptions\CategoriaProductoNoEncontradaException;

class ActualizarCategoriaProductoUseCase
{
    public function __construct(
        private readonly CategoriaProductoRepository $repository,
    ) {}

    /** @throws CategoriaProductoNoEncontradaException */
    public function ejecutar(ActualizarCategoriaProductoDto $dto): CategoriaProductoResultDto
    {
        $this->repository->findById($dto->id)
            ?? throw new CategoriaProductoNoEncontradaException($dto->id);

        $categoria = new CategoriaProducto(
            id: $dto->id,
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
