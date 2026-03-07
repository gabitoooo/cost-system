<?php

namespace App\Application\AsignacionCostoCompartido;

use App\Application\Contracts\TransactionManager;
use App\Application\AsignacionCostoCompartido\Dtos\AsignarCostoCompartidoDto;
use App\Application\AsignacionCostoCompartido\Dtos\AsignacionCostoCompartidoResultDto;
use App\Domain\AsignacionCostoCompartido\AsignacionCostoCompartido;
use App\Domain\AsignacionCostoCompartido\AsignacionCostoCompartidoRepository;
use App\Domain\AsignacionCostoCompartido\Exceptions\PorcentajeInvalidoException;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\Recurso\RecursoRepository;
use App\Domain\RecursoCompartido\Exceptions\RecursoCompartidoNoEncontradoException;
use App\Domain\RecursoCompartido\RecursoCompartidoRepository;

class AsignarCostoCompartidoUseCase
{
    public function __construct(
        private readonly AsignacionCostoCompartidoRepository $repository,
        private readonly RecursoCompartidoRepository         $recursoCompartidoRepository,
        private readonly GrupoRecursoRepository              $grupoRepository,
        private readonly RecursoRepository                   $recursoRepository,
        private readonly TransactionManager                  $transaction,
    ) {}

    /**
     * @return AsignacionCostoCompartidoResultDto[]
     * @throws RecursoCompartidoNoEncontradoException
     * @throws PorcentajeInvalidoException
     */
    public function ejecutar(AsignarCostoCompartidoDto $dto): array
    {
        // Validar que el recurso compartido exista
        $this->recursoCompartidoRepository->findById($dto->recursoCompartidoId)
            ?? throw new RecursoCompartidoNoEncontradoException($dto->recursoCompartidoId);

        // Validar que cada grupo de recursos exista
        foreach ($dto->asignaciones as $asignacion) {
            $this->grupoRepository->findById($asignacion->grupoRecursosId)
                ?? throw new GrupoRecursoNoEncontradoException($asignacion->grupoRecursosId);
        }

        // Construir entidades de dominio (id=0 indica que son nuevas)
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
        // La suma de porcentajes debe ser exactamente 100%
        $total = array_sum(array_map(fn($a) => $a->porcentaje, $entidades));
        if (abs($total - 100) > 0.01) {
            throw new PorcentajeInvalidoException(round($total, 2));
        }

        // Persistir y recalcular CCR dentro de una transacción atómica
        $guardadas = $this->transaction->run(function () use ($dto, $entidades) {
            // Reemplazar todas las asignaciones anteriores del recurso compartido
            $this->repository->deleteByRecursoCompartido($dto->recursoCompartidoId);

            $saved = array_map(
                fn($entidad) => $this->repository->save($entidad),
                $entidades,
            );

            // Recalcular la tasa CCR de cada grupo afectado por las nuevas asignaciones
            $gruposAfectados = array_unique(array_map(fn($a) => $a->grupoRecursosId, $saved));

            foreach ($gruposAfectados as $grupoId) {
                $grupo           = $this->grupoRepository->findById($grupoId);
                $costoRecursos   = $this->recursoRepository->sumCostoByGrupo($grupoId);       // costo de recursos propios
                $costoCompartido = $this->repository->sumCostoCompartidoByGrupo($grupoId);    // costo compartido prorrateado
                $tasa            = $grupo->calcularCcr($costoRecursos, $costoCompartido);     // CCR = (propio + compartido) / capacidad
                $this->grupoRepository->save($grupo->setTasa($tasa));
            }

            return $saved;
        });

        // Mapear entidades guardadas a DTOs de respuesta
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
