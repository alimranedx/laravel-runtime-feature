<?php

use Illuminate\Support\Facades\Route;
use Imran\LaravelRuntimeFeature\Http\Controllers\FeatureController;

Route::middleware(['web'])->prefix('features-manager')->name('features.')->group(function () {
    Route::get('/', [FeatureController::class, 'index'])->name('index');
    Route::get('/create', [FeatureController::class, 'create'])->name('create');
    Route::post('/', [FeatureController::class, 'store'])->name('store');
    Route::get('/{feature}/edit', [FeatureController::class, 'edit'])->name('edit');
    Route::put('/{feature}', [FeatureController::class, 'update'])->name('update');
    Route::delete('/{feature}', [FeatureController::class, 'destroy'])->name('destroy');
    Route::post('/{feature}/toggle', [FeatureController::class, 'toggle'])->name('toggle');
});
