<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhaseTwoCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedIdentityDocumentTypes();
        $this->seedInvoiceDocumentTypes();
        $this->seedPaymentMethods();
        $this->seedDemoCompanyStructure();
    }

    private function seedIdentityDocumentTypes(): void
    {
        $types = [
            ['codigo' => '0', 'nombre' => 'Sin documento'],
            ['codigo' => '1', 'nombre' => 'DNI'],
            ['codigo' => '4', 'nombre' => 'Carne de extranjeria'],
            ['codigo' => '6', 'nombre' => 'RUC'],
            ['codigo' => '7', 'nombre' => 'Pasaporte'],
        ];

        foreach ($types as $type) {
            DB::table('tipos_documento_identidad')->updateOrInsert(
                ['codigo' => $type['codigo']],
                [
                    'nombre' => $type['nombre'],
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    private function seedInvoiceDocumentTypes(): void
    {
        $types = [
            ['codigo' => '01', 'nombre' => 'Factura electronica', 'requiere_cliente' => true],
            ['codigo' => '03', 'nombre' => 'Boleta electronica', 'requiere_cliente' => false],
            ['codigo' => '07', 'nombre' => 'Nota de credito electronica', 'requiere_cliente' => true],
            ['codigo' => '08', 'nombre' => 'Nota de debito electronica', 'requiere_cliente' => true],
            ['codigo' => '09', 'nombre' => 'Guia de remision electronica', 'requiere_cliente' => true],
        ];

        foreach ($types as $type) {
            DB::table('tipos_comprobante')->updateOrInsert(
                ['codigo' => $type['codigo']],
                [
                    'nombre' => $type['nombre'],
                    'requiere_cliente' => $type['requiere_cliente'],
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    private function seedPaymentMethods(): void
    {
        $methods = [
            ['codigo' => 'efectivo', 'nombre' => 'Efectivo', 'requiere_referencia' => false],
            ['codigo' => 'tarjeta', 'nombre' => 'Tarjeta', 'requiere_referencia' => true],
            ['codigo' => 'yape', 'nombre' => 'Yape', 'requiere_referencia' => true],
            ['codigo' => 'plin', 'nombre' => 'Plin', 'requiere_referencia' => true],
            ['codigo' => 'transferencia', 'nombre' => 'Transferencia', 'requiere_referencia' => true],
            ['codigo' => 'credito', 'nombre' => 'Credito', 'requiere_referencia' => true],
        ];

        foreach ($methods as $method) {
            DB::table('metodos_pago')->updateOrInsert(
                ['codigo' => $method['codigo']],
                [
                    'nombre' => $method['nombre'],
                    'requiere_referencia' => $method['requiere_referencia'],
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    private function seedDemoCompanyStructure(): void
    {
        DB::table('empresas')->updateOrInsert(
            ['ruc' => '00000000000'],
            [
                'razon_social' => 'FarmaGo Demo S.A.C.',
                'nombre_comercial' => 'FarmaGo Demo',
                'direccion_fiscal' => 'Direccion demo sin valor tributario',
                'ubigeo' => '150101',
                'telefono' => null,
                'email' => 'demo@farmago.local',
                'moneda_codigo' => 'PEN',
                'igv_porcentaje' => 18,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $empresaId = DB::table('empresas')->where('ruc', '00000000000')->value('id');

        DB::table('sucursales')->updateOrInsert(
            ['empresa_id' => $empresaId, 'codigo' => 'PRINCIPAL'],
            [
                'nombre' => 'Local principal',
                'direccion' => 'Direccion demo sin valor tributario',
                'ubigeo' => '150101',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $sucursalId = DB::table('sucursales')
            ->where('empresa_id', $empresaId)
            ->where('codigo', 'PRINCIPAL')
            ->value('id');

        DB::table('almacenes')->updateOrInsert(
            ['sucursal_id' => $sucursalId, 'codigo' => 'ALM-PRIN'],
            [
                'nombre' => 'Almacen principal',
                'principal' => true,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('cajas')->updateOrInsert(
            ['sucursal_id' => $sucursalId, 'codigo' => 'CAJA-01'],
            [
                'nombre' => 'Caja 01',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('configuraciones_sunat')->updateOrInsert(
            ['empresa_id' => $empresaId],
            [
                'tipo_proveedor' => 'pse',
                'ambiente' => 'beta',
                'activo' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        foreach (['01' => 'F001', '03' => 'B001', '07' => 'FC01', '08' => 'FD01'] as $type => $series) {
            DB::table('series_documentos')->updateOrInsert(
                [
                    'empresa_id' => $empresaId,
                    'sucursal_id' => $sucursalId,
                    'tipo_comprobante' => $type,
                    'serie' => $series,
                ],
                [
                    'correlativo_actual' => 0,
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
