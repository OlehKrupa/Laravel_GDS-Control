<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получение ролей
        $admin = Role::where('name', 'ADMIN')->first();
        $analyst = Role::where('name', 'ANALYST')->first();
        $operator = Role::where('name', 'OPERATOR')->first();

        // Получение разрешений
        $permissions = [
            'edit user station',
            'use stations',
            'create reports',
            'edit roles',
            'crud stations',
            'update records',
            'delete records',
            'settings',
            'forecast',
            'logs'
        ];

        // Назначение всех разрешений роли ADMIN
        foreach ($permissions as $permission) {
            $perm = Permission::where('name', $permission)->first();
            $admin->givePermissionTo($perm);
        }

        // Назначение разрешений роли ANALYST
        $analyst->givePermissionTo([
            'forecast',
            'use stations',
            'create reports'
        ]);

        // Назначение разрешений роли OPERATOR
        $operator->givePermissionTo([
            'create records',
            'update records',
            'delete records'
        ]);
    }
}
