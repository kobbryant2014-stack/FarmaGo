<?php

namespace App\Services;

use InvalidArgumentException;

class VentaCalculatorService
{
    public function calcularDetalle(
        float $cantidad,
        float $precioUnitario,
        float $descuento = 0,
        float $igvPorcentaje = 18,
        ?string $afectacionTributaria = null
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

        $base = $this->roundMoney($subtotal - $descuento);
        $afectacion = $afectacionTributaria ?? '10';
        $igv = 0.0;
        $total = $base;

        if ($afectacion === '10') {
            $igv = $this->roundMoney($base * ($igvPorcentaje / 100));
            $total = $this->roundMoney($base + $igv);
        } elseif ($afectacion === '20') {
            $total = $this->roundMoney($base);
        } elseif ($afectacion === '30') {
            $total = $this->roundMoney($base);
        }

        return [
            'cantidad' => $cantidad,
            'precio_unitario' => $this->roundMoney($precioUnitario),
            'subtotal' => $subtotal,
            'descuento' => $this->roundMoney($descuento),
            'base_imponible' => $base,
            'afectacion_tributaria' => $afectacion,
            'igv' => $igv,
            'total' => $total,
        ];
    }

    public function calcularTotales(array $detalles): array
    {
        if (empty($detalles)) {
            throw new InvalidArgumentException('La venta debe tener al menos un detalle.');
        }

        $subtotal = 0.0;
        $descuento = 0.0;
        $gravado = 0.0;
        $exonerado = 0.0;
        $inafecto = 0.0;
        $igv = 0.0;
        $total = 0.0;

        foreach ($detalles as $detalle) {
            $subtotal += (float) ($detalle['subtotal'] ?? 0);
            $descuento += (float) ($detalle['descuento'] ?? 0);
            $base = (float) ($detalle['base_imponible'] ?? 0);
            $afectacion = (string) ($detalle['afectacion_tributaria'] ?? '10');

            if ($afectacion === '20') {
                $exonerado += $base;
            } elseif ($afectacion === '30') {
                $inafecto += $base;
            } else {
                $gravado += $base;
            }

            $igv += (float) ($detalle['igv'] ?? 0);
            $total += (float) ($detalle['total'] ?? 0);
        }

        $igv = $this->roundMoney($igv);
        $total = $this->roundMoney($total);

        return [
            'subtotal' => $this->roundMoney($subtotal),
            'descuento_total' => $this->roundMoney($descuento),
            'gravado_total' => $this->roundMoney($gravado),
            'exonerado_total' => $this->roundMoney($exonerado),
            'inafecto_total' => $this->roundMoney($inafecto),
            'igv_total' => $igv,
            'total' => $total,
        ];
    }

    private function roundMoney(float $value): float
    {
        return round($value, 2);
    }
}
