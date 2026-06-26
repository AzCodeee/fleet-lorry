<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionController;


Route::middleware(['auth'])->prefix('')->group( function() {
    Route::prefix('/region')->name('region.')->group(function () {
        Route::get('/', [RegionController::class, 'index'])->name('index');
        Route::get('/show/{id}', [RegionController::class, 'show'])->name('show');
        Route::post('/store', [RegionController::class, 'store'])->name('store');
        Route::put('/update', [RegionController::class, 'update'])->name('update');
    });
});
