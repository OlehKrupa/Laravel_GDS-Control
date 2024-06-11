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

        // Ensure there is another station of a different type
        $otherStation = Station::factory()->create();

        // Получаем роли
        $adminRole = Role::where('name', 'ADMIN')->first();
        $operatorRole = Role::where('name', 'OPERATOR')->first();
        $analystRole = Role::where('name', 'ANALYST')->first();

        // Создаем пользователей с заданными ролями и станциями
        User::factory()->withRole('ADMIN')->create([
            'name' => 'Admin User',
            'surname' => 'User',
            'email' => 'admin@example.com',
            'station_id' => $kremenchukStation->id,
        ])->assignRole($adminRole);

        User::factory()->withRole('ANALYST')->create([
            'name' => 'Analyst User',
            'surname' => 'User',
            'email' => 'analyst@example.com',
            'station_id' => $kremenchukStation->id,
        ])->assignRole($analystRole);

        // Создаем первого оператора с другой станцией
        User::factory()->withRole('OPERATOR')->create([
            'name' => 'Operator User',
            'surname' => 'User',
            'email' => 'operator@example.com',
            'station_id' => $otherStation->id,
        ])->assignRole($operatorRole);

        // Создаем остальных операторов
        User::factory()->count(8)->withRole('OPERATOR')->create()->each(function ($user) use ($operatorRole) {
            $user->assignRole($operatorRole);
        });
    }
}
