<?php

namespace App\Filament\Resources\GandaDewasaPutraResource\Widgets;

use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GandaDewasaPutraStats extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $slug  = 'ganda-dewasa-putra';

        $total   = Registration::where('kategori', $slug)->count();
        $paid    = Registration::where('kategori', $slug)->where('status', 'paid')->count();
        $pending = Registration::where('kategori', $slug)->where('status', 'pending')->count();
        $failed  = Registration::where('kategori', $slug)->whereIn('status', ['failed', 'expired'])->count();
        $revenue = Registration::where('kategori', $slug)->where('status', 'paid')->sum('harga');

        $paidPercent = $total > 0 ? round(($paid / $total) * 100) : 0;

        return [
            Stat::make('Total Peserta', $total . ' tim')
                ->description('Ganda Dewasa Putra')
                ->descriptionIcon('heroicon-m-user')
                ->color('primary')
                ->chart(
                    Registration::where('kategori', $slug)
                        ->selectRaw('COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->groupByRaw('DATE(created_at)')
                        ->orderByRaw('DATE(created_at)')
                        ->pluck('count')
                        ->toArray()
                ),

            Stat::make('Sudah Bayar', $paid . ' tim')
                ->description("{$paidPercent}% dari total peserta")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Menunggu Bayar', $pending . ' tim')
                ->description("{$failed} gagal / expired")
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Pemasukan', 'Rp ' . number_format($revenue, 0, ',', '.'))
                ->description('Dari ' . $paid . ' tim terbayar')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
