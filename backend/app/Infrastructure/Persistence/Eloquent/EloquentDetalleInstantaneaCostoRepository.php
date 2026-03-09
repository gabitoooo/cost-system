<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\DetalleInstantaneaCosto\DetalleInstantaneaCosto;
use App\Domain\DetalleInstantaneaCosto\DetalleInstantaneaCostoRepository;
use App\Infrastructure\Persistence\Models\DetalleInstantaneaCosto as DetalleInstantaneaCostoModel;

class EloquentDetalleInstantaneaCostoRepository implements DetalleInstantaneaCostoRepository
{
    public function save(DetalleInstantaneaCosto $detalle): DetalleInstantaneaCosto
    {
        $model = new DetalleInstantaneaCostoModel();

        $model->instantanea_id       = $detalle->instantaneaId;
        $model->actividad_id         = $detalle->actividadId;
        $model->tiempo_consumido_min = $detalle->tiempoConsumidoMin;
        $model->tasa_costo_por_minuto = $detalle->tasaCostoPorMinuto;
        $model->costo_actividad      = $detalle->costoActividad;
        $model->save();

        return $this->toEntity($model);
    }

    private function toEntity(DetalleInstantaneaCostoModel $model): DetalleInstantaneaCosto
    {
        return new DetalleInstantaneaCosto(
            id: $model->id,
            instantaneaId: $model->instantanea_id,
            actividadId: $model->actividad_id,
            tiempoConsumidoMin: (float) $model->tiempo_consumido_min,
            tasaCostoPorMinuto: (float) $model->tasa_costo_por_minuto,
            costoActividad: (float) $model->costo_actividad,
        );
    }
}
