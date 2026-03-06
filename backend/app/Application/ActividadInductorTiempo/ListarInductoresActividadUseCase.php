<?php

namespace App\Application\ActividadInductorTiempo;

use App\Application\ActividadInductorTiempo\Dtos\ActividadInductorTiempoResultDto;
use App\Domain\Actividad\ActividadRepository;
use App\Domain\Actividad\Exceptions\ActividadNoEncontradaException;
use App\Domain\ActividadInductorTiempo\ActividadInductorTiempoRepository;

class ListarInductoresActividadUseCase
{
    public function __construct(
        private readonly ActividadInductorTiempoRepository $repository,
        private readonly ActividadRepository               $actividadRepository,
    ) {}

    /**
     * @return ActividadInductorTiempoResultDto[]
     * @throws ActividadNoEncontradaException
     */
    public function ejecutar(int $actividadId): array
    {
        $this->actividadRepository->findById($actividadId)
            ?? throw new ActividadNoEncontradaException($actividadId);

        return array_map(
            fn($a) => new ActividadInductorTiempoResultDto(
                id: $a->id,
                actividadId: $a->actividadId,
                inductorTiempoId: $a->inductorTiempoId,
                betaMinutos: $a->betaMinutos,
                tamanoLote: $a->tamanoLote,
            ),
            $this->repository->findByActividad($actividadId),
        );
    }
}
