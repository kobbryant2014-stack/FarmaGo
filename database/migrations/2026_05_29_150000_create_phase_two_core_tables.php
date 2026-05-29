<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('ruc', 11)->unique();
            $table->string('razon_social', 255);
            $table->string('nombre_comercial', 255)->nullable();
            $table->string('direccion_fiscal', 255);
            $table->string('ubigeo', 6)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('logo_path')->nullable();
            $table->string('moneda_codigo', 3)->default('PEN');
            $table->decimal('igv_porcentaje', 5, 2)->default(18.00);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('activo');
        });

        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('codigo', 20);
            $table->string('nombre', 120);
            $table->string('direccion', 255);
            $table->string('ubigeo', 6)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['empresa_id', 'codigo']);
            $table->index(['empresa_id', 'activo']);
        });

        Schema::create('almacenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->constrained('sucursales')->cascadeOnDelete();
            $table->string('codigo', 20);
            $table->string('nombre', 120);
            $table->boolean('principal')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['sucursal_id', 'codigo']);
            $table->index(['sucursal_id', 'activo']);
        });

        Schema::create('configuraciones_sistema', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->cascadeOnDelete();
            $table->string('clave', 120);
            $table->text('valor')->nullable();
            $table->string('tipo', 30)->default('string');
            $table->boolean('cifrado')->default(false);
            $table->timestamps();

            $table->unique(['empresa_id', 'sucursal_id', 'clave'], 'config_sistema_scope_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones_sistema');
        Schema::dropIfExists('almacenes');
        Schema::dropIfExists('sucursales');
        Schema::dropIfExists('empresas');
    }
};
