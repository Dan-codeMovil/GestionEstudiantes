<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
    $table->id();
    $table->string('usuario');
    $table->string('ip');
    $table->string('tipo_transaccion'); // Ejemplo: CREAR, EDITAR, ELIMINAR, LOGIN, LOGOUT
    $table->text('descripcion')->nullable();
    $table->timestamp('fecha_hora');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};
