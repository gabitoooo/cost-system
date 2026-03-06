<?php

namespace App\Application\Recurso;

use App\Application\Recurso\Dtos\RecursoResultDto;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\Recurso\RecursoRepository;

class ListarRecursosUseCase
{
    public function __construct(
        private readonly RecursoRepository      $repository,
        private readonly GrupoRecursoRepository $grupoRepository,
    ) {}

    /**
     * @return RecursoResultDto[]
     * @throws GrupoRecursoNoEncontradoException
     */
    public function ejecutar(int $grupoRecursosId): array
    {
        $this->grupoRepository->findById($grupoRecursosId)
            ?? throw new GrupoRecursoNoEncontradoException($grupoRecursosId);

        return array_map(
            fn($r) => new RecursoResultDto(
                id: $r->id,
                grupoRecursosId: $r->grupoRecursosId,
                nombre: $r->nombre,
                tipo: $r->tipo,
                costoMensual: $r->costoMensual,
            ),
            $this->repository->findByGrupo($grupoRecursosId),
        );
    }
}
