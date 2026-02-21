<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->string('estado', 25)->default('pendiente');
            $table->decimal('nota_curso', 5, 2)->nullable();
            $table->timestamp('notas_validadas_at')->nullable();
            $table->foreignId('notas_validadas_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('pagos_validados_at')->nullable();
            $table->foreignId('pagos_validados_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validado_at')->nullable();
            $table->foreignId('validado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('certificado_emitido_at')->nullable();
            $table->string('certificado_codigo', 50)->nullable();
            $table->string('estado_envio_diplomado', 25)->nullable();
            $table->string('codigo_tracking', 100)->nullable();
            $table->string('diploma_firmado')->nullable();
            $table->timestamp('diploma_firmado_cargado_at')->nullable();
            $table->timestamps();
        });

        Schema::create('certificacion_nota_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificacion_id')->constrained('certificaciones')->cascadeOnDelete();
            $table->string('item_name', 255);
            $table->unsignedInteger('moodle_item_id')->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->decimal('nota_moodle', 5, 2)->nullable();
            $table->decimal('nota_override', 5, 2)->nullable();
            $table->boolean('modificado_por_admin')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificacion_nota_items');
        Schema::dropIfExists('certificaciones');
    }
};
