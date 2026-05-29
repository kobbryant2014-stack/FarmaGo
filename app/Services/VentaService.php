<?php

namespace App\Services;

use App\Models\DetalleVenta;
use App\Models\Lote;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Models\Receta;
use App\Models\Venta;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaService
{
    public function procesarVenta(array $data): Venta
    {
        return DB::transaction(function () use ($data) {
            $this->validarRecetasMedicas($data);

            $venta = Venta::create([
                'cliente_id' => $data['cliente_id'] ?? null,
                'user_id' => Auth::id(),
                'total' => 0,
                'metodo_pago' => $data['metodo_pago'],
                'estado' => 'completada',
                'fecha' => now(),
            ]);

            $total = 0;

            foreach ($data['productos'] as $item) {
                $total += $this->procesarDetalleVenta($venta, $item);
            }

            $venta->update(['total' => $total]);

            if (! empty($data['recetas'])) {
                $venta->recetas()->attach($data['recetas']);
            }

            return $venta->load([
                'detalles.producto',
                'detalles.lote',
                'cliente',
                'recetas',
            ]);
        });
    }

    protected function procesarDetalleVenta(Venta $venta, array $item): float
    {
        if (! empty($item['lote_id'])) {
            $lote = Lote::with('producto')->lockForUpdate()->findOrFail($item['lote_id']);
            $this->validarLoteParaVenta($lote, (float) $item['cantidad']);

            return $this->registrarDetalleYSalida($venta, $lote, (float) $item['cantidad'], $item);
        }

        if (empty($item['producto_id'])) {
            throw new Exception('Debe indicar producto_id o lote_id para vender.');
        }

        $asignaciones = app(FefoStockService::class)->seleccionarLotesParaSalida(
            (int) $item['producto_id'],
            (float) $item['cantidad'],
            $item['almacen_id'] ?? null
        );

        $subtotal = 0;

        foreach ($asignaciones as $asignacion) {
            $subtotal += $this->registrarDetalleYSalida(
                $venta,
                $asignacion['lote'],
                (float) $asignacion['cantidad'],
                $item
            );
        }

        return $subtotal;
    }

    protected function registrarDetalleYSalida(Venta $venta, Lote $lote, float $cantidad, array $item): float
    {
        $precioUnitario = $item['precio_unitario'] ?? $lote->producto->precio_venta;
        $subtotal = $cantidad * (float) $precioUnitario;

        DetalleVenta::create([
            'venta_id' => $venta->id,
            'producto_id' => $lote->producto_id,
            'lote_id' => $lote->id,
            'receta_id' => $item['receta_id'] ?? null,
            'cantidad' => $cantidad,
            'precio_unitario' => $precioUnitario,
            'subtotal' => $subtotal,
            'unidad_venta' => $item['unidad_venta'] ?? 'unidad',
            'descuento' => $item['descuento'] ?? 0,
            'afectacion_tributaria' => $item['afectacion_tributaria'] ?? $lote->producto->afectacion_tributaria ?? '10',
            'igv' => $item['igv'] ?? 0,
            'total' => $item['total'] ?? $subtotal,
        ]);

        MovimientoInventario::create([
            'producto_id' => $lote->producto_id,
            'lote_id' => $lote->id,
            'almacen_id' => $lote->almacen_id,
            'user_id' => Auth::id(),
            'tipo' => 'salida',
            'cantidad' => -$cantidad,
            'origen' => 'venta',
            'origen_id' => $venta->id,
            'motivo' => "Venta #{$venta->id}",
            'fecha_movimiento' => now(),
            'estado' => 'valido',
        ]);

        return $subtotal;
    }

    protected function validarLoteParaVenta(Lote $lote, float $cantidad): void
    {
        app(FefoStockService::class)->validarLoteParaSalida($lote, $cantidad);
    }

    protected function validarRecetasMedicas(array $data): void
    {
        $productosIds = collect($data['productos'])
            ->map(function (array $item) {
                if (! empty($item['producto_id'])) {
                    return $item['producto_id'];
                }

                if (! empty($item['lote_id'])) {
                    return Lote::find($item['lote_id'])?->producto_id;
                }

                return null;
            })
            ->filter()
            ->unique()
            ->values();

        $productosConReceta = Producto::whereIn('id', $productosIds)
            ->where(function ($query) {
                $query->where('requiere_receta', true)
                    ->orWhere('requiere_receta_retenida', true)
                    ->orWhere('es_controlado', true);
            })
            ->get();

        if ($productosConReceta->isEmpty()) {
            return;
        }

        if (empty($data['recetas'])) {
            $nombres = $productosConReceta->pluck('nombre')->join(', ');

            throw new Exception("Los siguientes productos requieren receta medica: {$nombres}");
        }

        $recetasIds = $data['recetas'];
        $recetasValidas = Receta::whereIn('id', $recetasIds)->count();

        if ($recetasValidas !== count($recetasIds)) {
            throw new Exception('Una o mas recetas medicas no son validas.');
        }
    }

    public function anularVenta(int $ventaId, string $motivo): Venta
    {
        return DB::transaction(function () use ($ventaId, $motivo) {
            $venta = Venta::with('detalles')->findOrFail($ventaId);

            if (! $venta->puedeAnularse()) {
                throw new Exception("La venta #{$ventaId} no puede ser anulada porque ya esta anulada.");
            }

            foreach ($venta->detalles as $detalle) {
                MovimientoInventario::create([
                    'producto_id' => $detalle->producto_id,
                    'lote_id' => $detalle->lote_id,
                    'almacen_id' => $detalle->lote?->almacen_id,
                    'user_id' => Auth::id(),
                    'tipo' => 'entrada',
                    'cantidad' => $detalle->cantidad,
                    'origen' => 'anulacion_venta',
                    'origen_id' => $venta->id,
                    'motivo' => "Anulacion de venta #{$venta->id}: {$motivo}",
                    'fecha_movimiento' => now(),
                    'estado' => 'valido',
                ]);
            }

            $venta->update([
                'estado' => 'anulada',
                'anulado_por' => Auth::id(),
                'fecha_anulacion' => now(),
                'motivo_anulacion' => $motivo,
            ]);

            return $venta->fresh(['detalles', 'anuladoPor']);
        });
    }

    public function ventasDelDia()
    {
        return Venta::with(['cliente', 'usuario', 'detalles.producto'])
            ->whereDate('fecha', today())
            ->completadas()
            ->orderBy('fecha', 'desc')
            ->get();
    }

    public function totalVentasDelDia(): float
    {
        return (float) Venta::whereDate('fecha', today())
            ->completadas()
            ->sum('total');
    }

    public function ventasDelUsuario(int $userId, ?string $fechaInicio = null, ?string $fechaFin = null)
    {
        $query = Venta::with(['cliente', 'detalles.producto'])
            ->where('user_id', $userId);

        if ($fechaInicio) {
            $query->whereDate('fecha', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->whereDate('fecha', '<=', $fechaFin);
        }

        return $query->orderBy('fecha', 'desc')->get();
    }

    public function buscarProductosParaVenta(string $termino)
    {
        return Producto::with(['categoria', 'lotes' => fn ($query) => $query->disponibles()->orderBy('fecha_vencimiento')])
            ->vendibles()
            ->busquedaPos($termino)
            ->get()
            ->filter(fn (Producto $producto) => $producto->lotes->isNotEmpty())
            ->values();
    }

    public function modificarVenta(int $ventaId, array $data, string $motivo): Venta
    {
        return DB::transaction(function () use ($ventaId, $data, $motivo) {
            $ventaOriginal = Venta::with('detalles')->findOrFail($ventaId);

            if (! $ventaOriginal->puedeModificarse()) {
                throw new Exception(
                    "La venta #{$ventaId} no puede modificarse. Estado: {$ventaOriginal->estado}."
                );
            }

            foreach ($ventaOriginal->detalles as $detalle) {
                MovimientoInventario::create([
                    'producto_id' => $detalle->producto_id,
                    'lote_id' => $detalle->lote_id,
                    'almacen_id' => $detalle->lote?->almacen_id,
                    'user_id' => Auth::id(),
                    'tipo' => 'entrada',
                    'cantidad' => $detalle->cantidad,
                    'origen' => 'modificacion_venta',
                    'origen_id' => $ventaOriginal->id,
                    'motivo' => "Modificacion de venta #{$ventaOriginal->id}: {$motivo}",
                    'fecha_movimiento' => now(),
                    'estado' => 'valido',
                ]);
            }

            $nuevaVenta = $this->procesarVenta($data);

            $ventaOriginal->update([
                'estado' => 'anulada',
                'anulado_por' => Auth::id(),
                'fecha_anulacion' => now(),
                'motivo_anulacion' => "Modificada - Razon: {$motivo}. Nueva venta: #{$nuevaVenta->id}",
                'reemplazada_por' => $nuevaVenta->id,
            ]);

            $nuevaVenta->update([
                'venta_original_id' => $ventaOriginal->id,
            ]);

            return $nuevaVenta->load([
                'detalles.producto',
                'detalles.lote',
                'cliente',
                'recetas',
                'ventaOriginal',
            ]);
        });
    }

    public function historialModificacionesVenta(int $ventaId)
    {
        $venta = Venta::findOrFail($ventaId);

        return $venta->cadenaModificaciones()->map(function ($v, $index) {
            return [
                'version' => $index + 1,
                'id' => $v->id,
                'fecha' => $v->fecha,
                'total' => $v->total,
                'estado' => $v->estado,
                'usuario' => $v->usuario->name,
                'cliente' => $v->cliente ? $v->cliente->nombre : 'Publico general',
                'es_original' => is_null($v->venta_original_id),
                'es_activa' => is_null($v->reemplazada_por) && $v->estado === 'completada',
                'motivo_anulacion' => $v->motivo_anulacion,
            ];
        });
    }
}
