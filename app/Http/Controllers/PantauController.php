<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PantauController extends Controller
{
    protected string $filePath = 'pantau/visits.json';

    /* ── TRACK HIT (dipanggil dari tiap page) ── */
    public function track(Request $request)
    {
        $request->validate([
            'page'  => 'required|string|max:60',
            'label' => 'nullable|string|max:120',
        ]);

        $page  = $request->input('page');
        $label = $request->input('label', $page);
        $today = Carbon::now('Asia/Makassar')->format('Y-m-d');

        $this->withLock(function ($data) use ($page, $label, $today) {
            if (!isset($data[$page])) {
                $data[$page] = [
                    'label'      => $label,
                    'total'      => 0,
                    'days'       => [],
                    'last_visit' => null,
                ];
            }

            $data[$page]['label']       = $label; // update label kalau berubah
            $data[$page]['total']++;
            $data[$page]['days'][$today] = ($data[$page]['days'][$today] ?? 0) + 1;
            $data[$page]['last_visit']   = Carbon::now('Asia/Makassar')->toDateTimeString();

            return $data;
        });

        return response()->json(['ok' => true]);
    }

    /* ── DASHBOARD (private) ── */
    public function index(Request $request)
    {
        if ($request->query('key') !== config('services.pantau.secret')) {
            abort(404);
        }

        $path = storage_path('app/' . $this->filePath);
        $data = file_exists($path) ? (json_decode(file_get_contents($path), true) ?? []) : [];

        // urutkan dari yang paling banyak diakses
        uasort($data, fn($a, $b) => $b['total'] <=> $a['total']);

        // siapkan 7 hari terakhir buat mini chart
        $last7 = collect(range(6, 0))->map(fn($i) => Carbon::now('Asia/Makassar')->subDays($i)->format('Y-m-d'));

        return view('pantau-view', compact('data', 'last7'));
    }

    /* ── HELPER: read-modify-write dengan file lock (anti race condition) ── */
    protected function withLock(callable $callback): void
    {
        $path = storage_path('app/' . $this->filePath);
        $dir  = dirname($path);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $fp = fopen($path, 'c+');
        flock($fp, LOCK_EX);

        $content = stream_get_contents($fp);
        $data    = $content ? (json_decode($content, true) ?? []) : [];

        $data = $callback($data);

        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}