<?php

namespace App\Application\GrupoRecurso;

use App\Application\GrupoRecurso\Dtos\CrearGrupoRecursoDto;
use App\Application\GrupoRecurso\Dtos\GrupoRecursoResultDto;
use App\Domain\Departamento\DepartamentoRepository;
use App\Domain\Departamento\Exceptions\DepartamentoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecurso;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;

class CrearGrupoRecursoUseCase
{
    public function __construct(
        private readonly GrupoRecursoRepository  $repository,
        private readonly DepartamentoRepository  $departamentoRepository,
    ) {}

    /** @throws DepartamentoNoEncontradoException */
    public function ejecutar(CrearGrupoRecursoDto $dto): GrupoRecursoResultDto
    {
        $this->departamentoRepository->findById($dto->departamentoId)
            ?? throw new DepartamentoNoEncontradoException($dto->departamentoId);

        $grupo = new GrupoRecurso(
            id: 0,
            departamentoId: $dto->departamentoId,
            nombre: $dto->nombre,
            capacidadPracticaMinutos: $dto->capacidadPracticaMinutos,
            tasaCostoPorMinuto: null,
        );

        $guardado = $this->repository->save($grupo);

        return $this->toDto($guardado);
    }

    private function toDto(GrupoRecurso $g): GrupoRecursoResultDto
    {
        return new GrupoRecursoResultDto(
            id: $g->id,
            departamentoId: $g->departamentoId,
            nombre: $g->nombre,
            capacidadPracticaMinutos: $g->capacidadPracticaMinutos,
            tasaCostoPorMinuto: $g->tasaCostoPorMinuto,
        );
    }
}
