<?php

namespace App\Application\AsignacionRecursoGrupo;

use App\Application\AsignacionRecursoGrupo\Dtos\ActualizarPorcentajeDto;
use App\Application\AsignacionRecursoGrupo\Dtos\RecursoEnGrupoResultDto;
use App\Domain\AsignacionRecursoGrupo\AsignacionRecursoGrupo;
use App\Domain\AsignacionRecursoGrupo\AsignacionRecursoGrupoRepository;
use App\Domain\AsignacionRecursoGrupo\Exceptions\AsignacionNoEncontradaException;
use App\Domain\AsignacionRecursoGrupo\Exceptions\PorcentajeInvalidoException;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\Recurso\Exceptions\RecursoNoEncontradoException;
use App\Domain\Recurso\RecursoRepository;

class ActualizarPorcentajeAsignacionUseCase
{
    public function __construct(
        private readonly AsignacionRecursoGrupoRepository $repository,
        private readonly GrupoRecursoRepository           $grupoRepository,
        private readonly RecursoRepository                $recursoRepository,
    ) {}

    /**
     * @throws GrupoRecursoNoEncontradoException
     * @throws RecursoNoEncontradoException
     * @throws AsignacionNoEncontradaException
     * @throws PorcentajeInvalidoException
     */
    public function ejecutar(ActualizarPorcentajeDto $dto): RecursoEnGrupoResultDto
    {
        $grupo = $this->grupoRepository->findById($dto->grupoRecursosId)
            ?? throw new GrupoRecursoNoEncontradoException($dto->grupoRecursosId);

        $recurso = $this->recursoRepository->findById($dto->recursoId)
            ?? throw new RecursoNoEncontradoException($dto->recursoId);

        $existente = $this->repository->findByRecursoAndGrupo($dto->recursoId, $dto->grupoRecursosId)
            ?? throw new AsignacionNoEncontradaException($dto->recursoId, $dto->grupoRecursosId);

        // Descontar el porcentaje actual antes de validar el nuevo total
        $totalSinEsta = $this->repository->sumPorcentajeByRecurso($dto->recursoId) - $existente->porcentaje;
        if ($totalSinEsta + $dto->porcentaje > 100.01) {
            throw new PorcentajeInvalidoException(round($totalSinEsta + $dto->porcentaje, 2));
        }

        // Reemplazar: borrar la existente y crear con el nuevo porcentaje
        $this->repository->deleteById($existente->id);
        $asignacion = $this->repository->save(new AsignacionRecursoGrupo(
            id: 0,
            recursoId: $dto->recursoId,
            grupoRecursosId: $dto->grupoRecursosId,
            porcentaje: $dto->porcentaje,
        ));

        // Recalcular CCR del grupo
        $costo = $this->repository->sumCostoProrateadoByGrupo($dto->grupoRecursosId);
        $this->grupoRepository->save($grupo->setTasa($grupo->calcularCcr($costo, 0.0)));

        return new RecursoEnGrupoResultDto(
            id: $asignacion->id,
            recursoId: $recurso->id,
            nombre: $recurso->nombre,
            tipo: $recurso->tipo,
            costoMensual: $recurso->costoMensual,
            grupoRecursosId: $asignacion->grupoRecursosId,
            porcentaje: $asignacion->porcentaje,
        );
    }
}
