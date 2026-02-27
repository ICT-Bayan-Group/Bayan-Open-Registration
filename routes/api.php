<?php
// routes/api.php

use App\Http\Controllers\MidtransController;
use Illuminate\Support\Facades\Route;

// Midtrans Webhook — must be excluded from CSRF
Route::post('/midtrans/callback', [MidtransController::class, 'callback'])
    ->name('midtrans.callback');