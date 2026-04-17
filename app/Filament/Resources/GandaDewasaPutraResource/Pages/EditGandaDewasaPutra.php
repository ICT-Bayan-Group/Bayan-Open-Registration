<?php

namespace App\Filament\Resources\GandaDewasaPutraResource\Pages;

use App\Filament\Resources\GandaDewasaPutraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGandaDewasaPutra extends EditRecord
{
    protected static string $resource = GandaDewasaPutraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}