<?php

namespace App\Application\Producto;

use App\Application\Producto\Dtos\CrearProductoDto;
use App\Application\Producto\Dtos\ProductoResultDto;
use App\Domain\CategoriaProducto\CategoriaProductoRepository;
use App\Domain\CategoriaProducto\Exceptions\CategoriaProductoNoEncontradaException;
use App\Domain\Producto\Producto;
use App\Domain\Producto\ProductoRepository;

class CrearProductoUseCase
{
    public function __construct(
        private readonly ProductoRepository          $repository,
        private readonly CategoriaProductoRepository $categoriaRepository,
    ) {}

    /** @throws CategoriaProductoNoEncontradaException */
    public function ejecutar(CrearProductoDto $dto): ProductoResultDto
    {
        if ($dto->categoriaId !== null) {
            $this->categoriaRepository->findById($dto->categoriaId)
                ?? throw new CategoriaProductoNoEncontradaException($dto->categoriaId);
        }

        $producto = new Producto(
            id: 0,
            categoriaId: $dto->categoriaId,
            nombre: $dto->nombre,
            descripcion: $dto->descripcion,
            costoMaterialDirecto: $dto->costoMaterialDirecto,
        );

        $guardado = $this->repository->save($producto);

        return new ProductoResultDto(
            id: $guardado->id,
            categoriaId: $guardado->categoriaId,
            nombre: $guardado->nombre,
            descripcion: $guardado->descripcion,
            costoMaterialDirecto: $guardado->costoMaterialDirecto,
        );
    }
}
