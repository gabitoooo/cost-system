<?php

namespace App\Application\ActividadInductorTiempo;

use App\Application\ActividadInductorTiempo\Dtos\ActividadInductorTiempoResultDto;
use App\Application\ActividadInductorTiempo\Queries\ListarInductoresConDatosQueri;
use App\Domain\Actividad\ActividadRepository;
use App\Domain\Actividad\Exceptions\ActividadNoEncontradaException;

class ListarInductoresActividadUseCase
{
    public function __construct(
        private readonly ListarInductoresConDatosQueri $repository,
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
        return $this->repository->ejecutar($actividadId);    
       
    }
}
