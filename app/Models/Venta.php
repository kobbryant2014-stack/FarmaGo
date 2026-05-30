<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    protected $fillable = [
        'cliente_id',
        'user_id',
        'anulado_por',
        'reemplazada_por',
        'venta_original_id',
        'subtotal',
        'descuento_total',
        'gravado_total',
        'exonerado_total',
        'inafecto_total',
        'igv_total',
        'total',
        'metodo_pago',
        'estado',
        'fecha',
        'fecha_anulacion',
        'motivo_anulacion',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'descuento_total' => 'decimal:2',
        'gravado_total' => 'decimal:2',
        'exonerado_total' => 'decimal:2',
        'inafecto_total' => 'decimal:2',
        'igv_total' => 'decimal:2',
        'total' => 'decimal:2',
        'fecha' => 'datetime',
        'fecha_anulacion' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function anuladoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'anulado_por');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function recetas(): BelongsToMany
    {
        return $this->belongsToMany(Receta::class, 'venta_receta')
            ->withTimestamps();
    }

    public function ventaOriginal(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'venta_original_id');
    }

    public function reemplazadaPor(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'reemplazada_por');
    }

    public function historialModificaciones(): HasMany
    {
        return $this->hasMany(Venta::class, 'venta_original_id');
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    public function scopeAnuladas($query)
    {
        return $query->where('estado', 'anulada');
    }

    public function scopeDelUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOriginales($query)
    {
        return $query->whereNull('venta_original_id');
    }

    public function scopeModificaciones($query)
    {
        return $query->whereNotNull('venta_original_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', 'completada')
            ->whereNull('reemplazada_por');
    }

    public function puedeAnularse(): bool
    {
        return $this->estado === 'completada'
            && is_null($this->reemplazada_por);
    }

    public function puedeModificarse(): bool
    {
        return $this->estado === 'completada'
            && is_null($this->reemplazada_por);
    }

    public function esModificacion(): bool
    {
        return ! is_null($this->venta_original_id);
    }

    public function fueModificada(): bool
    {
        return ! is_null($this->reemplazada_por);
    }

    public function ventaActiva()
    {
        $venta = $this;

        while ($venta->reemplazada_por) {
            $venta = $venta->reemplazadaPor;
        }

        return $venta;
    }

    public function cadenaModificaciones()
    {
        $cadena = collect([$this]);
        $venta = $this;

        while ($venta->ventaOriginal) {
            $venta = $venta->ventaOriginal;
            $cadena->prepend($venta);
        }

        $venta = $this;
        while ($venta->reemplazadaPor) {
            $venta = $venta->reemplazadaPor;
            $cadena->push($venta);
        }

        return $cadena->unique('id')->values();
    }
}
