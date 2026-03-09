<?php

namespace App\Application\AsignacionRecursoGrupo;

use App\Domain\AsignacionRecursoGrupo\AsignacionRecursoGrupoRepository;
use App\Domain\AsignacionRecursoGrupo\Exceptions\AsignacionNoEncontradaException;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;

class QuitarRecursoDeGrupoUseCase
{
    public function __construct(
        private readonly AsignacionRecursoGrupoRepository $repository,
        private readonly GrupoRecursoRepository           $grupoRepository,
    ) {}

    /**
     * @throws GrupoRecursoNoEncontradoException
     * @throws AsignacionNoEncontradaException
     */
    public function ejecutar(int $grupoId, int $recursoId): void
    {
        $grupo = $this->grupoRepository->findById($grupoId)
            ?? throw new GrupoRecursoNoEncontradoException($grupoId);

        $asignacion = $this->repository->findByRecursoAndGrupo($recursoId, $grupoId)
            ?? throw new AsignacionNoEncontradaException($recursoId, $grupoId);

        $this->repository->deleteById($asignacion->id);

        // Recalcular CCR del grupo con el recurso ya quitado
        $costo = $this->repository->sumCostoProrateadoByGrupo($grupoId);
        $this->grupoRepository->save($grupo->setTasa($grupo->calcularCcr($costo, 0.0)));
    }
}
