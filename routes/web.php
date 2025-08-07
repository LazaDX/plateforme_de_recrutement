<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Livewire\OffreLivewire;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\PostuleOffreController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\EnqueteurController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Models\Offre;
use App\Models\Region;


Route::middleware('auth')->group(function () {
    Route::get('/enqueteur/offre', [OffreController::class, 'index'])
        ->name('enqueteur.offre');
    Route::get('/enqueteur/offre/{offre}', [OffreController::class, 'show'])
        ->name('enqueteur.offre.show');

    Route::get('/regions', [GeoController::class, 'regions'])->name('regions');
    Route::get('/regions/{region}/districts', [GeoController::class, 'districts'])->name('districts');
    Route::get('/districts/{district}/communes', [GeoController::class, 'communes'])->name('communes');

    Route::post('/offres/{offre}/postuler', [PostuleOffreController::class, 'store'])
        ->name('enqueteur.offre.postuler');
});


require __DIR__.'/auth.php';
