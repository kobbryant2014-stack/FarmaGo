<?php

namespace Tests\Feature\Inventory;

use App\Models\Almacen;
use App\Models\Categoria;
use App\Models\Compra;
use App\Models\Empresa;
use App\Models\Lote;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\User;
use App\Services\FefoStockService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FefoStockServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_selects_lots_using_fefo_order(): void
    {
        $context = $this->inventoryContext();
        $producto = $context['producto'];

        $lateLot = $this->createLot($context, [
            'numero_lote' => 'LATE-001',
            'fecha_vencimiento' => now()->addDays(90)->toDateString(),
            'stock_inicial' => 10,
        ]);

        $earlyLot = $this->createLot($context, [
            'numero_lote' => 'EARLY-001',
            'fecha_vencimiento' => now()->addDays(30)->toDateString(),
            'stock_inicial' => 5,
        ]);

        $allocations = app(FefoStockService::class)
            ->seleccionarLotesParaSalida($producto->id, 8);

        $this->assertCount(2, $allocations);
        $this->assertSame($earlyLot->id, $allocations[0]['lote']->id);
        $this->assertSame(5.0, $allocations[0]['cantidad']);
        $this->assertSame($lateLot->id, $allocations[1]['lote']->id);
        $this->assertSame(3.0, $allocations[1]['cantidad']);
    }

    public function test_ignores_expired_and_immobilized_lots(): void
    {
        $context = $this->inventoryContext();
        $producto = $context['producto'];

        $this->createLot($context, [
            'numero_lote' => 'EXPIRED-001',
            'fecha_vencimiento' => now()->subDay()->toDateString(),
            'stock_inicial' => 10,
        ]);

        $this->createLot($context, [
            'numero_lote' => 'BLOCKED-001',
            'fecha_vencimiento' => now()->addDays(60)->toDateString(),
            'stock_inicial' => 10,
            'estado' => 'inmovilizado',
        ]);

        $availableLot = $this->createLot($context, [
            'numero_lote' => 'OK-001',
            'fecha_vencimiento' => now()->addDays(45)->toDateString(),
            'stock_inicial' => 4,
        ]);

        $allocations = app(FefoStockService::class)
            ->seleccionarLotesParaSalida($producto->id, 4);

        $this->assertCount(1, $allocations);
        $this->assertSame($availableLot->id, $allocations[0]['lote']->id);

        $this->expectException(Exception::class);

        app(FefoStockService::class)->seleccionarLotesParaSalida($producto->id, 5);
    }

    public function test_purchase_entry_does_not_double_count_lot_stock(): void
    {
        $context = $this->inventoryContext();

        $lote = $this->createLot($context, [
            'numero_lote' => 'STOCK-001',
            'stock_inicial' => 10,
        ]);

        $this->assertSame(10.0, $lote->fresh()->stock_actual);

        MovimientoInventario::create([
            'producto_id' => $context['producto']->id,
            'lote_id' => $lote->id,
            'almacen_id' => $context['almacen']->id,
            'user_id' => $context['user']->id,
            'tipo' => 'salida',
            'cantidad' => -3,
            'origen' => 'venta',
            'origen_id' => 1,
            'motivo' => 'Venta de prueba',
            'fecha_movimiento' => now(),
            'estado' => 'valido',
        ]);

        $this->assertSame(7.0, $lote->fresh()->stock_actual);
    }

    private function inventoryContext(): array
    {
        $user = User::factory()->create();

        $empresa = Empresa::create([
            'ruc' => '20000000001',
            'razon_social' => 'FarmaGo Test SAC',
            'direccion_fiscal' => 'Av. Test 123',
            'activo' => true,
        ]);

        $sucursal = Sucursal::create([
            'empresa_id' => $empresa->id,
            'codigo' => '001',
            'nombre' => 'Principal',
            'direccion' => 'Av. Test 123',
            'activo' => true,
        ]);

        $almacen = Almacen::create([
            'sucursal_id' => $sucursal->id,
            'codigo' => 'ALM',
            'nombre' => 'Almacen principal',
            'principal' => true,
            'activo' => true,
        ]);

        $categoria = Categoria::create([
            'nombre' => 'Medicamentos',
            'activo' => true,
        ]);

        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor Test',
            'ruc' => '20111111111',
            'activo' => true,
        ]);

        $compra = Compra::create([
            'proveedor_id' => $proveedor->id,
            'user_id' => $user->id,
            'total' => 0,
            'estado' => 'recibida',
            'fecha' => now(),
        ]);

        $producto = Producto::create([
            'codigo_barra' => '775000000001',
            'codigo_interno' => 'PTEST-001',
            'nombre' => 'Paracetamol Test',
            'categoria_id' => $categoria->id,
            'precio_venta' => 2.50,
            'stock_minimo' => 1,
            'requiere_receta' => false,
            'estado' => 'activo',
            'activo' => true,
        ]);

        return compact('user', 'empresa', 'sucursal', 'almacen', 'categoria', 'proveedor', 'compra', 'producto');
    }

    private function createLot(array $context, array $overrides = []): Lote
    {
        $attributes = array_merge([
            'producto_id' => $context['producto']->id,
            'compra_id' => $context['compra']->id,
            'proveedor_id' => $context['proveedor']->id,
            'almacen_id' => $context['almacen']->id,
            'numero_lote' => 'LOT-'.uniqid(),
            'fecha_vencimiento' => now()->addDays(60)->toDateString(),
            'stock_inicial' => 10,
            'precio_compra' => 1.00,
            'estado' => 'activo',
            'activo' => true,
        ], $overrides);

        $lote = Lote::create($attributes);

        MovimientoInventario::create([
            'producto_id' => $context['producto']->id,
            'lote_id' => $lote->id,
            'almacen_id' => $context['almacen']->id,
            'user_id' => $context['user']->id,
            'tipo' => 'entrada',
            'cantidad' => $attributes['stock_inicial'],
            'origen' => 'compra',
            'origen_id' => $context['compra']->id,
            'motivo' => 'Compra de prueba',
            'fecha_movimiento' => now(),
            'estado' => 'valido',
        ]);

        return $lote;
    }
}
