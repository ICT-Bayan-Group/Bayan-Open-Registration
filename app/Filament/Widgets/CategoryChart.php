<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class CategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Kategori Peserta';
    protected static ?int    $sort    = 2;
    public function getColumnSpan(): int | string | array
        {
            return 1;
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
                    'data'            => [$regu, $open],
                    'backgroundColor' => ['#6366F1', '#10B981'],
                    'borderWidth'     => 0,
                    'hoverOffset'     => 6,
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
            'cutout'  => '72%',
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels'   => [
                        'usePointStyle' => true,
                        'pointStyle'    => 'rectRounded',
                        'padding'       => 16,
                        'font'          => ['size' => 12],
                    ],
                ],
            ],
        ];
    }
}