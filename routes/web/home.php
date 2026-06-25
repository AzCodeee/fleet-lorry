<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DecryptController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Auth::routes();
// Route::get('/', [HomeController::class, 'root'])->name('home');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/2fa', [LoginController::class, 'show2FAform'])
        ->middleware('guest')
        ->name('user.2fa');

Route::post('/2fa', [LoginController::class, 'verify2FA'])
        ->middleware('guest')
        ->name('user.2fa.verify');

Route::get('/decrypt-id/{id}', [DecryptController::class, 'show'])
        ->middleware('auth')
        ->name('decrypt.show');

require __DIR__.'/auth.php';
