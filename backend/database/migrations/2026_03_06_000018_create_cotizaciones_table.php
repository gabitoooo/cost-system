<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('lista_precio_id')->constrained('listas_precio');
            $table->string('nombre_cliente');
            $table->string('estado', 50);
            $table->boolean('es_pedido_directo')->default(false);
            $table->decimal('costo_total', 12, 4)->default(0);
            $table->decimal('precio_total', 12, 4)->default(0);
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
