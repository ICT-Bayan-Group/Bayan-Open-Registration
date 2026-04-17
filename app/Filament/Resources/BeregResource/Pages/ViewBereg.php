<?php

namespace App\Filament\Resources\BeregResource\Pages;

use App\Filament\Resources\BeregResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBereg extends ViewRecord
{
    protected static string $resource = BeregResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
