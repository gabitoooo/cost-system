<?php

namespace App\Application\RecursoCompartido;

use App\Application\RecursoCompartido\Dtos\ActualizarRecursoCompartidoDto;
use App\Application\RecursoCompartido\Dtos\RecursoCompartidoResultDto;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\RecursoCompartido\Exceptions\RecursoCompartidoNoEncontradoException;
use App\Domain\RecursoCompartido\RecursoCompartido;
use App\Domain\RecursoCompartido\RecursoCompartidoRepository;
use App\Domain\AsignacionCostoCompartido\AsignacionCostoCompartidoRepository;

class ActualizarRecursoCompartidoUseCase
{
    public function __construct(
        private readonly RecursoCompartidoRepository        $repository,
        private readonly AsignacionCostoCompartidoRepository $asignacionRepository,
        private readonly GrupoRecursoRepository             $grupoRepository,
    ) {}

    /** @throws RecursoCompartidoNoEncontradoException */
    public function ejecutar(ActualizarRecursoCompartidoDto $dto): RecursoCompartidoResultDto
    {
        $existente = $this->repository->findById($dto->id)
            ?? throw new RecursoCompartidoNoEncontradoException($dto->id);

        $recurso = new RecursoCompartido(
            id: $dto->id,
            departamentoId: $existente->departamentoId,
            nombre: $dto->nombre,
            tipo: $dto->tipo,
            costoMensual: $dto->costoMensual,
        );

        $guardado = $this->repository->save($recurso);

        // El costo cambió: recalcular CCR de todos los grupos con asignación activa
        $asignaciones = $this->asignacionRepository->findByRecursoCompartido($dto->id);
        foreach ($asignaciones as $asignacion) {
            $this->grupoRepository->recalcularCcr($asignacion->grupoRecursosId);
        }

        return new RecursoCompartidoResultDto(
            id: $guardado->id,
            departamentoId: $guardado->departamentoId,
            nombre: $guardado->nombre,
            tipo: $guardado->tipo,
            costoMensual: $guardado->costoMensual,
        );
    }
}
