<?php

namespace App\Filament\Resources\GandaVeteranPutraResource\Pages;

use App\Filament\Resources\GandaVeteranPutraResource;
use App\Filament\Resources\GandaVeteranPutraResource\Widgets\GandaVeteranPutraStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGandaVeteranPutra extends ListRecords
{
    protected static string $resource = GandaVeteranPutraResource::class;

    protected function getHeaderWidgets(): array
    {
        return [GandaVeteranPutraStats::class];
    }

   // protected function getHeaderActions(): array
   // {
      //  return [Actions\CreateAction::make()];
   // }
}
