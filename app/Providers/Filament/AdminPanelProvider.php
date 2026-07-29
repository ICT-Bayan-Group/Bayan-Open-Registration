<?php

namespace App\Providers\Filament;

use App\Filament\Resources\RegistrationResource;
use App\Filament\Resources\GandaDewasaPutraResource;
use App\Filament\Resources\GandaDewasaPutriResource;
use App\Filament\Resources\GandaVeteranPutraResource;
use App\Filament\Resources\BeregResource;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\CategoryChart;
use App\Filament\Widgets\StatusChart;
use App\Filament\Widgets\ProvinceChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\HtmlString;
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->colors([
                'primary' => Color::Orange,
            ])
            ->brandLogo(fn () => new HtmlString('
                <style>
                    .bo-logo-dark { display: none; }
                    html.dark .bo-logo-dark { display: block; }
                    html.dark .bo-logo-light { display: none; }
                </style>
                <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775803080/bayanopen-logo_mfcb55.png"
                    alt="Bayan Open 2026"
                    class="bo-logo-light"
                    style="height: 3.5rem;" />
                <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1776413938/LOGO_BO2026_White_bpz9gb.png"
                    alt="Bayan Open 2026"
                    class="bo-logo-dark"
                    style="height: 3.5rem;" />
            '))
            ->brandLogoHeight('3rem')
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()

            // ── CSS khusus kartu KTP/Paspor/Veteran, di-inject SEKALI ──
            // ── di <head>, di luar tree Livewire supaya gak ganggu ──
            // ── boundary detection komponen (lihat catatan di ──────
            // ── ktp-dark-styles.blade.php).                     ──────
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => view('filament.partials.ktp-dark-styles')->render(),
            )

            ->pages([
                \App\Filament\Pages\Dashboard::class,
                \App\Filament\Pages\IctPanel::class,        // ← tambahkan ini
                  \App\Filament\Pages\RevenueReport::class,   // ← dan ini
            ])

            ->resources([
                // ── Semua peserta (gabungan semua kategori) ──────────────
                //RegistrationResource::class,

                // ── Verifikasi beregu ─────────────────────────────────────
                \App\Filament\Resources\VerifikasiBeregResource::class,

                // ── Per kategori ──────────────────────────────────────────
                GandaDewasaPutraResource::class,
                GandaDewasaPutriResource::class,
                GandaVeteranPutraResource::class,
                BeregResource::class,
            ])

            ->widgets([
                StatsOverview::class,
                RevenueChart::class,
                CategoryChart::class,
                StatusChart::class,
                ProvinceChart::class,
            ])

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}