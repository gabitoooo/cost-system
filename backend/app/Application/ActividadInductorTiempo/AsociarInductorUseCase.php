<?php

namespace App\Application\ActividadInductorTiempo;

use App\Application\ActividadInductorTiempo\Dtos\ActividadInductorTiempoResultDto;
use App\Application\ActividadInductorTiempo\Dtos\AsociarInductorDto;
use App\Domain\Actividad\ActividadRepository;
use App\Domain\Actividad\Exceptions\ActividadNoEncontradaException;
use App\Domain\ActividadInductorTiempo\ActividadInductorTiempo;
use App\Domain\ActividadInductorTiempo\ActividadInductorTiempoRepository;
use App\Domain\ActividadInductorTiempo\Exceptions\InductorYaAsociadoException;
use App\Domain\InductorTiempo\Exceptions\InductorTiempoNoEncontradoException;
use App\Domain\InductorTiempo\InductorTiempoRepository;

class AsociarInductorUseCase
{
    public function __construct(
        private readonly ActividadInductorTiempoRepository $repository,
        private readonly ActividadRepository               $actividadRepository,
        private readonly InductorTiempoRepository          $inductorRepository,
    ) {}

    /**
     * @throws ActividadNoEncontradaException
     * @throws InductorTiempoNoEncontradoException
     * @throws InductorYaAsociadoException
     */
    public function ejecutar(AsociarInductorDto $dto): ActividadInductorTiempoResultDto
    {
        $this->actividadRepository->findById($dto->actividadId)
            ?? throw new ActividadNoEncontradaException($dto->actividadId);

        $this->inductorRepository->findById($dto->inductorTiempoId)
            ?? throw new InductorTiempoNoEncontradoException($dto->inductorTiempoId);

        $existente = $this->repository->findByActividadAndInductor($dto->actividadId, $dto->inductorTiempoId);
        if ($existente !== null) {
            throw new InductorYaAsociadoException($dto->actividadId, $dto->inductorTiempoId);
        }

        $ait = ActividadInductorTiempo::crear(
            actividadId: $dto->actividadId,
            inductorTiempoId: $dto->inductorTiempoId,
        );

        $guardado = $this->repository->save($ait);

        return new ActividadInductorTiempoResultDto(
            id: $guardado->id,
            actividadId: $guardado->actividadId,
            inductorTiempoId: $guardado->inductorTiempoId,
        );
    }
}
