<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('detalles_instantanea_costo');
        Schema::create('detalles_instantanea_costo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instantanea_id')->constrained('instantaneas_costo_producto');
            $table->foreignId('actividad_id')->constrained('actividades');
            $table->decimal('tiempo_consumido_min', 8, 4);
            $table->decimal('tasa_costo_por_minuto', 10, 4);
            $table->decimal('costo_actividad', 12, 4);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_instantanea_costo');
    }
};
