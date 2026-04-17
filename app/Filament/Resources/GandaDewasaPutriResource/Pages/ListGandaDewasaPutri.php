<?php

namespace App\Filament\Resources\GandaDewasaPutriResource\Pages;

use App\Filament\Resources\GandaDewasaPutriResource;
use App\Filament\Resources\GandaDewasaPutriResource\Widgets\GandaDewasaPutriStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGandaDewasaPutri extends ListRecords
{
    protected static string $resource = GandaDewasaPutriResource::class;

    protected function getHeaderWidgets(): array
    {
        return [GandaDewasaPutriStats::class];
    }

    protected function getHeaderActions(): array
    {
       // return [Actions\CreateAction::make()];
    }
}
