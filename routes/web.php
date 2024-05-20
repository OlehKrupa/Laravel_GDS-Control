<?php

use App\Http\Controllers\JournalController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GassinessController;
use Illuminate\Support\Facades\Route;

// Welcome route
Route::view('/', 'welcome');

// Dashboard and other authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/journal', 'journal')->name('journal');
    Route::view('/reports', 'reports')->name('reports');

    // Stations resource
    Route::resource('stations', StationController::class);

    // Settings routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('{setting}', [SettingsController::class, 'update'])->name('update');
        Route::delete('{setting}', [SettingsController::class, 'destroy'])->name('destroy');
    });

    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Gassiness routes
    Route::prefix('gassiness')->name('gassiness.')->group(function () {
        Route::get('/', [GassinessController::class, 'index'])->name('index');
        Route::get('/create', [GassinessController::class, 'create'])->name('create');
        Route::post('/', [GassinessController::class, 'store'])->name('store');
        Route::get('{gassiness}', [GassinessController::class, 'show'])->name('show');
        Route::get('{gassiness}/edit', [GassinessController::class, 'edit'])->name('edit');
        Route::put('{gassiness}', [GassinessController::class, 'update'])->name('update');
        Route::delete('{gassiness}', [GassinessController::class, 'destroy'])->name('destroy');
    });

    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // Journal routes
    Route::resource('journals', JournalController::class);

    // Reports routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::post('/generate', [ReportController::class, 'generate'])->name('generate');
    });
});

require __DIR__.'/auth.php';

