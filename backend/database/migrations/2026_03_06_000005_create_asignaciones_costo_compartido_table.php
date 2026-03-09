<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones_recursos_grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recurso_id')->constrained('recursos');
            $table->foreignId('grupo_recursos_id')->constrained('grupos_recursos');
            $table->decimal('porcentaje', 5, 2);
            $table->timestamps();
            $table->unique(['recurso_id', 'grupo_recursos_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones_costo_compartido');
    }
};
