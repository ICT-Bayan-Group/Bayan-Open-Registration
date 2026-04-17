<?php

namespace App\Filament\Resources\BeregResource\Pages;

use App\Filament\Resources\BeregResource;
use App\Filament\Resources\BeregResource\Widgets\BeregStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBereg extends ListRecords
{
    protected static string $resource = BeregResource::class;

    protected function getHeaderWidgets(): array
    {
        return [BeregStats::class];
    }

   // protected function getHeaderActions(): array
   // {
        //return [Actions\CreateAction::make()];
   // }
}
