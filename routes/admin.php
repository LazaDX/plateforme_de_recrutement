<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\GeoController;


Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
});



Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('backOffice.pages.dashboard');
    })->name('dashboard');

    Route::get('/enqueteurs', function () {
        return view('backOffice.pages.enqueteurs');
    })->name('enqueteurs');

    Route::get('/historiques', function () {
        return view('backOffice.pages.historiques');
    })->name('historiques');

    Route::get('/analytiques', function () {
        return view('backOffice.pages.analytiques');
    })->name('analytiques');


    Route::get('/offers', function() {
        return view('backOffice.pages.offer');
    })->name('offers');

    Route::get('/offers/create', function() {
        return view('backOffice.pages.offer-create');
    })->name('offers.create');

    Route::get('/profile', function() {
        return view('backOffice.pages.profile');
    })->name('profile');

    Route::get('/regions', [GeoController::class, 'regions'])->name('regions');
    Route::get('/regions/{region}/districts', [GeoController::class, 'districts'])->name('districts');
    Route::get('/districts/{district}/communes', [GeoController::class, 'communes'])->name('communes');

    Route::post('/creat-offers', [OffreController::class, 'store'])->name('offers.store');

    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
});




