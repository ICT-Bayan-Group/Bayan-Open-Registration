<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    // ============================================================
    // MODAL PILIHAN JALUR
    // ============================================================

    public function index()
    {
        return view('registration.register');
    }

    // ============================================================
    // HALAMAN FORM PER KATEGORI
    // ============================================================

    public function showGandaDewasaPutra()
    {
        return view('registration.form-dewasa', [
            'kategori' => 'ganda-dewasa-putra',
            'label'    => 'Ganda Dewasa Putra',
            'harga'    => 150000,
        ]);
    }

    public function showGandaDewasaPutri()
    {
        return view('registration.form-dewasa', [
            'kategori' => 'ganda-dewasa-putri',
            'label'    => 'Ganda Dewasa Putri',
            'harga'    => 150000,
        ]);
    }

    public function showGandaVeteranPutra()
    {
        return view('registration.form-veteran');
    }

    public function showBeregu()
    {
        return view('registration.form-dewasa', [
            'kategori' => 'beregu',
            'label'    => 'Beregu',
            'harga'    => 200000,
        ]);
    }

    // ============================================================
    // STORE — VALIDASI & SIMPAN PENDAFTARAN
    // ============================================================

    public function store(Request $request)
    {
        $kategori = $request->input('kategori');

        // ── Validasi dasar (berlaku untuk semua kategori) ──────────
        $validated = $request->validate([
            'nama'          => 'required|string|max:100',
            'tim_pb'        => 'required|string|max:100',
            'email'         => 'required|email|max:150',
            'no_hp'         => 'required|string|max:20',
            'provinsi'      => 'required|string|max:100',
            'kota'          => 'required|string|max:100',
            'alamat'        => 'required|string|max:500',
            'nama_pelatih'  => 'nullable|string|max:100',
            'no_hp_pelatih' => 'nullable|string|max:20',
            'pemain'        => 'required|array|min:1|max:10',
            'pemain.*'      => 'required|string|max:100',
            'kategori'      => 'required|in:ganda-dewasa-putra,ganda-dewasa-putri,ganda-veteran-putra,beregu',
        ], [
            'nama.required'      => 'Nama ketua tim wajib diisi.',
            'tim_pb.required'    => 'Nama tim / PB wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'no_hp.required'     => 'Nomor HP wajib diisi.',
            'provinsi.required'  => 'Provinsi wajib dipilih.',
            'kota.required'      => 'Kota wajib diisi.',
            'alamat.required'    => 'Alamat lengkap wajib diisi.',
            'pemain.required'    => 'Minimal 1 nama pemain harus diisi.',
            'pemain.min'         => 'Minimal 1 pemain harus didaftarkan.',
            'pemain.max'         => 'Maksimal 10 pemain per tim.',
            'pemain.*.required'  => 'Nama pemain tidak boleh kosong.',
            'kategori.required'  => 'Pilih kategori terlebih dahulu.',
            'kategori.in'        => 'Kategori tidak valid.',
        ]);

        // ── Validasi jumlah pemain per kategori ────────────────────
        $minPemain = match($kategori) {
            'beregu'               => 3,
            default                => 2,
        };

        $pemainDiisi = array_values(
            array_filter($validated['pemain'], fn($p) => !empty(trim($p)))
        );

        if (count($pemainDiisi) < $minPemain) {
            return back()->withInput()->withErrors([
                'pemain' => "Kategori {$kategori} membutuhkan minimal {$minPemain} pemain.",
            ]);
        }
        $validated['pemain'] = $pemainDiisi;

        // ── Validasi khusus Ganda Veteran Putra ────────────────────
        $veteranData = [];
        if ($kategori === 'ganda-veteran-putra') {

            $request->validate([
                'tgl_lahir'      => 'required|array|size:2',
                'tgl_lahir.*'    => 'required|string',
                'usia_valid'     => 'required|array|size:2',
                'usia_valid.*'   => 'required|in:1',
                'usia_hitung'    => 'nullable|array|size:2',
            ], [
                'tgl_lahir.required'   => 'Tanggal lahir kedua pemain wajib ada (scan KTP terlebih dahulu).',
                'tgl_lahir.size'       => 'Scan KTP untuk 2 pemain terlebih dahulu.',
                'usia_valid.required'  => 'Verifikasi usia wajib dilakukan via scan KTP.',
                'usia_valid.size'      => 'Kedua pemain harus di-scan KTP-nya.',
                'usia_valid.*.in'      => 'Kedua pemain harus memenuhi syarat usia veteran (min. 30 tahun per 24 Agustus 2026).',
            ]);

            $veteranData = [
                'tgl_lahir_pemain' => $request->input('tgl_lahir'),
                'usia_pemain'      => $request->input('usia_hitung'),
            ];
        }

        // ── Validasi upload KTP files ───────────────────────────────
        // Veteran pakai OCR (file tetap diupload via input file),
        // Dewasa & Beregu upload manual
        $request->validate([
            'ktp_files'   => 'required|array|min:' . count($pemainDiisi),
            'ktp_files.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'ktp_files.required'   => 'File KTP pemain wajib diupload.',
            'ktp_files.min'        => 'Upload KTP untuk semua pemain yang didaftarkan.',
            'ktp_files.*.required' => 'Semua file KTP wajib diisi.',
            'ktp_files.*.mimes'    => 'File KTP harus berformat JPG, PNG, atau PDF.',
            'ktp_files.*.max'      => 'Ukuran file KTP maksimal 5MB.',
        ]);

        // ── Hitung harga ───────────────────────────────────────────
        $harga = match($kategori) {
            'beregu' => 200000,
            default  => 150000,
        };

        // ── Buat record Registration ───────────────────────────────
        $registration = Registration::create([
            'nama'          => $validated['nama'],
            'tim_pb'        => $validated['tim_pb'],
            'email'         => $validated['email'],
            'no_hp'         => $validated['no_hp'],
            'provinsi'      => $validated['provinsi'],
            'kota'          => $validated['kota'],
            'alamat'        => $validated['alamat'],
            'nama_pelatih'  => $validated['nama_pelatih']  ?? null,
            'no_hp_pelatih' => $validated['no_hp_pelatih'] ?? null,
            'pemain'        => $validated['pemain'],
            'kategori'      => $kategori,
            'harga'         => $harga,
            'status'        => 'pending',
            // Data veteran (null untuk kategori lain)
            'tgl_lahir_pemain' => $veteranData['tgl_lahir_pemain'] ?? null,
            'usia_pemain'      => $veteranData['usia_pemain']      ?? null,
        ]);

        // ── Upload & simpan file KTP ───────────────────────────────
        $ktpPaths = [];
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
        }

        $registration->update(['ktp_files' => $ktpPaths]);

        // ── Proses pembayaran via Midtrans ─────────────────────────
        try {
            $snapToken = $this->midtrans->createSnapToken($registration);
            return view('registration.payment', compact('registration', 'snapToken'));
        } catch (\Exception $e) {
            // Rollback: hapus KTP files & record jika Midtrans gagal
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

        if (!$registration->pdf_receipt_path) {
            abort(404, 'Receipt belum tersedia.');
        }

        $path = storage_path('app/' . $registration->pdf_receipt_path);

        if (!file_exists($path)) {
            abort(404, 'File receipt tidak ditemukan.');
        }

        return response()->download(
            $path,
            'receipt-' . $registration->midtrans_order_id . '.pdf'
        );
    }
}