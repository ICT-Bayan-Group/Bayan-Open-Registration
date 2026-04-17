<?php
// FILE: app/Filament/Resources/GandaVeteranPutraResource/Widgets/GandaVeteranPutraStats.php

namespace App\Filament\Resources\GandaVeteranPutraResource\Widgets;

use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GandaVeteranPutraStats extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $slug    = 'ganda-veteran-putra';
        $total   = Registration::where('kategori', $slug)->count();
        $paid    = Registration::where('kategori', $slug)->where('status', 'paid')->count();
        $pending = Registration::where('kategori', $slug)->where('status', 'pending')->count();
        $failed  = Registration::where('kategori', $slug)->whereIn('status', ['failed', 'expired'])->count();
        $revenue = Registration::where('kategori', $slug)->where('status', 'paid')->sum('harga');
        $paidPercent = $total > 0 ? round(($paid / $total) * 100) : 0;

        // Hitung yang lolos/tidak lolos syarat veteran
        $lolos = Registration::where('kategori', $slug)
            ->whereNotNull('usia_pemain')
            ->whereRaw("(
                JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '\$[0]')) + 0 >= 45
                AND JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '\$[1]')) + 0 >= 45
                AND (JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '\$[0]')) + JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '\$[1]'))) >= 95
            )")
            ->count();

        $tidakLolos = Registration::where('kategori', $slug)
            ->whereNotNull('usia_pemain')
            ->whereRaw("(
                JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '\$[0]')) + 0 < 45
                OR JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '\$[1]')) + 0 < 45
                OR (JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '\$[0]')) + JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '\$[1]'))) < 95
            )")
            ->count();

        $belumScan = $total - $lolos - $tidakLolos;

        return [
            Stat::make('Total Peserta', $total . ' tim')
                ->description('Ganda Veteran Putra')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->chart(
                    Registration::where('kategori', $slug)
                        ->selectRaw('COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->groupByRaw('DATE(created_at)')
                        ->orderByRaw('DATE(created_at)')
                        ->pluck('count')->toArray()
                ),
            Stat::make('Sudah Bayar', $paid . ' tim')
                ->description("{$paidPercent}% dari total peserta")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Lolos Syarat Veteran', $lolos . ' tim')
                ->description("{$tidakLolos} tidak lolos · {$belumScan} belum scan KTP")
                ->descriptionIcon('heroicon-m-shield-check')
                ->color($tidakLolos > 0 ? 'danger' : 'success'),
            Stat::make('Pemasukan', 'Rp ' . number_format($revenue, 0, ',', '.'))
                ->description('Dari ' . $paid . ' tim terbayar')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
