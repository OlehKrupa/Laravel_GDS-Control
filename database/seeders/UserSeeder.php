<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Station;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there is a station of type ЛВУМГ in Кременчук
        $kremenchukStation = Station::factory()->lvumgKremenchuk()->create();

        // Получаем роли
        $adminRole = Role::where('name', 'ADMIN')->first();
        $operatorRole = Role::where('name', 'OPERATOR')->first();
        $analystRole = Role::where('name', 'ANALYST')->first();

        // Создаем пользователей с заданными ролями и станциями
        User::factory()->withRole('ADMIN')->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'station_id' => $kremenchukStation->id,
        ])->assignRole($adminRole);

        User::factory()->withRole('ANALYST')->create([
            'name' => 'Analyst User',
            'email' => 'analyst@example.com',
            'station_id' => $kremenchukStation->id,
        ])->assignRole($analystRole);

        User::factory()->count(8)->withRole('OPERATOR')->create()->each(function ($user) use ($operatorRole) {
            $user->assignRole($operatorRole);
        });

        // Создание остальных пользователей (если необходимо)
        User::factory()->count(12)->create();
    }
}
