<?php
namespace App\Application\ActividadInductorTiempo\Queries;

use App\Application\ActividadInductorTiempo\Dtos\ActividadInductoTiempoViewDtop;

interface ListarInductoresConDatosQueri
{
    /**
     * @return ActividadInductoTiempoViewDtop[]
     */
    public function ejecutar(int $actividadId): array;
}