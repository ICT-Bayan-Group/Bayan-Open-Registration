<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class CategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Breakdown Kategori';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'data' => [
                        Registration::where('kategori','regu')->count(),
                        Registration::where('kategori','open')->count(),
                    ],
                    'backgroundColor' => ['#6366F1', '#10B981'],
                    'borderWidth'     => 0,
                ],
            ],
            'labels' => ['Regu', 'Open'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}