<?php

namespace App\Application\AsignacionRecursoGrupo;

use App\Application\Contracts\TransactionManager;
use App\Application\AsignacionRecursoGrupo\Dtos\AsignarRecursoGrupoDto;
use App\Application\AsignacionRecursoGrupo\Dtos\AsignacionRecursoGrupoResultDto;
use App\Domain\AsignacionRecursoGrupo\AsignacionRecursoGrupo;
use App\Domain\AsignacionRecursoGrupo\AsignacionRecursoGrupoRepository;
use App\Domain\AsignacionRecursoGrupo\Exceptions\PorcentajeInvalidoException;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\Recurso\Exceptions\RecursoNoEncontradoException;
use App\Domain\Recurso\RecursoRepository;

class AsignarRecursoGrupoUseCase
{
    public function __construct(
        private readonly AsignacionRecursoGrupoRepository $repository,
        private readonly RecursoRepository                $recursoRepository,
        private readonly GrupoRecursoRepository           $grupoRepository,
        private readonly TransactionManager               $transaction,
    ) {}

    /**
     * @return AsignacionRecursoGrupoResultDto[]
     * @throws RecursoNoEncontradoException
     * @throws GrupoRecursoNoEncontradoException
     * @throws PorcentajeInvalidoException
     */
    public function ejecutar(AsignarRecursoGrupoDto $dto): array
    {
        // Validar que el recurso exista
        $this->recursoRepository->findById($dto->recursoId)
            ?? throw new RecursoNoEncontradoException($dto->recursoId);

        // Validar que cada grupo de recursos exista
        foreach ($dto->asignaciones as $asignacion) {
            $this->grupoRepository->findById($asignacion->grupoRecursosId)
                ?? throw new GrupoRecursoNoEncontradoException($asignacion->grupoRecursosId);
        }

        // Construir entidades de dominio
        $entidades = array_map(
            fn($a) => new AsignacionRecursoGrupo(
                id: 0,
                recursoId: $dto->recursoId,
                grupoRecursosId: $a->grupoRecursosId,
                porcentaje: $a->porcentaje,
            ),
            $dto->asignaciones,
        );

        // La suma de porcentajes no debe exceder 100%
        $total = array_sum(array_map(fn($a) => $a->porcentaje, $entidades));
        if ($total > 100.01) {
            throw new PorcentajeInvalidoException(round($total, 2));
        }

        // Persistir y recalcular CCR dentro de una transacción atómica
        $guardadas = $this->transaction->run(function () use ($dto, $entidades) {
            // Reemplazar todas las asignaciones anteriores del recurso
            $this->repository->deleteByRecurso($dto->recursoId);

            $saved = array_map(
                fn($entidad) => $this->repository->save($entidad),
                $entidades,
            );

            // Recalcular la tasa CCR de cada grupo afectado
            $gruposAfectados = array_unique(array_map(fn($a) => $a->grupoRecursosId, $saved));

            foreach ($gruposAfectados as $grupoId) {
                $grupo           = $this->grupoRepository->findById($grupoId);
                $costoProrrateado = $this->repository->sumCostoProrateadoByGrupo($grupoId);
                $tasa            = $grupo->calcularCcr($costoProrrateado, 0.0);
                $this->grupoRepository->save($grupo->setTasa($tasa));
            }

            return $saved;
        });

        // Mapear entidades a DTOs de respuesta
        return array_map(
            fn($a) => new AsignacionRecursoGrupoResultDto(
                id: $a->id,
                recursoId: $a->recursoId,
                grupoRecursosId: $a->grupoRecursosId,
                porcentaje: $a->porcentaje,
            ),
            $guardadas,
        );
    }
}
