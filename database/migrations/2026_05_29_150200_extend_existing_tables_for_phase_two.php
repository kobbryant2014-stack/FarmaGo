<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->nullOnDelete();
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->unsignedSmallInteger('failed_login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
        });

        Schema::table('proveedores', function (Blueprint $table) {
            $table->string('tipo_documento', 10)->default('RUC');
            $table->string('razon_social', 180)->nullable();
            $table->string('estado', 30)->default('activo');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->index(['tipo_documento', 'ruc']);
            $table->index('estado');
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->string('tipo_documento', 10)->nullable();
            $table->string('nombres', 120)->nullable();
            $table->string('apellidos', 120)->nullable();
            $table->string('razon_social', 180)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->boolean('cliente_frecuente')->default(false);
            $table->unsignedInteger('puntos_fidelizacion')->default(0);
            $table->boolean('consentimiento_datos')->default(false);
            $table->string('estado', 30)->default('activo');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->index(['tipo_documento', 'documento']);
            $table->index('razon_social');
            $table->index('estado');
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->string('codigo_interno', 50)->nullable()->unique();
            $table->foreignId('laboratorio_id')->nullable()->constrained('laboratorios')->nullOnDelete();
            $table->foreignId('principio_activo_id')->nullable()->constrained('principios_activos')->nullOnDelete();
            $table->foreignId('presentacion_id')->nullable()->constrained('presentaciones_producto')->nullOnDelete();
            $table->string('dci', 180)->nullable();
            $table->string('principio_activo_texto', 180)->nullable();
            $table->string('concentracion', 80)->nullable();
            $table->string('forma_farmaceutica', 80)->nullable();
            $table->string('presentacion_texto', 120)->nullable();
            $table->string('fabricante', 160)->nullable();
            $table->string('registro_sanitario', 80)->nullable();
            $table->string('condicion_venta', 40)->default('libre');
            $table->string('unidad_medida', 30)->default('unidad');
            $table->decimal('precio_compra', 10, 2)->default(0);
            $table->decimal('precio_unidad', 10, 2)->nullable();
            $table->decimal('precio_caja', 10, 2)->nullable();
            $table->decimal('precio_blister', 10, 2)->nullable();
            $table->integer('stock_maximo')->nullable();
            $table->string('afectacion_tributaria', 10)->default('10');
            $table->decimal('igv_porcentaje', 5, 2)->default(18.00);
            $table->boolean('requiere_receta_retenida')->default(false);
            $table->boolean('es_controlado')->default(false);
            $table->boolean('requiere_cadena_frio')->default(false);
            $table->string('ubicacion_almacen', 120)->nullable();
            $table->string('estado', 30)->default('activo');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->index('dci');
            $table->index('principio_activo_texto');
            $table->index('registro_sanitario');
            $table->index('condicion_venta');
            $table->index('estado');
        });

        Schema::table('compras', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->nullOnDelete();
            $table->string('tipo_comprobante_proveedor', 20)->nullable();
            $table->string('serie_comprobante_proveedor', 20)->nullable();
            $table->string('numero_comprobante_proveedor', 40)->nullable();
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('descuento_total', 10, 2)->default(0);
            $table->decimal('igv_total', 10, 2)->default(0);
            $table->string('estado_pago', 30)->default('pendiente');

            $table->index(['empresa_id', 'sucursal_id']);
            $table->index('estado_pago');
        });

        Schema::table('lotes', function (Blueprint $table) {
            $table->foreignId('almacen_id')->nullable()->constrained('almacenes')->nullOnDelete();
            $table->date('fecha_fabricacion')->nullable();
            $table->string('estado', 30)->default('activo');
            $table->string('motivo_bloqueo', 255)->nullable();

            $table->index(['almacen_id', 'estado']);
            $table->index('fecha_vencimiento');
        });

        Schema::table('detalle_compra', function (Blueprint $table) {
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('igv', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
        });

        Schema::table('ventas', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->nullOnDelete();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('descuento_total', 10, 2)->default(0);
            $table->decimal('gravado_total', 10, 2)->default(0);
            $table->decimal('exonerado_total', 10, 2)->default(0);
            $table->decimal('inafecto_total', 10, 2)->default(0);
            $table->decimal('igv_total', 10, 2)->default(0);

            $table->index(['empresa_id', 'sucursal_id']);
        });

        Schema::table('detalle_venta', function (Blueprint $table) {
            $table->foreignId('receta_id')->nullable()->constrained('recetas')->nullOnDelete();
            $table->string('unidad_venta', 30)->default('unidad');
            $table->decimal('descuento', 10, 2)->default(0);
            $table->string('afectacion_tributaria', 10)->default('10');
            $table->decimal('igv', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            $table->index('receta_id');
        });

        Schema::table('movimientos_inventario', function (Blueprint $table) {
            $table->foreignId('almacen_id')->nullable()->constrained('almacenes')->nullOnDelete();
            $table->string('estado', 30)->default('valido');
            $table->foreignId('movimiento_reversado_id')->nullable()->constrained('movimientos_inventario')->nullOnDelete();

            $table->index(['almacen_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::table('movimientos_inventario', function (Blueprint $table) {
            $table->dropForeign(['almacen_id']);
            $table->dropForeign(['movimiento_reversado_id']);
            $table->dropColumn(['almacen_id', 'estado', 'movimiento_reversado_id']);
        });

        Schema::table('detalle_venta', function (Blueprint $table) {
            $table->dropForeign(['receta_id']);
            $table->dropColumn(['receta_id', 'unidad_venta', 'descuento', 'afectacion_tributaria', 'igv', 'total']);
        });

        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropForeign(['sucursal_id']);
            $table->dropColumn([
                'empresa_id',
                'sucursal_id',
                'subtotal',
                'descuento_total',
                'gravado_total',
                'exonerado_total',
                'inafecto_total',
                'igv_total',
            ]);
        });

        Schema::table('detalle_compra', function (Blueprint $table) {
            $table->dropColumn(['descuento', 'igv', 'total']);
        });

        Schema::table('lotes', function (Blueprint $table) {
            $table->dropForeign(['almacen_id']);
            $table->dropColumn(['almacen_id', 'fecha_fabricacion', 'estado', 'motivo_bloqueo']);
        });

        Schema::table('compras', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropForeign(['sucursal_id']);
            $table->dropColumn([
                'empresa_id',
                'sucursal_id',
                'tipo_comprobante_proveedor',
                'serie_comprobante_proveedor',
                'numero_comprobante_proveedor',
                'fecha_emision',
                'fecha_vencimiento',
                'subtotal',
                'descuento_total',
                'igv_total',
                'estado_pago',
            ]);
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['laboratorio_id']);
            $table->dropForeign(['principio_activo_id']);
            $table->dropForeign(['presentacion_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn([
                'codigo_interno',
                'laboratorio_id',
                'principio_activo_id',
                'presentacion_id',
                'dci',
                'principio_activo_texto',
                'concentracion',
                'forma_farmaceutica',
                'presentacion_texto',
                'fabricante',
                'registro_sanitario',
                'condicion_venta',
                'unidad_medida',
                'precio_compra',
                'precio_unidad',
                'precio_caja',
                'precio_blister',
                'stock_maximo',
                'afectacion_tributaria',
                'igv_porcentaje',
                'requiere_receta_retenida',
                'es_controlado',
                'requiere_cadena_frio',
                'ubicacion_almacen',
                'estado',
                'created_by',
                'updated_by',
            ]);
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn([
                'tipo_documento',
                'nombres',
                'apellidos',
                'razon_social',
                'fecha_nacimiento',
                'cliente_frecuente',
                'puntos_fidelizacion',
                'consentimiento_datos',
                'estado',
                'created_by',
                'updated_by',
            ]);
        });

        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['tipo_documento', 'razon_social', 'estado', 'created_by', 'updated_by']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sucursal_id']);
            $table->dropColumn(['sucursal_id', 'last_login_at', 'last_login_ip', 'failed_login_attempts', 'locked_until']);
        });
    }
};
