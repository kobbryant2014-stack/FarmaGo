<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 160);
            $table->string('cmp', 30);
            $table->string('especialidad', 120)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique('cmp');
            $table->index('nombre_completo');
        });

        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->string('tipo_documento', 10)->nullable();
            $table->string('documento', 25)->nullable();
            $table->string('nombres', 120);
            $table->string('apellidos', 120)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->text('notas_sensibles')->nullable();
            $table->string('estado', 30)->default('activo');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tipo_documento', 'documento']);
            $table->index('cliente_id');
        });

        Schema::table('recetas', function (Blueprint $table) {
            $table->foreignId('paciente_id')->nullable()->constrained('pacientes')->nullOnDelete();
            $table->foreignId('medico_id')->nullable()->constrained('medicos')->nullOnDelete();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('tipo_receta', 30)->default('simple');
            $table->string('adjunto_path')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('validado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('validado_at')->nullable();
            $table->string('estado', 30)->default('registrada');

            $table->index(['tipo_receta', 'estado']);
            $table->index('fecha_vencimiento');
        });

        Schema::create('receta_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receta_id')->constrained('recetas')->cascadeOnDelete();
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();
            $table->string('nombre_prescrito', 180);
            $table->string('dosis', 120)->nullable();
            $table->decimal('cantidad_prescrita', 12, 3)->default(0);
            $table->decimal('cantidad_dispensada', 12, 3)->default(0);
            $table->string('frecuencia', 120)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        Schema::create('medicamentos_controlados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->string('tipo_control', 60);
            $table->string('codigo_registro', 80)->nullable();
            $table->boolean('requiere_receta_especial')->default(true);
            $table->string('estado', 30)->default('activo');
            $table->timestamps();

            $table->unique('producto_id');
            $table->index(['tipo_control', 'estado']);
        });

        Schema::create('movimientos_medicamento_controlado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicamento_controlado_id')->constrained('medicamentos_controlados')->cascadeOnDelete();
            $table->foreignId('lote_id')->constrained('lotes')->restrictOnDelete();
            $table->foreignId('receta_id')->nullable()->constrained('recetas')->nullOnDelete();
            $table->foreignId('venta_id')->nullable()->constrained('ventas')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('autorizado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo', 30);
            $table->decimal('cantidad', 12, 3);
            $table->text('motivo')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('estado', 30)->default('valido');
            $table->timestamps();

            $table->index(['medicamento_controlado_id', 'tipo']);
            $table->index(['lote_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_medicamento_controlado');
        Schema::dropIfExists('medicamentos_controlados');
        Schema::dropIfExists('receta_detalles');

        Schema::table('recetas', function (Blueprint $table) {
            $table->dropForeign(['paciente_id']);
            $table->dropForeign(['medico_id']);
            $table->dropForeign(['validado_por']);
            $table->dropColumn([
                'paciente_id',
                'medico_id',
                'fecha_vencimiento',
                'tipo_receta',
                'adjunto_path',
                'diagnostico',
                'observaciones',
                'validado_por',
                'validado_at',
                'estado',
            ]);
        });

        Schema::dropIfExists('pacientes');
        Schema::dropIfExists('medicos');
    }
};
