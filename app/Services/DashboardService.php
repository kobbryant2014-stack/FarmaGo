<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Venta;

class DashboardService
{
    public function resumen(): array
    {
        $inventario = app(InventarioService::class);
        $ventas = app(VentaService::class);

        return [
            'total_productos' => Producto::count(),
            'total_clientes' => Cliente::count(),
            'total_proveedores' => Proveedor::count(),
            'ventas_dia' => Venta::whereDate('fecha', today())->completadas()->count(),
            'total_vendido_hoy' => $ventas->totalVentasDelDia(),
            'productos_stock_bajo' => $inventario->productosConStockBajo(),
            'lotes_proximos_vencer' => $inventario->lotesProximosVencer(30),
            'ultimas_ventas' => Venta::with(['cliente', 'usuario'])
                ->latest('fecha')
                ->limit(5)
                ->get(),
        ];
    }
}
