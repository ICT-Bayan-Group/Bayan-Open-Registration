<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme('https');
        }

        \Livewire\Livewire::component('editable-ktp-detail', \App\Livewire\EditableKtpDetail::class);
         \Livewire\Livewire::component('editable-registration-ktp', \App\Livewire\EditableRegistrationKtp::class);
    }
}