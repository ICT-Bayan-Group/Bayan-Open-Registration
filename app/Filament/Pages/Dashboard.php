<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CategoryChart;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\StatusChart;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getColumns(): int | array
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            CategoryChart::class,
            StatusChart::class,
            RevenueChart::class,
        ];
    }
}