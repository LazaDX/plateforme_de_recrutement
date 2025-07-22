<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;


Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
});



Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('backOffice.pages.dashboard');
    })->name('dashboard');

    Route::get('/offers', function() {
        return view('backOffice.pages.offer');
    })->name('offers');

    Route::get('/profile', function() {
        return view('backOffice.pages.profile');
    })->name('profile');

    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
});




