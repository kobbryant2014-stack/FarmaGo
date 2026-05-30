<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrador General',
                'email' => 'admin@farmago.com',
                'password' => 'admin123',
                'roles' => ['Admin', 'Administrador', 'Administrador general'],
            ],
            [
                'name' => 'Quimico Farmaceutico',
                'email' => 'quimico@farmago.com',
                'password' => 'quimico123',
                'roles' => ['Quimico farmaceutico'],
            ],
            [
                'name' => 'Cajero',
                'email' => 'cajero@farmago.com',
                'password' => 'cajero123',
                'roles' => ['Cajero'],
            ],
            [
                'name' => 'Almacenero',
                'email' => 'almacen@farmago.com',
                'password' => 'almacen123',
                'roles' => ['Almacenero', 'Inventario'],
            ],
            [
                'name' => 'Contabilidad',
                'email' => 'contabilidad@farmago.com',
                'password' => 'contabilidad123',
                'roles' => ['Contabilidad'],
            ],
            [
                'name' => 'Auditor',
                'email' => 'auditor@farmago.com',
                'password' => 'auditor123',
                'roles' => ['Auditor'],
            ],
            [
                'name' => 'Supervisor',
                'email' => 'supervisor@farmago.com',
                'password' => 'supervisor123',
                'roles' => ['Supervisor'],
            ],
        ];

        foreach ($users as $definition) {
            $user = User::updateOrCreate(
                ['email' => $definition['email']],
                [
                    'name' => $definition['name'],
                    'password' => Hash::make($definition['password']),
                    'email_verified_at' => now(),
                    'active' => true,
                    'failed_login_attempts' => 0,
                    'locked_until' => null,
                ]
            );

            $user->syncRoles($definition['roles']);
        }

        $this->command?->info('Usuarios demo de FarmaGo creados correctamente.');
        $this->command?->line('Credenciales demo solo para ambiente local/semilla.');
        $this->command?->line('Admin: admin@farmago.com / admin123');
        $this->command?->line('Cajero: cajero@farmago.com / cajero123');
        $this->command?->line('Almacen: almacen@farmago.com / almacen123');
    }
}
