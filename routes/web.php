<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LorryController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SiteController;

/*
|--------------------------------------------------------------------------
| Web Routes — Fleet Management System
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects
    Route::resource('projects', ProjectController::class);

    // Tickets
    Route::resource('tickets', TicketController::class);
    Route::patch('tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
         ->name('tickets.updateStatus');

    // Maintenance
    Route::resource('maintenance', MaintenanceController::class);
    Route::patch('maintenance/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])
         ->name('maintenance.updateStatus');

    // Settings — Drivers
    Route::resource('drivers', DriverController::class);

    // Settings — Lorries + spare parts
    Route::resource('lorries', LorryController::class);
    Route::post('lorries/{lorry}/spare-parts', [LorryController::class, 'storeSparePart'])
         ->name('lorries.spare-parts.store');

    // Settings — Regions
    Route::resource('regions', RegionController::class);

    // Settings — Sites
    Route::resource('sites', SiteController::class);

    // Profile stub (for header dropdown)
    Route::get('/profile', fn() => view('profile'))->name('profile.show');

});

// Auth routes (using Laravel Breeze / Fortify / etc.)
require __DIR__ . '/auth.php';
