<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome route
Route::view('/', 'welcome');

// Dashboard and other authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/journal', 'journal')->name('journal');
    Route::view('/reports', 'reports')->name('reports');

    Route::resource('stations', StationController::class);

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings/{setting}', [SettingsController::class, 'update'])->name('settings.update');
    Route::delete('settings/{setting}', [SettingsController::class, 'destroy'])->name('settings.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
});

require __DIR__.'/auth.php';
