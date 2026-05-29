<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'ruc',
        'razon_social',
        'nombre_comercial',
        'direccion_fiscal',
        'ubigeo',
        'telefono',
        'email',
        'logo_path',
        'moneda_codigo',
        'igv_porcentaje',
        'activo',
    ];

    protected $casts = [
        'igv_porcentaje' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function sucursales(): HasMany
    {
        return $this->hasMany(Sucursal::class);
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}
