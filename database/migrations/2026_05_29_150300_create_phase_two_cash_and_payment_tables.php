<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->constrained('sucursales')->cascadeOnDelete();
            $table->string('codigo', 20);
            $table->string('nombre', 100);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['sucursal_id', 'codigo']);
            $table->index(['sucursal_id', 'activo']);
        });

        Schema::create('sesiones_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained('cajas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->dateTime('apertura_at');
            $table->dateTime('cierre_at')->nullable();
            $table->decimal('monto_inicial', 12, 2)->default(0);
            $table->decimal('monto_esperado', 12, 2)->nullable();
            $table->decimal('monto_contado', 12, 2)->nullable();
            $table->decimal('diferencia', 12, 2)->nullable();
            $table->text('observacion_cierre')->nullable();
            $table->string('estado', 30)->default('abierta');
            $table->timestamps();

            $table->index(['caja_id', 'estado']);
            $table->index(['user_id', 'apertura_at']);
        });

        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesion_caja_id')->constrained('sesiones_caja')->cascadeOnDelete();
            $table->foreignId('metodo_pago_id')->nullable()->constrained('metodos_pago')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('tipo', 30);
            $table->decimal('monto', 12, 2);
            $table->string('origen', 60)->nullable();
            $table->unsignedBigInteger('origen_id')->nullable();
            $table->string('referencia', 120)->nullable();
            $table->text('motivo')->nullable();
            $table->string('estado', 30)->default('valido');
            $table->timestamps();

            $table->index(['sesion_caja_id', 'tipo']);
            $table->index(['origen', 'origen_id']);
        });

        Schema::create('detalles_arqueo_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesion_caja_id')->constrained('sesiones_caja')->cascadeOnDelete();
            $table->foreignId('metodo_pago_id')->constrained('metodos_pago');
            $table->decimal('monto_esperado', 12, 2)->default(0);
            $table->decimal('monto_contado', 12, 2)->default(0);
            $table->decimal('diferencia', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['sesion_caja_id', 'metodo_pago_id'], 'arqueo_caja_metodo_unique');
        });

        Schema::table('ventas', function (Blueprint $table) {
            $table->foreignId('sesion_caja_id')->nullable()->constrained('sesiones_caja')->nullOnDelete();
            $table->index('sesion_caja_id');
        });

        Schema::create('pagos_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();
            $table->foreignId('metodo_pago_id')->constrained('metodos_pago');
            $table->decimal('monto', 12, 2);
            $table->decimal('monto_recibido', 12, 2)->nullable();
            $table->decimal('vuelto', 12, 2)->nullable();
            $table->string('referencia', 120)->nullable();
            $table->string('estado', 30)->default('confirmado');
            $table->timestamps();

            $table->index(['venta_id', 'estado']);
            $table->index('metodo_pago_id');
        });

        Schema::create('pagos_proveedor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('compras')->cascadeOnDelete();
            $table->foreignId('metodo_pago_id')->constrained('metodos_pago');
            $table->foreignId('user_id')->constrained('users');
            $table->dateTime('pagado_at');
            $table->decimal('monto', 12, 2);
            $table->string('referencia', 120)->nullable();
            $table->string('estado', 30)->default('confirmado');
            $table->timestamps();

            $table->index(['compra_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_proveedor');
        Schema::dropIfExists('pagos_venta');

        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['sesion_caja_id']);
            $table->dropColumn('sesion_caja_id');
        });

        Schema::dropIfExists('detalles_arqueo_caja');
        Schema::dropIfExists('movimientos_caja');
        Schema::dropIfExists('sesiones_caja');
        Schema::dropIfExists('cajas');
    }
};
