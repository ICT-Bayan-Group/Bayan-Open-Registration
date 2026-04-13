<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class ProvinceChart extends ChartWidget
{
    protected static ?string $heading = 'Peserta per Provinsi';

    protected function getData(): array
    {
        $data = Registration::query()
            ->selectRaw('provinsi, COUNT(*) as total')
            ->groupBy('provinsi')
            ->orderByDesc('total')
            ->limit(10) // opsional: top 10 provinsi
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Peserta',
                    'data' => $data->pluck('total'),
                ],
            ],
            'labels' => $data->pluck('provinsi'),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // bisa 'pie' juga
    }
}