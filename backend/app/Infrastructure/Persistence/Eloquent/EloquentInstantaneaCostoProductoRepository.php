<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\InstantaneaCostoProducto\InstantaneaCostoProducto;
use App\Domain\InstantaneaCostoProducto\InstantaneaCostoProductoRepository;
use App\Infrastructure\Persistence\Models\InstantaneaCostoProducto as InstantaneaCostoProductoModel;

class EloquentInstantaneaCostoProductoRepository implements InstantaneaCostoProductoRepository
{
    public function save(InstantaneaCostoProducto $instantanea): InstantaneaCostoProducto
    {
        $model = new InstantaneaCostoProductoModel();

        $model->producto_id             = $instantanea->productoId;
        $model->precio_producto_id      = $instantanea->precioProductoId;
        $model->cantidad_minima         = $instantanea->cantidadMinima;
        $model->cantidad_maxima         = $instantanea->cantidadMaxima;
        $model->costo_indirecto         = $instantanea->costoIndirecto;
        $model->costo_material_directo  = $instantanea->costoMaterialDirecto;
        $model->costo_unitario          = $instantanea->costoUnitario;
        $model->costo_total             = $instantanea->costoTotal;
        $model->calculado_en            = $instantanea->calculadoEn;
        $model->save();

        return $this->toEntity($model);
    }

    private function toEntity(InstantaneaCostoProductoModel $model): InstantaneaCostoProducto
    {
        return new InstantaneaCostoProducto(
            id: $model->id,
            productoId: $model->producto_id,
            precioProductoId: $model->precio_producto_id,
            cantidadMinima: $model->cantidad_minima,
            cantidadMaxima: $model->cantidad_maxima,
            costoIndirecto: (float) $model->costo_indirecto,
            costoMaterialDirecto: (float) $model->costo_material_directo,
            costoUnitario: (float) $model->costo_unitario,
            costoTotal: (float) $model->costo_total,
            calculadoEn: $model->calculado_en,
        );
    }
}
