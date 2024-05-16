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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->text('operational_switching')->nullable();
            $table->text('received_orders')->nullable();
            $table->text('completed_works')->nullable();
            $table->text('visits_by_outsiders')->nullable();
            $table->text('inspection_of_pressure_tanks')->nullable();
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
        Schema::dropIfExists('notes');
    }
};
