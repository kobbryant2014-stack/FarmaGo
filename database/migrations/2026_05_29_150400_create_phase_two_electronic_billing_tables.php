<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones_sunat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('tipo_proveedor', 20)->default('sunat');
            $table->string('ambiente', 20)->default('beta');
            $table->text('usuario_sol_cifrado')->nullable();
            $table->text('clave_sol_cifrada')->nullable();
            $table->string('certificado_path')->nullable();
            $table->text('certificado_clave_cifrada')->nullable();
            $table->string('endpoint_url')->nullable();
            $table->json('credenciales_proveedor')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique('empresa_id');
            $table->index(['tipo_proveedor', 'ambiente']);
        });

        Schema::create('series_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->cascadeOnDelete();
            $table->string('tipo_comprobante', 4);
            $table->string('serie', 10);
            $table->unsignedBigInteger('correlativo_actual')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['empresa_id', 'sucursal_id', 'tipo_comprobante', 'serie'], 'serie_documento_unique');
            $table->index(['tipo_comprobante', 'activo']);
        });

        Schema::create('comprobantes_electronicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->nullOnDelete();
            $table->foreignId('venta_id')->nullable()->constrained('ventas')->nullOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->string('tipo_comprobante', 4);
            $table->string('serie', 10);
            $table->unsignedBigInteger('numero');
            $table->date('fecha_emision');
            $table->string('moneda_codigo', 3)->default('PEN');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('igv_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->text('qr_payload')->nullable();
            $table->string('xml_hash', 128)->nullable();
            $table->string('tipo_proveedor', 20)->default('sunat');
            $table->string('estado', 30)->default('pendiente');
            $table->string('estado_sunat', 40)->nullable();
            $table->string('cdr_codigo', 20)->nullable();
            $table->text('cdr_descripcion')->nullable();
            $table->json('respuesta_proveedor')->nullable();
            $table->dateTime('enviado_at')->nullable();
            $table->dateTime('aceptado_at')->nullable();
            $table->timestamps();

            $table->unique(['empresa_id', 'tipo_comprobante', 'serie', 'numero'], 'cpe_unique_number');
            $table->index(['estado', 'fecha_emision']);
            $table->index('venta_id');
        });

        Schema::create('comprobante_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comprobante_electronico_id')->constrained('comprobantes_electronicos')->cascadeOnDelete();
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();
            $table->string('descripcion', 255);
            $table->decimal('cantidad', 12, 3);
            $table->decimal('precio_unitario', 12, 2);
            $table->string('afectacion_tributaria', 10)->default('10');
            $table->decimal('igv', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->timestamps();

            $table->index('comprobante_electronico_id');
        });

        Schema::create('comprobante_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comprobante_electronico_id')->constrained('comprobantes_electronicos')->cascadeOnDelete();
            $table->string('tipo_archivo', 30);
            $table->string('disk', 60)->default('local');
            $table->string('path');
            $table->string('sha256', 128)->nullable();
            $table->string('estado', 30)->default('disponible');
            $table->timestamps();

            $table->unique(['comprobante_electronico_id', 'tipo_archivo'], 'cpe_archivo_tipo_unique');
        });

        Schema::create('comprobante_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comprobante_electronico_id')->constrained('comprobantes_electronicos')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo_evento', 60);
            $table->string('estado_anterior', 30)->nullable();
            $table->string('estado_nuevo', 30)->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->text('error_mensaje')->nullable();
            $table->timestamps();

            $table->index(['comprobante_electronico_id', 'tipo_evento']);
        });

        Schema::create('notas_credito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comprobante_electronico_id')->constrained('comprobantes_electronicos')->cascadeOnDelete();
            $table->foreignId('comprobante_referencia_id')->constrained('comprobantes_electronicos')->restrictOnDelete();
            $table->string('codigo_motivo', 10);
            $table->string('descripcion_motivo', 255);
            $table->decimal('total', 12, 2);
            $table->string('estado', 30)->default('pendiente');
            $table->timestamps();
        });

        Schema::create('notas_debito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comprobante_electronico_id')->constrained('comprobantes_electronicos')->cascadeOnDelete();
            $table->foreignId('comprobante_referencia_id')->constrained('comprobantes_electronicos')->restrictOnDelete();
            $table->string('codigo_motivo', 10);
            $table->string('descripcion_motivo', 255);
            $table->decimal('total', 12, 2);
            $table->string('estado', 30)->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas_debito');
        Schema::dropIfExists('notas_credito');
        Schema::dropIfExists('comprobante_eventos');
        Schema::dropIfExists('comprobante_archivos');
        Schema::dropIfExists('comprobante_items');
        Schema::dropIfExists('comprobantes_electronicos');
        Schema::dropIfExists('series_documentos');
        Schema::dropIfExists('configuraciones_sunat');
    }
};
