<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tramos_regla_precio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regla_precio_id')->constrained('reglas_precio');
            $table->integer('cantidad_minima');
            $table->integer('cantidad_maxima')->nullable();
            $table->string('tipo_calculo', 30);
            $table->decimal('valor', 10, 4);
            $table->timestamp('created_at')->nullable();

            $table->unique(['regla_precio_id', 'cantidad_minima']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tramos_regla_precio');
    }
};
