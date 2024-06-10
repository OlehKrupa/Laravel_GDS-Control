<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StationSeeder::class,
            RoleSeeder::class,
            PermissionsTableSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            SettingsSeeder::class,
            RegimeSeeder::class,
        ]);

    }
}
