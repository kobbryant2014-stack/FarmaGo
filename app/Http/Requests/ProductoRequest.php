<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productoId = $this->route('producto')?->id;

        return [
            'codigo_barra' => ['nullable', 'string', 'max:50', Rule::unique('productos', 'codigo_barra')->ignore($productoId)],
            'codigo_interno' => ['nullable', 'string', 'max:50', Rule::unique('productos', 'codigo_interno')->ignore($productoId)],
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'dci' => ['nullable', 'string', 'max:180'],
            'principio_activo_texto' => ['nullable', 'string', 'max:180'],
            'registro_sanitario' => ['nullable', 'string', 'max:80'],
            'precio_compra' => ['nullable', 'numeric', 'min:0'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'stock_maximo' => ['nullable', 'integer', 'min:0'],
            'requiere_receta' => ['nullable', 'boolean'],
            'estado' => ['nullable', 'string', 'max:30'],
            'activo' => ['nullable', 'boolean'],
        ];
    }
}
