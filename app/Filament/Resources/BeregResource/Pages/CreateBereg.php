<?php

namespace App\Filament\Resources\BeregResource\Pages;

use App\Filament\Resources\BeregResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBereg extends CreateRecord
{
    protected static string $resource = BeregResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['kategori'] = 'beregu';
        $data['harga']    = 1000000;
        return $data;
    }
}
