<?php

namespace App\Application\Actividad;

use App\Domain\Actividad\ActividadRepository;
use App\Domain\Actividad\Exceptions\ActividadNoEncontradaException;

class EliminarActividadUseCase
{
    public function __construct(
        private readonly ActividadRepository $repository,
    ) {}

    /** @throws ActividadNoEncontradaException */
    public function ejecutar(int $id): void
    {
        $this->repository->findById($id)
            ?? throw new ActividadNoEncontradaException($id);

        $this->repository->delete($id);
    }
}
