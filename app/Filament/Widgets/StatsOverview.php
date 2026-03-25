<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    // hapus $columnSpan — tidak perlu untuk StatsOverview

    protected function getStats(): array
    {
        $totalPeserta = Registration::count();
        $totalRevenue = Registration::where('status', 'paid')->sum('harga');
        $sudahBayar   = Registration::where('status', 'paid')->count();
        $regu         = Registration::where('kategori', 'regu')->count();
        $open         = Registration::where('kategori', 'open')->count();
        $pending      = Registration::where('status', 'pending')->count();

        $sparkline = Registration::selectRaw('DATE(created_at) as d, COUNT(*) as n')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('d')->orderBy('d')
            ->pluck('n')->toArray();

        return [
            Stat::make('Total Peserta', $totalPeserta)
                ->description('Semua kategori')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart($sparkline)
                ->color('primary'),

            Stat::make('Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Pembayaran lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Sudah Bayar', $sudahBayar)
                ->description(round($sudahBayar / max($totalPeserta, 1) * 100) . '% dari total peserta')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Peserta Regu', $regu)
                ->description('Kategori beregu (tim)')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Peserta Open', $open)
                ->description('Kategori open (perorangan)')
                ->descriptionIcon('heroicon-m-user')
                ->color('info'),

            Stat::make('Pending Bayar', $pending)
                ->description('Menunggu konfirmasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}