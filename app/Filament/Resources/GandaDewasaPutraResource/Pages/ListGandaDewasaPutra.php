<?php
// ── ListGandaDewasaPutra.php ───────────────────────────────────────────────────
namespace App\Filament\Resources\GandaDewasaPutraResource\Pages;

use App\Filament\Resources\GandaDewasaPutraResource;
use App\Filament\Resources\GandaDewasaPutraResource\Widgets\GandaDewasaPutraStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGandaDewasaPutra extends ListRecords
{
    protected static string $resource = GandaDewasaPutraResource::class;

    protected function getHeaderWidgets(): array
    {
        return [GandaDewasaPutraStats::class];
    }

   // protected function getHeaderActions(): array
   // {
      //  return [Actions\CreateAction::make()];
   // }
}