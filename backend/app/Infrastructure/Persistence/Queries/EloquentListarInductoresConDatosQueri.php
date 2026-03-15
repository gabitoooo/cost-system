<?php

namespace App\Infrastructure\Persistence\Queries;

use App\Application\ActividadInductorTiempo\Dtos\ActividadInductoTiempoViewDtop;
use App\Application\ActividadInductorTiempo\Queries\ListarInductoresConDatosQueri;
use Illuminate\Support\Facades\DB;

class EloquentListarInductoresConDatosQueri implements ListarInductoresConDatosQueri
{

    /**
     * @return ActividadInductoTiempoViewDtop[]
     */
    public function ejecutar(int $actividadId): array
    {
        return DB::table('actividad_inductores_tiempo as ait')
            ->select('ait.id', 'ait.actividad_id', 'ait.inductor_tiempo_id', 'it.nombre as nombre_inductor','it.tipo_calculo as tipo_calculo' )
            ->join('inductores_tiempo as it', 'it.id', '=', 'ait.inductor_tiempo_id')
            ->where('ait.actividad_id', $actividadId)
            ->get()
            ->map(fn($r) => new ActividadInductoTiempoViewDtop(
                id: $r->id,
                actividadId: $r->actividad_id,
                inductorTiempoId: $r->inductor_tiempo_id,
                inductorTiempoNombre: $r->nombre_inductor,
                tipoCalculo: $r->tipo_calculo
            ))
            ->all();
    }
}
