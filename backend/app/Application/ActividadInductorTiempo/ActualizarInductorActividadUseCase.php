<?php

namespace App\Application\ActividadInductorTiempo;

use App\Application\ActividadInductorTiempo\Dtos\ActividadInductorTiempoResultDto;
use App\Application\ActividadInductorTiempo\Dtos\ActualizarInductorActividadDto;
use App\Domain\ActividadInductorTiempo\ActividadInductorTiempo;
use App\Domain\ActividadInductorTiempo\ActividadInductorTiempoRepository;
use App\Domain\ActividadInductorTiempo\Exceptions\AsociacionNoEncontradaException;
use App\Domain\ActividadInductorTiempo\Exceptions\TamanoLoteRequeridoException;
use App\Domain\InductorTiempo\Exceptions\InductorTiempoNoEncontradoException;
use App\Domain\InductorTiempo\InductorTiempoRepository;

class ActualizarInductorActividadUseCase
{
    public function __construct(
        private readonly ActividadInductorTiempoRepository $repository,
        private readonly InductorTiempoRepository          $inductorRepository,
    ) {}

    /**
     * @throws AsociacionNoEncontradaException
     * @throws InductorTiempoNoEncontradoException
     * @throws TamanoLoteRequeridoException
     */
    public function ejecutar(ActualizarInductorActividadDto $dto): ActividadInductorTiempoResultDto
    {
        $existente = $this->repository->findByActividadAndInductor($dto->actividadId, $dto->inductorTiempoId)
            ?? throw new AsociacionNoEncontradaException($dto->actividadId, $dto->inductorTiempoId);

        $inductor = $this->inductorRepository->findById($dto->inductorTiempoId)
            ?? throw new InductorTiempoNoEncontradoException($dto->inductorTiempoId);

        // La regla tamano_lote requerido vive en el dominio (factory method)
        $ait = ActividadInductorTiempo::crear(
            actividadId: $dto->actividadId,
            inductorTiempoId: $dto->inductorTiempoId,
            tipoCalculo: $inductor->tipoCalculo,
            betaMinutos: $dto->betaMinutos,
            tamanoLote: $dto->tamanoLote,
        );

        // Preservar el id existente para que el repositorio haga UPDATE en lugar de INSERT
        $aitConId = new ActividadInductorTiempo(
            id: $existente->id,
            actividadId: $ait->actividadId,
            inductorTiempoId: $ait->inductorTiempoId,
            betaMinutos: $ait->betaMinutos,
            tamanoLote: $ait->tamanoLote,
        );

        $guardado = $this->repository->save($aitConId);

        return new ActividadInductorTiempoResultDto(
            id: $guardado->id,
            actividadId: $guardado->actividadId,
            inductorTiempoId: $guardado->inductorTiempoId,
            betaMinutos: $guardado->betaMinutos,
            tamanoLote: $guardado->tamanoLote,
        );
    }
}
