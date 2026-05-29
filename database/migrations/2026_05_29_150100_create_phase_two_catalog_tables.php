<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_documento_identidad', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 4)->unique();
            $table->string('nombre', 80);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('tipos_comprobante', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 4)->unique();
            $table->string('nombre', 100);
            $table->boolean('requiere_cliente')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('catalogos_sunat', function (Blueprint $table) {
            $table->id();
            $table->string('catalogo', 20);
            $table->string('codigo', 20);
            $table->string('descripcion', 255);
            $table->date('vigente_desde')->nullable();
            $table->date('vigente_hasta')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['catalogo', 'codigo']);
        });

        Schema::create('laboratorios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 160);
            $table->string('ruc', 11)->nullable();
            $table->string('pais', 80)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('nombre');
            $table->index('activo');
        });

        Schema::create('principios_activos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 180)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('presentaciones_producto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('codigo_unidad', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('nombre', 80);
            $table->boolean('requiere_referencia')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metodos_pago');
        Schema::dropIfExists('presentaciones_producto');
        Schema::dropIfExists('principios_activos');
        Schema::dropIfExists('laboratorios');
        Schema::dropIfExists('catalogos_sunat');
        Schema::dropIfExists('tipos_comprobante');
        Schema::dropIfExists('tipos_documento_identidad');
    }
};
