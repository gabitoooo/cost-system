<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\AsignacionRecursoGrupo\AsignacionRecursoGrupo;
use App\Domain\AsignacionRecursoGrupo\AsignacionRecursoGrupoRepository;
use App\Infrastructure\Persistence\Models\AsignacionRecursoGrupo as AsignacionModel;

class EloquentAsignacionRecursoGrupoRepository implements AsignacionRecursoGrupoRepository
{
    // --- flujo centrado en recurso ---

    public function findByRecurso(int $recursoId): array
    {
        return AsignacionModel::where('recurso_id', $recursoId)
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function deleteByRecurso(int $recursoId): void
    {
        AsignacionModel::where('recurso_id', $recursoId)->delete();
    }

    public function sumCostoProrateadoByGrupo(int $grupoId): float
    {
        return (float) AsignacionModel::where('asignaciones_recursos_grupos.grupo_recursos_id', $grupoId)
            ->join('recursos', 'recursos.id', '=', 'asignaciones_recursos_grupos.recurso_id')
            ->selectRaw('SUM(recursos.costo_mensual * asignaciones_recursos_grupos.porcentaje / 100) as total')
            ->value('total') ?? 0.0;
    }

    // --- flujo centrado en grupo ---

    public function findByGrupo(int $grupoId): array
    {
        return AsignacionModel::where('grupo_recursos_id', $grupoId)
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function findByRecursoAndGrupo(int $recursoId, int $grupoId): ?AsignacionRecursoGrupo
    {
        $model = AsignacionModel::where('recurso_id', $recursoId)
            ->where('grupo_recursos_id', $grupoId)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function sumPorcentajeByRecurso(int $recursoId): float
    {
        return (float) AsignacionModel::where('recurso_id', $recursoId)->sum('porcentaje');
    }

    public function deleteById(int $id): void
    {
        AsignacionModel::findOrFail($id)->delete();
    }

    // --- compartido ---

    public function save(AsignacionRecursoGrupo $asignacion): AsignacionRecursoGrupo
    {
        $model = new AsignacionModel();
        $model->recurso_id        = $asignacion->recursoId;
        $model->grupo_recursos_id = $asignacion->grupoRecursosId;
        $model->porcentaje        = $asignacion->porcentaje;
        $model->save();

        return $this->toEntity($model);
    }

    private function toEntity(AsignacionModel $model): AsignacionRecursoGrupo
    {
        return new AsignacionRecursoGrupo(
            id: $model->id,
            recursoId: $model->recurso_id,
            grupoRecursosId: $model->grupo_recursos_id,
            porcentaje: (float) $model->porcentaje,
        );
    }
}
