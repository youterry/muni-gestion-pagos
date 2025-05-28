<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id(); // ID primario (Laravel usa 'id' por defecto)
            // Clave foránea a la tabla 'users'. Asegúrate de que la tabla 'users' exista.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('monto', 10, 2); // Monto con 2 decimales
            $table->date('fecha'); // Solo la fecha
            // Columna ENUM con valores permitidos y un valor por defecto
            $table->enum('estado', ['pendiente', 'completado', 'cancelado'])->default('pendiente');
            $table->timestamps(); // Columnas created_at y updated_at
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};