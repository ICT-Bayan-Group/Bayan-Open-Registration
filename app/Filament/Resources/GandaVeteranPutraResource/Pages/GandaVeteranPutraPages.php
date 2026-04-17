<?php

namespace App\Filament\Resources\GandaVeteranPutraResource\Pages;

use App\Filament\Resources\GandaVeteranPutraResource;
use App\Filament\Resources\GandaVeteranPutraResource\Widgets\GandaVeteranPutraStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;

class ListGandaVeteranPutra extends ListRecords
{
    protected static string $resource = GandaVeteranPutraResource::class;

    protected function getHeaderWidgets(): array
    {
        return [GandaVeteranPutraStats::class];
    }

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}

class CreateGandaVeteranPutra extends CreateRecord
{
    protected static string $resource = GandaVeteranPutraResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['kategori'] = 'ganda-veteran-putra';
        $data['harga']    = $data['harga'] ?? 400000;
        return $data;
    }
}

class ViewGandaVeteranPutra extends ViewRecord
{
    protected static string $resource = GandaVeteranPutraResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}

class EditGandaVeteranPutra extends EditRecord
{
    protected static string $resource = GandaVeteranPutraResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\ViewAction::make(), Actions\DeleteAction::make()];
    }
}
