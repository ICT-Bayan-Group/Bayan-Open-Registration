<?php

namespace App\Filament\Concerns;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

trait RequiresAdminPassword
{
    private static string $PASSWORD_HASH = '$2y$12$31Y9w.yl1C/h/tWdRC.8GuE0hUAvsy3pZPBEUOAHEodSpi4tYw6.6';

    protected static function verifyAdminPassword(string $input): bool
    {
        return Hash::check($input, self::$PASSWORD_HASH);
    }

    protected static function passwordForm(): array
    {
        return [
            \Filament\Forms\Components\TextInput::make('action_password')
                ->label('🔐 Password Admin')
                ->password()
                ->required()
                ->autocomplete('off')
                ->helperText('Masukkan password untuk melanjutkan aksi ini'),
        ];
    }

    protected static function abortIfWrongPassword(array $data): bool
    {
        if (! self::verifyAdminPassword($data['action_password'] ?? '')) {
            Notification::make()
                ->title('Password salah')
                ->body('Anda tidak memiliki akses untuk melakukan aksi ini.')
                ->danger()
                ->send();
            return false;
        }
        return true;
    }
}