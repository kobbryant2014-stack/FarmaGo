<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use App\Models\Producto;
use App\Services\InventarioService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class KardexController extends Controller
{
    public function index(): View
    {
        return view('kardex.index', [
            'productos' => Producto::orderBy('nombre')->get(),
            'lotes' => Lote::with('producto')->orderBy('numero_lote')->get(),
        ]);
    }

    public function producto(Request $request, Producto $producto, InventarioService $inventario): View
    {
        try {
            $movimientos = $inventario->kardexPorProducto(
                $producto->id,
                $request->input('fecha_inicio'),
                $request->input('fecha_fin')
            );
        } catch (Throwable $exception) {
            report($exception);
            $movimientos = collect();
            session()->flash('error', 'No se pudo consultar el Kardex del producto.');
        }

        return view('kardex.producto', compact('producto', 'movimientos'));
    }

    public function lote(Lote $lote, InventarioService $inventario): View
    {
        try {
            $movimientos = $inventario->kardexPorLote($lote->id);
        } catch (Throwable $exception) {
            report($exception);
            $movimientos = collect();
            session()->flash('error', 'No se pudo consultar el Kardex del lote.');
        }

        return view('kardex.lote', [
            'lote' => $lote->load('producto'),
            'movimientos' => $movimientos,
        ]);
    }
}
