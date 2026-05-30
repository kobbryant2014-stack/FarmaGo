<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'tipo_documento',
        'documento',
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
        'telefono',
        'email',
        'direccion',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'cliente_frecuente' => 'boolean',
        'consentimiento_datos' => 'boolean',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function recetas(): HasMany
    {
        return $this->hasMany(Receta::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Accessor: Nombre completo con documento
    public function getNombreCompletoAttribute(): string
    {
        return $this->documento
            ? "{$this->nombre} ({$this->documento})"
            : $this->nombre;
    }
}
