<?php

namespace App\Application\GrupoRecurso;

use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;

class EliminarGrupoRecursoUseCase
{
    public function __construct(
        private readonly GrupoRecursoRepository $repository,
    ) {}

    /** @throws GrupoRecursoNoEncontradoException */
    public function ejecutar(int $id): void
    {
        $this->repository->findById($id)
            ?? throw new GrupoRecursoNoEncontradoException($id);

        $this->repository->delete($id);
    }
}
