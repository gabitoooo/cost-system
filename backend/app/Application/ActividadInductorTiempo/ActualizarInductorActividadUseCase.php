<?php

namespace App\Application\ActividadInductorTiempo;

use App\Application\ActividadInductorTiempo\Dtos\ActividadInductorTiempoResultDto;
use App\Application\ActividadInductorTiempo\Dtos\ActualizarInductorActividadDto;
use App\Domain\ActividadInductorTiempo\ActividadInductorTiempoRepository;
use App\Domain\ActividadInductorTiempo\Exceptions\AsociacionNoEncontradaException;

class ActualizarInductorActividadUseCase
{
    public function __construct(
        private readonly ActividadInductorTiempoRepository $repository,
    ) {}

    /**
     * @throws AsociacionNoEncontradaException
     */
    public function ejecutar(ActualizarInductorActividadDto $dto): ActividadInductorTiempoResultDto
    {
        $existente = $this->repository->findByActividadAndInductor($dto->actividadId, $dto->inductorTiempoId)
            ?? throw new AsociacionNoEncontradaException($dto->actividadId, $dto->inductorTiempoId);

        return new ActividadInductorTiempoResultDto(
            id: $existente->id,
            actividadId: $existente->actividadId,
            inductorTiempoId: $existente->inductorTiempoId,
        );
    }
}
