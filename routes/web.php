<?php

use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', fn() => view('home'))->name('home');

// Registration
Route::prefix('daftar')->name('registration.')->group(function () {
    Route::get('/', [RegistrationController::class, 'create'])->name('create');
    Route::post('/', [RegistrationController::class, 'store'])->name('store');
    Route::get('/sukses', [RegistrationController::class, 'success'])->name('success');
    Route::get('/pending', [RegistrationController::class, 'pending'])->name('pending');
    Route::get('/error', [RegistrationController::class, 'error'])->name('error');
    Route::get('/status/{uuid}', [RegistrationController::class, 'status'])->name('status');
    Route::get('/receipt/{uuid}', [RegistrationController::class, 'downloadReceipt'])->name('receipt');
});