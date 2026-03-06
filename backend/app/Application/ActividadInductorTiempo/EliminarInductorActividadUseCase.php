<?php

namespace App\Application\ActividadInductorTiempo;

use App\Domain\ActividadInductorTiempo\ActividadInductorTiempoRepository;
use App\Domain\ActividadInductorTiempo\Exceptions\AsociacionNoEncontradaException;

class EliminarInductorActividadUseCase
{
    public function __construct(
        private readonly ActividadInductorTiempoRepository $repository,
    ) {}

    /** @throws AsociacionNoEncontradaException */
    public function ejecutar(int $actividadId, int $inductorTiempoId): void
    {
        $this->repository->findByActividadAndInductor($actividadId, $inductorTiempoId)
            ?? throw new AsociacionNoEncontradaException($actividadId, $inductorTiempoId);

        $this->repository->delete($actividadId, $inductorTiempoId);
    }
}
