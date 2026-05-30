<?php

namespace App\Services;

use InvalidArgumentException;

class VentaCalculatorService
{
    public function calcularDetalle(
        float $cantidad,
        float $precioUnitario,
        float $descuento = 0,
        float $igvPorcentaje = 18
    ): array {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException('La cantidad vendida debe ser mayor a cero.');
        }

        if ($precioUnitario < 0) {
            throw new InvalidArgumentException('El precio unitario no puede ser negativo.');
        }

        if ($descuento < 0) {
            throw new InvalidArgumentException('El descuento no puede ser negativo.');
        }

        $subtotal = $this->roundMoney($cantidad * $precioUnitario);

        if ($descuento > $subtotal) {
            throw new InvalidArgumentException('El descuento no puede ser mayor al subtotal.');
        }

        $baseImponible = $this->roundMoney($subtotal - $descuento);
        $igv = $this->roundMoney($baseImponible * ($igvPorcentaje / 100));
        $total = $this->roundMoney($baseImponible + $igv);

        return [
            'cantidad' => $cantidad,
            'precio_unitario' => $this->roundMoney($precioUnitario),
            'subtotal' => $subtotal,
            'descuento' => $this->roundMoney($descuento),
            'base_imponible' => $baseImponible,
            'igv' => $igv,
            'total' => $total,
        ];
    }

    public function calcularTotales(array $detalles): array
    {
        if (empty($detalles)) {
            throw new InvalidArgumentException('La venta debe tener al menos un detalle.');
        }

        $subtotal = 0;
        $descuento = 0;
        $gravado = 0;
        $igv = 0;
        $total = 0;

        foreach ($detalles as $detalle) {
            $subtotal += (float) $detalle['subtotal'];
            $descuento += (float) $detalle['descuento'];
            $gravado += (float) $detalle['base_imponible'];
            $igv += (float) $detalle['igv'];
            $total += (float) $detalle['total'];
        }

        return [
            'subtotal' => $this->roundMoney($subtotal),
            'descuento_total' => $this->roundMoney($descuento),
            'gravado_total' => $this->roundMoney($gravado),
            'exonerado_total' => 0,
            'inafecto_total' => 0,
            'igv_total' => $this->roundMoney($igv),
            'total' => $this->roundMoney($total),
        ];
    }

    private function roundMoney(float $value): float
    {
        return round($value, 2);
    }
}
