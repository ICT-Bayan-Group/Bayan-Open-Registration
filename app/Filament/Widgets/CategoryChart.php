<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class CategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Kategori Peserta';
    protected static ?int $sort = 2;

    public function getColumnSpan(): int | string | array
    {
        return [
            'default' => 2,
            'md' => 1,
            'lg' => 1,
        ];
    }

    protected function getMaxHeight(): string
    {
        return '220px'; // 🔥 kecilin tinggi
    }

    protected function getData(): array
    {
        $regu = Registration::where('kategori', 'beregu')->count();
        $open = Registration::whereIn('kategori', [
            'ganda-dewasa-putra',
            'ganda-dewasa-putri',
            'ganda-veteran-putra',
        ])->count();

        $total = max($regu + $open, 1);

        return [
            'datasets' => [
                [
                    'data' => [$regu, $open],
                    'backgroundColor' => ['#6366F1', '#10B981'],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => [
                'Beregu (' . round($regu / $total * 100) . '%)',
                'Open/Ganda (' . round($open / $total * 100) . '%)',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false, // 🔥 WAJIB
            'cutout' => '75%', // donut lebih kecil
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 12,
                        'font' => ['size' => 11], // 🔥 kecilin font
                    ],
                ],
            ],
        ];
    }
}