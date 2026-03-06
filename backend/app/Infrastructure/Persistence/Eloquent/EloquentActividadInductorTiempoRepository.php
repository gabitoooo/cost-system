<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\ActividadInductorTiempo\ActividadInductorTiempo;
use App\Domain\ActividadInductorTiempo\ActividadInductorTiempoRepository;
use App\Infrastructure\Persistence\Models\ActividadInductorTiempo as ActividadInductorTiempoModel;

class EloquentActividadInductorTiempoRepository implements ActividadInductorTiempoRepository
{
    public function findByActividad(int $actividadId): array
    {
        return ActividadInductorTiempoModel::where('actividad_id', $actividadId)
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function findByActividadAndInductor(int $actividadId, int $inductorTiempoId): ?ActividadInductorTiempo
    {
        $model = ActividadInductorTiempoModel::where('actividad_id', $actividadId)
            ->where('inductor_tiempo_id', $inductorTiempoId)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function save(ActividadInductorTiempo $ait): ActividadInductorTiempo
    {
        $model = $ait->id === 0
            ? new ActividadInductorTiempoModel()
            : ActividadInductorTiempoModel::findOrFail($ait->id);

        $model->actividad_id      = $ait->actividadId;
        $model->inductor_tiempo_id = $ait->inductorTiempoId;
        $model->beta_minutos      = $ait->betaMinutos;
        $model->tamano_lote       = $ait->tamanoLote;
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $actividadId, int $inductorTiempoId): void
    {
        ActividadInductorTiempoModel::where('actividad_id', $actividadId)
            ->where('inductor_tiempo_id', $inductorTiempoId)
            ->delete();
    }

    private function toEntity(ActividadInductorTiempoModel $model): ActividadInductorTiempo
    {
        return new ActividadInductorTiempo(
            id: $model->id,
            actividadId: $model->actividad_id,
            inductorTiempoId: $model->inductor_tiempo_id,
            betaMinutos: (float) $model->beta_minutos,
            tamanoLote: $model->tamano_lote !== null ? (float) $model->tamano_lote : null,
        );
    }
}
