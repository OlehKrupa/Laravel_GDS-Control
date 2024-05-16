<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'ADMIN',
            'label' => 'Адміністратор',
        ]);

        Role::create([
            'name' => 'OPERATOR',
            'label' => 'Оператор',
        ]);

        Role::create([
            'name' => 'ANALYST',
            'label' => 'Аналітик',
        ]);
    }
}
