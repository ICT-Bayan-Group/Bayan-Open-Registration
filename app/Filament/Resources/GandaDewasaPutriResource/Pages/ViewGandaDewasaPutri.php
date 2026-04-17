<?php

namespace App\Filament\Resources\GandaDewasaPutriResource\Pages;

use App\Filament\Resources\GandaDewasaPutriResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGandaDewasaPutri extends ViewRecord
{
    protected static string $resource = GandaDewasaPutriResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
