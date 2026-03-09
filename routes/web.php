<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\KtpOcrController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahController; 
// Home
Route::get('/', fn() => view('home'))->name('home');

// ── Wilayah.id Proxy (CORS workaround) ─────────────────────────
Route::get('/wilayah/provinces',        [WilayahController::class, 'provinces']);
Route::get('/wilayah/regencies/{id}',   [WilayahController::class, 'regencies'])->where('id', '[0-9]+');

// OCR
Route::post('/ocr/ktp', [KtpOcrController::class, 'scan'])->name('ocr.ktp');

// Registration
Route::prefix('daftar')->name('registration.')->group(function () {

    // UBAH: 'create' → 'index' untuk modal pilihan jalur
    Route::get('/', [RegistrationController::class, 'index'])->name('index');

    // TAMBAH: 4 halaman form per kategori
    Route::get('/ganda-dewasa-putra',  [RegistrationController::class, 'showGandaDewasaPutra'])->name('ganda-dewasa-putra');
    Route::get('/ganda-dewasa-putri',  [RegistrationController::class, 'showGandaDewasaPutri'])->name('ganda-dewasa-putri');
    Route::get('/ganda-veteran-putra', [RegistrationController::class, 'showGandaVeteranPutra'])->name('ganda-veteran-putra');
    Route::get('/beregu',              [RegistrationController::class, 'showBeregu'])->name('beregu');

    // POST store tetap sama
    Route::post('/', [RegistrationController::class, 'store'])->name('store');

    // Sisanya tidak berubah
    Route::get('/sukses',        [RegistrationController::class, 'success'])->name('success');
    Route::get('/pending',       [RegistrationController::class, 'pending'])->name('pending');
    Route::get('/error',         [RegistrationController::class, 'error'])->name('error');
    Route::get('/status/{uuid}', [RegistrationController::class, 'status'])->name('status');
    Route::get('/receipt/{uuid}',[RegistrationController::class, 'downloadReceipt'])->name('receipt');
});