<?php

namespace App\Application\ProductoActividadValorInductor;

use App\Domain\ProductoActividadValorInductor\Exceptions\ValorInductorNoEncontradoException;
use App\Domain\ProductoActividadValorInductor\ProductoActividadValorInductorRepository;

class EliminarValorInductorUseCase
{
    public function __construct(
        private readonly ProductoActividadValorInductorRepository $repository,
    ) {}

    /** @throws ValorInductorNoEncontradoException */
    public function ejecutar(int $productoActividadId, int $inductorTiempoId): void
    {
        $existente = $this->repository->findByProductoActividadAndInductor(
            $productoActividadId,
            $inductorTiempoId
        );

        if ($existente === null) {
            throw new ValorInductorNoEncontradoException($inductorTiempoId);
        }

        $this->repository->delete($existente->id);
    }
}
