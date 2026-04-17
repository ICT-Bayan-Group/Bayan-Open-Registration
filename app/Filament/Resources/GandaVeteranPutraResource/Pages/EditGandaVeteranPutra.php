<?php

namespace App\Filament\Resources\GandaVeteranPutraResource\Pages;

use App\Filament\Resources\GandaVeteranPutraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGandaVeteranPutra extends EditRecord
{
    protected static string $resource = GandaVeteranPutraResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\ViewAction::make(), Actions\DeleteAction::make()];
    }
}
