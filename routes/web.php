<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Livewire\OffreLivewire;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\PostuleOffreController;
use App\Http\Controllers\GeoController;
use App\Models\Offre;
use App\Models\Region;


// Route::middleware('auth')->group(function () {
//     Route::get('/', function () {
//         return view('frontOffice.pages.home');
//     })->name('home');

//     Route::get('/offres', OffreLivewire::class)->name('offres');
// });

Route::get('/enqueteur/offre', [OffreController::class, 'index'])
    ->middleware(['auth'])
    ->name('enqueteur.offre');

Route::get('/enqueteur/offre/{offre}', [OffreController::class, 'show'])
    ->middleware(['auth'])
    ->name('enqueteur.offre.show');


Route::get('/regions', [GeoController::class, 'regions'])->middleware(['auth'])->name('regions');
Route::get('/regions/{region}/districts', [GeoController::class, 'districts'])->middleware(['auth'])->name('districts');
Route::get('/districts/{district}/communes', [GeoController::class, 'communes'])->middleware(['auth'])->name('communes');

Route::post('/offres/{offre}/postuler', [PostuleOffreController::class, 'store'])
    ->name('enqueteur.offre.postuler')
    ->middleware('auth');

// Route::get('/enqueteur/postule', function(){
// return view('frontOffice.pages.postule-offre');
// })->middleware(['auth'])->name('enqueteur.postule');


require __DIR__.'/auth.php';
