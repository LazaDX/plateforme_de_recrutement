<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;

Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('backOffice.auth.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login']);
});

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('backOffice.pages.dashboard');
    })->name('backOffice.pages.dashboard');

    Route::post('admin/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout');
});


Route::get('/admin', function () {
    return view('backOffice.layouts.admin');
});

