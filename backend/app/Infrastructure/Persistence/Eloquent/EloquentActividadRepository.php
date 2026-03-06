<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Actividad\Actividad;
use App\Domain\Actividad\ActividadRepository;
use App\Infrastructure\Persistence\Models\Actividad as ActividadModel;

class EloquentActividadRepository implements ActividadRepository
{
    public function findById(int $id): ?Actividad
    {
        $model = ActividadModel::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function findByGrupo(int $grupoRecursosId): array
    {
        return ActividadModel::where('grupo_recursos_id', $grupoRecursosId)
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function save(Actividad $actividad): Actividad
    {
        $model = $actividad->id === 0
            ? new ActividadModel()
            : ActividadModel::findOrFail($actividad->id);

        $model->grupo_recursos_id    = $actividad->grupoRecursosId;
        $model->nombre               = $actividad->nombre;
        $model->tiempo_base_minutos  = $actividad->tiempoBaseMinutos;
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        ActividadModel::findOrFail($id)->delete();
    }

    private function toEntity(ActividadModel $model): Actividad
    {
        return new Actividad(
            id: $model->id,
            grupoRecursosId: $model->grupo_recursos_id,
            nombre: $model->nombre,
            tiempoBaseMinutos: (float) $model->tiempo_base_minutos,
        );
    }
}
