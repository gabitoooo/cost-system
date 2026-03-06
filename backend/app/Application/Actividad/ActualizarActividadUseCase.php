<?php

namespace App\Application\Actividad;

use App\Application\Actividad\Dtos\ActividadResultDto;
use App\Application\Actividad\Dtos\ActualizarActividadDto;
use App\Domain\Actividad\Actividad;
use App\Domain\Actividad\ActividadRepository;
use App\Domain\Actividad\Exceptions\ActividadNoEncontradaException;

class ActualizarActividadUseCase
{
    public function __construct(
        private readonly ActividadRepository $repository,
    ) {}

    /** @throws ActividadNoEncontradaException */
    public function ejecutar(ActualizarActividadDto $dto): ActividadResultDto
    {
        $existente = $this->repository->findById($dto->id)
            ?? throw new ActividadNoEncontradaException($dto->id);

        $actividad = new Actividad(
            id: $dto->id,
            grupoRecursosId: $existente->grupoRecursosId,
            nombre: $dto->nombre,
            tiempoBaseMinutos: $dto->tiempoBaseMinutos,
        );

        $guardada = $this->repository->save($actividad);

        return new ActividadResultDto(
            id: $guardada->id,
            grupoRecursosId: $guardada->grupoRecursosId,
            nombre: $guardada->nombre,
            tiempoBaseMinutos: $guardada->tiempoBaseMinutos,
        );
    }
}
