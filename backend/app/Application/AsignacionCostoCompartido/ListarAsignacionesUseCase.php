<?php

namespace App\Application\AsignacionCostoCompartido;

use App\Application\AsignacionCostoCompartido\Dtos\AsignacionCostoCompartidoResultDto;
use App\Domain\AsignacionCostoCompartido\AsignacionCostoCompartidoRepository;
use App\Domain\RecursoCompartido\Exceptions\RecursoCompartidoNoEncontradoException;
use App\Domain\RecursoCompartido\RecursoCompartidoRepository;

class ListarAsignacionesUseCase
{
    public function __construct(
        private readonly AsignacionCostoCompartidoRepository $repository,
        private readonly RecursoCompartidoRepository         $recursoCompartidoRepository,
    ) {}

    /**
     * @return AsignacionCostoCompartidoResultDto[]
     * @throws RecursoCompartidoNoEncontradoException
     */
    public function ejecutar(int $recursoCompartidoId): array
    {
        $this->recursoCompartidoRepository->findById($recursoCompartidoId)
            ?? throw new RecursoCompartidoNoEncontradoException($recursoCompartidoId);

        return array_map(
            fn($a) => new AsignacionCostoCompartidoResultDto(
                id: $a->id,
                recursoCompartidoId: $a->recursoCompartidoId,
                grupoRecursosId: $a->grupoRecursosId,
                porcentaje: $a->porcentaje,
            ),
            $this->repository->findByRecursoCompartido($recursoCompartidoId),
        );
    }
}
