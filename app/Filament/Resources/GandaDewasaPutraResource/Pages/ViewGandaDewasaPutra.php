<?php

namespace App\Filament\Resources\GandaDewasaPutraResource\Pages;

use App\Filament\Resources\GandaDewasaPutraResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGandaDewasaPutra extends ViewRecord
{
    protected static string $resource = GandaDewasaPutraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}