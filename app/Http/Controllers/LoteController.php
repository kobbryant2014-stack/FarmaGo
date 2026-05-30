<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoteRequest;
use App\Models\Almacen;
use App\Models\Compra;
use App\Models\Lote;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class LoteController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lote::with(['producto', 'proveedor', 'almacen'])
            ->orderBy('fecha_vencimiento');

        if ($request->filled('buscar')) {
            $buscar = $request->string('buscar')->toString();
            $query->where(function ($query) use ($buscar) {
                $query->where('numero_lote', 'like', "%{$buscar}%")
                    ->orWhereHas('producto', fn ($query) => $query->where('nombre', 'like', "%{$buscar}%"));
            });
        }

        return view('lotes.index', [
            'lotes' => $query->paginate(10)->withQueryString(),
            'buscar' => $request->string('buscar')->toString(),
        ]);
    }

    public function create(): View
    {
        return view('lotes.create', $this->formData(new Lote(['activo' => true, 'estado' => 'activo'])));
    }

    public function store(LoteRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $lote = Lote::create($request->safe()->merge([
                    'activo' => $request->boolean('activo', true),
                ])->all());

                if ((float) $lote->stock_inicial > 0) {
                    MovimientoInventario::create([
                        'producto_id' => $lote->producto_id,
                        'lote_id' => $lote->id,
                        'almacen_id' => $lote->almacen_id,
                        'user_id' => Auth::id(),
                        'tipo' => 'entrada',
                        'cantidad' => $lote->stock_inicial,
                        'origen' => 'compra',
                        'origen_id' => $lote->compra_id,
                        'motivo' => "Registro manual de lote {$lote->numero_lote}",
                        'fecha_movimiento' => now(),
                        'estado' => 'valido',
                    ]);
                }
            });

            return redirect()->route('lotes.index')->with('success', 'Lote creado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo guardar el lote.')->withInput();
        }
    }

    public function show(Lote $lote): View
    {
        return view('lotes.show', [
            'lote' => $lote->load(['producto', 'proveedor', 'almacen', 'movimientos']),
        ]);
    }

    public function edit(Lote $lote): View
    {
        return view('lotes.edit', $this->formData($lote));
    }

    public function update(LoteRequest $request, Lote $lote): RedirectResponse
    {
        try {
            $lote->update($request->safe()->merge([
                'activo' => $request->boolean('activo', true),
            ])->all());

            return redirect()->route('lotes.index')->with('success', 'Lote actualizado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo actualizar el lote.')->withInput();
        }
    }

    public function destroy(Lote $lote): RedirectResponse
    {
        try {
            $lote->update([
                'activo' => false,
                'estado' => 'retirado',
                'motivo_bloqueo' => 'Desactivado desde gestion de lotes',
            ]);

            return redirect()->route('lotes.index')->with('success', 'Lote desactivado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo desactivar el lote.');
        }
    }

    private function formData(Lote $lote): array
    {
        return [
            'lote' => $lote,
            'productos' => Producto::vendibles()->orderBy('nombre')->get(),
            'proveedores' => Proveedor::activos()->orderBy('nombre')->get(),
            'compras' => Compra::recibidas()->latest('fecha')->limit(100)->get(),
            'almacenes' => Almacen::activos()->orderBy('nombre')->get(),
        ];
    }
}
