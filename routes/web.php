<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Livewire\OffreLivewire;

Route::middleware(['auth'])->group(function () {
    Route::get('/offres', OffreLivewire::class)->name('offres.index');
    Route::get('/enqueteur/offre', OffreLivewire::class)->name('enqueteur.offre');

});

require __DIR__.'/auth.php';

// Route::get('/enqueteur/offre', function () {
//     return view('frontOffice.pages.offre');
// })->middleware(['auth'])->name('enqueteur.offre');

// Route::get('/offres', \App\Http\Livewire\JobOffers::class)->name('offres.index');

// require __DIR__.'/auth.php';



// Route::get('/offres', \App\Http\Livewire\OffreLivewire::class)->name('offres.index');
// Route::get('/enqueteur/offre', function() {
//     return view('frontOffice.pages.offre');
// })->middleware(['auth'])->name('enqueteur.offre');
// Route::get('/postuler/{offre}', [PostuleController::class, 'create'])->name('postuler');

// require __DIR__.'/auth.php';
