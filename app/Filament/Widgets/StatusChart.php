<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class StatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Pembayaran';
    protected static ?int    $sort    = 3;
    public function getColumnSpan(): int | string | array
        {
            return 1;
        }

    protected function getData(): array
    {
        $paid    = Registration::where('status', 'paid')->count();
        $pending = Registration::where('status', 'pending')->count();
        $total   = max($paid + $pending, 1);

        return [
            'datasets' => [
                [
                    'data'            => [$paid, $pending],
                    'backgroundColor' => ['#10B981', '#F59E0B'],
                    'borderWidth'     => 0,
                    'hoverOffset'     => 6,
                ],
            ],
            'labels' => [
                'Paid ('    . round($paid    / $total * 100) . '%)',
                'Pending (' . round($pending / $total * 100) . '%)',
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
            'cutout'  => '68%',
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels'   => [
                        'usePointStyle' => true,
                        'pointStyle'    => 'rectRounded',
                        'padding'       => 14,
                        'font'          => ['size' => 12],
                    ],
                ],
            ],
        ];
    }
}