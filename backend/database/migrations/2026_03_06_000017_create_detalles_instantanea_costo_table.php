<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_instantanea_costo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('precio_producto_id')->constrained('precios_producto');
            $table->jsonb('snapshot_tdabc');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_instantanea_costo');
    }
};
