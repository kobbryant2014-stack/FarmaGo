<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ajustes_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('almacen_id')->constrained('almacenes')->restrictOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->foreignId('lote_id')->constrained('lotes')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('autorizado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('stock_anterior', 12, 3);
            $table->decimal('stock_nuevo', 12, 3);
            $table->decimal('diferencia', 12, 3);
            $table->text('motivo');
            $table->string('estado', 30)->default('aplicado');
            $table->timestamps();
        });

        Schema::create('transferencias_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('almacen_origen_id')->constrained('almacenes')->restrictOnDelete();
            $table->foreignId('almacen_destino_id')->constrained('almacenes')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->dateTime('fecha');
            $table->text('motivo')->nullable();
            $table->string('estado', 30)->default('pendiente');
            $table->timestamps();

            $table->index(['almacen_origen_id', 'almacen_destino_id'], 'trans_inv_almacenes_idx');
        });

        Schema::create('transferencia_inventario_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transferencia_inventario_id');
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->foreignId('lote_id')->constrained('lotes')->restrictOnDelete();
            $table->decimal('cantidad', 12, 3);
            $table->timestamps();

            $table->foreign('transferencia_inventario_id', 'trans_inv_det_trans_fk')
                ->references('id')
                ->on('transferencias_inventario')
                ->cascadeOnDelete();
        });

        Schema::create('devoluciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->restrictOnDelete();
            $table->foreignId('comprobante_electronico_id')->nullable()->constrained('comprobantes_electronicos')->nullOnDelete();
            $table->foreignId('autorizado_por')->constrained('users');
            $table->string('tipo', 30);
            $table->text('motivo');
            $table->boolean('afecta_stock')->default(true);
            $table->boolean('afecta_caja')->default(true);
            $table->string('estado', 30)->default('registrada');
            $table->timestamps();

            $table->index(['venta_id', 'estado']);
        });

        Schema::create('devolucion_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devolucion_id')->constrained('devoluciones')->cascadeOnDelete();
            $table->foreignId('detalle_venta_id')->constrained('detalle_venta')->restrictOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->foreignId('lote_id')->constrained('lotes')->restrictOnDelete();
            $table->decimal('cantidad', 12, 3);
            $table->decimal('monto', 12, 2);
            $table->string('estado_reingreso', 30)->default('pendiente');
            $table->timestamps();
        });

        Schema::create('alertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->cascadeOnDelete();
            $table->string('tipo_alerta', 60);
            $table->string('severidad', 20)->default('warning');
            $table->string('entidad_tipo', 120)->nullable();
            $table->unsignedBigInteger('entidad_id')->nullable();
            $table->string('titulo', 160);
            $table->text('mensaje');
            $table->date('fecha_limite')->nullable();
            $table->dateTime('leida_at')->nullable();
            $table->string('estado', 30)->default('activa');
            $table->timestamps();

            $table->index(['tipo_alerta', 'estado']);
            $table->index(['entidad_tipo', 'entidad_id']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('accion', 80);
            $table->string('modulo', 80);
            $table->string('entidad_tipo', 120)->nullable();
            $table->unsignedBigInteger('entidad_id')->nullable();
            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->text('motivo')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('estado', 30)->default('registrado');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
            $table->index(['modulo', 'created_at']);
            $table->index(['entidad_tipo', 'entidad_id']);
        });

        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo_backup', 30);
            $table->string('disk', 60)->default('local');
            $table->string('path')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->string('sha256', 128)->nullable();
            $table->dateTime('iniciado_at');
            $table->dateTime('finalizado_at')->nullable();
            $table->string('estado', 30)->default('running');
            $table->text('error_mensaje')->nullable();
            $table->timestamps();

            $table->index(['tipo_backup', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backups');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('alertas');
        Schema::dropIfExists('devolucion_detalles');
        Schema::dropIfExists('devoluciones');
        Schema::dropIfExists('transferencia_inventario_detalles');
        Schema::dropIfExists('transferencias_inventario');
        Schema::dropIfExists('ajustes_inventario');
    }
};
