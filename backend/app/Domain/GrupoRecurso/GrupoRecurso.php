<?php

namespace App\Domain\GrupoRecurso;

class GrupoRecurso
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $departamentoId,
        public readonly string $nombre,
        public readonly float  $capacidadPracticaMinutos,
        public readonly ?float $tasaCostoPorMinuto,
    ) {}

    /**
     * Calcula la Tasa de Costo por Recurso (CCR) del grupo.
     *
     * CCR = (SUM(recursos.costo_mensual) + SUM(recurso_compartido.costo_mensual * porcentaje / 100))
     *       / capacidad_practica_minutos
     */
    public function calcularCcr(float $costoRecursos, float $costoCompartido): float
    {
        return ($costoRecursos + $costoCompartido) / $this->capacidadPracticaMinutos;
    }
}
