<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente')?->id;
        $tipoDocumento = $this->input('tipo_documento');

        $documentRules = ['nullable', 'string', Rule::unique('clientes', 'documento')->ignore($clienteId)];

        if ($tipoDocumento === 'DNI') {
            $documentRules[] = 'digits:8';
        }

        if ($tipoDocumento === 'RUC') {
            $documentRules[] = 'digits:11';
        }

        return [
            'tipo_documento' => ['nullable', 'string', 'max:10'],
            'documento' => $documentRules,
            'nombre' => ['required', 'string', 'max:150'],
            'nombres' => ['nullable', 'string', 'max:120'],
            'apellidos' => ['nullable', 'string', 'max:120'],
            'razon_social' => ['nullable', 'string', 'max:180'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'direccion' => ['nullable', 'string'],
            'activo' => ['nullable', 'boolean'],
        ];
    }
}
