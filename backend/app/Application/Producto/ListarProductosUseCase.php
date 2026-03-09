<?php

namespace App\Application\Producto;

use App\Application\Producto\Dtos\ProductoResultDto;
use App\Domain\Producto\Producto;
use App\Domain\Producto\ProductoRepository;

class ListarProductosUseCase
{
    public function __construct(
        private readonly ProductoRepository $repository,
    ) {}

    /** @return ProductoResultDto[] */
    public function ejecutar(): array
    {
        return array_map(
            fn(Producto $p) => new ProductoResultDto(
                id: $p->id,
                categoriaId: $p->categoriaId,
                nombre: $p->nombre,
                descripcion: $p->descripcion,
                costoMaterialDirecto: $p->costoMaterialDirecto,
            ),
            $this->repository->findAll()
        );
    }
}
