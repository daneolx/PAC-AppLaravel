<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_membresias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->unsignedInteger('duracion_dias');
            $table->decimal('precio', 12, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Pivot: tipo_membresia -> productos
        Schema::create('tipo_membresia_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_membresia_id')->constrained('tipo_membresias')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->unique(['tipo_membresia_id', 'producto_id']);
        });

        Schema::create('membresia_alumnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->foreignId('tipo_membresia_id')->constrained('tipo_membresias')->restrictOnDelete();
            $table->date('fecha_compra');
            $table->date('fecha_vencimiento');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membresia_alumnos');
        Schema::dropIfExists('tipo_membresia_producto');
        Schema::dropIfExists('tipo_membresias');
    }
};
