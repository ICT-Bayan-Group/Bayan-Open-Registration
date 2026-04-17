<?php

namespace App\Filament\Resources\RegistrationResource\Widgets;

use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegistrationStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        // ── Global ───────────────────────────────────────────────
        $totalAll   = Registration::count();
        $totalPaid  = Registration::where('status', 'paid')->count();
        $totalPending = Registration::where('status', 'pending')->count();

        // ── Per Kategori ──────────────────────────────────────────
        $categories = [
            'ganda-dewasa-putra'  => 'Ganda Dewasa Putra',
            'ganda-dewasa-putri'  => 'Ganda Dewasa Putri',
            'ganda-veteran-putra' => 'Ganda Veteran Putra',
            'beregu'              => 'Beregu',
        ];

        $stats = [
        ];

        // ── Stat per kategori ─────────────────────────────────────
        foreach ($categories as $slug => $label) {
            $total   = Registration::where('kategori', $slug)->count();
            $paid    = Registration::where('kategori', $slug)->where('status', 'paid')->count();
            $pending = Registration::where('kategori', $slug)->where('status', 'pending')->count();
            $expired = Registration::where('kategori', $slug)->whereIn('status', ['failed', 'expired'])->count();

            $paidPercent = $total > 0 ? round(($paid / $total) * 100) : 0;

            $color = match ($slug) {
                'ganda-dewasa-putra'  => 'primary',
                'ganda-dewasa-putri'  => 'info',
                'ganda-veteran-putra' => 'warning',
                'beregu'              => 'success',
                default               => 'gray',
            };

            $icon = match ($slug) {
                'beregu'              => 'heroicon-m-user-group',
                'ganda-veteran-putra' => 'heroicon-m-star',
                default               => 'heroicon-m-user',
            };

            $stats[] = Stat::make($label, $total . ' tim')
                ->description("✅ {$paid} paid · ⏳ {$pending} pending · ❌ {$expired} gagal · {$paidPercent}% lunas")
                ->descriptionIcon($icon)
                ->color($color);
        }

        return $stats;
    }
}