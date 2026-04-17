<?php

namespace App\Filament\Resources\GandaDewasaPutriResource\Pages;

use App\Filament\Resources\GandaDewasaPutriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGandaDewasaPutri extends EditRecord
{
    protected static string $resource = GandaDewasaPutriResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\ViewAction::make(), Actions\DeleteAction::make()];
    }
}
