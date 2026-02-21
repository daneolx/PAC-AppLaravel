<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Profesiones
        Schema::create('profesiones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
        });

        // Distritos
        Schema::create('distritos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
        });

        // Alumnos
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento', 20)->default('DNI');
            $table->string('numero_documento', 20);
            $table->string('nombre', 100);
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('email')->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('discapacidad', 5)->default('NO');
            $table->string('grado_academico', 200)->nullable();
            $table->foreignId('profesion_id')->nullable()->constrained('profesiones')->nullOnDelete();
            $table->string('colegiatura', 100)->nullable();
            $table->string('estado', 20)->default('activo');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['tipo_documento', 'numero_documento']);
        });

        // Direcciones
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 20)->default('DOMICILIO');
            $table->string('direccion', 300);
            $table->string('referencia', 200)->nullable();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->foreignId('distrito_id')->nullable()->constrained('distritos')->nullOnDelete();
            $table->string('estado', 20)->default('activo');
        });

        // Matrículas
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->foreignId('program_id')->nullable()->constrained('programs')->restrictOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('productos')->restrictOnDelete();
            $table->string('enrollment_code', 20)->unique()->nullable();
            $table->timestamp('enrollment_date')->useCurrent();
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->string('enrollment_status', 20)->default('pre_registered');
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->foreignId('canceled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->uuid('verification_uuid')->unique();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('asesor_comercial')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('habilitado_en_moodle')->default(false);
            $table->unsignedInteger('moodle_user_id')->nullable();
            $table->boolean('via_membresia')->default(false);
            $table->string('status', 10)->default('active');
            $table->timestamps();
            $table->unique(['alumno_id', 'product_id']);
        });

        // Contratos
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->string('contract_number', 50)->unique();
            $table->string('pdf_file')->nullable();
            $table->string('contract_status', 20)->default('generated');
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamp('last_status_change')->useCurrent();
            $table->foreignId('status_changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->uuid('verification_uuid')->unique();
            $table->string('status', 10)->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('direcciones');
        Schema::dropIfExists('alumnos');
        Schema::dropIfExists('distritos');
        Schema::dropIfExists('profesiones');
    }
};
