<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\AsignacionCostoCompartido\AsignacionCostoCompartido;
use App\Domain\AsignacionCostoCompartido\AsignacionCostoCompartidoRepository;
use App\Infrastructure\Persistence\Models\AsignacionCostoCompartido as AsignacionModel;
use Illuminate\Support\Facades\DB;

class EloquentAsignacionCostoCompartidoRepository implements AsignacionCostoCompartidoRepository
{
    public function findByRecursoCompartido(int $recursoCompartidoId): array
    {
        return AsignacionModel::where('recurso_compartido_id', $recursoCompartidoId)
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function syncAsignaciones(int $recursoCompartidoId, array $asignaciones): array
    {
        return DB::transaction(function () use ($recursoCompartidoId, $asignaciones) {
            AsignacionModel::where('recurso_compartido_id', $recursoCompartidoId)->delete();

            $guardadas = [];
            foreach ($asignaciones as $asignacion) {
                $model = new AsignacionModel();
                $model->recurso_compartido_id = $recursoCompartidoId;
                $model->grupo_recursos_id     = $asignacion->grupoRecursosId;
                $model->porcentaje            = $asignacion->porcentaje;
                $model->save();

                $guardadas[] = $this->toEntity($model);
            }

            return $guardadas;
        });
    }

    private function toEntity(AsignacionModel $model): AsignacionCostoCompartido
    {
        return new AsignacionCostoCompartido(
            id: $model->id,
            recursoCompartidoId: $model->recurso_compartido_id,
            grupoRecursosId: $model->grupo_recursos_id,
            porcentaje: (float) $model->porcentaje,
        );
    }
}
