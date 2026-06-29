<?php

namespace Tests\Unit;

use App\Services\VentaTotalCalculator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class VentaTotalCalculatorTest extends TestCase
{
    private VentaTotalCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculator = new VentaTotalCalculator();
    }

    public function test_calcula_total_de_venta_gravada_con_igv(): void
    {
        $resultado = $this->calculator->calcular([
            ['cantidad' => 2, 'precio_unitario' => 10.00],
            ['cantidad' => 1, 'precio_unitario' => 5.50],
        ]);

        $this->assertSame(25.50, $resultado['subtotal']);
        $this->assertSame(25.50, $resultado['gravado_total']);
        $this->assertSame(4.59, $resultado['igv_total']);
        $this->assertSame(30.09, $resultado['total']);
    }

    public function test_aplica_descuento_sin_superar_el_subtotal(): void
    {
        $resultado = $this->calculator->calcular([
            ['cantidad' => 3, 'precio_unitario' => 12.00, 'descuento' => 6.00],
        ]);

        $this->assertSame(36.00, $resultado['subtotal']);
        $this->assertSame(6.00, $resultado['descuento_total']);
        $this->assertSame(30.00, $resultado['gravado_total']);
        $this->assertSame(35.40, $resultado['total']);
    }

    public function test_separa_items_exonerados_e_inafectos_del_igv(): void
    {
        $resultado = $this->calculator->calcular([
            ['cantidad' => 1, 'precio_unitario' => 10.00, 'afectacion_tributaria' => '10'],
            ['cantidad' => 1, 'precio_unitario' => 8.00, 'afectacion_tributaria' => '20'],
            ['cantidad' => 2, 'precio_unitario' => 3.00, 'afectacion_tributaria' => '30'],
        ]);

        $this->assertSame(10.00, $resultado['gravado_total']);
        $this->assertSame(8.00, $resultado['exonerado_total']);
        $this->assertSame(6.00, $resultado['inafecto_total']);
        $this->assertSame(1.80, $resultado['igv_total']);
        $this->assertSame(25.80, $resultado['total']);
    }

    public function test_rechaza_venta_sin_items(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->calculator->calcular([]);
    }

    public function test_rechaza_cantidad_cero_o_negativa(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->calculator->calcular([
            ['cantidad' => 0, 'precio_unitario' => 10.00],
        ]);
    }

    public function test_rechaza_descuento_mayor_al_subtotal(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->calculator->calcular([
            ['cantidad' => 1, 'precio_unitario' => 10.00, 'descuento' => 10.01],
        ]);
    }
}
