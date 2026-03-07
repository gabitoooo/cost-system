<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\AsignacionCostoCompartido\AsignacionCostoCompartido;
use App\Domain\AsignacionCostoCompartido\AsignacionCostoCompartidoRepository;
use App\Infrastructure\Persistence\Models\AsignacionCostoCompartido as AsignacionModel;

class EloquentAsignacionCostoCompartidoRepository implements AsignacionCostoCompartidoRepository
{
    public function findByRecursoCompartido(int $recursoCompartidoId): array
    {
        return AsignacionModel::where('recurso_compartido_id', $recursoCompartidoId)
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function save(AsignacionCostoCompartido $asignacion): AsignacionCostoCompartido
    {
        $model = new AsignacionModel();
        $model->recurso_compartido_id = $asignacion->recursoCompartidoId;
        $model->grupo_recursos_id     = $asignacion->grupoRecursosId;
        $model->porcentaje            = $asignacion->porcentaje;
        $model->save();

        return $this->toEntity($model);
    }

    public function deleteByRecursoCompartido(int $recursoCompartidoId): void
    {
        AsignacionModel::where('recurso_compartido_id', $recursoCompartidoId)->delete();
    }

    public function sumCostoCompartidoByGrupo(int $grupoId): float
    {
        return (float) AsignacionModel::where('asignaciones_costo_compartido.grupo_recursos_id', $grupoId)
            ->join('recursos_compartidos', 'recursos_compartidos.id', '=', 'asignaciones_costo_compartido.recurso_compartido_id')
            ->selectRaw('SUM(recursos_compartidos.costo_mensual * asignaciones_costo_compartido.porcentaje / 100) as total')
            ->value('total') ?? 0.0;
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
