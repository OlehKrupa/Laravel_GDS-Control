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
//            StationSeeder::class,
//            UserSeeder::class,
            SettingsSeeder::class,
//            RoleSeeder::class,
        ]);

    }
}
