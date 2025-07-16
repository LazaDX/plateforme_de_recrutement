<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/enqueteur/enqueteur-offer', function () {
    return view('frontOffice.pages.enqueteur-offre');
})->middleware(['auth'])->name('enqueteur.dashboard');

require __DIR__.'/auth.php';



