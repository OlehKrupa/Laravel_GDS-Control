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
        Schema::create('gassiness', function (Blueprint $table) {
            $table->id();
            $table->float('MPR');
            $table->json('measurements');
            $table->string('device');
            $table->integer('factory_number');
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
        Schema::dropIfExists('gassiness');
    }
};
