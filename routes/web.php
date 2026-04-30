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
Route::get('/kontak', fn() => view('contact'))->name('contact');

// ── Wilayah cascade ─────────────────────────────────────────────
Route::prefix('wilayah')->group(function () {
    Route::get('/provinces',       [WilayahController::class, 'provinces']);
    Route::get('/regencies/{id}',  [WilayahController::class, 'regencies'])->where('id', '[0-9]+');
});

// ── OCR KTP ─────────────────────────────────────────────────────
Route::post('/ocr/ktp', [KtpOcrController::class, 'scan'])->name('ocr.ktp');

// ── Payment by token ─────────────────────────────────────────────
Route::get('/payment/{token}', [RegistrationController::class, 'paymentByToken'])
    ->name('registration.payment.token');

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

    // Upload payment proof
    Route::post('/upload-payment/{uuid}', [RegistrationController::class, 'uploadPayment'])
        ->name('upload-payment')
        ->where('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

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

    // Status & receipt
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

// ── Serve foto Paspor (admin only) ─────────────────────────────
Route::middleware('auth:web')
    ->get('/admin/paspor/{uuid}/{filename}', [RegistrationController::class, 'servePaspor'])
    ->name('admin.paspor.serve')
    ->where('filename', '[^/]+');

// ── Admin Action Password Verification ─────────────────────────
Route::middleware('auth:web')->group(function () {

    Route::post('/admin/verify-action-password', function (\Illuminate\Http\Request $request) {
        $input = $request->input('password', '');

        // Hash dari "Okedeh.12345!" — tidak pernah keluar ke frontend
        $hash  = '$2y$12$YOUR_BCRYPT_HASH_HERE';
        $valid = \Illuminate\Support\Facades\Hash::check($input, $hash);

        if (! $valid) {
            return response()->json(['ok' => false], 403);
        }

        // Signed token berlaku 5 menit
        $token = encrypt(now()->timestamp . '|admin_action_verified');

        return response()->json(['ok' => true, 'token' => $token]);
    })->name('admin.verify-action-password');

    Route::post('/admin/validate-action-token', function (\Illuminate\Http\Request $request) {
        try {
            $payload   = decrypt($request->input('token', ''));
            [$ts, $sig] = explode('|', $payload, 2);

            $expired = (now()->timestamp - (int) $ts) > 300; // 5 menit
            $valid   = $sig === 'admin_action_verified';

            if ($expired || ! $valid) {
                return response()->json(['ok' => false, 'reason' => 'expired'], 403);
            }

            return response()->json(['ok' => true]);

        } catch (\Throwable) {
            return response()->json(['ok' => false, 'reason' => 'invalid'], 403);
        }
    })->name('admin.validate-action-token');

});