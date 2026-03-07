<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\GrupoRecurso\GrupoRecurso;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Infrastructure\Persistence\Models\GrupoRecurso as GrupoRecursoModel;

class EloquentGrupoRecursoRepository implements GrupoRecursoRepository
{
    public function findById(int $id): ?GrupoRecurso
    {
        $model = GrupoRecursoModel::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function findByDepartamento(int $departamentoId): array
    {
        return GrupoRecursoModel::where('departamento_id', $departamentoId)
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function save(GrupoRecurso $grupo): GrupoRecurso
    {
        $model = $grupo->id === 0
            ? new GrupoRecursoModel()
            : GrupoRecursoModel::findOrFail($grupo->id);

        $model->departamento_id            = $grupo->departamentoId;
        $model->nombre                     = $grupo->nombre;
        $model->capacidad_practica_minutos = $grupo->capacidadPracticaMinutos;
        $model->tasa_costo_por_minuto      = $grupo->tasaCostoPorMinuto;
        $model->save();  
        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        GrupoRecursoModel::findOrFail($id)->delete();
    }

    private function toEntity(GrupoRecursoModel $model): GrupoRecurso
    {
        return new GrupoRecurso(
            id: $model->id,
            departamentoId: $model->departamento_id,
            nombre: $model->nombre,
            capacidadPracticaMinutos: (float) $model->capacidad_practica_minutos,
            tasaCostoPorMinuto: $model->tasa_costo_por_minuto !== null
                ? (float) $model->tasa_costo_por_minuto
                : null,
        );
    }
}
