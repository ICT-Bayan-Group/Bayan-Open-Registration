<?php

namespace App\Providers\Filament;

use App\Filament\Resources\RegistrationResource;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\CategoryChart;
use App\Filament\Widgets\StatusChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Orange,
            ])
            ->brandLogo('https://res.cloudinary.com/djs5pi7ev/image/upload/v1773109896/LOGO_BO2026_pzbvxh.png')
            ->brandLogoHeight('3rem')
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()

            ->pages([
                \Filament\Pages\Dashboard::class,
            ])

            ->resources([
                RegistrationResource::class,
            ])

            ->widgets([
                StatsOverview::class,
                RevenueChart::class,
                CategoryChart::class,
                StatusChart::class,
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