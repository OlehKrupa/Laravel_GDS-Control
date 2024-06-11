<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создание разрешений
        Permission::create(['name' => 'edit user station']);
        Permission::create(['name' => 'use stations']);
        Permission::create(['name' => 'create reports']);
        Permission::create(['name' => 'edit roles']);
        Permission::create(['name' => 'crud stations']);
        Permission::create(['name' => 'create records']);
        Permission::create(['name' => 'update records']);
        Permission::create(['name' => 'delete records']);
        Permission::create(['name' => 'settings']);
        Permission::create(['name' => 'forecast']);
        Permission::create(['name' => 'logs']);
    }
}
