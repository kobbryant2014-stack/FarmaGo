<?php

namespace App\Services;

use App\Models\Lote;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventarioService
{
    public function ajustarInventario(array $data): MovimientoInventario
    {
        return DB::transaction(function () use ($data) {
            $lote = Lote::with('producto')->lockForUpdate()->findOrFail($data['lote_id']);

            if (! $lote->activo) {
                throw new Exception("El lote {$lote->numero_lote} esta inactivo.");
            }

            if (empty($data['motivo'])) {
                throw new Exception('El motivo del ajuste de inventario es obligatorio.');
            }

            $userId = $data['user_id'] ?? Auth::id();

            if (! $userId) {
                throw new Exception('El ajuste de inventario requiere un usuario responsable.');
            }

            $stockActual = (float) $lote->stock_actual;
            $stockNuevo = (float) $data['stock_nuevo'];
            $diferencia = $stockNuevo - $stockActual;

            if (abs($diferencia) < 0.0001) {
                throw new Exception('El stock nuevo es igual al stock actual.');
            }

            if ($stockNuevo < 0) {
                throw new Exception('El stock nuevo no puede ser negativo.');
            }

            $movimiento = MovimientoInventario::create([
                'producto_id' => $lote->producto_id,
                'lote_id' => $lote->id,
                'almacen_id' => $lote->almacen_id,
                'user_id' => $userId,
                'tipo' => 'ajuste',
                'cantidad' => $diferencia,
                'origen' => 'ajuste_manual',
                'origen_id' => null,
                'motivo' => $data['motivo'],
                'fecha_movimiento' => now(),
                'estado' => 'valido',
            ]);

            if ($lote->almacen_id) {
                DB::table('ajustes_inventario')->insert([
                    'almacen_id' => $lote->almacen_id,
                    'producto_id' => $lote->producto_id,
                    'lote_id' => $lote->id,
                    'user_id' => $userId,
                    'autorizado_por' => $data['autorizado_por'] ?? null,
                    'stock_anterior' => $stockActual,
                    'stock_nuevo' => $stockNuevo,
                    'diferencia' => $diferencia,
                    'motivo' => $data['motivo'],
                    'estado' => 'aplicado',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            app(AuditService::class)->record('ajuste_inventario', 'inventario', [
                'user_id' => $userId,
                'entidad_tipo' => 'lotes',
                'entidad_id' => $lote->id,
                'datos_anteriores' => ['stock' => $stockActual],
                'datos_nuevos' => ['stock' => $stockNuevo],
                'motivo' => $data['motivo'],
            ]);

            return $movimiento->load(['lote', 'producto', 'usuario', 'almacen']);
        });
    }

    public function productosConStockBajo()
    {
        return Producto::with(['categoria', 'lotes' => fn ($query) => $query->disponibles()])
            ->vendibles()
            ->bajoStock()
            ->get()
            ->map(function (Producto $producto) {
                $producto->stock_disponible_calculado = $producto->stock_disponible;

                return $producto;
            });
    }

    public function lotesProximosVencer(int $dias = 30)
    {
        return Lote::with(['producto', 'proveedor', 'almacen'])
            ->disponibles()
            ->proximosVencer($dias)
            ->orderBy('fecha_vencimiento', 'asc')
            ->get()
            ->map(function (Lote $lote) {
                $lote->stock_disponible = $lote->stock_actual;
                $lote->dias_para_vencer = now()->startOfDay()->diffInDays($lote->fecha_vencimiento);

                return $lote;
            });
    }

    public function lotesVencidos()
    {
        return Lote::with(['producto', 'proveedor', 'almacen'])
            ->activos()
            ->vencidos()
            ->whereRaw(Lote::stockActualSql('lotes').' > 0')
            ->orderBy('fecha_vencimiento', 'asc')
            ->get()
            ->map(function (Lote $lote) {
                $lote->stock_disponible = $lote->stock_actual;

                return $lote;
            });
    }

    public function kardexProducto(int $productoId, ?string $fechaInicio = null, ?string $fechaFin = null)
    {
        return $this->kardexPorProducto($productoId, $fechaInicio, $fechaFin);
    }

    public function kardexPorProducto(int $productoId, ?string $fechaInicio = null, ?string $fechaFin = null)
    {
        $query = MovimientoInventario::with(['lote', 'usuario', 'almacen'])
            ->validos()
            ->where('producto_id', $productoId);

        if ($fechaInicio) {
            $query->whereDate('fecha_movimiento', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->whereDate('fecha_movimiento', '<=', $fechaFin);
        }

        $stockAcumulado = 0;

        return $query->orderBy('fecha_movimiento', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function (MovimientoInventario $movimiento) use (&$stockAcumulado) {
                $stockAcumulado += (float) $movimiento->cantidad;

                $movimiento->stock_acumulado = $stockAcumulado;

                return $movimiento;
            });
    }

    public function kardexLote(int $loteId)
    {
        return $this->kardexPorLote($loteId);
    }

    public function kardexPorLote(int $loteId)
    {
        Lote::findOrFail($loteId);
        $stockAcumulado = 0;

        return MovimientoInventario::with(['usuario', 'almacen'])
            ->validos()
            ->where('lote_id', $loteId)
            ->orderBy('fecha_movimiento', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function (MovimientoInventario $movimiento) use (&$stockAcumulado) {
                $stockAcumulado += (float) $movimiento->cantidad;

                $movimiento->stock_acumulado = $stockAcumulado;

                return $movimiento;
            });
    }

    public function resumenPorCategoria()
    {
        $stockSql = Lote::stockActualSql('l');

        return DB::table('productos')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select(
                'categorias.nombre as categoria',
                DB::raw('COUNT(productos.id) as total_productos'),
                DB::raw("SUM(COALESCE((
                    SELECT SUM({$stockSql})
                    FROM lotes l
                    WHERE l.producto_id = productos.id
                    AND l.activo = 1
                    AND (l.estado IS NULL OR l.estado = 'activo')
                ), 0)) as stock_total")
            )
            ->where('productos.activo', true)
            ->groupBy('categorias.id', 'categorias.nombre')
            ->get();
    }

    public function desactivarLotesVencidos(): int
    {
        return DB::transaction(function () {
            $lotesVencidos = Lote::activos()
                ->vencidos()
                ->where(function ($query) {
                    $query->whereNull('estado')
                        ->orWhere('estado', 'activo');
                })
                ->get();

            foreach ($lotesVencidos as $lote) {
                $lote->update([
                    'activo' => false,
                    'estado' => 'vencido',
                    'motivo_bloqueo' => "Vencimiento {$lote->fecha_vencimiento->format('d/m/Y')}",
                ]);

                app(AuditService::class)->record('bloqueo_lote_vencido', 'inventario', [
                    'entidad_tipo' => 'lotes',
                    'entidad_id' => $lote->id,
                    'datos_nuevos' => [
                        'estado' => 'vencido',
                        'activo' => false,
                    ],
                    'motivo' => "Vencimiento {$lote->fecha_vencimiento->format('d/m/Y')}",
                ]);
            }

            return $lotesVencidos->count();
        });
    }

    public function valorizacionInventario(): array
    {
        $lotes = Lote::with('producto')
            ->activos()
            ->where(function ($query) {
                $query->whereNull('estado')
                    ->orWhere('estado', 'activo');
            })
            ->get();

        $valorTotal = 0;
        $cantidadTotal = 0;
        $detalles = [];

        foreach ($lotes as $lote) {
            $stockActual = (float) $lote->stock_actual;

            if ($stockActual > 0) {
                $valorLote = $stockActual * (float) $lote->precio_compra;
                $valorTotal += $valorLote;
                $cantidadTotal += $stockActual;

                $detalles[] = [
                    'producto' => $lote->producto->nombre,
                    'lote' => $lote->numero_lote,
                    'stock' => $stockActual,
                    'precio_compra' => (float) $lote->precio_compra,
                    'valor_total' => $valorLote,
                ];
            }
        }

        return [
            'valor_total' => $valorTotal,
            'cantidad_total_unidades' => $cantidadTotal,
            'total_lotes_activos' => count($detalles),
            'detalles' => collect($detalles)->sortByDesc('valor_total')->values()->all(),
        ];
    }
}
