<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompraRequest;
use App\Models\Almacen;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Services\CompraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class CompraController extends Controller
{
    public function index(Request $request): View
    {
        $query = Compra::with(['proveedor', 'usuario'])->latest('fecha');

        if ($request->filled('buscar')) {
            $buscar = $request->string('buscar')->toString();
            $query->whereHas('proveedor', function ($query) use ($buscar) {
                $query->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('ruc', 'like', "%{$buscar}%");
            });
        }

        return view('compras.index', [
            'compras' => $query->paginate(10)->withQueryString(),
            'buscar' => $request->string('buscar')->toString(),
        ]);
    }

    public function create(): View
    {
        return view('compras.create', [
            'proveedores' => Proveedor::activos()->orderBy('nombre')->get(),
            'productos' => Producto::vendibles()->orderBy('nombre')->get(),
            'almacenes' => Almacen::activos()->orderBy('nombre')->get(),
        ]);
    }

    public function store(CompraRequest $request, CompraService $compras): RedirectResponse
    {
        try {
            $compra = $compras->registrarCompra($request->validated());

            return redirect()
                ->route('compras.show', $compra)
                ->with('success', 'Compra registrada correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withErrors($exception->getMessage() ?: 'No se pudo registrar la compra.')
                ->withInput();
        }
    }

    public function show(Compra $compra): View
    {
        return view('compras.show', [
            'compra' => $compra->load(['proveedor', 'usuario', 'detalles.producto', 'detalles.lote']),
        ]);
    }

    public function destroy(Compra $compra, CompraService $compras): RedirectResponse
    {
        try {
            $compras->anularCompra($compra->id, 'Anulacion desde interfaz administrativa');

            return redirect()->route('compras.index')->with('success', 'Compra anulada correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors($exception->getMessage() ?: 'No se pudo anular la compra.');
        }
    }
}
