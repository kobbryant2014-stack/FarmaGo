<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionsByModule = [
            'dashboard' => [
                'ver dashboard',
                'ver alertas',
            ],
            'seguridad' => [
                'ver usuarios',
                'crear usuarios',
                'editar usuarios',
                'desactivar usuarios',
                'bloquear usuarios',
                'cambiar contrasenas usuarios',
                'asignar roles',
                'gestionar permisos',
                'ver auditoria',
                'exportar auditoria',
            ],
            'configuracion' => [
                'ver configuracion',
                'editar configuracion empresa',
                'gestionar sucursales',
                'gestionar almacenes',
                'configurar sunat',
                'configurar series comprobantes',
                'gestionar backups',
            ],
            'productos' => [
                'ver categorias',
                'crear categorias',
                'editar categorias',
                'desactivar categorias',
                'ver productos',
                'crear productos',
                'editar productos',
                'desactivar productos',
                'editar precios productos',
                'ver productos sensibles',
            ],
            'inventario' => [
                'ver lotes',
                'crear lotes',
                'editar lotes',
                'desactivar lotes',
                'ver movimientos inventario',
                'ajustar inventario',
                'autorizar ajustes inventario',
                'transferir inventario',
                'inmovilizar productos',
                'retirar productos mercado',
            ],
            'compras' => [
                'ver proveedores',
                'crear proveedores',
                'editar proveedores',
                'desactivar proveedores',
                'ver compras',
                'registrar compras',
                'anular compras',
                'ver detalle compras',
                'registrar pagos proveedores',
                'ver cuentas por pagar',
            ],
            'ventas' => [
                'ver ventas',
                'ver ventas propias',
                'realizar ventas',
                'anular ventas',
                'autorizar anulacion ventas',
                'ver detalle ventas',
                'aplicar descuentos venta',
                'seleccionar lote manual',
                'registrar pagos venta',
            ],
            'facturacion' => [
                'emitir comprobantes',
                'ver comprobantes electronicos',
                'reenviar comprobantes',
                'emitir nota credito',
                'emitir nota debito',
                'anular comprobantes',
                'descargar xml cdr pdf',
            ],
            'caja' => [
                'acceder caja',
                'abrir caja',
                'cerrar caja',
                'registrar ingresos caja',
                'registrar egresos caja',
                'autorizar diferencia caja',
                'ver cierres caja',
            ],
            'clientes' => [
                'ver clientes',
                'crear clientes',
                'editar clientes',
                'desactivar clientes',
                'ver datos personales clientes',
            ],
            'recetas' => [
                'ver recetas',
                'registrar recetas',
                'validar recetas',
                'observar recetas',
                'anular recetas',
                'ver ventas recetas',
            ],
            'controlados' => [
                'ver medicamentos controlados',
                'dispensar medicamentos controlados',
                'autorizar medicamentos controlados',
                'ajustar medicamentos controlados',
                'ver libro controlados',
                'exportar reporte controlados',
            ],
            'reportes' => [
                'ver reportes ventas',
                'ver reportes inventario',
                'ver reportes compras',
                'ver reportes caja',
                'ver reportes facturacion',
                'ver reportes sanitarios',
                'ver reportes contabilidad',
                'exportar reportes',
            ],
            'alertas_legacy' => [
                'ver alertas vencimientos',
                'ver alertas stock bajo',
            ],
        ];

        $permissions = collect($permissionsByModule)
            ->flatten()
            ->unique()
            ->values();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $roles = [
            'Admin',
            'Administrador',
            'Administrador general',
            'Quimico farmaceutico',
            'Cajero',
            'Supervisor',
            'Almacenero',
            'Contabilidad',
            'Auditor',
            'Inventario',
            'Empleado',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }

        Role::findByName('Admin')->syncPermissions($permissions);
        Role::findByName('Administrador')->syncPermissions($permissions);
        Role::findByName('Administrador general')->syncPermissions($permissions);

        Role::findByName('Supervisor')->syncPermissions([
            'ver dashboard',
            'ver alertas',
            'ver productos',
            'crear productos',
            'editar productos',
            'editar precios productos',
            'ver lotes',
            'ver movimientos inventario',
            'ajustar inventario',
            'autorizar ajustes inventario',
            'transferir inventario',
            'ver proveedores',
            'ver compras',
            'registrar compras',
            'anular compras',
            'ver ventas',
            'anular ventas',
            'autorizar anulacion ventas',
            'ver comprobantes electronicos',
            'reenviar comprobantes',
            'emitir nota credito',
            'emitir nota debito',
            'acceder caja',
            'autorizar diferencia caja',
            'ver cierres caja',
            'ver clientes',
            'ver datos personales clientes',
            'ver recetas',
            'validar recetas',
            'ver medicamentos controlados',
            'autorizar medicamentos controlados',
            'ver libro controlados',
            'ver reportes ventas',
            'ver reportes inventario',
            'ver reportes caja',
            'ver reportes facturacion',
            'ver reportes sanitarios',
            'exportar reportes',
            'ver auditoria',
        ]);

        Role::findByName('Quimico farmaceutico')->syncPermissions([
            'ver dashboard',
            'ver alertas',
            'ver productos',
            'ver productos sensibles',
            'ver lotes',
            'inmovilizar productos',
            'retirar productos mercado',
            'ver clientes',
            'ver datos personales clientes',
            'ver recetas',
            'registrar recetas',
            'validar recetas',
            'observar recetas',
            'anular recetas',
            'ver ventas recetas',
            'ver medicamentos controlados',
            'dispensar medicamentos controlados',
            'autorizar medicamentos controlados',
            'ajustar medicamentos controlados',
            'ver libro controlados',
            'exportar reporte controlados',
            'ver reportes sanitarios',
            'exportar reportes',
            'ver auditoria',
        ]);

        Role::findByName('Cajero')->syncPermissions([
            'ver dashboard',
            'ver alertas',
            'ver productos',
            'ver lotes',
            'ver clientes',
            'crear clientes',
            'editar clientes',
            'realizar ventas',
            'ver ventas propias',
            'ver detalle ventas',
            'aplicar descuentos venta',
            'registrar pagos venta',
            'emitir comprobantes',
            'ver comprobantes electronicos',
            'acceder caja',
            'abrir caja',
            'cerrar caja',
            'registrar ingresos caja',
            'registrar egresos caja',
            'ver recetas',
            'registrar recetas',
            'ver ventas recetas',
            'ver alertas vencimientos',
            'ver alertas stock bajo',
        ]);

        Role::findByName('Almacenero')->syncPermissions([
            'ver dashboard',
            'ver alertas',
            'ver categorias',
            'crear categorias',
            'editar categorias',
            'ver productos',
            'crear productos',
            'editar productos',
            'ver lotes',
            'crear lotes',
            'editar lotes',
            'desactivar lotes',
            'ver movimientos inventario',
            'ajustar inventario',
            'transferir inventario',
            'ver proveedores',
            'crear proveedores',
            'editar proveedores',
            'ver compras',
            'registrar compras',
            'ver detalle compras',
            'ver reportes inventario',
            'ver reportes compras',
            'exportar reportes',
            'ver alertas vencimientos',
            'ver alertas stock bajo',
        ]);

        Role::findByName('Inventario')->syncPermissions(Role::findByName('Almacenero')->permissions);

        Role::findByName('Contabilidad')->syncPermissions([
            'ver dashboard',
            'ver compras',
            'ver detalle compras',
            'registrar pagos proveedores',
            'ver cuentas por pagar',
            'ver ventas',
            'ver detalle ventas',
            'ver comprobantes electronicos',
            'reenviar comprobantes',
            'emitir nota credito',
            'emitir nota debito',
            'descargar xml cdr pdf',
            'acceder caja',
            'ver cierres caja',
            'ver reportes ventas',
            'ver reportes compras',
            'ver reportes caja',
            'ver reportes facturacion',
            'ver reportes contabilidad',
            'exportar reportes',
        ]);

        Role::findByName('Auditor')->syncPermissions([
            'ver dashboard',
            'ver alertas',
            'ver usuarios',
            'ver productos',
            'ver lotes',
            'ver movimientos inventario',
            'ver proveedores',
            'ver compras',
            'ver detalle compras',
            'ver ventas',
            'ver detalle ventas',
            'ver comprobantes electronicos',
            'descargar xml cdr pdf',
            'ver cierres caja',
            'ver clientes',
            'ver datos personales clientes',
            'ver recetas',
            'ver medicamentos controlados',
            'ver libro controlados',
            'ver reportes ventas',
            'ver reportes inventario',
            'ver reportes compras',
            'ver reportes caja',
            'ver reportes facturacion',
            'ver reportes sanitarios',
            'ver reportes contabilidad',
            'exportar reportes',
            'ver auditoria',
            'exportar auditoria',
        ]);

        Role::findByName('Empleado')->syncPermissions([
            'ver dashboard',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command?->info('Roles y permisos de FarmaGo sincronizados.');
        $this->command?->line('Permisos: '.Permission::count());
        $this->command?->line('Roles: '.Role::count());
    }
}
