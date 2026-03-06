<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos');
            $table->foreignId('actividad_id')->constrained('actividades');
            $table->timestamps();

            $table->unique(['producto_id', 'actividad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_actividades');
    }
};
