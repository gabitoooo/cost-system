<?php

namespace App\Application\Costeo;

use App\Application\Contracts\TransactionManager;
use App\Application\Costeo\Dtos\CalcularCostoProductoDto;
use App\Application\Costeo\Dtos\CalcularCostoProductoResultDto;
use App\Application\Costeo\Dtos\DetalleActividadCalculadaDto;
use App\Application\Costeo\Dtos\DetalleInductorCalculadoDto;
use App\Domain\Actividad\ActividadRepository;
use App\Domain\DetalleInstantaneaCosto\DetalleInstantaneaCosto;
use App\Domain\DetalleInstantaneaCosto\DetalleInstantaneaCostoRepository;
use App\Domain\GrupoRecurso\GrupoRecursoRepository;
use App\Domain\InstantaneaCostoProducto\InstantaneaCostoProducto;
use App\Domain\InstantaneaCostoProducto\InstantaneaCostoProductoRepository;
use App\Domain\InductorTiempo\Enums\InductorTiempoTipoCalculoEnum;
use App\Domain\InductorTiempo\InductorTiempoRepository;
use App\Domain\Producto\Exceptions\ProductoNoEncontradoException;
use App\Domain\Producto\ProductoRepository;
use App\Domain\ProductoActividad\ProductoActividadRepository;
use App\Domain\ProductoActividadValorInductor\ProductoActividadValorInductorRepository;

class CalcularCostoProductoUseCase
{
    public function __construct(
        private readonly ProductoRepository                       $productoRepository,
        private readonly ProductoActividadRepository              $productoActividadRepository,
        private readonly ProductoActividadValorInductorRepository $valorInductorRepository,
        private readonly ActividadRepository                      $actividadRepository,
        private readonly GrupoRecursoRepository                   $grupoRecursoRepository,
        private readonly InductorTiempoRepository                 $inductorTiempoRepository,
        private readonly InstantaneaCostoProductoRepository       $instantaneaRepository,
        private readonly DetalleInstantaneaCostoRepository        $detalleRepository,
        private readonly TransactionManager                       $transactionManager,
    ) {}

    /** @throws ProductoNoEncontradoException */
    public function ejecutar(CalcularCostoProductoDto $dto): CalcularCostoProductoResultDto
    {
        $producto = $this->productoRepository->findById($dto->productoId)
            ?? throw new ProductoNoEncontradoException($dto->productoId);

        $productoActividades = $this->productoActividadRepository->findByProducto($dto->productoId);

        $detalleActividades = [];
        $costoIndirecto = 0.0;

        foreach ($productoActividades as $pa) {
            $actividad = $this->actividadRepository->findById($pa->actividadId);
            if ($actividad === null) {
                continue;
            }

            $grupoRecurso = $this->grupoRecursoRepository->findById($actividad->grupoRecursosId);
            if ($grupoRecurso === null) {
                continue;
            }

            $tasaCostoPorMinuto = (float) ($grupoRecurso->tasaCostoPorMinuto ?? 0);

            $valoresInductor = $this->valorInductorRepository->findByProductoActividad($pa->id);

            $inductoresCalculados = [];
            $sumaMinutosInductores = 0.0;

            foreach ($valoresInductor as $valor) {
                $inductor = $this->inductorTiempoRepository->findById($valor->inductorTiempoId);
                if ($inductor === null) {
                    continue;
                }

                $x = match ($inductor->tipoCalculo) {
                    InductorTiempoTipoCalculoEnum::MANUAL      => (float) ($valor->valorX ?? 0),
                    InductorTiempoTipoCalculoEnum::POR_CANTIDAD => (float) $dto->cantidadPedido,
                    InductorTiempoTipoCalculoEnum::POR_LOTE    => (float) ceil($dto->cantidadPedido / ($valor->tamanoLote ?? 1)),
                };

                $minutosAportados = $valor->betaMinutos * $x;
                $sumaMinutosInductores += $minutosAportados;

                $inductoresCalculados[] = new DetalleInductorCalculadoDto(
                    nombre: $inductor->nombre,
                    tipoCalculo: $inductor->tipoCalculo->value,
                    betaMinutos: $valor->betaMinutos,
                    valorX: $x,
                    minutosAportados: $minutosAportados,
                    tamanoLote: $valor->tamanoLote,
                );
            }

            $tiempoTotalActividad = $actividad->tiempoBaseMinutos + $sumaMinutosInductores;
            $costoActividad = $tiempoTotalActividad * $tasaCostoPorMinuto;
            $costoIndirecto += $costoActividad;

            $detalleActividades[] = new DetalleActividadCalculadaDto(
                actividadId: $actividad->id,
                nombre: $actividad->nombre,
                tiempoBaseMinutos: $actividad->tiempoBaseMinutos,
                tasaCostoPorMinuto: $tasaCostoPorMinuto,
                inductores: $inductoresCalculados,
                tiempoTotalMinutos: $tiempoTotalActividad,
                costoActividad: $costoActividad,
            );
        }

        $costoMaterialDirecto = $producto->costoMaterialDirecto;
        $costoUnitario = $costoIndirecto + $costoMaterialDirecto;
        $costoTotal = $costoUnitario * $dto->cantidadPedido;
        $calculadoEn = now()->toDateTimeString();

        $result = $this->transactionManager->run(function () use (
            $dto,
            $producto,
            $costoIndirecto,
            $costoMaterialDirecto,
            $costoUnitario,
            $costoTotal,
            $calculadoEn,
            $detalleActividades
        ) {
            $instantanea = $this->instantaneaRepository->save(new InstantaneaCostoProducto(
                id: 0,
                productoId: $producto->id,
                precioProductoId: null,
                cantidadMinima: $dto->cantidadPedido,
                cantidadMaxima: null,
                costoIndirecto: $costoIndirecto,
                costoMaterialDirecto: $costoMaterialDirecto,
                costoUnitario: $costoUnitario,
                costoTotal: $costoTotal,
                calculadoEn: $calculadoEn,
            ));

            foreach ($detalleActividades as $detalle) {
                $this->detalleRepository->save(new \App\Domain\DetalleInstantaneaCosto\DetalleInstantaneaCosto(
                    id: 0,
                    instantaneaId: $instantanea->id,
                    actividadId: $detalle->actividadId,
                    tiempoConsumidoMin: $detalle->tiempoTotalMinutos,
                    tasaCostoPorMinuto: $detalle->tasaCostoPorMinuto,
                    costoActividad: $detalle->costoActividad,
                ));
            }

            return $instantanea->id;
        });

        return new CalcularCostoProductoResultDto(
            instantaneaId: $result,
            productoId: $producto->id,
            cantidadPedido: $dto->cantidadPedido,
            costoMaterialDirecto: $costoMaterialDirecto,
            costoIndirecto: $costoIndirecto,
            costoUnitario: $costoUnitario,
            costoTotal: $costoTotal,
            detalleActividades: $detalleActividades,
        );
    }
}
