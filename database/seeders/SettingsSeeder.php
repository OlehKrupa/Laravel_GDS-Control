<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Settings::create([
            'name' => 'alpha',
            'label' => 'Значення альфа',
            'value' => '0.5',
            'updated_by' => 1,
        ]);

        Settings::create([
            'name' => 'beta',
            'label' => 'Значення бета',
            'value' => '0.5',
            'updated_by' => 1,
        ]);
    }
}
