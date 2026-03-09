<?php

namespace App\Application\ProductoActividad;

use App\Domain\ProductoActividad\Exceptions\ProductoActividadNoEncontradaException;
use App\Domain\ProductoActividad\ProductoActividadRepository;

class EliminarActividadProductoUseCase
{
    public function __construct(
        private readonly ProductoActividadRepository $repository,
    ) {}

    /** @throws ProductoActividadNoEncontradaException */
    public function ejecutar(int $productoId, int $actividadId): void
    {
        $existente = $this->repository->findByProductoAndActividad($productoId, $actividadId);
        if ($existente === null) {
            throw new ProductoActividadNoEncontradaException($actividadId, $productoId);
        }

        $this->repository->deleteByProductoAndActividad($productoId, $actividadId);
    }
}
