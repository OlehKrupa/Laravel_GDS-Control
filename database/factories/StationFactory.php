<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Station;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Station>
 */
class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = [
            'Кременчук', 'Горішні Плавні', 'Світловодськ', 'Глобино', 'Козельщина', 'Кобеляки', 'Потоки', 'Павлиш'
        ];

        $type = $this->faker->randomElement(['ГРС', 'ГРП', 'АГРС-м', 'ГРС-с', 'ГРС-в']);
        $city = $this->faker->randomElement($cities);
        $label = $type . ' ' . $city;

        // Check for duplicates and add suffix if necessary
        $suffix = 1;
        while (Station::where('label', $label)->exists()) {
            $label = $type . ' ' . $city . '-' . $suffix;
            $suffix++;
        }

        return [
            'label' => $label,
            'city' => $city,
            'region' => 'Кременчук',
            'type' => $type,
        ];
    }

    public function lvumgKremenchuk()
    {
        return $this->state(function (array $attributes) {
            return [
                'label' => 'ЛВУМГ Кременчук',
                'city' => 'Кременчук',
                'region' => 'Кременчук',
                'type' => 'ЛВУМГ',
            ];
        });
    }
}
