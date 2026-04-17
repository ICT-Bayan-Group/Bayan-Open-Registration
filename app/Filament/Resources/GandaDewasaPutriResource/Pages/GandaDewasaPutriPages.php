<?php
// FILE: app/Filament/Resources/GandaDewasaPutriResource/Pages/ListGandaDewasaPutri.php

namespace App\Filament\Resources\GandaDewasaPutriResource\Pages;

use App\Filament\Resources\GandaDewasaPutriResource;
use App\Filament\Resources\GandaDewasaPutriResource\Widgets\GandaDewasaPutriStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;

class ListGandaDewasaPutri extends ListRecords
{
    protected static string $resource = GandaDewasaPutriResource::class;

    protected function getHeaderWidgets(): array
    {
        return [GandaDewasaPutriStats::class];
    }

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}

class CreateGandaDewasaPutri extends CreateRecord
{
    protected static string $resource = GandaDewasaPutriResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['kategori'] = 'ganda-dewasa-putri';
        $data['harga']    = $data['harga'] ?? 400000;
        return $data;
    }
}

class ViewGandaDewasaPutri extends ViewRecord
{
    protected static string $resource = GandaDewasaPutriResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}

class EditGandaDewasaPutri extends EditRecord
{
    protected static string $resource = GandaDewasaPutriResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\ViewAction::make(), Actions\DeleteAction::make()];
    }
}
