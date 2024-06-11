<?php

namespace Database\Seeders;

use App\Models\Journal;
use App\Models\SelfSpendings;
use App\Models\Spendings;
use App\Models\Gassiness;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RegimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Группировка пользователей по станциям
        $stations = User::with('station')->get()->groupBy('station_id');

        foreach ($stations as $stationId => $users) {
            // Получение информации о станции
            $station = $users->first()->station;

            // Проверка типа станции
            if ($station->type == 'ЛВУМГ') {
                continue; // Пропуск станций с типом ЛВУМГ
            }

            // Создание записей для каждой станции за последние 30 дней
            $numUsers = count($users);
            $currentDate = Carbon::now();

            for ($i = 0; $i < 30; $i++) {
                $user = $users[$i % $numUsers]; // Чередование пользователей

                $date = $currentDate->copy()->subDays($i);

                // Создание записи в Journal
                Journal::create([
                    'pressure_in' => rand(10, 100) / 10,
                    'pressure_out_1' => rand(10, 100) / 10,
                    'pressure_out_2' => rand(10, 100) / 10,
                    'temperature_1' => rand(10, 100) / 10,
                    'temperature_2' => rand(10, 100) / 10,
                    'odorant_value_1' => rand(10, 100) / 10,
                    'odorant_value_2' => rand(10, 100) / 10,
                    'gas_heater_temperature_in' => rand(10, 100) / 10,
                    'gas_heater_temperature_out' => rand(10, 100) / 10,
                    'user_id' => $user->id,
                    'user_station_id' => $user->station_id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // Создание записи в SelfSpendings
                SelfSpendings::create([
                    'heater_time' => rand(1, 24),
                    'boiler_time' => rand(1, 24),
                    'heater_gas' => rand(10, 100) / 10,
                    'boiler_gas' => rand(10, 100) / 10,
                    'user_id' => $user->id,
                    'user_station_id' => $user->station_id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // Создание записи в Spendings
                Spendings::create([
                    'gas' => rand(100, 1000),
                    'odorant' => rand(1, 10),
                    'user_id' => $user->id,
                    'user_station_id' => $user->station_id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // Создание записи в Gassiness
                Gassiness::create([
                    'MPR' => 1,
                    'measurements' => array_fill(0, 10, 0),
                    'device' => 'Device ' . rand(1, 100),
                    'factory_number' => rand(1000, 9999),
                    'user_id' => $user->id,
                    'user_station_id' => $user->station_id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
