<?php

namespace App\Filament\Resources\GandaVeteranPutraResource\Pages;

use App\Filament\Resources\GandaVeteranPutraResource;
use Filament\Resources\Pages\CreateRecord;

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
