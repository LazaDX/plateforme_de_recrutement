<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/enqueteur/offre', function () {
    return view('frontOffice.pages.offre');
})->middleware(['auth'])->name('enqueteur.offre');

require __DIR__.'/auth.php';



