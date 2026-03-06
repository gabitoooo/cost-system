<?php

namespace App\Application\GrupoRecurso;

use App\Application\GrupoRecurso\Dtos\ActualizarGrupoRecursoDto;
use App\Application\GrupoRecurso\Dtos\GrupoRecursoResultDto;
use App\Domain\GrupoRecurso\Exceptions\GrupoRecursoNoEncontradoException;
use App\Domain\GrupoRecurso\GrupoRecurso;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;

class ActualizarGrupoRecursoUseCase
{
    public function __construct(
        private readonly GrupoRecursoRepository $repository,
    ) {}

    /** @throws GrupoRecursoNoEncontradoException */
    public function ejecutar(ActualizarGrupoRecursoDto $dto): GrupoRecursoResultDto
    {
        $existente = $this->repository->findById($dto->id)
            ?? throw new GrupoRecursoNoEncontradoException($dto->id);

        $grupo = new GrupoRecurso(
            id: $dto->id,
            departamentoId: $existente->departamentoId,
            nombre: $dto->nombre,
            capacidadPracticaMinutos: $dto->capacidadPracticaMinutos,
            tasaCostoPorMinuto: $existente->tasaCostoPorMinuto,
        );

        $guardado = $this->repository->save($grupo);

        // La capacidad puede haber cambiado: recalcular CCR
        $actualizado = $this->repository->recalcularCcr($guardado->id);

        return new GrupoRecursoResultDto(
            id: $actualizado->id,
            departamentoId: $actualizado->departamentoId,
            nombre: $actualizado->nombre,
            capacidadPracticaMinutos: $actualizado->capacidadPracticaMinutos,
            tasaCostoPorMinuto: $actualizado->tasaCostoPorMinuto,
        );
    }
}
