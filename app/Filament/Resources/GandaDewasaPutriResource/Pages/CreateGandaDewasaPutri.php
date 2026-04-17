<?php

namespace App\Filament\Resources\GandaDewasaPutriResource\Pages;

use App\Filament\Resources\GandaDewasaPutriResource;
use Filament\Resources\Pages\CreateRecord;

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
