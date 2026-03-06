<?php

namespace App\Application\GrupoRecurso;

use App\Application\GrupoRecurso\Dtos\GrupoRecursoResultDto;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;

class MostrarGrupoRecursoUseCase
{
    public function __construct(
        private readonly GrupoRecursoRepository $repository,
    ) {}

    /** @throws GrupoRecursoNoEncontradoException */
    public function ejecutar(int $id): GrupoRecursoResultDto
    {
        $grupo = $this->repository->findById($id)
            ?? throw new GrupoRecursoNoEncontradoException($id);

        return new GrupoRecursoResultDto(
            id: $grupo->id,
            departamentoId: $grupo->departamentoId,
            nombre: $grupo->nombre,
            capacidadPracticaMinutos: $grupo->capacidadPracticaMinutos,
            tasaCostoPorMinuto: $grupo->tasaCostoPorMinuto,
        );
    }
}
