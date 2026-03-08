<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WilayahController extends Controller
{
    private string $base = 'https://emsifa.github.io/api-wilayah-indonesia/api';

    public function provinces()
    {
        $data = Cache::remember('wilayah:provinces', 86400, function () {
            return Http::timeout(15)->get("{$this->base}/provinces.json")->json();
        });
        return response()->json($data);
    }

    public function regencies(string $id)
    {
        $id   = preg_replace('/[^0-9]/', '', $id);
        $data = Cache::remember("wilayah:regencies:{$id}", 86400, function () use ($id) {
            return Http::timeout(15)->get("{$this->base}/regencies/{$id}.json")->json();
        });
        return response()->json($data);
    }
}