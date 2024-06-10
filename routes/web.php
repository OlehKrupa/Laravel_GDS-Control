<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SelfSpendingsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SpendingsController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\GassinessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Welcome route
Route::view('/', 'welcome');

// Authentication routes
require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/get-fields', [DashboardController::class, 'getFields']);
    Route::get('/chart-data', [DashboardController::class, 'getChartData']);
    Route::get('/forecast-data', [DashboardController::class, 'getForecastData'])->name('forecast-data');

    // Journal routes
    Route::view('/journal', 'journal')->name('journal');
    Route::resource('journals', JournalController::class);
    Route::post('/journals/generate-report', [JournalController::class, 'generateReport'])->name('journals.generateReport');

    // Reports route
    Route::view('/reports', 'reports')->name('reports');

    // Stations resource
    Route::resource('stations', StationController::class);
    Route::post('stations/generate', [StationController::class, 'generate'])->name('station.generate.report');

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
        Route::post('/generate-report', [GassinessController::class, 'generateReport'])->name('generateReport');
    });

    // Notes routes
    Route::prefix('notes')->name('notes.')->group(function () {
        Route::get('/', [NoteController::class, 'index'])->name('index');
        Route::get('/create', [NoteController::class, 'create'])->name('create');
        Route::post('/', [NoteController::class, 'store'])->name('store');
        Route::get('{note}/edit', [NoteController::class, 'edit'])->name('edit');
        Route::put('{note}', [NoteController::class, 'update'])->name('update');
        Route::delete('{note}', [NoteController::class, 'destroy'])->name('destroy');
        Route::post('/report', [NoteController::class, 'generateReport'])->name('generateReport');
    });

    // Spendings routes
    Route::resource('spendings', SpendingsController::class);
    Route::post('spendings/report', [SpendingsController::class, 'generateReport'])->name('spendings.generateReport');

    // SelfSpendings routes
    Route::prefix('selfSpendings')->name('selfSpendings.')->group(function () {
        Route::get('/', [SelfSpendingsController::class, 'index'])->name('index');
        Route::get('/create', [SelfSpendingsController::class, 'create'])->name('create');
        Route::post('/', [SelfSpendingsController::class, 'store'])->name('store');
        Route::get('{selfSpending}', [SelfSpendingsController::class, 'edit'])->name('edit');
        Route::put('{selfSpending}', [SelfSpendingsController::class, 'update'])->name('update');
        Route::delete('{selfSpending}', [SelfSpendingsController::class, 'destroy'])->name('destroy');
        Route::post('/report', [SelfSpendingsController::class, 'generateReport'])->name('generateReport');
    });

    // Settings routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('{setting}', [SettingsController::class, 'update'])->name('update');
        Route::delete('{setting}', [SettingsController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::resource('users', UserController::class)->except(['show']);

        // Route for soft deleted users
        Route::get('users/trashed', [UserController::class, 'trashed'])->name('users.trashed');
        Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{id}/forceDelete', [UserController::class, 'forceDelete'])->name('users.forceDelete');

        Route::get('/logs', [AdminController::class, 'index'])->name('logs');
        Route::delete('/undo/{model}/{log}', [AdminController::class, 'undo'])->name('undo');
        Route::delete('/delete/{log}', [AdminController::class, 'delete'])->name('delete');
    });
});
