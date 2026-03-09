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
            $table->timestamps();
            $table->unique(['actividad_id', 'inductor_tiempo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_inductores_tiempo');
    }
};
