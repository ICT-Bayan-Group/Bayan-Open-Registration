<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading     = 'Revenue per Hari';
    protected static ?string $description = '30 hari terakhir';
    protected static ?int    $sort        = 1;
    public function getColumnSpan(): int | string | array
        {
            return 1;
        }

    protected function getData(): array
    {
        $data = Registration::where('status', 'paid')
            ->where('updated_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(updated_at) as date, SUM(harga) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $values = [];

        for ($i = 29; $i >= 0; $i--) {
            $date     = now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->translatedFormat('d M');
            $values[] = (int) ($data[$date] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label'            => 'Revenue',
                    'data'             => $values,
                    'borderColor'      => '#10B981',
                    'backgroundColor'  => 'rgba(16,185,129,0.07)',
                    'fill'             => true,
                    'tension'          => 0.4,
                    'pointRadius'      => 0,
                    'pointHoverRadius' => 4,
                    'borderWidth'      => 1.5,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend'  => ['display' => false],
                'tooltip' => [
                    'mode'      => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'ticks' => ['maxTicksLimit' => 8, 'maxRotation' => 0],
                    'grid'  => ['display' => false],
                ],
                'y' => [
                    'grid' => ['color' => 'rgba(0,0,0,0.05)'],
                ],
            ],
        ];
    }
}