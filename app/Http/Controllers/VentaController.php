<?php

namespace App\Http\Controllers;

use App\Http\Requests\VentaRequest;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Venta;
use App\Services\FacturacionElectronicaService;
use App\Services\VentaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class VentaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Venta::with(['cliente', 'usuario'])->latest('fecha');

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->date('fecha'));
        }

        return view('ventas.index', [
            'ventas' => $query->paginate(10)->withQueryString(),
            'fecha' => $request->input('fecha'),
        ]);
    }

    public function create(): View
    {
        return view('ventas.create', [
            'clientes' => Cliente::activos()->orderBy('nombre')->get(),
            'productos' => Producto::vendibles()->orderBy('nombre')->get(),
        ]);
    }

    public function store(
        VentaRequest $request,
        VentaService $ventas,
        FacturacionElectronicaService $facturacion
    ): RedirectResponse
    {
        try {
            $venta = $ventas->procesarVenta($request->validated());
            $tipoComprobante = $request->input('tipo_comprobante', 'TICKET');

            if (in_array($tipoComprobante, ['BOLETA', 'FACTURA'], true)) {
                $comprobante = $facturacion->emitirDesdeVenta(
                    $venta,
                    $tipoComprobante === 'FACTURA' ? '01' : '03'
                );

                return redirect()
                    ->route('facturacion.show', $comprobante)
                    ->with('success', "{$tipoComprobante} electronica registrada correctamente.");
            }

            return redirect()
                ->route('ventas.comprobante', [
                    'venta' => $venta,
                    'tipo' => $tipoComprobante,
                ])
                ->with('success', 'Venta registrada correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withErrors($exception->getMessage() ?: 'No se pudo registrar la venta.')
                ->withInput();
        }
    }

    public function show(Venta $venta): View
    {
        return view('ventas.show', [
            'venta' => $venta->load(['cliente', 'usuario', 'detalles.producto', 'detalles.lote']),
        ]);
    }

    public function comprobante(Request $request, Venta $venta): View
    {
        return view('ventas.comprobante', [
            'venta' => $venta->load(['cliente', 'usuario', 'detalles.producto', 'detalles.lote']),
            'tipo' => $request->string('tipo', 'TICKET')->toString(),
        ]);
    }

    public function destroy(Venta $venta, VentaService $ventas): RedirectResponse
    {
        try {
            $ventas->anularVenta($venta->id, 'Anulacion desde interfaz administrativa');

            return redirect()->route('ventas.index')->with('success', 'Venta anulada correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors($exception->getMessage() ?: 'No se pudo anular la venta.');
        }
    }
}
