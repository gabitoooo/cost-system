<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reglas_precio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lista_precio_id')->constrained('listas_precio');
            $table->string('nombre');
            $table->integer('prioridad');
            $table->string('aplica_a', 20);
            $table->foreignId('producto_id')->nullable()->constrained('productos');
            $table->foreignId('categoria_id')->nullable()->constrained('categorias_producto');
            $table->string('tipo_calculo', 30);
            $table->decimal('valor', 10, 4)->nullable();
            $table->decimal('margen_minimo', 5, 2)->nullable();
            $table->integer('cantidad_minima')->nullable();
            $table->integer('cantidad_maxima')->nullable();
            $table->date('vigente_desde')->nullable();
            $table->date('vigente_hasta')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reglas_precio');
    }
};
