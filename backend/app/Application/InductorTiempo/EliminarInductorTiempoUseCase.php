<?php

namespace App\Application\InductorTiempo;

use App\Domain\InductorTiempo\Exceptions\InductorTiempoNoEncontradoException;
use App\Domain\InductorTiempo\InductorTiempoRepository;

class EliminarInductorTiempoUseCase
{
    public function __construct(
        private readonly InductorTiempoRepository $repository,
    ) {}

    /** @throws InductorTiempoNoEncontradoException */
    public function ejecutar(int $id): void
    {
        $this->repository->findById($id)
            ?? throw new InductorTiempoNoEncontradoException($id);

        $this->repository->delete($id);
    }
}
