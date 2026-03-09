<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\ProductoActividadValorInductor\ProductoActividadValorInductor;
use App\Domain\ProductoActividadValorInductor\ProductoActividadValorInductorRepository;
use App\Infrastructure\Persistence\Models\ProductoActividadValorInductor as ProductoActividadValorInductorModel;

class EloquentProductoActividadValorInductorRepository implements ProductoActividadValorInductorRepository
{
    public function findByProductoActividad(int $productoActividadId): array
    {
        return ProductoActividadValorInductorModel::where('producto_actividad_id', $productoActividadId)
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->all();
    }

    public function findByProductoActividadAndInductor(int $productoActividadId, int $inductorTiempoId): ?ProductoActividadValorInductor
    {
        $model = ProductoActividadValorInductorModel::where('producto_actividad_id', $productoActividadId)
            ->where('inductor_tiempo_id', $inductorTiempoId)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function findById(int $id): ?ProductoActividadValorInductor
    {
        $model = ProductoActividadValorInductorModel::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function save(ProductoActividadValorInductor $valor): ProductoActividadValorInductor
    {
        $model = $valor->id === 0
            ? new ProductoActividadValorInductorModel()
            : ProductoActividadValorInductorModel::findOrFail($valor->id);

        $model->producto_actividad_id = $valor->productoActividadId;
        $model->inductor_tiempo_id    = $valor->inductorTiempoId;
        $model->valor_x               = $valor->valorX;
        $model->beta_minutos          = $valor->betaMinutos;
        $model->tamano_lote           = $valor->tamanoLote;
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        ProductoActividadValorInductorModel::findOrFail($id)->delete();
    }

    private function toEntity(ProductoActividadValorInductorModel $model): ProductoActividadValorInductor
    {
        return new ProductoActividadValorInductor(
            id: $model->id,
            productoActividadId: $model->producto_actividad_id,
            inductorTiempoId: $model->inductor_tiempo_id,
            valorX: $model->valor_x !== null ? (float) $model->valor_x : null,
            betaMinutos: (float) $model->beta_minutos,
            tamanoLote: $model->tamano_lote !== null ? (float) $model->tamano_lote : null,
        );
    }
}
