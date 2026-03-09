<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instantaneas_costo_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('precio_producto_id')->nullable()->constrained('precios_producto');
            $table->integer('cantidad_minima');
            $table->integer('cantidad_maxima')->nullable();
            $table->decimal('costo_indirecto', 12, 4);
            $table->decimal('costo_material_directo', 12, 2);
            $table->decimal('costo_unitario', 12, 4);
            $table->decimal('costo_total', 14, 4);
            $table->timestamp('calculado_en');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instantaneas_costo_producto');
    }
};
