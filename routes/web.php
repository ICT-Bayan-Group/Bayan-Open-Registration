<?php

use App\Http\Controllers\KtpOcrController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\RegistrationRevisionController;
use Illuminate\Support\Facades\Route;

// ── Auth ────────────────────────────────────────────────────────
Route::get('/login', fn() => redirect()->route('filament.admin.auth.login'))->name('login');

// ── Home ────────────────────────────────────────────────────────
Route::get('/', fn() => view('home'))->name('home');
Route::get('/v1', fn() => view('welcome'))->name('welcome');
Route::get('/bagan', fn() => view('bagan'))->name('bagan');
Route::get('/jadwal', fn() => view('jadwal'))->name('jadwal');
Route::get('/livescore', fn() => view('livescore'))->name('livescore');

// ── Wilayah cascade ─────────────────────────────────────────────
Route::prefix('wilayah')->group(function () {
    Route::get('/provinces',       [WilayahController::class, 'provinces']);
    Route::get('/regencies/{id}',  [WilayahController::class, 'regencies'])->where('id', '[0-9]+');
});

// ── OCR KTP ─────────────────────────────────────────────────────
Route::post('/ocr/ktp', [KtpOcrController::class, 'scan'])->name('ocr.ktp');

// ── Payment by token ─────────────────────────────────────────────
Route::get('/payment/{token}', [RegistrationController::class, 'paymentByToken'])
    ->name('registration.payment.token')
    ->where('token', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

// ── Pendaftaran ─────────────────────────────────────────────────
Route::prefix('daftar')->name('registration.')->group(function () {

    // Halaman pilih kategori
    Route::get('/', [RegistrationController::class, 'index'])->name('index');

    // Form per kategori
    Route::get('/ganda-dewasa-putra',  [RegistrationController::class, 'showGandaDewasaPutra'])->name('ganda-dewasa-putra');
    Route::get('/ganda-dewasa-putri',  [RegistrationController::class, 'showGandaDewasaPutri'])->name('ganda-dewasa-putri');
    Route::get('/ganda-veteran-putra', [RegistrationController::class, 'showGandaVeteranPutra'])->name('ganda-veteran-putra');
    Route::get('/beregu/slot', [RegistrationController::class, 'beregSlot'])->name('beregu.slot');
    Route::get('/beregu',              [RegistrationController::class, 'showBeregu'])->name('beregu');
    Route::get('/revisi/{token}',        [RegistrationRevisionController::class, 'show'])
        ->name('revisi')
        ->where('token', '[0-9a-f]{64}');

    Route::put('/revisi/{token}',        [RegistrationRevisionController::class, 'update'])
        ->name('revision.update')
        ->where('token', '[0-9a-f]{64}');
 
    Route::get('/revisi/sukses/{uuid}',  [RegistrationRevisionController::class, 'success'])
        ->name('revision.success');
    
    Route::get('/revisi/preview-ktp/{token}/{index}', [RegistrationRevisionController::class, 'previewKtp'])
        ->name('revision.ktp.preview');

    // Submit form
    Route::post('/', [RegistrationController::class, 'store'])->name('store');

    // Status halaman setelah submit
    Route::get('/pending-payment/{uuid}', function ($uuid) {
        $registration = \App\Models\Registration::where('uuid', $uuid)->firstOrFail();
        return view('registration.pending-payment', compact('registration'));
    })->name('pending-payment');

    // ↓ INI YANG HILANG — pending-review untuk kategori beregu
    Route::get('/pending-review/{uuid}', function ($uuid) {
        $registration = \App\Models\Registration::where('uuid', $uuid)->firstOrFail();
        return view('registration.pending-review', compact('registration'));
    })->name('pending-review');

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