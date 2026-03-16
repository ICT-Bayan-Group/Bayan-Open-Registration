<?php

use App\Http\Controllers\KtpOcrController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

// ── Auth ────────────────────────────────────────────────────────
Route::get('/login', fn() => redirect()->route('filament.admin.auth.login'))->name('login');

// ── Home ────────────────────────────────────────────────────────
Route::get('/', fn() => view('home'))->name('home');
Route::get('/bagan', fn() => view('bagan'))->name('bagan');
Route::get('/jadwal', fn() => view('jadwal'))->name('jadwal');

// ── Wilayah cascade ─────────────────────────────────────────────
Route::prefix('wilayah')->group(function () {
    Route::get('/provinces',       [WilayahController::class, 'provinces']);
    Route::get('/regencies/{id}',  [WilayahController::class, 'regencies'])->where('id', '[0-9]+');
});

// ── OCR KTP ─────────────────────────────────────────────────────
Route::post('/ocr/ktp', [KtpOcrController::class, 'scan'])->name('ocr.ktp');

// ── Payment by token ─────────────────────────────────────────────
// Nama route HARUS cocok dengan yang dipanggil di RegistrationController:
// route('registration.payment.token', $paymentToken)
Route::get('/payment/{token}', [RegistrationController::class, 'paymentByToken'])
    ->name('registration.payment.token')                               // ← fix nama
    ->where('token', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

// ── Pendaftaran ─────────────────────────────────────────────────
Route::prefix('daftar')->name('registration.')->group(function () {

    // Halaman pilih kategori
    Route::get('/', [RegistrationController::class, 'index'])->name('index');

    // Form per kategori
    Route::get('/ganda-dewasa-putra',  [RegistrationController::class, 'showGandaDewasaPutra'])->name('ganda-dewasa-putra');
    Route::get('/ganda-dewasa-putri',  [RegistrationController::class, 'showGandaDewasaPutri'])->name('ganda-dewasa-putri');
    Route::get('/ganda-veteran-putra', [RegistrationController::class, 'showGandaVeteranPutra'])->name('ganda-veteran-putra');
    Route::get('/beregu',              [RegistrationController::class, 'showBeregu'])->name('beregu');

    // Submit form
    Route::post('/', [RegistrationController::class, 'store'])->name('store');

    // Di dalam group prefix('daftar')->name('registration.'):
    Route::get('/pending-payment/{uuid}', function ($uuid) {
        $registration = \App\Models\Registration::where('uuid', $uuid)->firstOrFail();
        return view('registration.pending-payment', compact('registration'));
    })->name('pending-payment');

    // Callback & status
    Route::get('/sukses',        [RegistrationController::class, 'success'])->name('success');
    Route::get('/pending',       [RegistrationController::class, 'pending'])->name('pending');
    Route::get('/error',         [RegistrationController::class, 'error'])->name('error');
    Route::get('/status/{uuid}', [RegistrationController::class, 'status'])->name('status');
    Route::get('/receipt/{uuid}',[RegistrationController::class, 'downloadReceipt'])->name('receipt');
});

Route::get('/registration/{uuid}/receipt-status', [RegistrationController::class, 'receiptStatus'])
    ->name('registration.receipt.status');

// ── Serve foto KTP (admin only) ─────────────────────────────────
Route::middleware('auth:web')
    ->get('/admin/ktp/{uuid}/{filename}', [RegistrationController::class, 'serveKtp'])
    ->name('admin.ktp.serve')
    ->where('filename', '[^/]+');