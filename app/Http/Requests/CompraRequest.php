<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proveedor_id' => ['required', 'exists:proveedores,id'],
            'almacen_id' => ['nullable', 'exists:almacenes,id'],
            'fecha' => ['nullable', 'date'],
            'productos' => ['required', 'array', 'min:1'],
            'productos.*.producto_id' => ['required', 'exists:productos,id'],
            'productos.*.numero_lote' => ['required', 'string', 'max:50'],
            'productos.*.fecha_vencimiento' => ['required', 'date', 'after:today'],
            'productos.*.fecha_fabricacion' => ['nullable', 'date', 'before_or_equal:today'],
            'productos.*.cantidad' => ['required', 'numeric', 'min:1'],
            'productos.*.precio_unitario' => ['required', 'numeric', 'min:0'],
        ];
    }
}
