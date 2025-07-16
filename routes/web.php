<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/enqueteur/dashboard', function () {
    return view('frontOffice.pages.dashboard');
})->middleware(['auth'])->name('enqueteur.dashboard');

require __DIR__.'/auth.php';



