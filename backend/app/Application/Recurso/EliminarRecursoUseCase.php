<?php

namespace App\Application\Recurso;

use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\Recurso\Exceptions\RecursoNoEncontradoException;
use App\Domain\Recurso\RecursoRepository;

class EliminarRecursoUseCase
{
    public function __construct(
        private readonly RecursoRepository      $repository,
        private readonly GrupoRecursoRepository $grupoRepository,
    ) {}

    /** @throws RecursoNoEncontradoException */
    public function ejecutar(int $id): void
    {
        $existente = $this->repository->findById($id)
            ?? throw new RecursoNoEncontradoException($id);

        $grupoId = $existente->grupoRecursosId;

        $this->repository->delete($id);

        $this->grupoRepository->recalcularCcr($grupoId);
    }
}
