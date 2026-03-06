<?php

namespace App\Application\Recurso;

use App\Application\Recurso\Dtos\ActualizarRecursoDto;
use App\Application\Recurso\Dtos\RecursoResultDto;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\Recurso\Exceptions\RecursoNoEncontradoException;
use App\Domain\Recurso\Recurso;
use App\Domain\Recurso\RecursoRepository;

class ActualizarRecursoUseCase
{
    public function __construct(
        private readonly RecursoRepository      $repository,
        private readonly GrupoRecursoRepository $grupoRepository,
    ) {}

    /** @throws RecursoNoEncontradoException */
    public function ejecutar(ActualizarRecursoDto $dto): RecursoResultDto
    {
        $existente = $this->repository->findById($dto->id)
            ?? throw new RecursoNoEncontradoException($dto->id);

        $recurso = new Recurso(
            id: $dto->id,
            grupoRecursosId: $existente->grupoRecursosId,
            nombre: $dto->nombre,
            tipo: $dto->tipo,
            costoMensual: $dto->costoMensual,
        );

        $guardado = $this->repository->save($recurso);

        $this->grupoRepository->recalcularCcr($existente->grupoRecursosId);

        return new RecursoResultDto(
            id: $guardado->id,
            grupoRecursosId: $guardado->grupoRecursosId,
            nombre: $guardado->nombre,
            tipo: $guardado->tipo,
            costoMensual: $guardado->costoMensual,
        );
    }
}
