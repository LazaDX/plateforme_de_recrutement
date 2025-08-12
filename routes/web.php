<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Livewire\OffreLivewire;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\PostuleOffreController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\EnqueteurController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\EnqueteurDashboardController;
use App\Models\Offre;
use App\Models\Region;


Route::middleware('auth')->group(function () {

    Route::get('/enqueteur/profile', [EnqueteurController::class, 'edit'])->name('enqueteur.profile.edit');
    Route::post('/enqueteur/profile', [EnqueteurController::class, 'update'])
         ->name('enqueteur.profile.update');

    Route::get('/enqueteur/offre', [OffreController::class, 'index'])
        ->name('enqueteur.offre');
    Route::get('/enqueteur/offre/{offre}', [OffreController::class, 'show'])
        ->name('enqueteur.offre.show');

    Route::get('/enqueteur/dashboard', [EnqueteurDashboardController::class, 'dashboard'])->name('enqueteur.dashboard');
    Route::get('/enqueteur/candidatures', [EnqueteurDashboardController::class, 'candidatures'])->name('enqueteur.candidatures');
    Route::delete('/enqueteur/candidature/{id}/cancel', [EnqueteurDashboardController::class, 'cancelCandidature'])->name('enqueteur.candidature.cancel');

    Route::get('/regions', [GeoController::class, 'regions'])->name('regions');
    Route::get('/regions/{region}/districts', [GeoController::class, 'districts'])->name('districts');
    Route::get('/districts/{district}/communes', [GeoController::class, 'communes'])->name('communes');

    Route::post('/offres/{offre}/postuler', [PostuleOffreController::class, 'store'])
        ->name('enqueteur.offre.postuler');
});


require __DIR__.'/auth.php';
