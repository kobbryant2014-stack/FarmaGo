<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lote extends Model
{
    protected $fillable = [
        'producto_id',
        'compra_id',
        'proveedor_id',
        'almacen_id',
        'numero_lote',
        'fecha_vencimiento',
        'fecha_fabricacion',
        'stock_inicial',
        'precio_compra',
        'estado',
        'motivo_bloqueo',
        'activo',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'fecha_fabricacion' => 'date',
        'precio_compra' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public static function stockActualSql(string $tableAlias = 'lotes'): string
    {
        return "{$tableAlias}.stock_inicial + COALESCE((
            SELECT SUM(CASE
                WHEN mi.tipo = 'entrada' AND mi.origen = 'compra' THEN 0
                ELSE mi.cantidad
            END)
            FROM movimientos_inventario mi
            WHERE mi.lote_id = {$tableAlias}.id
        ), 0)";
    }

    public function getStockActualAttribute(): float
    {
        $movimientos = $this->movimientos()
            ->where(function ($query) {
                $query->where('tipo', '!=', 'entrada')
                    ->orWhere('origen', '!=', 'compra')
                    ->orWhereNull('origen');
            })
            ->sum('cantidad');

        return (float) $this->stock_inicial + (float) $movimientos;
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeDisponibles($query, ?int $almacenId = null)
    {
        $stockSql = self::stockActualSql('lotes');

        return $query->where('activo', true)
            ->where(function ($query) {
                $query->whereNull('estado')
                    ->orWhere('estado', 'activo');
            })
            ->whereDate('fecha_vencimiento', '>', now()->toDateString())
            ->when($almacenId, fn ($query) => $query->where('almacen_id', $almacenId))
            ->whereRaw("{$stockSql} > 0")
            ->whereHas('producto', fn ($query) => $query->vendibles());
    }

    public function scopeVencidos($query)
    {
        return $query->whereDate('fecha_vencimiento', '<=', now()->toDateString());
    }

    public function scopeProximosVencer($query, int $dias = 30)
    {
        return $query->whereDate('fecha_vencimiento', '<=', now()->addDays($dias)->toDateString())
            ->whereDate('fecha_vencimiento', '>', now()->toDateString());
    }

    public function estaVencido(): bool
    {
        return $this->fecha_vencimiento->lte(now()->startOfDay());
    }

    public function estaInmovilizado(): bool
    {
        return in_array($this->estado, ['inmovilizado', 'retirado', 'vencido'], true);
    }

    public function estaDisponibleParaVenta(): bool
    {
        return (bool) $this->activo
            && ! $this->estaVencido()
            && ! $this->estaInmovilizado()
            && $this->producto?->estaDisponibleParaVenta();
    }

    public function proximoAVencer(int $dias = 30): bool
    {
        return $this->fecha_vencimiento->lte(now()->addDays($dias))
            && ! $this->estaVencido();
    }

    public function tieneStock(float $cantidad = 1): bool
    {
        return $this->stock_actual >= $cantidad;
    }
}
