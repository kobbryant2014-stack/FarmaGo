<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PresentacionProducto extends Model
{
    use SoftDeletes;

    protected $table = 'presentaciones_producto';

    protected $fillable = [
        'nombre',
        'codigo_unidad',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'presentacion_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}
