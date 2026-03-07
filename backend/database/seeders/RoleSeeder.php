<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'administrador',
            'administrador_costos',
            'vendedor',
            'encargado_produccion',
        ];

        foreach ($roles as $rol) {
            Role::firstOrCreate(['name' => $rol, 'guard_name' => 'web']);
        }

        // Usuario admin de prueba
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name'     => 'Administrador',
                'password' => bcrypt('password'),
            ]
        );

        $admin->syncRoles(['administrador']);
    }
}
