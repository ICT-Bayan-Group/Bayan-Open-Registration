<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class StatusChart extends ChartWidget
{
    protected static ?string $heading = 'Breakdown Status';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'data' => [
                        Registration::where('status','paid')->count(),
                        Registration::where('status','pending')->count(),
                        Registration::where('status','failed')->count(),
                        Registration::where('status','expired')->count(),
                    ],
                    'backgroundColor' => ['#10B981','#F59E0B','#EF4444','#6B7280'],
                    'borderWidth'     => 0,
                ],
            ],
            'labels' => ['Paid','Pending','Failed','Expired'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}