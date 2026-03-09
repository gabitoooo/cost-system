<?php

namespace App\Application\ProductoActividad;

use App\Application\ProductoActividad\Dtos\ProductoActividadResultDto;
use App\Domain\Producto\Exceptions\ProductoNoEncontradoException;
use App\Domain\Producto\ProductoRepository;
use App\Domain\ProductoActividad\ProductoActividadRepository;

class ListarActividadesProductoUseCase
{
    public function __construct(
        private readonly ProductoActividadRepository $repository,
        private readonly ProductoRepository          $productoRepository,
    ) {}

    /**
     * @throws ProductoNoEncontradoException
     * @return ProductoActividadResultDto[]
     */
    public function ejecutar(int $productoId): array
    {
        $this->productoRepository->findById($productoId)
            ?? throw new ProductoNoEncontradoException($productoId);

        return array_map(
            fn($pa) => new ProductoActividadResultDto(
                id: $pa->id,
                productoId: $pa->productoId,
                actividadId: $pa->actividadId,
            ),
            $this->repository->findByProducto($productoId)
        );
    }
}
