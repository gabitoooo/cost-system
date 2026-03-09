<?php

namespace App\Domain\ProductoActividad;

interface ProductoActividadRepository
{
    public function findByProducto(int $productoId): array;
    public function findByProductoAndActividad(int $productoId, int $actividadId): ?ProductoActividad;
    public function findById(int $id): ?ProductoActividad;
    public function save(ProductoActividad $pa): ProductoActividad;
    public function delete(int $id): void;
    public function deleteByProductoAndActividad(int $productoId, int $actividadId): void;
}
