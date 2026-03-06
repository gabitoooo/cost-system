<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('precios_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('regla_precio_id')->constrained('reglas_precio');
            $table->foreignId('tramo_id')->nullable()->constrained('tramos_regla_precio');
            $table->integer('cantidad_referencia')->nullable();
            $table->decimal('costo_material_directo', 12, 2);
            $table->decimal('costo_indirecto', 12, 4);
            $table->decimal('costo_unitario', 12, 4);
            $table->decimal('precio_unitario', 12, 4);
            $table->string('estado', 20);
            $table->timestamp('calculado_en')->nullable();
            $table->timestamp('archivado_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('precios_producto');
    }
};
