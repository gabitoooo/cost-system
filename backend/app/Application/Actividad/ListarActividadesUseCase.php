<?php

namespace App\Application\Actividad;

use App\Application\Actividad\Dtos\ActividadResultDto;
use App\Domain\Actividad\ActividadRepository;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;

class ListarActividadesUseCase
{
    public function __construct(
        private readonly ActividadRepository    $repository,
        private readonly GrupoRecursoRepository $grupoRepository,
    ) {}

    /**
     * @return ActividadResultDto[]
     * @throws GrupoRecursoNoEncontradoException
     */
    public function ejecutar(int $grupoRecursosId): array
    {
        $this->grupoRepository->findById($grupoRecursosId)
            ?? throw new GrupoRecursoNoEncontradoException($grupoRecursosId);

        return array_map(
            fn($a) => new ActividadResultDto(
                id: $a->id,
                grupoRecursosId: $a->grupoRecursosId,
                nombre: $a->nombre,
                tiempoBaseMinutos: $a->tiempoBaseMinutos,
            ),
            $this->repository->findByGrupo($grupoRecursosId),
        );
    }
}
