<?php

namespace App\Services;

use InvalidArgumentException;

class VentaTotalCalculator
{
    /**
     * @param  array<int, array{cantidad:mixed, precio_unitario:mixed, descuento?:mixed, afectacion_tributaria?:string}>  $items
     * @return array{subtotal:float, descuento_total:float, gravado_total:float, exonerado_total:float, inafecto_total:float, igv_total:float, total:float}
     */
    public function calcular(array $items, float $igvPorcentaje = 18.0): array
    {
        if ($items === []) {
            throw new InvalidArgumentException('La venta debe tener al menos un item.');
        }

        if ($igvPorcentaje < 0) {
            throw new InvalidArgumentException('El porcentaje de IGV no puede ser negativo.');
        }

        $subtotal = 0.0;
        $descuentoTotal = 0.0;
        $gravadoTotal = 0.0;
        $exoneradoTotal = 0.0;
        $inafectoTotal = 0.0;

        foreach ($items as $item) {
            $cantidad = (float) ($item['cantidad'] ?? 0);
            $precioUnitario = (float) ($item['precio_unitario'] ?? 0);
            $descuento = (float) ($item['descuento'] ?? 0);
            $afectacion = (string) ($item['afectacion_tributaria'] ?? '10');

            if ($cantidad <= 0) {
                throw new InvalidArgumentException('La cantidad de cada item debe ser mayor a cero.');
            }

            if ($precioUnitario < 0) {
                throw new InvalidArgumentException('El precio unitario no puede ser negativo.');
            }

            $lineaSubtotal = round($cantidad * $precioUnitario, 2);

            if ($descuento < 0 || $descuento > $lineaSubtotal) {
                throw new InvalidArgumentException('El descuento debe estar entre cero y el subtotal del item.');
            }

            $base = round($lineaSubtotal - $descuento, 2);

            $subtotal += $lineaSubtotal;
            $descuentoTotal += $descuento;

            if ($afectacion === '20') {
                $exoneradoTotal += $base;
            } elseif ($afectacion === '30') {
                $inafectoTotal += $base;
            } else {
                $gravadoTotal += $base;
            }
        }

        $igvTotal = round($gravadoTotal * ($igvPorcentaje / 100), 2);
        $total = round($gravadoTotal + $exoneradoTotal + $inafectoTotal + $igvTotal, 2);

        return [
            'subtotal' => round($subtotal, 2),
            'descuento_total' => round($descuentoTotal, 2),
            'gravado_total' => round($gravadoTotal, 2),
            'exonerado_total' => round($exoneradoTotal, 2),
            'inafecto_total' => round($inafectoTotal, 2),
            'igv_total' => $igvTotal,
            'total' => $total,
        ];
    }
}
