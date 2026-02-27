<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Peserta', Registration::count())
                ->description('Semua kategori')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Total Revenue', 'Rp ' . number_format(
                Registration::where('status','paid')->sum('harga'), 0, ',', '.'
            ))
                ->description('Pembayaran lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Peserta Regu', Registration::where('kategori','regu')->count())
                ->description('Kategori Regu')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Peserta Open', Registration::where('kategori','open')->count())
                ->description('Kategori Open')
                ->descriptionIcon('heroicon-m-user')
                ->color('info'),

            Stat::make('Pending Bayar', Registration::where('status','pending')->count())
                ->description('Menunggu pembayaran')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Sudah Bayar', Registration::where('status','paid')->count())
                ->description('Status paid')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}