<?php

namespace App\Application\Actividad;

use App\Application\Actividad\Dtos\ActividadResultDto;
use App\Application\Actividad\Dtos\CrearActividadDto;
use App\Domain\Actividad\Actividad;
use App\Domain\Actividad\ActividadRepository;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;

class CrearActividadUseCase
{
    public function __construct(
        private readonly ActividadRepository    $repository,
        private readonly GrupoRecursoRepository $grupoRepository,
    ) {}

    /** @throws GrupoRecursoNoEncontradoException */
    public function ejecutar(CrearActividadDto $dto): ActividadResultDto
    {
        $this->grupoRepository->findById($dto->grupoRecursosId)
            ?? throw new GrupoRecursoNoEncontradoException($dto->grupoRecursosId);

        $actividad = new Actividad(
            id: 0,
            grupoRecursosId: $dto->grupoRecursosId,
            nombre: $dto->nombre,
            tiempoBaseMinutos: $dto->tiempoBaseMinutos,
        );

        $guardada = $this->repository->save($actividad);

        return $this->toDto($guardada);
    }

    private function toDto(Actividad $a): ActividadResultDto
    {
        return new ActividadResultDto(
            id: $a->id,
            grupoRecursosId: $a->grupoRecursosId,
            nombre: $a->nombre,
            tiempoBaseMinutos: $a->tiempoBaseMinutos,
        );
    }
}
