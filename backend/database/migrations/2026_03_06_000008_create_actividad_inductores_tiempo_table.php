<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividad_inductores_tiempo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades');
            $table->foreignId('inductor_tiempo_id')->constrained('inductores_tiempo');
            $table->decimal('beta_minutos', 8, 4);
            $table->decimal('tamano_lote', 10, 2)->nullable();
            $table->timestamps();

            $table->unique(['actividad_id', 'inductor_tiempo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_inductores_tiempo');
    }
};
