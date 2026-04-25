<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPaidRegistration;
use App\Mail\RegistrationApproved;
use App\Mail\RegistrationPaid;
use App\Mail\RegistrationRejected;
use App\Models\Registration;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{

    // ============================================================
    // HALAMAN PILIHAN KATEGORI
    // ============================================================

    public function index()
    {
        return view('home');
    }

    // ============================================================
    // FORM PER KATEGORI
    // ============================================================

    public function showGandaDewasaPutra()
    {
        return view('registration.form-dewasa', [
            'kategori'  => 'ganda-dewasa-putra',
            'label'     => 'Ganda Dewasa Putra',
            'harga'     => 400000,
            'minPemain' => 2,
            'maxPemain' => 2,
        ]);
    }

    public function showGandaDewasaPutri()
    {
        return view('registration.form-dewasa', [
            'kategori'  => 'ganda-dewasa-putri',
            'label'     => 'Ganda Dewasa Putri',
            'harga'     => 400000,
            'minPemain' => 2,
            'maxPemain' => 2,
        ]);
    }

    public function showGandaVeteranPutra()
    {
        return view('registration.form-veteran');
    }

    public function showBeregu()
    {
        return view('registration.form-beregu');
    }

    // ============================================================
    // STORE — Support AJAX (X-Requested-With: XMLHttpRequest)
    // ============================================================

    public function store(Request $request)
    {
        // ── Deteksi apakah request dari AJAX ──────────────────────
        $isAjax = $request->ajax()
            || $request->wantsJson()
            || $request->header('Accept') === 'application/json';

        $kategori = $request->input('kategori');
        $isBeregu = ($kategori === 'beregu');
        // ── 0. Cek kuota beregu (max 32 tim paid) ─────────────────────
        if ($isBeregu) {
            $filled = $this->getBeregPaidCount();
            if ($filled >= self::MAX_BEREGU_TEAMS) {
                $msg = 'Kuota pendaftaran beregu sudah penuh (32/32 tim). Pendaftaran ditutup.';
                if ($isAjax) {
                    return response()->json([
                        'errors'  => ['kuota' => [$msg]],
                        'message' => $msg,
                        'penuh'   => true,
                    ], 422);
                }
                return back()->withInput()->withErrors(['kuota' => $msg]);
            }
        }

        // ── 1. Validasi dasar ──────────────────────────────────────
        // Jika validasi gagal dan request AJAX → Laravel otomatis return 422 JSON
        // Jika bukan AJAX → redirect back dengan errors (fallback)
        $validated = $request->validate([
            'nama'          => 'required|string|max:100',
            'tim_pb'        => 'required|string|max:100',
            'email'         => 'required|email|max:150',
            'no_hp'         => ['required','string','max:20','regex:/^(\+62|62|0)8[1-9][0-9]{6,}$/'],
            'provinsi'      => 'required|string|max:100',
            'kota'          => 'required|string|max:100',
            'nama_pelatih'  => 'nullable|string|max:100',
            'no_hp_pelatih' => 'nullable|string|max:20',
            'pemain'        => 'required|array|min:1|max:10',
            'pemain.*'      => 'required|string|max:100',
            'kategori'      => 'required|in:ganda-dewasa-putra,ganda-dewasa-putri,ganda-veteran-putra,beregu',
        ], [
            'nama.required'     => 'Nama ketua tim wajib diisi.',
            'tim_pb.required'   => 'Nama tim / PB wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'no_hp.required'    => 'Nomor HP wajib diisi.',
            'no_hp.regex'       => 'Nomor HP harus dalam format Indonesia, misal 081234567890 atau +6281234567890.',
            'provinsi.required' => 'Provinsi wajib dipilih.',
            'kota.required'     => 'Kota wajib dipilih.',
            'pemain.required'   => 'Data pemain wajib diisi.',
            'pemain.*.required' => 'Nama pemain tidak boleh kosong.',
            'kategori.required' => 'Pilih kategori terlebih dahulu.',
            'kategori.in'       => 'Kategori tidak valid.',
        ]);

        // ── 2. Validasi jumlah pemain per kategori ─────────────────
        [$minPemain, $maxPemain] = match ($kategori) {
            'beregu' => [6, 8],
            default  => [2, 2],
        };

        $pemainDiisi = array_values(
            array_filter($validated['pemain'], fn ($p) => ! empty(trim($p)))
        );

        if (count($pemainDiisi) < $minPemain) {
            $errorMsg = "Kategori {$kategori} membutuhkan minimal {$minPemain} pemain.";
            if ($isAjax) {
                return response()->json([
                    'errors' => ['pemain' => [$errorMsg]],
                ], 422);
            }
            return back()->withInput()->withErrors(['pemain' => $errorMsg]);
        }

        if (count($pemainDiisi) > $maxPemain) {
            $errorMsg = "Kategori {$kategori} maksimal {$maxPemain} pemain.";
            if ($isAjax) {
                return response()->json([
                    'errors' => ['pemain' => [$errorMsg]],
                ], 422);
            }
            return back()->withInput()->withErrors(['pemain' => $errorMsg]);
        }

        $validated['pemain'] = $pemainDiisi;
        $jumlahPemain        = count($pemainDiisi);

        // ── 3. Ambil data KTP dari form ────────────────────────────
        $nikArr = array_pad(
            array_slice($request->input('nik', []), 0, $jumlahPemain),
            $jumlahPemain,
            null
        );

        $tglLahirArr = array_pad(
            array_slice($request->input('tgl_lahir', []), 0, $jumlahPemain),
            $jumlahPemain,
            null
        );

        $jenisKelaminArr = array_pad(
            array_slice($request->input('jenis_kelamin', []), 0, $jumlahPemain),
            $jumlahPemain,
            null
        );

        // ── 4. Hitung usia backend ─────────────────────────────────
        $usiaArr = array_map(function ($tgl) {
            if (! $tgl) return null;
            try {
                $tgl   = str_replace('/', '-', trim($tgl));
                $parts = explode('-', $tgl);
                $birth = (count($parts) === 3 && strlen($parts[0]) <= 2 && strlen($parts[2]) === 4)
                    ? Carbon::createFromFormat('d-m-Y', $tgl)
                    : Carbon::parse($tgl);
                return (int) now()->format('Y') - (int) $birth->format('Y');
            } catch (\Exception) {
                return null;
            }
        }, $tglLahirArr);

        // ── 5. Validasi upload KTP ─────────────────────────────────
        $ktpValidation = $request->validate([
            'ktp_files'   => 'required|array|min:' . $jumlahPemain,
            'ktp_files.*' => 'required|file|mimes:jpg,jpeg,png,webp,heic,heif|max:10240',
        ], [
            'ktp_files.required'   => 'File KTP wajib diupload untuk semua pemain.',
            'ktp_files.min'        => 'Upload KTP untuk semua pemain yang didaftarkan.',
            'ktp_files.*.required' => 'Semua file KTP wajib diisi.',
            'ktp_files.*.mimes'    => 'File KTP harus berformat JPG, PNG, WebP, atau HEIC.',
            'ktp_files.*.max'      => 'Ukuran file KTP maksimal 10MB.',
        ]);

        // ── 5b. Validasi jenis kelamin per kategori ────────────────
        if (in_array($kategori, ['ganda-dewasa-putra', 'ganda-dewasa-putri'], true)) {
            $genderYangDiharuskan = ($kategori === 'ganda-dewasa-putra') ? 'L' : 'P';
            $labelGender          = ($kategori === 'ganda-dewasa-putra') ? 'Laki-laki' : 'Perempuan';
            $labelGenderSalah     = ($kategori === 'ganda-dewasa-putra') ? 'Perempuan' : 'Laki-laki';

            foreach ($pemainDiisi as $i => $namaPemain) {
                $genderPemain = strtoupper(trim($jenisKelaminArr[$i] ?? ''));

                if (empty($genderPemain)) {
                    $errorMsg = sprintf(
                        'Pemain %d (%s): Jenis kelamin tidak terdeteksi dari KTP. '
                        . 'Pastikan scan KTP berhasil sebelum submit.',
                        $i + 1, $namaPemain
                    );
                    if ($isAjax) {
                        return response()->json([
                            'errors' => ['jenis_kelamin.' . $i => [$errorMsg]],
                        ], 422);
                    }
                    return back()->withInput()->withErrors(['jenis_kelamin' => $errorMsg]);
                }

                if ($genderPemain !== $genderYangDiharuskan) {
                    $errorMsg = sprintf(
                        'Pemain %d (%s) terdeteksi sebagai %s. '
                        . 'Kategori %s hanya untuk pemain %s.',
                        $i + 1, $namaPemain, $labelGenderSalah, $kategori, $labelGender
                    );
                    if ($isAjax) {
                        return response()->json([
                            'errors' => ['jenis_kelamin.' . $i => [$errorMsg]],
                        ], 422);
                    }
                    return back()->withInput()->withErrors(['jenis_kelamin' => $errorMsg]);
                }
            }
        }

        // ── 6. Validasi kota KTP untuk beregu ─────────────────────
        $kotaArr      = $request->input('kota_ktp', []);
        $cityValidArr = [];
        $validCount   = 0;

        if ($isBeregu) {
            foreach ($pemainDiisi as $i => $nama) {
                $kotaRaw = strtoupper(trim($kotaArr[$i] ?? ''));
                $isValid = $this->isCityValid($kotaRaw);
                if ($isValid) $validCount++;
                $cityValidArr[] = [
                    'index'    => $i + 1,
                    'nama'     => $nama,
                    'city_raw' => $kotaRaw,
                    'valid'    => $isValid,
                ];
            }

            if ($validCount < 6) {
                $errorMsg = "Minimal 6 anggota harus ber-KTP Kota Balikpapan. "
                          . "Saat ini hanya {$validCount} anggota yang valid.";
                if ($isAjax) {
                    return response()->json([
                        'errors' => ['pemain' => [$errorMsg]],
                    ], 422);
                }
                return back()->withInput()->withErrors(['pemain' => $errorMsg]);
            }
        }

        // ── 7. Validasi khusus Ganda Veteran Putra ─────────────────
        if ($kategori === 'ganda-veteran-putra') {
            $request->validate([
                'usia_valid'    => 'required|array|size:2',
                'usia_valid.*'  => 'required|in:1',
                'usia_hitung'   => 'required|array|size:2',
                'usia_hitung.*' => 'required|integer|min:45',
            ], [
                'usia_valid.required'  => 'Verifikasi usia wajib dilakukan via scan KTP.',
                'usia_valid.size'      => 'Kedua pemain harus di-scan KTP-nya.',
                'usia_valid.*.in'      => 'Kedua pemain harus memenuhi syarat veteran (min. 45 tahun).',
                'usia_hitung.required' => 'Data usia pemain tidak ditemukan.',
                'usia_hitung.*.min'    => 'Setiap pemain minimal berusia 45 tahun.',
            ]);

            $usiaHitung = $request->input('usia_hitung', []);
            $u0 = (int) ($usiaHitung[0] ?? 0);
            $u1 = (int) ($usiaHitung[1] ?? 0);

            if ($u0 < 45) {
                $errorMsg = "Pemain 1 berusia {$u0} tahun, tidak memenuhi syarat veteran (min. 45 tahun).";
                if ($isAjax) return response()->json(['errors' => ['usia_hitung.0' => [$errorMsg]]], 422);
                return back()->withInput()->withErrors(['usia_hitung' => $errorMsg]);
            }
            if ($u1 < 45) {
                $errorMsg = "Pemain 2 berusia {$u1} tahun, tidak memenuhi syarat veteran (min. 45 tahun).";
                if ($isAjax) return response()->json(['errors' => ['usia_hitung.1' => [$errorMsg]]], 422);
                return back()->withInput()->withErrors(['usia_hitung' => $errorMsg]);
            }
            $totalUsia = $u0 + $u1;
            if ($totalUsia < 95) {
                $errorMsg = "Total usia kedua pemain hanya {$totalUsia} tahun ({$u0} + {$u1}). Minimal total usia adalah 95 tahun.";
                if ($isAjax) return response()->json(['errors' => ['usia_hitung' => [$errorMsg]]], 422);
                return back()->withInput()->withErrors(['usia_hitung' => $errorMsg]);
            }
        }

        // ── 8. Harga ───────────────────────────────────────────────
        $harga = match ($kategori) {
            'beregu' => 1000000,
            default  => 400000,
        };

        // ── 9. Tentukan approval status ────────────────────────────
        $approvalStatus = $isBeregu ? 'pending_review' : 'approved';

        // ── 10. Generate payment token ─────────────────────────────
        $paymentToken          = Str::uuid()->toString();
        $paymentTokenExpiresAt = $isBeregu ? null : now()->addHours(24);

        // ── 11. Buat record Registration ───────────────────────────
        $registration = Registration::create([
            'nama'                     => $validated['nama'],
            'tim_pb'                   => $validated['tim_pb'],
            'email'                    => $validated['email'],
            'no_hp'                    => $validated['no_hp'],
            'provinsi'                 => $validated['provinsi'],
            'kota'                     => $validated['kota'],
            'nama_pelatih'             => $validated['nama_pelatih']  ?? null,
            'no_hp_pelatih'            => $validated['no_hp_pelatih'] ?? null,
            'pemain'                   => $pemainDiisi,
            'nik'                      => $nikArr,
            'tgl_lahir'                => $tglLahirArr,
            'usia_pemain'              => $usiaArr,
            'jenis_kelamin_pemain'     => $jenisKelaminArr,
            'ktp_city_valid'           => $isBeregu ? $cityValidArr : null,
            'kategori'                 => $kategori,
            'harga'                    => $harga,
            'status'                   => 'pending',
            'approval_status'          => $approvalStatus,
            'payment_token'            => $paymentToken,
            'payment_token_expires_at' => $paymentTokenExpiresAt,
            'tgl_lahir_pemain'         => $kategori === 'ganda-veteran-putra' ? $tglLahirArr : null,
        ]);

        // ── 12. Upload file KTP ────────────────────────────────────
        $ktpPaths   = [];
        $ktpRawData = [];

        foreach ($request->file('ktp_files') as $i => $file) {
            $namaFile = sprintf(
                'pemain-%d-%s.%s',
                $i + 1,
                $registration->uuid,
                $file->getClientOriginalExtension()
            );

            $path = $file->storeAs(
                "ktp/{$registration->uuid}",
                $namaFile,
                'private'
            );

            $ktpPaths[] = $path;

            $ktpRawData[] = [
                'index'         => $i + 1,
                'nama'          => $pemainDiisi[$i]              ?? null,
                'nik'           => $nikArr[$i]                   ?? null,
                'tgl_lahir'     => $tglLahirArr[$i]              ?? null,
                'usia'          => $usiaArr[$i]                  ?? null,
                'jenis_kelamin' => $jenisKelaminArr[$i]          ?? null,
                'kota'          => $cityValidArr[$i]['city_raw'] ?? null,
                'file_path'     => $path,
                'file_name'     => $namaFile,
            ];
        }

        $registration->update([
            'ktp_files' => $ktpPaths,
            'ktp_data'  => $ktpRawData,
        ]);

        // ── 13. Routing berdasarkan kategori ───────────────────────

        // Beregu: pending review (tidak langsung bayar)
        if ($isBeregu) {
            $pendingUrl = route('registration.pending-review', $registration->uuid);

            if ($isAjax) {
                return response()->json([
                    'success'  => true,
                    'redirect' => $pendingUrl,
                    'uuid'     => $registration->uuid,
                    'message'  => 'Pendaftaran berhasil dikirim untuk direview.',
                ]);
            }
            return view('registration.pending-review', compact('registration'));
        }

        // Non-beregu: kirim email approved lalu arahkan ke payment
        try {
            Mail::to($registration->email)
                ->send(new RegistrationApproved($registration));
        } catch (\Exception $e) {
            logger()->error('[Registration] Gagal kirim email approved: ' . $e->getMessage(), [
                'registration_id' => $registration->id,
                'email'           => $registration->email,
            ]);
        }

        $whatsappService = app(\App\Services\WhatsAppService::class);
        try {
            $whatsappService->sendPaymentLink($registration);
        } catch (\Throwable $e) {
            logger()->error('[Registration] Gagal kirim WhatsApp payment link: ' . $e->getMessage(), [
                'registration_id' => $registration->id,
            ]);
        }

        // SESUDAH — selalu ke pending-payment dulu (halaman "cek email"):
        $pendingPaymentUrl = route('registration.pending-payment', $registration->uuid);

        if ($isAjax) {
            return response()->json([
                'success'       => true,
                'redirect'      => $pendingPaymentUrl,
                'uuid'          => $registration->uuid,
                'payment_token' => $paymentToken,
                'message'       => 'Pendaftaran berhasil. Silakan cek email dan WhatsApp untuk link pembayaran.',
            ]);
        }

        return view('registration.pending-payment', compact('registration'));
    }

    // ============================================================
    // PAYMENT VIA TOKEN
    // ============================================================

    public function paymentByToken(string $token)
    {
        $registration = Registration::where('payment_token', $token)
            ->where('approval_status', 'approved')
            ->firstOrFail();

        if (! $registration->paymentTokenValid()) {
            return view('registration.payment-expired', compact('registration'));
        }

        // If already paid, show status page
        if ($registration->status === 'paid') {
            return redirect()->route('registration.status', $registration->uuid);
        }

        // Show payment page with bank transfer instructions
        return view('registration.payment', compact('registration'));
    }

    // ============================================================
    // STATUS & RECEIPT
    // ============================================================

    public function status(string $uuid)
    {
        $registration = Registration::where('uuid', $uuid)->firstOrFail();
        return view('registration.status', compact('registration'));
    }

    public function downloadReceipt(string $uuid)
    {
        $registration = Registration::where('uuid', $uuid)
            ->where('status', 'paid')
            ->firstOrFail();

        if (! $registration->pdf_receipt_path) {
            abort(404, 'Receipt belum tersedia.');
        }

        $path = storage_path('app/' . $registration->pdf_receipt_path);

        if (! file_exists($path)) {
            abort(404, 'File receipt tidak ditemukan.');
        }

        return response()->download(
            $path,
            'receipt-' . $registration->uuid . '.pdf'
        );
    }

    // ============================================================
    // SERVE FOTO KTP (admin only, protected)
    // ============================================================

    public function serveKtp(string $uuid, string $filename)
    {
        abort_unless(auth('web')->check(), 403);

        $path = storage_path("app/private/ktp/{$uuid}/{$filename}");

        if (! file_exists($path)) {
            abort(404, 'File KTP tidak ditemukan.');
        }

        return response()->file($path, [
            'Content-Type'        => mime_content_type($path),
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control'       => 'private, max-age=3600',
        ]);
    }

    // ============================================================
    // UPLOAD PAYMENT PROOF
    // ============================================================

    public function uploadPayment(Request $request, string $uuid)
    {
        $registration = Registration::where('uuid', $uuid)->firstOrFail();

        // Validate that registration is approved and pending payment
        if ($registration->approval_status !== 'approved' || !in_array($registration->status, ['pending', 'failed'])) {
            return back()->withErrors(['error' => 'Pendaftaran tidak dalam status yang memungkinkan upload bukti pembayaran.']);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120' // 5MB max
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diupload.',
            'payment_proof.image'    => 'File harus berupa gambar.',
            'payment_proof.mimes'    => 'Format gambar harus JPG, PNG, atau WebP.',
            'payment_proof.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        // Store the file
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Update registration status
        $registration->update([
            'payment_proof' => $path,
            'status' => 'pending_verification',
        ]);

        // Notify admin WhatsApp that a payment proof has been uploaded.
        app(WhatsAppService::class)->notifyAdminPaymentUploaded($registration);

        return redirect()->route('registration.status', $uuid)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Status pembayaran akan diperbarui setelah diverifikasi admin.');
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    private const VALID_CITY_KEYWORDS = [
        'BALIKPAPAN',
        'KAB. BALIKPAPAN',
        'KABUPATEN BALIKPAPAN',
    ];

    private const MAX_BEREGU_TEAMS = 0;

    private function isCityValid(string $city): bool
    {
        if (empty($city)) return false;

        $city = strtoupper(trim($city));

        foreach (self::VALID_CITY_KEYWORDS as $keyword) {
            if (str_contains($city, $keyword)) {
                return true;
            }
        }

        return false;
    }
    private function getBeregPaidCount(): int
    {
        return Registration::where('kategori', 'beregu')
            ->where('status', 'paid')
            ->count();
    }

    public function beregSlot(): \Illuminate\Http\JsonResponse
    {
        $filled = $this->getBeregPaidCount();
        $sisa   = max(0, self::MAX_BEREGU_TEAMS - $filled);

        return response()->json([
            'max'    => self::MAX_BEREGU_TEAMS,
            'filled' => $filled,
            'sisa'   => $sisa,
            'penuh'  => $sisa === 0,
        ]);
    }
}