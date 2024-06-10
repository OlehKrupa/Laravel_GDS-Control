<?php

namespace Database\Factories;

use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'surname' => $this->faker->lastName,
            'patronymic' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), // password
            'remember_token' => Str::random(10),
            'station_id' => Station::inRandomOrder()->first()->id, // По умолчанию случайный station_id
        ];
    }

    /**
     * Assign a role to the user and set the appropriate station id.
     *
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withRole(string $role): Factory
    {
        return $this->state(function (array $attributes) use ($role) {
            $stationId = null;

            if (in_array($role, ['ADMIN', 'ANALYST'])) {
                $stationId = 1; // Для админов и аналитиков назначаем station_id = 1
            } else {
                $stationId = Station::where('type', '!=', 'ЛВУМГ')->inRandomOrder()->first()->id;
            }

            return [
                'station_id' => $stationId,
            ];
        });
    }
}
