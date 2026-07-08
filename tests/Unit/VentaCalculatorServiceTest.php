<?php

namespace Tests\Unit;

use App\Services\VentaCalculatorService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class VentaCalculatorServiceTest extends TestCase
{
    private VentaCalculatorService $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculator = new VentaCalculatorService();
    }

    public function test_calcula_total_de_venta_gravada_con_igv(): void
    {
        $detalle1 = $this->calculator->calcularDetalle(2, 10.00, 0, 18.0, '10');
        $detalle2 = $this->calculator->calcularDetalle(1, 5.50, 0, 18.0, '10');

        $resultado = $this->calculator->calcularTotales([$detalle1, $detalle2]);

        $this->assertSame(25.50, $resultado['subtotal']);
        $this->assertSame(25.50, $resultado['gravado_total']);
        $this->assertSame(4.59, $resultado['igv_total']);
        $this->assertSame(30.09, $resultado['total']);
    }

    public function test_aplica_descuento_sin_superar_el_subtotal(): void
    {
        $detalle = $this->calculator->calcularDetalle(3, 12.00, 6.00, 18.0, '10');

        $resultado = $this->calculator->calcularTotales([$detalle]);

        $this->assertSame(36.00, $resultado['subtotal']);
        $this->assertSame(6.00, $resultado['descuento_total']);
        $this->assertSame(30.00, $resultado['gravado_total']);
        $this->assertSame(35.40, $resultado['total']);
    }

    public function test_separa_items_exonerados_e_inafectos_del_igv(): void
    {
        $detalleGravado = $this->calculator->calcularDetalle(1, 10.00, 0, 18.0, '10');
        $detalleExonerado = $this->calculator->calcularDetalle(1, 8.00, 0, 18.0, '20');
        $detalleInafecto = $this->calculator->calcularDetalle(2, 3.00, 0, 18.0, '30');

        $resultado = $this->calculator->calcularTotales([$detalleGravado, $detalleExonerado, $detalleInafecto]);

        $this->assertSame(10.00, $resultado['gravado_total']);
        $this->assertSame(8.00, $resultado['exonerado_total']);
        $this->assertSame(6.00, $resultado['inafecto_total']);
        $this->assertSame(1.80, $resultado['igv_total']);
        $this->assertSame(25.80, $resultado['total']);
    }

    public function test_rechaza_descuento_mayor_al_subtotal(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->calculator->calcularDetalle(1, 10.00, 10.01, 18.0, '10');
    }
}
