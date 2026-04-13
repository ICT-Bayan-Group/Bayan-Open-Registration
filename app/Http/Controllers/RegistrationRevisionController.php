<?php

namespace App\Http\Controllers;

use App\Mail\RegistrationRevisionRequired;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class RegistrationRevisionController extends Controller
{
    /**
     * GET /daftar/revisi/{token}
     * Tampilkan form revisi pre-filled dengan data lama
     */
    public function show(string $token)
    {
        $registration = Registration::where('revision_token', $token)->firstOrFail();

        if (! $registration->isRevisionTokenValid($token)) {
            return view('registration.revision-expired', compact('registration'));
        }

        return view('registration.revisi', compact('registration', 'token'));
    }
    public function previewKtp(string $token, int $index)
        {
            // Cari pendaftaran berdasarkan token revisi
            $registration = Registration::where('revision_token', $token)->firstOrFail();

            // Ambil path file sesuai index pemain
            $files = $registration->ktp_files ?? [];
            $path = $files[$index] ?? null;

            if (!$path || !Storage::disk('private')->exists($path)) {
                abort(404);
            }

            // Kembalikan file sebagai response gambar
            return response()->file(Storage::disk('private')->path($path));
        }

    /**
     * PUT /daftar/revisi/{token}
     * Proses submit form revisi
     */
    public function update(Request $request, string $token)
    {
        $registration = Registration::where('revision_token', $token)->firstOrFail();

        if (! $registration->isRevisionTokenValid($token)) {
            return response()->json([
                'message' => 'Link revisi sudah kadaluarsa. Hubungi panitia untuk mendapatkan link baru.',
            ], 422);
        }

        // ── Validate ──────────────────────────────────────────────
        $request->validate([
            'tim_pb'              => 'required|string|max:100',
            'nama'                => 'required|string|max:100',
            'email'               => 'required|email',
            'no_hp'               => 'required|string|max:20',
            'provinsi'            => 'required|string|max:100',
            'kota'                => 'required|string|max:100',
            'nama_pelatih'        => 'nullable|string|max:100',
            'no_hp_pelatih'       => 'nullable|string|max:20',
            'pemain'              => 'required|array|min:6|max:8',
            'pemain.*'            => 'required|string|max:100',
            'nik'                 => 'required|array|min:6|max:8',
            'nik.*'               => 'required|string|max:20',
            'tgl_lahir'           => 'required|array|min:6|max:8',
            'tgl_lahir.*'         => 'required|string|max:20',
            'kota_ktp'            => 'required|array|min:6|max:8',
            'ktp_files'           => 'nullable|array|max:8',
            'ktp_files.*'         => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // ── Handle KTP file uploads ───────────────────────────────
        $existingFiles = $registration->ktp_files ?? [];
        $newFiles      = [];
        $ktpCityValid  = $registration->ktp_city_valid ?? [];

        if ($request->hasFile('ktp_files')) {
            foreach ($request->file('ktp_files') as $index => $file) {
                if ($file && $file->isValid()) {
                    // Delete old file if exists
                    if (!empty($existingFiles[$index])) {
                        Storage::disk('private')->delete($existingFiles[$index]);
                    }

                    $path = $file->store(
                        'ktp/' . $registration->uuid,
                        'private'
                    );
                    $newFiles[$index] = $path;
                } else {
                    // Keep existing file
                    $newFiles[$index] = $existingFiles[$index] ?? null;
                }
            }
        } else {
            $newFiles = $existingFiles;
        }

        // ── Build city valid array from submitted kota_ktp ────────
        // Re-evaluate city validity based on new kota_ktp values
        $newCityValid = [];
        $kotaKtp = $request->input('kota_ktp', []);
        $pemain  = $request->input('pemain', []);

        foreach ($pemain as $i => $nama) {
            $kota = strtoupper(trim($kotaKtp[$i] ?? ''));
            $valid = str_contains($kota, 'BALIKPAPAN');
            $newCityValid[] = [
                'index'    => $i + 1,
                'nama'     => $nama,
                'city_raw' => $kotaKtp[$i] ?? '',
                'valid'    => $valid,
            ];
        }

        // ── Submit revision ───────────────────────────────────────
        $registration->submitRevision([
            'tim_pb'         => $request->tim_pb,
            'nama'           => $request->nama,
            'email'          => $request->email,
            'no_hp'          => $request->no_hp,
            'provinsi'       => $request->provinsi,
            'kota'           => $request->kota,
            'nama_pelatih'   => $request->nama_pelatih,
            'no_hp_pelatih'  => $request->no_hp_pelatih,
            'pemain'         => $request->pemain,
            'nik'            => $request->nik,
            'tgl_lahir'      => $request->tgl_lahir,
            'kota_ktp'       => $request->kota_ktp,
            'ktp_files'      => array_values($newFiles),
            'ktp_city_valid' => $newCityValid,
        ]);

        return response()->json([
            'message'  => 'Revisi berhasil dikirim! Tim admin akan meninjau kembali pendaftaran Anda.',
            'redirect' => route('registration.revision.success', ['uuid' => $registration->uuid]),
        ]);
    }

    /**
     * GET /daftar/revisi/sukses/{uuid}
     */
    public function success(string $uuid)
    {
        $registration = Registration::where('uuid', $uuid)->firstOrFail();
        return view('registration.revision-success', compact('registration'));
    }
}