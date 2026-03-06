<?php

namespace App\Application\RecursoCompartido;

use App\Domain\RecursoCompartido\Exceptions\RecursoCompartidoNoEncontradoException;
use App\Domain\RecursoCompartido\RecursoCompartidoRepository;

class EliminarRecursoCompartidoUseCase
{
    public function __construct(
        private readonly RecursoCompartidoRepository $repository,
    ) {}

    /** @throws RecursoCompartidoNoEncontradoException */
    public function ejecutar(int $id): void
    {
        $this->repository->findById($id)
            ?? throw new RecursoCompartidoNoEncontradoException($id);

        $this->repository->delete($id);
    }
}
