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
        Schema::create('self_spendings', function (Blueprint $table) {
            $table->id();
            $table->float('heater_time');
            $table->float('boiler_time');
            $table->float('heater_gas');
            $table->float('boiler_gas');
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
        Schema::dropIfExists('self_spendings');
    }
};
