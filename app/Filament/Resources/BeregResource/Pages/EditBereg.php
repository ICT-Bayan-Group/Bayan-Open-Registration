<?php

namespace App\Filament\Resources\BeregResource\Pages;

use App\Filament\Resources\BeregResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBereg extends EditRecord
{
    protected static string $resource = BeregResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\ViewAction::make(), Actions\DeleteAction::make()];
    }
}
