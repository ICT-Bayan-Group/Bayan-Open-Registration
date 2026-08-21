<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PantauController;

Route::post('/track-visit', [PantauController::class, 'track']);
// No external payment callbacks needed for manual verification