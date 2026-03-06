<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_determinacion_precio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_cotizacion_id')->constrained('items_cotizacion');
            $table->foreignId('regla_precio_id')->nullable()->constrained('reglas_precio');
            $table->foreignId('tramo_id')->nullable()->constrained('tramos_regla_precio');
            $table->string('tipo_calculo_aplicado', 30);
            $table->decimal('costo_base_usado', 12, 4);
            $table->decimal('valor_regla_usado', 10, 4)->nullable();
            $table->decimal('precio_calculado', 12, 4);
            $table->timestamp('calculado_en');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_determinacion_precio');
    }
};
