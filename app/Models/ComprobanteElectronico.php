<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComprobanteElectronico extends Model
{
    protected $table = 'comprobantes_electronicos';

    protected $fillable = [
        'empresa_id',
        'sucursal_id',
        'venta_id',
        'cliente_id',
        'tipo_comprobante',
        'serie',
        'numero',
        'fecha_emision',
        'moneda_codigo',
        'subtotal',
        'igv_total',
        'total',
        'qr_payload',
        'xml_hash',
        'tipo_proveedor',
        'estado',
        'estado_sunat',
        'cdr_codigo',
        'cdr_descripcion',
        'respuesta_proveedor',
        'enviado_at',
        'aceptado_at',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'subtotal' => 'decimal:2',
        'igv_total' => 'decimal:2',
        'total' => 'decimal:2',
        'respuesta_proveedor' => 'array',
        'enviado_at' => 'datetime',
        'aceptado_at' => 'datetime',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ComprobanteItem::class);
    }

    public function getNumeroCompletoAttribute(): string
    {
        return $this->serie.'-'.str_pad((string) $this->numero, 8, '0', STR_PAD_LEFT);
    }
}
