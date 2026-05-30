<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SerieDocumento extends Model
{
    protected $table = 'series_documentos';

    protected $fillable = [
        'empresa_id',
        'sucursal_id',
        'tipo_comprobante',
        'serie',
        'correlativo_actual',
        'activo',
    ];

    protected $casts = [
        'correlativo_actual' => 'integer',
        'activo' => 'boolean',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }
}
