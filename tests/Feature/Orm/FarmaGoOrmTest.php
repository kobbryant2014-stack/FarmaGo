<?php

namespace Tests\Feature\Orm;

use App\Models\Almacen;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\DetalleVenta;
use App\Models\Empresa;
use App\Models\Lote;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FarmaGoOrmTest extends TestCase
{
    use RefreshDatabase;

    public function test_crea_lee_actualiza_y_elimina_producto_con_eloquent(): void
    {
        $context = $this->contexto();
        $producto = Producto::create([
            'codigo_barra' => '7751234567890',
            'codigo_interno' => 'ORM-001',
            'nombre' => 'Ibuprofeno 400 mg',
            'categoria_id' => $context['categoria']->id,
            'precio_venta' => 4.50,
            'stock_minimo' => 5,
            'requiere_receta' => false,
            'estado' => 'activo',
            'activo' => true,
        ]);

        $this->assertDatabaseHas('productos', ['codigo_interno' => 'ORM-001']);
        $this->assertSame('Medicamentos', $producto->categoria->nombre);

        $producto->update(['precio_venta' => 4.90, 'stock_minimo' => 8]);

        $this->assertSame('4.90', $producto->fresh()->precio_venta);
        $this->assertSame(8, $producto->fresh()->stock_minimo);

        $producto->delete();

        $this->assertSoftDeleted('productos', ['id' => $producto->id]);
    }

    public function test_valida_relaciones_de_venta_detalles_producto_lote_cliente_y_usuario(): void
    {
        $context = $this->contexto();
        $lote = $this->crearLoteConStock($context, ['stock_inicial' => 12]);

        $venta = Venta::create([
            'cliente_id' => $context['cliente']->id,
            'user_id' => $context['user']->id,
            'subtotal' => 20,
            'descuento_total' => 0,
            'gravado_total' => 20,
            'igv_total' => 3.60,
            'total' => 23.60,
            'metodo_pago' => 'efectivo',
            'estado' => 'completada',
            'fecha' => now(),
        ]);

        DetalleVenta::create([
            'venta_id' => $venta->id,
            'producto_id' => $context['producto']->id,
            'lote_id' => $lote->id,
            'cantidad' => 2,
            'precio_unitario' => 10,
            'subtotal' => 20,
            'descuento' => 0,
            'igv' => 3.60,
            'total' => 23.60,
        ]);

        $ventaConRelaciones = Venta::with(['cliente', 'usuario', 'detalles.producto', 'detalles.lote'])
            ->findOrFail($venta->id);

        $this->assertTrue($ventaConRelaciones->relationLoaded('detalles'));
        $this->assertSame($context['cliente']->id, $ventaConRelaciones->cliente->id);
        $this->assertSame($context['user']->id, $ventaConRelaciones->usuario->id);
        $this->assertSame('Paracetamol Test', $ventaConRelaciones->detalles->first()->producto->nombre);
        $this->assertSame($lote->numero_lote, $ventaConRelaciones->detalles->first()->lote->numero_lote);
    }

    public function test_consultas_filtradas_de_productos_vendibles_con_receta_y_bajo_stock(): void
    {
        $context = $this->contexto();
        $this->crearLoteConStock($context, ['stock_inicial' => 2]);

        Producto::create([
            'codigo_interno' => 'ORM-RECETA',
            'nombre' => 'Antibiotico con receta',
            'categoria_id' => $context['categoria']->id,
            'precio_venta' => 12.00,
            'stock_minimo' => 1,
            'requiere_receta' => true,
            'estado' => 'activo',
            'activo' => true,
        ]);

        Producto::create([
            'codigo_interno' => 'ORM-INACTIVO',
            'nombre' => 'Producto inactivo',
            'categoria_id' => $context['categoria']->id,
            'precio_venta' => 5.00,
            'estado' => 'inactivo',
            'activo' => false,
        ]);

        $this->assertSame(2, Producto::vendibles()->count());
        $this->assertSame(1, Producto::conReceta()->count());
        $this->assertTrue(Producto::bajoStock()->pluck('id')->contains($context['producto']->id));
        $this->assertTrue($context['producto']->fresh()->estaEnStockBajo());
    }

    public function test_scopes_de_venta_distinguen_completadas_anuladas_y_activas(): void
    {
        $context = $this->contexto();
        $activa = Venta::create([
            'cliente_id' => $context['cliente']->id,
            'user_id' => $context['user']->id,
            'total' => 15,
            'metodo_pago' => 'efectivo',
            'estado' => 'completada',
            'fecha' => now(),
        ]);

        Venta::create([
            'cliente_id' => $context['cliente']->id,
            'user_id' => $context['user']->id,
            'total' => 9,
            'metodo_pago' => 'tarjeta',
            'estado' => 'anulada',
            'fecha' => now(),
        ]);

        $this->assertSame(1, Venta::completadas()->count());
        $this->assertSame(1, Venta::anuladas()->count());
        $this->assertTrue(Venta::activas()->pluck('id')->contains($activa->id));
        $this->assertTrue($activa->puedeAnularse());
    }

    private function contexto(): array
    {
        $user = User::factory()->create();

        $empresa = Empresa::create([
            'ruc' => '20999999991',
            'razon_social' => 'FarmaGo ORM SAC',
            'direccion_fiscal' => 'Av. Pruebas 123',
            'activo' => true,
        ]);

        $sucursal = Sucursal::create([
            'empresa_id' => $empresa->id,
            'codigo' => '001',
            'nombre' => 'Principal',
            'direccion' => 'Av. Pruebas 123',
            'activo' => true,
        ]);

        $almacen = Almacen::create([
            'sucursal_id' => $sucursal->id,
            'codigo' => 'ALM-ORM',
            'nombre' => 'Almacen de pruebas ORM',
            'principal' => true,
            'activo' => true,
        ]);

        $categoria = Categoria::create([
            'nombre' => 'Medicamentos',
            'activo' => true,
        ]);

        $cliente = Cliente::create([
            'tipo_documento' => 'DNI',
            'documento' => '12345678',
            'nombres' => 'Cliente',
            'apellidos' => 'Prueba',
            'nombre' => 'Cliente Prueba',
            'estado' => 'activo',
            'activo' => true,
        ]);

        $proveedor = Proveedor::create([
            'nombre' => 'Proveedor ORM',
            'ruc' => '20123456789',
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
            'codigo_barra' => '7750000000999',
            'codigo_interno' => 'ORM-STOCK',
            'nombre' => 'Paracetamol Test',
            'categoria_id' => $categoria->id,
            'precio_venta' => 2.50,
            'stock_minimo' => 5,
            'requiere_receta' => false,
            'estado' => 'activo',
            'activo' => true,
        ]);

        return compact('user', 'empresa', 'sucursal', 'almacen', 'categoria', 'cliente', 'proveedor', 'compra', 'producto');
    }

    private function crearLoteConStock(array $context, array $overrides = []): Lote
    {
        $attributes = array_merge([
            'producto_id' => $context['producto']->id,
            'compra_id' => $context['compra']->id,
            'proveedor_id' => $context['proveedor']->id,
            'almacen_id' => $context['almacen']->id,
            'numero_lote' => 'ORM-LOT-'.uniqid(),
            'fecha_vencimiento' => now()->addDays(90)->toDateString(),
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
            'motivo' => 'Entrada de prueba ORM',
            'fecha_movimiento' => now(),
            'estado' => 'valido',
        ]);

        return $lote;
    }
}
