<?php

namespace App\Filament\Resources\BeregResource\Pages;

use App\Filament\Resources\BeregResource;
use App\Filament\Resources\BeregResource\Widgets\BeregStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;

class ListBereg extends ListRecords
{
    protected static string $resource = BeregResource::class;

    protected function getHeaderWidgets(): array
    {
        return [BeregStats::class];
    }

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}

class CreateBereg extends CreateRecord
{
    protected static string $resource = BeregResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['kategori'] = 'beregu';
        $data['harga']    = 1000000; // beregu harga tetap 1jt
        return $data;
    }
}

class ViewBereg extends ViewRecord
{
    protected static string $resource = BeregResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}

class EditBereg extends EditRecord
{
    protected static string $resource = BeregResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\ViewAction::make(), Actions\DeleteAction::make()];
    }
}
