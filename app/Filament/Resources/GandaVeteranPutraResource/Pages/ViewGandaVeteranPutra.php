<?php

namespace App\Filament\Resources\GandaVeteranPutraResource\Pages;

use App\Filament\Resources\GandaVeteranPutraResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGandaVeteranPutra extends ViewRecord
{
    protected static string $resource = GandaVeteranPutraResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
