<?php

namespace App\Domain\ProductoActividadValorInductor;

interface ProductoActividadValorInductorRepository
{
    public function findByProductoActividad(int $productoActividadId): array;
    public function findByProductoActividadAndInductor(int $productoActividadId, int $inductorTiempoId): ?ProductoActividadValorInductor;
    public function findById(int $id): ?ProductoActividadValorInductor;
    public function save(ProductoActividadValorInductor $valor): ProductoActividadValorInductor;
    public function delete(int $id): void;
}
