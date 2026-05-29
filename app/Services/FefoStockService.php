<?php

namespace App\Services;

use App\Models\Lote;
use App\Models\Producto;
use Exception;
use Illuminate\Support\Collection;

class FefoStockService
{
    public function seleccionarLotesParaSalida(
        int $productoId,
        float $cantidad,
        ?int $almacenId = null
    ): Collection {
        if ($cantidad <= 0) {
            throw new Exception('La cantidad solicitada debe ser mayor a cero.');
        }

        $producto = Producto::findOrFail($productoId);

        if (! $producto->estaDisponibleParaVenta()) {
            throw new Exception("El producto {$producto->nombre} no esta disponible para venta.");
        }

        $lotes = $this->lotesDisponibles($productoId, $almacenId)
            ->lockForUpdate()
            ->get();

        $pendiente = $cantidad;
        $seleccion = collect();

        foreach ($lotes as $lote) {
            $stockDisponible = (float) $lote->stock_actual;

            if ($stockDisponible <= 0) {
                continue;
            }

            $cantidadLote = min($pendiente, $stockDisponible);

            $seleccion->push([
                'lote' => $lote,
                'cantidad' => $cantidadLote,
            ]);

            $pendiente -= $cantidadLote;

            if ($pendiente <= 0) {
                break;
            }
        }

        if ($pendiente > 0) {
            $stockDisponible = $cantidad - $pendiente;

            throw new Exception(
                "Stock insuficiente para {$producto->nombre}. Disponible: {$stockDisponible}, solicitado: {$cantidad}."
            );
        }

        return $seleccion;
    }

    public function validarLoteParaSalida(Lote $lote, float $cantidad): void
    {
        $lote->loadMissing('producto');

        if (! $lote->producto || ! $lote->producto->estaDisponibleParaVenta()) {
            throw new Exception("El producto del lote {$lote->numero_lote} no esta disponible para venta.");
        }

        if (! $lote->activo) {
            throw new Exception("El lote {$lote->numero_lote} esta inactivo.");
        }

        if ($lote->estaInmovilizado()) {
            throw new Exception("El lote {$lote->numero_lote} esta {$lote->estado}.");
        }

        if ($lote->estaVencido()) {
            throw new Exception(
                "El lote {$lote->numero_lote} esta vencido. Vencimiento: {$lote->fecha_vencimiento->format('d/m/Y')}."
            );
        }

        if (! $lote->tieneStock($cantidad)) {
            throw new Exception(
                "Stock insuficiente para {$lote->producto->nombre}. Disponible en lote {$lote->numero_lote}: {$lote->stock_actual}, solicitado: {$cantidad}."
            );
        }
    }

    public function lotesDisponibles(int $productoId, ?int $almacenId = null)
    {
        return Lote::with(['producto', 'almacen'])
            ->where('producto_id', $productoId)
            ->disponibles($almacenId)
            ->orderBy('fecha_vencimiento', 'asc')
            ->orderBy('id', 'asc');
    }
}
