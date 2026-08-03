<?php

namespace App\Services;

use Illuminate\Support\Str;

class AkomodasiHelper
{
    /**
     * Slug dibuat otomatis dari nama hotel — supaya kamu TIDAK perlu
     * nambahin field 'slug' manual ke 33 baris hotel di config/akomodasi.php.
     * Selama nama hotel tidak diubah, slug ini stabil (dipakai di URL).
     */
    public static function slug(string $name): string
    {
        return Str::slug($name);
    }

    /**
     * Lengkapi 1 baris data hotel dengan: slug, images (gallery), description, maps_query.
     * Kalau kamu sudah isi manual field 'images' / 'description' / 'maps_query' di config,
     * itu akan dipakai apa adanya (tidak ditimpa).
     */
    public static function enrichHotel(array $hotel): array
    {
        $hotel['slug'] = self::slug($hotel['name']);

        if (empty($hotel['images'])) {
            // Sementara cuma ada 1 foto (image_url). Nanti kalau kamu tambah field
            // 'images' => [...] di config untuk hotel tsb, ini otomatis kepakai.
            $hotel['images'] = !empty($hotel['image_url']) ? [$hotel['image_url']] : [];
        }

        if (empty($hotel['description'])) {
            $tierText = match ($hotel['tier']) {
                'premium'  => 'hotel kelas premium dengan fasilitas lengkap',
                'standard' => 'hotel kelas menengah yang nyaman dan strategis',
                default    => 'hotel dengan harga bersahabat, cocok untuk budget hemat',
            };

            $hotel['description'] = "{$hotel['name']} adalah {$tierText} di Balikpapan, dengan tipe kamar "
                . "{$hotel['room_type']}. Lokasinya mudah dijangkau dan cocok untuk peserta maupun "
                . "keluarga yang datang untuk Bayan Open 2026. Reservasi kamar dilakukan langsung ke pihak "
                . "hotel, bukan melalui panitia turnamen.";
        }

        if (empty($hotel['maps_query'])) {
            $hotel['maps_query'] = $hotel['name'] . ', Balikpapan, Kalimantan Timur';
        }

        if (empty($hotel['venues'])) {
            $hotel['venues'] = self::venueDistances($hotel['name']);
        }

        return $hotel;
    }

    /**
     * Ambil data jarak & estimasi waktu 1 hotel ke 3 venue Bayan Open 2026,
     * dari lookup table 'venues' + 'hotel_venue_distance' di config/akomodasi.php.
     * Kalau nama hotel tidak ada di lookup table, dikembalikan array kosong
     * (tidak error — bagian jarak venue cuma tidak ditampilkan di halaman detail).
     */
    public static function venueDistances(string $hotelName): array
    {
        $venueDefs = config('akomodasi.venues', []);
        $distances = config('akomodasi.hotel_venue_distance', [])[$hotelName] ?? [];

        $result = [];

        foreach ($venueDefs as $key => $def) {
            $entry = $distances[$key] ?? null;

            $result[$key] = [
                'name'         => $def['name'],
                'maps_query'   => $def['maps_query'] ?? $def['name'] . ', Balikpapan',
                'distance_km'  => $entry['km'] ?? null,
                'duration_min' => $entry['menit'] ?? null,
            ];
        }

        return $result;
    }

    /**
     * Lengkapi 1 baris data tour dengan: images (kolase, dipinjam sementara dari foto hotel),
     * dan maps_query. $index dipakai supaya tiap tour dapat set foto pinjaman yang berbeda-beda.
     */
    public static function enrichTour(array $tour, array $allHotels, int $index = 0): array
    {
        if (empty($tour['images'])) {
            $pool = collect($allHotels)
                ->pluck('image_url')
                ->filter()
                ->values();

            $poolCount = max($pool->count(), 1);
            $offset    = ($index * 3) % $poolCount;

            $borrowed = $pool->slice($offset, 3)->values();

            // kalau kepotong di ujung array, sambung dari awal lagi
            if ($borrowed->count() < 3) {
                $borrowed = $borrowed->merge($pool->take(3 - $borrowed->count()))->values();
            }

            $tour['images'] = $borrowed->all();
        }

        if (empty($tour['maps_query'])) {
            $tour['maps_query'] = $tour['title'] . ', Balikpapan, Kalimantan Timur';
        }

        return $tour;
    }

    public static function mapsEmbedUrl(string $query): string
    {
        return 'https://www.google.com/maps?q=' . urlencode($query) . '&output=embed';
    }
}