<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    // ============================================================
    // HALAMAN PILIHAN KATEGORI
    // ============================================================

    public function index()
    {
        return view('registration.register');
    }

    // ============================================================
    // FORM PER KATEGORI
    // ============================================================

    public function showGandaDewasaPutra()
    {
        return view('registration.form-dewasa', [
            'kategori'  => 'ganda-dewasa-putra',
            'label'     => 'Ganda Dewasa Putra',
            'harga'     => 150000,
            'minPemain' => 2,
            'maxPemain' => 2,
        ]);
    }

    public function showGandaDewasaPutri()
    {
        return view('registration.form-dewasa', [
            'kategori'  => 'ganda-dewasa-putri',
            'label'     => 'Ganda Dewasa Putri',
            'harga'     => 150000,
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
        return view('registration.form-dewasa', [
            'kategori'  => 'beregu',
            'label'     => 'Beregu',
            'harga'     => 200000,
            'minPemain' => 3,
            'maxPemain' => 10,
        ]);
    }

    // ============================================================
    // STORE
    // ============================================================

    public function store(Request $request)
    {
        $kategori = $request->input('kategori');

        // ── 1. Validasi dasar ──────────────────────────────────────
        $validated = $request->validate([
            'nama'          => 'required|string|max:100',
            'tim_pb'        => 'required|string|max:100',
            'email'         => 'required|email|max:150',
            'no_hp'         => 'required|string|max:20',
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
            'provinsi.required' => 'Provinsi wajib dipilih.',
            'kota.required'     => 'Kota wajib diisi.',
            'pemain.required'   => 'Minimal 1 nama pemain harus diisi.',
            'pemain.min'        => 'Minimal 1 pemain harus didaftarkan.',
            'pemain.max'        => 'Maksimal 10 pemain per tim.',
            'pemain.*.required' => 'Nama pemain tidak boleh kosong.',
            'kategori.required' => 'Pilih kategori terlebih dahulu.',
            'kategori.in'       => 'Kategori tidak valid.',
        ]);

        // ── 2. Validasi jumlah pemain per kategori ─────────────────
        $minPemain = match ($kategori) {
            'beregu' => 3,
            default  => 2,
        };

        $pemainDiisi = array_values(
            array_filter($validated['pemain'], fn ($p) => ! empty(trim($p)))
        );

        if (count($pemainDiisi) < $minPemain) {
            return back()->withInput()->withErrors([
                'pemain' => "Kategori {$kategori} membutuhkan minimal {$minPemain} pemain.",
            ]);
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

        // ── Hitung usia otomatis dari tgl_lahir ───────────────────
        $usiaArr = array_map(function ($tgl) {
            if (! $tgl) return null;
            try {
                $tgl   = str_replace('/', '-', trim($tgl));
                $parts = explode('-', $tgl);
                if (count($parts) === 3 && strlen($parts[0]) <= 2 && strlen($parts[2]) === 4) {
                    $birth = Carbon::createFromFormat('d-m-Y', $tgl);
                } else {
                    $birth = Carbon::parse($tgl);
                }
                return $birth->age;
            } catch (\Exception $e) {
                return null;
            }
        }, $tglLahirArr);

        // ── 4. Validasi upload KTP files ───────────────────────────
        $request->validate([
            'ktp_files'   => 'required|array|min:' . $jumlahPemain,
            'ktp_files.*' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'ktp_files.required'   => 'File KTP pemain wajib diupload.',
            'ktp_files.min'        => 'Upload KTP untuk semua pemain yang didaftarkan.',
            'ktp_files.*.required' => 'Semua file KTP wajib diisi.',
            'ktp_files.*.mimes'    => 'File KTP harus berformat JPG, PNG, atau WebP.',
            'ktp_files.*.max'      => 'Ukuran file KTP maksimal 5MB.',
        ]);

        // ── 5. Validasi khusus Ganda Veteran Putra ─────────────────
        // Syarat: masing-masing ≥ 45 tahun DAN total ≥ 95 tahun
        $veteranData = [];
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
                'usia_hitung.*.min'    => 'Setiap pemain minimal berusia 45 tahun per 24 Agustus 2026.',
            ]);

            // Double-check total usia di backend
            $usiaHitung = $request->input('usia_hitung', []);
            $u0         = (int) ($usiaHitung[0] ?? 0);
            $u1         = (int) ($usiaHitung[1] ?? 0);
            $totalUsia  = $u0 + $u1;

            // Cek per pemain (backend, tidak percaya frontend saja)
            if ($u0 < 45) {
                return back()->withInput()->withErrors([
                    'usia_hitung' => "Pemain 1 berusia {$u0} tahun, tidak memenuhi syarat veteran (min. 45 tahun).",
                ]);
            }
            if ($u1 < 45) {
                return back()->withInput()->withErrors([
                    'usia_hitung' => "Pemain 2 berusia {$u1} tahun, tidak memenuhi syarat veteran (min. 45 tahun).",
                ]);
            }

            // Cek total usia
            if ($totalUsia < 95) {
                return back()->withInput()->withErrors([
                    'usia_hitung' => "Total usia kedua pemain hanya {$totalUsia} tahun "
                                  . "({$u0} + {$u1}). Minimal total usia adalah 95 tahun.",
                ]);
            }

            $veteranData = [
                'tgl_lahir_pemain' => $tglLahirArr,
                'total_usia'       => $totalUsia,
            ];
        }

        // ── 6. Hitung harga ────────────────────────────────────────
        $harga = match ($kategori) {
            'beregu' => 200000,
            default  => 150000,
        };

        // ── 7. Buat record Registration ────────────────────────────
        $registration = Registration::create([
            'nama'          => $validated['nama'],
            'tim_pb'        => $validated['tim_pb'],
            'email'         => $validated['email'],
            'no_hp'         => $validated['no_hp'],
            'provinsi'      => $validated['provinsi'],
            'kota'          => $validated['kota'],
            'nama_pelatih'  => $validated['nama_pelatih']  ?? null,
            'no_hp_pelatih' => $validated['no_hp_pelatih'] ?? null,
            'pemain'        => $pemainDiisi,

            // Data KTP
            'nik'         => $nikArr,
            'tgl_lahir'   => $tglLahirArr,
            'usia_pemain' => $usiaArr,   // dihitung otomatis backend

            // Kategori & payment
            'kategori' => $kategori,
            'harga'    => $harga,
            'status'   => 'pending',

            // Khusus veteran
            'tgl_lahir_pemain' => $veteranData['tgl_lahir_pemain'] ?? null,
        ]);

        // ── 8. Upload file KTP ─────────────────────────────────────
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
                'index'     => $i + 1,
                'nama'      => $pemainDiisi[$i] ?? null,
                'nik'       => $nikArr[$i]       ?? null,
                'tgl_lahir' => $tglLahirArr[$i]  ?? null,
                'usia'      => $usiaArr[$i]       ?? null,
                'file_path' => $path,
                'file_name' => $namaFile,
            ];
        }

        $registration->update([
            'ktp_files' => $ktpPaths,
            'ktp_data'  => $ktpRawData,
        ]);

        // ── 9. Midtrans ────────────────────────────────────────────
        try {
            $snapToken = $this->midtrans->createSnapToken($registration);
            return view('registration.payment', compact('registration', 'snapToken'));
        } catch (\Exception $e) {
            foreach ($ktpPaths as $path) {
                Storage::disk('private')->delete($path);
            }
            Storage::disk('private')->deleteDirectory("ktp/{$registration->uuid}");
            $registration->delete();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal terhubung ke payment gateway. Silakan coba lagi.']);
        }
    }

    // ============================================================
    // CALLBACK & STATUS
    // ============================================================

    public function success(Request $request)
    {
        $orderId      = $request->query('order_id');
        $registration = Registration::where('midtrans_order_id', $orderId)->first();
        return view('registration.success', compact('registration'));
    }

    public function pending(Request $request)
    {
        $orderId      = $request->query('order_id');
        $registration = Registration::where('midtrans_order_id', $orderId)->first();
        return view('registration.pending', compact('registration'));
    }

    public function error()
    {
        return view('registration.error');
    }

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

        if (! $registration->pdf_receipt_path) abort(404, 'Receipt belum tersedia.');

        $path = storage_path('app/' . $registration->pdf_receipt_path);
        if (! file_exists($path)) abort(404, 'File receipt tidak ditemukan.');

        return response()->download(
            $path,
            'receipt-' . $registration->midtrans_order_id . '.pdf'
        );
    }

    public function serveKtp(string $uuid, string $filename)
    {
        abort_unless(auth('web')->check(), 403);

        $path = storage_path("app/private/ktp/{$uuid}/{$filename}");
        if (! file_exists($path)) abort(404, 'File KTP tidak ditemukan.');

        return response()->file($path, [
            'Content-Type'        => mime_content_type($path),
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control'       => 'private, max-age=3600',
        ]);
    }
}