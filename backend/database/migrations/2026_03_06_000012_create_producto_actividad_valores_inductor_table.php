<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_actividad_valores_inductor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_actividad_id')->constrained('producto_actividades');
            $table->foreignId('inductor_tiempo_id')->constrained('inductores_tiempo');
            $table->decimal('valor_x', 10, 2)->nullable();
            $table->decimal('beta_minutos', 8, 4);
            $table->decimal('tamano_lote', 10, 2)->nullable();
            $table->timestamps();
            $table->unique(['producto_actividad_id', 'inductor_tiempo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_actividad_valores_inductor');
    }
};
