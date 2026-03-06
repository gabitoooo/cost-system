<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items_cotizacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones');
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('precio_producto_id')->constrained('precios_producto');
            $table->integer('cantidad');
            $table->decimal('costo_unitario', 12, 4);
            $table->decimal('precio_unitario', 12, 4);
            $table->decimal('subtotal', 12, 4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items_cotizacion');
    }
};
