<?php

namespace App\Application\AsignacionCostoCompartido;

use App\Application\AsignacionCostoCompartido\Dtos\AsignarCostoCompartidoDto;
use App\Application\AsignacionCostoCompartido\Dtos\AsignacionCostoCompartidoResultDto;
use App\Domain\AsignacionCostoCompartido\AsignacionCostoCompartido;
use App\Domain\AsignacionCostoCompartido\AsignacionCostoCompartidoRepository;
use App\Domain\AsignacionCostoCompartido\Exceptions\PorcentajeInvalidoException;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\RecursoCompartido\Exceptions\RecursoCompartidoNoEncontradoException;
use App\Domain\RecursoCompartido\RecursoCompartidoRepository;

class AsignarCostoCompartidoUseCase
{
    public function __construct(
        private readonly AsignacionCostoCompartidoRepository $repository,
        private readonly RecursoCompartidoRepository         $recursoCompartidoRepository,
        private readonly GrupoRecursoRepository              $grupoRepository,
    ) {}

    /**
     * @return AsignacionCostoCompartidoResultDto[]
     * @throws RecursoCompartidoNoEncontradoException
     * @throws PorcentajeInvalidoException
     */
    public function ejecutar(AsignarCostoCompartidoDto $dto): array
    {
        $this->recursoCompartidoRepository->findById($dto->recursoCompartidoId)
            ?? throw new RecursoCompartidoNoEncontradoException($dto->recursoCompartidoId);

        $entidades = array_map(
            fn($a) => new AsignacionCostoCompartido(
                id: 0,
                recursoCompartidoId: $dto->recursoCompartidoId,
                grupoRecursosId: $a->grupoRecursosId,
                porcentaje: $a->porcentaje,
            ),
            $dto->asignaciones,
        );

        // Regla de negocio sobre una colección → Use Case (no en la entidad)
        $total = array_sum(array_map(fn($a) => $a->porcentaje, $entidades));
        if (abs($total - 100) > 0.01) {
            throw new PorcentajeInvalidoException(round($total, 2));
        }

        $guardadas = $this->repository->syncAsignaciones($dto->recursoCompartidoId, $entidades);

        foreach ($guardadas as $asignacion) {
            $this->grupoRepository->recalcularCcr($asignacion->grupoRecursosId);
        }

        return array_map(
            fn($a) => new AsignacionCostoCompartidoResultDto(
                id: $a->id,
                recursoCompartidoId: $a->recursoCompartidoId,
                grupoRecursosId: $a->grupoRecursosId,
                porcentaje: $a->porcentaje,
            ),
            $guardadas,
        );
    }
}
