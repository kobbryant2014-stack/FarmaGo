<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'producto_id',
        'lote_id',
        'almacen_id',
        'user_id',
        'tipo',
        'cantidad',
        'origen',
        'origen_id',
        'motivo',
        'fecha_movimiento',
        'estado',
        'movimiento_reversado_id',
    ];

    protected $casts = [
        'fecha_movimiento' => 'datetime',
        'cantidad' => 'float',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class);
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function movimientoReversado(): BelongsTo
    {
        return $this->belongsTo(self::class, 'movimiento_reversado_id');
    }

    public function scopeEntradas($query)
    {
        return $query->where('tipo', 'entrada');
    }

    public function scopeSalidas($query)
    {
        return $query->where('tipo', 'salida');
    }

    public function scopeAjustes($query)
    {
        return $query->where('tipo', 'ajuste');
    }

    public function scopeValidos($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('estado')
                ->orWhere('estado', 'valido');
        });
    }
}
