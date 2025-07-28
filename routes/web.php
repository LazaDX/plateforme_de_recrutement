<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Livewire\OffreLivewire;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\PostuleOffreController;
use App\Models\Offre;
use App\Models\Region;


Route::get('/enqueteur/offre', [OffreController::class, 'index'])
    ->middleware(['auth'])
    ->name('enqueteur.offre');

Route::get('/enqueteur/offre/{offre}', [OffreController::class, 'show'])
    ->middleware(['auth'])
    ->name('enqueteur.offre.show');


Route::post('/offres/{offre}/postuler', [PostuleOffreController::class, 'store'])
    ->name('offres.postuler')
    ->middleware('auth');

// Route::get('/enqueteur/postule', function(){
// return view('frontOffice.pages.postule-offre');
// })->middleware(['auth'])->name('enqueteur.postule');


require __DIR__.'/auth.php';
