<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $query = Cliente::latest();

        if ($request->filled('buscar')) {
            $buscar = $request->string('buscar')->toString();
            $query->where(function ($query) use ($buscar) {
                $query->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('documento', 'like', "%{$buscar}%")
                    ->orWhere('razon_social', 'like', "%{$buscar}%");
            });
        }

        return view('clientes.index', [
            'clientes' => $query->paginate(10)->withQueryString(),
            'buscar' => $request->string('buscar')->toString(),
        ]);
    }

    public function create(): View
    {
        return view('clientes.create', [
            'cliente' => new Cliente(['activo' => true, 'tipo_documento' => 'DNI']),
        ]);
    }

    public function store(ClienteRequest $request): RedirectResponse
    {
        try {
            Cliente::create($this->data($request));

            return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo guardar el cliente.')->withInput();
        }
    }

    public function show(Cliente $cliente): View
    {
        return view('clientes.show', [
            'cliente' => $cliente->load('ventas'),
        ]);
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(ClienteRequest $request, Cliente $cliente): RedirectResponse
    {
        try {
            $cliente->update($this->data($request));

            return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo actualizar el cliente.')->withInput();
        }
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        try {
            $cliente->update(['activo' => false, 'estado' => 'inactivo']);

            return redirect()->route('clientes.index')->with('success', 'Cliente desactivado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo desactivar el cliente.');
        }
    }

    private function data(ClienteRequest $request): array
    {
        return $request->safe()->merge([
            'activo' => $request->boolean('activo', true),
            'estado' => $request->boolean('activo', true) ? 'activo' : 'inactivo',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ])->all();
    }
}
