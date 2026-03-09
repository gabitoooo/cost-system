<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\ProductoActividad\ProductoActividad;
use App\Domain\ProductoActividad\ProductoActividadRepository;
use App\Infrastructure\Persistence\Models\ProductoActividad as ProductoActividadModel;

class EloquentProductoActividadRepository implements ProductoActividadRepository
{
    public function findByProducto(int $productoId): array
    {
        return ProductoActividadModel::where('producto_id', $productoId)
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function findByProductoAndActividad(int $productoId, int $actividadId): ?ProductoActividad
    {
        $model = ProductoActividadModel::where('producto_id', $productoId)
            ->where('actividad_id', $actividadId)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function findById(int $id): ?ProductoActividad
    {
        $model = ProductoActividadModel::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function save(ProductoActividad $pa): ProductoActividad
    {
        $model = $pa->id === 0
            ? new ProductoActividadModel()
            : ProductoActividadModel::findOrFail($pa->id);

        $model->producto_id  = $pa->productoId;
        $model->actividad_id = $pa->actividadId;
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        ProductoActividadModel::findOrFail($id)->delete();
    }

    public function deleteByProductoAndActividad(int $productoId, int $actividadId): void
    {
        ProductoActividadModel::where('producto_id', $productoId)
            ->where('actividad_id', $actividadId)
            ->delete();
    }

    private function toEntity(ProductoActividadModel $model): ProductoActividad
    {
        return new ProductoActividad(
            id: $model->id,
            productoId: $model->producto_id,
            actividadId: $model->actividad_id,
        );
    }
}
