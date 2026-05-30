<?php

namespace App\Http\Controllers;

use App\Models\ComprobanteElectronico;
use App\Models\Venta;
use App\Services\FacturacionElectronicaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class FacturacionElectronicaController extends Controller
{
    public function index(): View
    {
        return view('facturacion.index', [
            'comprobantes' => ComprobanteElectronico::with(['cliente', 'venta'])
                ->latest('fecha_emision')
                ->latest('id')
                ->paginate(15),
        ]);
    }

    public function show(ComprobanteElectronico $comprobante): View
    {
        return view('facturacion.show', [
            'comprobante' => $comprobante->load(['empresa', 'sucursal', 'cliente', 'venta', 'items.producto']),
        ]);
    }

    public function emitir(Request $request, Venta $venta, FacturacionElectronicaService $facturacion): RedirectResponse
    {
        $validated = $request->validate([
            'tipo_comprobante' => ['required', 'in:01,03'],
        ]);

        try {
            $comprobante = $facturacion->emitirDesdeVenta($venta, $validated['tipo_comprobante']);

            return redirect()
                ->route('facturacion.show', $comprobante)
                ->with('success', 'Comprobante electronico registrado. Queda pendiente el envio real a SUNAT.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors($exception->getMessage() ?: 'No se pudo emitir el comprobante electronico.');
        }
    }
}
