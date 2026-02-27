<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    public function create()
    {
        return view('registration.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Data Tim & Kontak
            'nama'           => 'required|string|max:100',
            'tim_pb'         => 'required|string|max:100',
            'email'          => 'required|email|max:150',
            'no_hp'          => 'required|string|max:20',
            'provinsi'       => 'required|string|max:100',
            'kota'           => 'required|string|max:100',
            'alamat'         => 'required|string|max:500',
            // Data Pelatih (opsional)
            'nama_pelatih'   => 'nullable|string|max:100',
            'no_hp_pelatih'  => 'nullable|string|max:20',
            // Data Pemain
            'pemain'         => 'required|array|min:1|max:10',
            'pemain.*'       => 'required|string|max:100',
            // Kategori
            'kategori'       => 'required|in:regu,open',
        ], [
            'nama.required'         => 'Nama ketua tim wajib diisi.',
            'tim_pb.required'       => 'Nama tim / PB wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'no_hp.required'        => 'Nomor HP wajib diisi.',
            'provinsi.required'     => 'Provinsi wajib dipilih.',
            'kota.required'         => 'Kota wajib diisi.',
            'alamat.required'       => 'Alamat lengkap wajib diisi.',
            'pemain.required'       => 'Minimal 1 nama pemain harus diisi.',
            'pemain.min'            => 'Minimal 1 pemain harus didaftarkan.',
            'pemain.max'            => 'Maksimal 10 pemain per tim.',
            'pemain.*.required'     => 'Nama pemain tidak boleh kosong.',
            'kategori.required'     => 'Pilih kategori terlebih dahulu.',
        ]);

        // Hitung harga otomatis
        $harga = $validated['kategori'] === 'regu' ? 200000 : 150000;

        // Filter pemain kosong
        $validated['pemain'] = array_values(array_filter($validated['pemain'], fn($p) => !empty(trim($p))));

        $registration = Registration::create([
            ...$validated,
            'harga'  => $harga,
            'status' => 'pending',
        ]);

        try {
            $snapToken = $this->midtrans->createSnapToken($registration);
            return view('registration.payment', compact('registration', 'snapToken'));
        } catch (\Exception $e) {
            $registration->delete();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal terhubung ke payment gateway. Silakan coba lagi.']);
        }
    }

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