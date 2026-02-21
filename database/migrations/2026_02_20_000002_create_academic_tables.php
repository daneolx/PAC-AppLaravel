<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Categorías
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('status', 10)->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Temas
        Schema::create('temas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('status', 10)->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Etiquetas
        Schema::create('etiquetas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('status', 10)->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Periodos Académicos
        Schema::create('academic_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('period_status', 10)->default('open');
            $table->string('status', 10)->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Programas Académicos
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('code', 20)->unique();
            $table->text('description')->nullable();
            $table->string('modality', 20)->default('presencial');
            $table->string('duration', 100);
            $table->decimal('base_cost', 12, 2);
            $table->unsignedInteger('max_students')->default(30);
            $table->string('status', 10)->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Productos (cursos, diplomados)
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tema_id')->nullable()->constrained('temas')->nullOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->string('tipo_producto', 20)->default('curso');
            $table->text('descripcion')->nullable();
            $table->string('sku', 50)->unique();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('modulo', 200)->nullable();
            $table->unsignedInteger('horas')->default(0);
            $table->unsignedInteger('creditos')->default(0);
            $table->decimal('precio', 12, 2)->default(0);
            $table->unsignedInteger('meses')->default(1);
            $table->decimal('matricula', 12, 2)->default(0);
            $table->decimal('mensualidad', 12, 2)->default(0);
            $table->decimal('derecho', 12, 2)->default(0);
            $table->string('imagen')->nullable();
            $table->unsignedInteger('moodle_course_id')->nullable();
            $table->boolean('requiere_membresia')->default(false);
            $table->string('status', 10)->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Producto-Etiqueta (pivot)
        Schema::create('producto_etiqueta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->foreignId('etiqueta_id')->constrained('etiquetas')->cascadeOnDelete();
            $table->unique(['producto_id', 'etiqueta_id']);
        });

        // Docentes
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('specialization', 200);
            $table->text('bio')->nullable();
            $table->string('status', 10)->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Docente-Producto (pivot)
        Schema::create('teacher_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->unique(['teacher_id', 'producto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_producto');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('producto_etiqueta');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('academic_periods');
        Schema::dropIfExists('etiquetas');
        Schema::dropIfExists('temas');
        Schema::dropIfExists('categorias');
    }
};
