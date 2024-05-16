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
        Schema::table('users', function (Blueprint $table) {
            // Добавляем новые столбцы
            $table->foreignId('station_id')->constrained('station');
            $table->string('surname');
            $table->string('patronymic');

            // Добавляем soft deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Удаляем столбцы при откате миграции
            $table->dropForeign(['station_id']);
            $table->dropColumn('station_id');
            $table->dropColumn('surname');
            $table->dropColumn('patronymic');

            // Удаляем soft deletes
            $table->dropSoftDeletes();
        });
    }
};
