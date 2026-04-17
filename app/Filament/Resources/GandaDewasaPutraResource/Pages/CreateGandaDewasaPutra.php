<?php

namespace App\Filament\Resources\GandaDewasaPutraResource\Pages;

use App\Filament\Resources\GandaDewasaPutraResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGandaDewasaPutra extends CreateRecord
{
    protected static string $resource = GandaDewasaPutraResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['kategori'] = 'ganda-dewasa-putra';
        $data['harga']    = $data['harga'] ?? 400000;
        return $data;
    }
}