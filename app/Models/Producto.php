<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'codigo_barra',
        'codigo_interno',
        'nombre',
        'descripcion',
        'imagen',
        'categoria_id',
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
        'precio_venta',
        'precio_unidad',
        'precio_caja',
        'precio_blister',
        'stock_minimo',
        'stock_maximo',
        'afectacion_tributaria',
        'igv_porcentaje',
        'requiere_receta',
        'requiere_receta_retenida',
        'es_controlado',
        'requiere_cadena_frio',
        'ubicacion_almacen',
        'estado',
        'created_by',
        'updated_by',
        'activo',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'precio_unidad' => 'decimal:2',
        'precio_caja' => 'decimal:2',
        'precio_blister' => 'decimal:2',
        'igv_porcentaje' => 'decimal:2',
        'requiere_receta' => 'boolean',
        'requiere_receta_retenida' => 'boolean',
        'es_controlado' => 'boolean',
        'requiere_cadena_frio' => 'boolean',
        'activo' => 'boolean',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function laboratorio(): BelongsTo
    {
        return $this->belongsTo(Laboratorio::class);
    }

    public function principioActivo(): BelongsTo
    {
        return $this->belongsTo(PrincipioActivo::class);
    }

    public function presentacion(): BelongsTo
    {
        return $this->belongsTo(PresentacionProducto::class);
    }

    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeVendibles($query)
    {
        return $query->where('activo', true)
            ->where(function ($query) {
                $query->whereNull('estado')
                    ->orWhere('estado', 'activo');
            });
    }

    public function scopeConReceta($query)
    {
        return $query->where('requiere_receta', true);
    }

    public function scopeBajoStock($query)
    {
        $stockSql = Lote::stockActualSql('lotes');

        return $query->where('stock_minimo', '>', 0)
            ->whereRaw("(
            SELECT COALESCE(SUM({$stockSql}), 0)
            FROM lotes
            WHERE lotes.producto_id = productos.id
            AND lotes.activo = 1
            AND (lotes.estado IS NULL OR lotes.estado = 'activo')
            AND lotes.fecha_vencimiento > CURRENT_DATE
        ) <= stock_minimo");
    }

    public function scopeBusquedaPos($query, string $termino)
    {
        return $query->where(function ($query) use ($termino) {
            $query->where('nombre', 'like', "%{$termino}%")
                ->orWhere('codigo_barra', 'like', "%{$termino}%")
                ->orWhere('codigo_interno', 'like', "%{$termino}%")
                ->orWhere('dci', 'like', "%{$termino}%")
                ->orWhere('principio_activo_texto', 'like', "%{$termino}%")
                ->orWhere('registro_sanitario', 'like', "%{$termino}%");
        });
    }

    public function getStockTotalAttribute(): float
    {
        return (float) $this->lotes()
            ->where('activo', true)
            ->where(function ($query) {
                $query->whereNull('estado')
                    ->orWhere('estado', 'activo');
            })
            ->get()
            ->sum('stock_actual');
    }

    public function getStockDisponibleAttribute(): float
    {
        return (float) $this->lotes()->disponibles()->get()->sum('stock_actual');
    }

    public function stockDisponible(): float
    {
        return $this->stock_disponible;
    }

    public function estaEnStockBajo(): bool
    {
        return (float) $this->stock_minimo > 0
            && $this->stockDisponible() <= (float) $this->stock_minimo;
    }

    public function tieneStock(float $cantidad = 1): bool
    {
        return $this->stock_disponible >= $cantidad;
    }

    public function estaDisponibleParaVenta(): bool
    {
        return (bool) $this->activo && ($this->estado === null || $this->estado === 'activo');
    }
}
