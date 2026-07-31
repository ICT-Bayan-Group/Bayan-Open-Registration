<?php

namespace App\Http\Controllers;

class AkomodasiTourController extends Controller
{
    /**
     * Halaman penuh /akomodasi-tour.
     * Data hotel & tour full frontend — tanpa query database (lihat PRD §7.3 & §11).
     */
    public function index()
    {
        $data = config('akomodasi');

        // Official hotel selalu tampil pertama, sisanya ikut urutan aslinya
        $hotels = collect($data['hotels'])
            ->sortByDesc('is_official')
            ->values()
            ->all();

        return view('akomodasi-tour', [
            'hotels'     => $hotels,
            'tours'      => $data['tours'],
            'updatedAt'  => $data['updated_at'],
        ]);
    }
}