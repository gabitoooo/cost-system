<?php

namespace App\Application\AsignacionRecursoGrupo;

use App\Application\AsignacionRecursoGrupo\Dtos\RecursoEnGrupoResultDto;
use App\Domain\AsignacionRecursoGrupo\AsignacionRecursoGrupoRepository;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\Recurso\RecursoRepository;

class ListarRecursosDeGrupoUseCase
{
    public function __construct(
        private readonly AsignacionRecursoGrupoRepository $repository,
        private readonly GrupoRecursoRepository           $grupoRepository,
        private readonly RecursoRepository                $recursoRepository,
    ) {}

    /**
     * @return RecursoEnGrupoResultDto[]
     * @throws GrupoRecursoNoEncontradoException
     */
    public function ejecutar(int $grupoId): array
    {
        $this->grupoRepository->findById($grupoId)
            ?? throw new GrupoRecursoNoEncontradoException($grupoId);

        return array_map(function ($asignacion) {
            $recurso = $this->recursoRepository->findById($asignacion->recursoId);
            return new RecursoEnGrupoResultDto(
                id: $asignacion->id,
                recursoId: $asignacion->recursoId,
                nombre: $recurso->nombre,
                tipo: $recurso->tipo,
                costoMensual: $recurso->costoMensual,
                grupoRecursosId: $asignacion->grupoRecursosId,
                porcentaje: $asignacion->porcentaje,
            );
        }, $this->repository->findByGrupo($grupoId));
    }
}
