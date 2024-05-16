<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
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
        return [
            'label' => $this->faker->word,
            'city' => $this->faker->city,
            'region' => $this->faker->word,
            'type' => $this->faker->randomElement(['type1', 'type2', 'type3']),
        ];
    }
}
