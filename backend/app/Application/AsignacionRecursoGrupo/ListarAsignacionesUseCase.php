<?php

namespace App\Application\AsignacionRecursoGrupo;

use App\Application\AsignacionRecursoGrupo\Dtos\AsignacionRecursoGrupoResultDto;
use App\Domain\AsignacionRecursoGrupo\AsignacionRecursoGrupoRepository;
use App\Domain\Recurso\Exceptions\RecursoNoEncontradoException;
use App\Domain\Recurso\RecursoRepository;

class ListarAsignacionesUseCase
{
    public function __construct(
        private readonly AsignacionRecursoGrupoRepository $repository,
        private readonly RecursoRepository                $recursoRepository,
    ) {}

    /**
     * @return AsignacionRecursoGrupoResultDto[]
     * @throws RecursoNoEncontradoException
     */
    public function ejecutar(int $recursoId): array
    {
        $this->recursoRepository->findById($recursoId)
            ?? throw new RecursoNoEncontradoException($recursoId);

        return array_map(
            fn($a) => new AsignacionRecursoGrupoResultDto(
                id: $a->id,
                recursoId: $a->recursoId,
                grupoRecursosId: $a->grupoRecursosId,
                porcentaje: $a->porcentaje,
            ),
            $this->repository->findByRecurso($recursoId),
        );
    }
}
