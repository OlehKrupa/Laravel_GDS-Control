<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Station;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создание станции ЛВУМГ в Кременчуге
        Station::factory()->lvumgKremenchuk()->create();

        // Создание остальных станций
        Station::factory()->count(12)->create();
    }
}
