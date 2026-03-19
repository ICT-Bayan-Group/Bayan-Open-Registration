<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getTitle(): string|Htmlable
    {
        return 'Admin Login';
    }

    public function getView(): string
    {
        return 'filament.resources.admin-resource.pages.login';
    }
}