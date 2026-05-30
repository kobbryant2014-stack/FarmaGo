<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $loteId = $this->route('lote')?->id;

        return [
            'producto_id' => ['required', 'exists:productos,id'],
            'compra_id' => ['required', 'exists:compras,id'],
            'proveedor_id' => ['required', 'exists:proveedores,id'],
            'almacen_id' => ['nullable', 'exists:almacenes,id'],
            'numero_lote' => [
                'required',
                'string',
                'max:50',
                Rule::unique('lotes', 'numero_lote')
                    ->where('producto_id', $this->input('producto_id'))
                    ->ignore($loteId),
            ],
            'fecha_fabricacion' => ['nullable', 'date', 'before_or_equal:today'],
            'fecha_vencimiento' => ['required', 'date'],
            'stock_inicial' => ['required', 'numeric', 'min:0'],
            'precio_compra' => ['required', 'numeric', 'min:0'],
            'estado' => ['required', 'in:activo,inmovilizado,retirado,vencido,agotado'],
            'motivo_bloqueo' => ['nullable', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
        ];
    }
}
