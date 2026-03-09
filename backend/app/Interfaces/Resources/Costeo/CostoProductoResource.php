<?php

namespace App\Interfaces\Resources\Costeo;

use App\Application\Costeo\Dtos\CalcularCostoProductoResultDto;
use App\Application\Costeo\Dtos\DetalleActividadCalculadaDto;
use App\Application\Costeo\Dtos\DetalleInductorCalculadoDto;
use Illuminate\Http\JsonResponse;

class CostoProductoResource
{
    public function __construct(private readonly CalcularCostoProductoResultDto $dto) {}

    public function toArray(): array
    {
        return [
            'instantanea_id'         => $this->dto->instantaneaId,
            'producto_id'            => $this->dto->productoId,
            'cantidad_pedido'        => $this->dto->cantidadPedido,
            'costo_material_directo' => $this->dto->costoMaterialDirecto,
            'costo_indirecto'        => $this->dto->costoIndirecto,
            'costo_unitario'         => $this->dto->costoUnitario,
            'costo_total'            => $this->dto->costoTotal,
            'detalle_actividades'    => array_map(
                fn(DetalleActividadCalculadaDto $a) => [
                    'actividad_id'        => $a->actividadId,
                    'nombre'              => $a->nombre,
                    'tiempo_base_minutos' => $a->tiempoBaseMinutos,
                    'tasa_costo_por_minuto' => $a->tasaCostoPorMinuto,
                    'tiempo_total_minutos' => $a->tiempoTotalMinutos,
                    'costo_actividad'     => $a->costoActividad,
                    'inductores'          => array_map(
                        fn(DetalleInductorCalculadoDto $i) => [
                            'nombre'           => $i->nombre,
                            'tipo_calculo'     => $i->tipoCalculo,
                            'beta_minutos'     => $i->betaMinutos,
                            'valor_x'          => $i->valorX,
                            'minutos_aportados' => $i->minutosAportados,
                            'tamano_lote'      => $i->tamanoLote,
                        ],
                        $a->inductores
                    ),
                ],
                $this->dto->detalleActividades
            ),
        ];
    }

    public function toResponse(int $status = 200): JsonResponse
    {
        return response()->json(['data' => $this->toArray()], $status);
    }
}
