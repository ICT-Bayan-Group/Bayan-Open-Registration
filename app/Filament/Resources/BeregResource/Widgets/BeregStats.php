<?php
// FILE: app/Filament/Resources/BeregResource/Widgets/BeregStats.php

namespace App\Filament\Resources\BeregResource\Widgets;

use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BeregStats extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $slug    = 'beregu';
        $total   = Registration::where('kategori', $slug)->count();
        $paid    = Registration::where('kategori', $slug)->where('status', 'paid')->count();
        $pending = Registration::where('kategori', $slug)->where('status', 'pending')->count();
        $failed  = Registration::where('kategori', $slug)->whereIn('status', ['failed', 'expired'])->count();
        $revenue = Registration::where('kategori', $slug)->where('status', 'paid')->sum('harga');
        $paidPercent = $total > 0 ? round(($paid / $total) * 100) : 0;

        // Hitung total pemain (beregu bisa lebih dari 2 pemain)
        $totalPemain = Registration::where('kategori', $slug)
            ->where('status', 'paid')
            ->get()
            ->sum(fn ($r) => count($r->pemain ?? []));

        return [
            Stat::make('Total Tim', $total . ' tim')
                ->description('Beregu')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart(
                    Registration::where('kategori', $slug)
                        ->selectRaw('COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->groupByRaw('DATE(created_at)')
                        ->orderByRaw('DATE(created_at)')
                        ->pluck('count')->toArray()
                ),
            Stat::make('Sudah Bayar', $paid . ' tim')
                ->description("{$paidPercent}% dari total tim")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Menunggu Bayar', $pending . ' tim')
                ->description("{$failed} gagal / expired")
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Pemasukan', 'Rp ' . number_format($revenue, 0, ',', '.'))
                ->description('Dari ' . $paid . ' tim · ' . $totalPemain . ' pemain terdaftar')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
