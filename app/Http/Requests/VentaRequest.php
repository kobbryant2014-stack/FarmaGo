<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'metodo_pago' => ['required', 'string', 'max:30'],
            'tipo_comprobante' => ['nullable', 'in:BOLETA,FACTURA,TICKET'],
            'productos' => ['required', 'array', 'min:1'],
            'productos.*.producto_id' => ['required', 'exists:productos,id'],
            'productos.*.cantidad' => ['required', 'numeric', 'min:1'],
            'productos.*.precio_unitario' => ['nullable', 'numeric', 'min:0'],
            'productos.*.descuento' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
