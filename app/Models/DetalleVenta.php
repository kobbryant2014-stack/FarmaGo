<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleVenta extends Model
{
    protected $table = 'detalle_venta';

    protected $fillable = [
        'venta_id',
        'producto_id',
        'lote_id',
        'receta_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'unidad_venta',
        'descuento',
        'afectacion_tributaria',
        'igv',
        'total',
    ];

    protected $casts = [
        'cantidad' => 'float',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'igv' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relaciones
    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class);
    }

    public function receta(): BelongsTo
    {
        return $this->belongsTo(Receta::class);
    }
}
