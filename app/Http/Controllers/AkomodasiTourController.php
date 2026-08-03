<?php

namespace App\Http\Controllers;

use App\Services\AkomodasiHelper;
use Illuminate\Support\Str;

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
            ->map(fn ($h) => AkomodasiHelper::enrichHotel($h))
            ->sortByDesc('is_official')
            ->values()
            ->all();

        $tours = collect($data['tours'])
            ->map(fn ($t, $i) => AkomodasiHelper::enrichTour($t, $data['hotels'], $i))
            ->values()
            ->all();

        return view('akomodasi-tour', [
            'hotels'    => $hotels,
            'tours'     => $tours,
            'updatedAt' => $data['updated_at'],
        ]);
    }

    /**
     * Halaman detail 1 hotel: /akomodasi-tour/hotel/{slug}
     */
    public function hotelDetail(string $slug)
    {
        $data = config('akomodasi');

        $hotel = collect($data['hotels'])->first(
            fn ($h) => Str::slug($h['name']) === $slug
        );

        abort_unless($hotel, 404);

        $hotel = AkomodasiHelper::enrichHotel($hotel);

        // Rekomendasi hotel lain: prioritaskan tier yang sama, lalu isi sisanya dari hotel lain
        $sameTier = collect($data['hotels'])
            ->reject(fn ($h) => Str::slug($h['name']) === $slug)
            ->filter(fn ($h) => $h['tier'] === $hotel['tier'])
            ->take(4);

        if ($sameTier->count() < 4) {
            $filler = collect($data['hotels'])
                ->reject(fn ($h) => Str::slug($h['name']) === $slug)
                ->reject(fn ($h) => $sameTier->contains(fn ($s) => $s['name'] === $h['name']))
                ->take(4 - $sameTier->count());

            $sameTier = $sameTier->merge($filler);
        }

        $others = $sameTier
            ->map(fn ($h) => AkomodasiHelper::enrichHotel($h))
            ->values();

        return view('akomodasi-tour-hotel-detail', [
            'hotel'  => $hotel,
            'others' => $others,
        ]);
    }

    /**
     * Halaman detail 1 city tour: /akomodasi-tour/tour/{slug}
     */
    public function tourDetail(string $slug)
    {
        $data = config('akomodasi');

        $index = collect($data['tours'])->search(fn ($t) => $t['slug'] === $slug);

        abort_if($index === false, 404);

        $tour = AkomodasiHelper::enrichTour($data['tours'][$index], $data['hotels'], $index);

        $others = collect($data['tours'])
            ->reject(fn ($t) => $t['slug'] === $slug)
            ->map(fn ($t) => AkomodasiHelper::enrichTour(
                $t,
                $data['hotels'],
                collect($data['tours'])->search(fn ($x) => $x['slug'] === $t['slug'])
            ))
            ->values();

        return view('akomodasi-tour-tour-detail', [
            'tour'   => $tour,
            'others' => $others,
        ]);
    }
}