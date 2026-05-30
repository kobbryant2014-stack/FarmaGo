<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProveedorRequest;
use App\Models\Proveedor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class ProveedorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Proveedor::latest();

        if ($request->filled('buscar')) {
            $buscar = $request->string('buscar')->toString();
            $query->where(function ($query) use ($buscar) {
                $query->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('razon_social', 'like', "%{$buscar}%")
                    ->orWhere('ruc', 'like', "%{$buscar}%");
            });
        }

        return view('proveedores.index', [
            'proveedores' => $query->paginate(10)->withQueryString(),
            'buscar' => $request->string('buscar')->toString(),
        ]);
    }

    public function create(): View
    {
        return view('proveedores.create', [
            'proveedor' => new Proveedor(['activo' => true, 'estado' => 'activo']),
        ]);
    }

    public function store(ProveedorRequest $request): RedirectResponse
    {
        try {
            Proveedor::create($this->data($request));

            return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo guardar el proveedor.')->withInput();
        }
    }

    public function show(Proveedor $proveedor): View
    {
        return view('proveedores.show', [
            'proveedor' => $proveedor->load('compras'),
        ]);
    }

    public function edit(Proveedor $proveedor): View
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(ProveedorRequest $request, Proveedor $proveedor): RedirectResponse
    {
        try {
            $proveedor->update($this->data($request));

            return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo actualizar el proveedor.')->withInput();
        }
    }

    public function destroy(Proveedor $proveedor): RedirectResponse
    {
        try {
            $proveedor->update(['activo' => false, 'estado' => 'inactivo']);

            return redirect()->route('proveedores.index')->with('success', 'Proveedor desactivado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo desactivar el proveedor.');
        }
    }

    private function data(ProveedorRequest $request): array
    {
        return $request->safe()->merge([
            'tipo_documento' => 'RUC',
            'activo' => $request->boolean('activo', true),
            'estado' => $request->boolean('activo', true) ? 'activo' : 'inactivo',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ])->all();
    }
}
