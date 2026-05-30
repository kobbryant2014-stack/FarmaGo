<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Venta;
use App\Services\InventarioService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function index(): View
    {
        return view('reportes.index');
    }

    public function ventas(Request $request): View
    {
        $query = Venta::with(['cliente', 'usuario'])->completadas()->latest('fecha');

        if ($request->filled('desde')) {
            $query->whereDate('fecha', '>=', $request->input('desde'));
        }

        if ($request->filled('hasta')) {
            $query->whereDate('fecha', '<=', $request->input('hasta'));
        }

        $ventas = $query->get();

        return view('reportes.ventas', [
            'ventas' => $ventas,
            'total' => $ventas->sum('total'),
        ]);
    }

    public function stockBajo(InventarioService $inventario): View
    {
        return view('reportes.stock-bajo', [
            'productos' => $inventario->productosConStockBajo(),
        ]);
    }

    public function vencimientos(InventarioService $inventario): View
    {
        return view('reportes.vencimientos', [
            'lotes' => $inventario->lotesProximosVencer(90),
        ]);
    }

    public function productosMasVendidos(): View
    {
        $productos = DetalleVenta::with('producto')
            ->selectRaw('producto_id, SUM(cantidad) as cantidad_total, SUM(total) as total_vendido')
            ->groupBy('producto_id')
            ->orderByDesc('cantidad_total')
            ->limit(10)
            ->get();

        return view('reportes.productos-mas-vendidos', compact('productos'));
    }
}
