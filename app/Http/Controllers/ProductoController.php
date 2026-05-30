<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ProductoController extends Controller
{
    public function index(Request $request): View
    {
        $query = Producto::with('categoria')
            ->withCount('lotes')
            ->latest();

        if ($request->filled('buscar')) {
            $query->busquedaPos($request->string('buscar')->toString());
        }

        return view('productos.index', [
            'productos' => $query->paginate(10)->withQueryString(),
            'buscar' => $request->string('buscar')->toString(),
        ]);
    }

    public function consultarPrecio(): View
    {
        return view('productos.consultar-precio');
    }

    public function buscarPorCodigo(Request $request): JsonResponse
    {
        $codigo = trim($request->string('codigo')->toString());

        if ($codigo === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Ingrese o escanee un codigo.',
            ], 422);
        }

        $producto = Producto::with(['categoria', 'laboratorio', 'presentacion'])
            ->vendibles()
            ->where(function ($query) use ($codigo) {
                $query->where('codigo_barra', $codigo)
                    ->orWhere('codigo_interno', $codigo)
                    ->orWhere('nombre', 'like', "%{$codigo}%");
            })
            ->first();

        if (! $producto) {
            return response()->json([
                'ok' => false,
                'message' => 'Producto no encontrado.',
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'producto' => [
                'id' => $producto->id,
                'codigo' => $producto->codigo_barra ?? $producto->codigo_interno,
                'nombre' => $producto->nombre,
                'categoria' => $producto->categoria->nombre ?? '-',
                'laboratorio' => $producto->laboratorio->nombre ?? $producto->fabricante ?? '-',
                'presentacion' => $producto->presentacion->nombre ?? $producto->presentacion_texto ?? '-',
                'precio_venta' => number_format((float) $producto->precio_venta, 2, '.', ''),
                'precio_compra' => number_format((float) $producto->precio_compra, 2, '.', ''),
                'stock_disponible' => number_format((float) $producto->stock_disponible, 2, '.', ''),
                'stock_minimo' => $producto->stock_minimo,
                'alerta_stock' => $producto->estaEnStockBajo(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('productos.create', [
            'producto' => new Producto(['activo' => true, 'estado' => 'activo']),
            'categorias' => Categoria::activos()->orderBy('nombre')->get(),
        ]);
    }

    public function store(ProductoRequest $request): RedirectResponse
    {
        try {
            Producto::create($this->data($request));

            return redirect()
                ->route('productos.index')
                ->with('success', 'Producto creado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withErrors('No se pudo guardar el producto.')
                ->withInput();
        }
    }

    public function show(Producto $producto): View
    {
        return view('productos.show', [
            'producto' => $producto->load(['categoria', 'lotes.almacen']),
        ]);
    }

    public function edit(Producto $producto): View
    {
        return view('productos.edit', [
            'producto' => $producto,
            'categorias' => Categoria::activos()->orderBy('nombre')->get(),
        ]);
    }

    public function update(ProductoRequest $request, Producto $producto): RedirectResponse
    {
        try {
            $producto->update($this->data($request));

            return redirect()
                ->route('productos.index')
                ->with('success', 'Producto actualizado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withErrors('No se pudo actualizar el producto.')
                ->withInput();
        }
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        try {
            $producto->update([
                'activo' => false,
                'estado' => 'inactivo',
            ]);

            return redirect()
                ->route('productos.index')
                ->with('success', 'Producto desactivado correctamente.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors('No se pudo desactivar el producto.');
        }
    }

    private function data(ProductoRequest $request): array
    {
        return $request->safe()->merge([
            'requiere_receta' => $request->boolean('requiere_receta'),
            'activo' => $request->boolean('activo', true),
            'estado' => $request->input('estado', 'activo'),
            'precio_compra' => $request->input('precio_compra', 0),
        ])->all();
    }
}
