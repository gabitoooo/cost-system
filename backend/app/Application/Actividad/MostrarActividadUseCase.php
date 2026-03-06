<?php

namespace App\Application\Actividad;

use App\Application\Actividad\Dtos\ActividadResultDto;
use App\Domain\Actividad\ActividadRepository;
use App\Domain\Actividad\Exceptions\ActividadNoEncontradaException;

class MostrarActividadUseCase
{
    public function __construct(
        private readonly ActividadRepository $repository,
    ) {}

    /** @throws ActividadNoEncontradaException */
    public function ejecutar(int $id): ActividadResultDto
    {
        $actividad = $this->repository->findById($id)
            ?? throw new ActividadNoEncontradaException($id);

        return new ActividadResultDto(
            id: $actividad->id,
            grupoRecursosId: $actividad->grupoRecursosId,
            nombre: $actividad->nombre,
            tiempoBaseMinutos: $actividad->tiempoBaseMinutos,
        );
    }
}
