<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('journal', function (Blueprint $table) {
            $table->id();
            $table->float('pressure_in');
            $table->float('pressure_out_1');
            $table->float('pressure_out_2')->nullable();
            $table->float('temperature_1');
            $table->float('temperature_2')->nullable();
            $table->float('odorant_value_1');
            $table->float('odorant_value_2')->nullable();
            $table->float('gas_heater_temperature_in');
            $table->float('gas_heater_temperature_out');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('user_station_id')->constrained('station');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal');
    }
};
