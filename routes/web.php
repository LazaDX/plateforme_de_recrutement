<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/enqueteur/offres', function () {
    return view('frontOffice.pages.offres');
})->middleware(['auth'])->name('enqueteur.offres');

require __DIR__.'/auth.php';



