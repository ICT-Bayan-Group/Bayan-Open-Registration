<?php

namespace App\Filament\Resources\GandaDewasaPutraResource\Pages;

use App\Filament\Resources\GandaDewasaPutraResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;

// ── CreateGandaDewasaPutra ────────────────────────────────────────────────────
class CreateGandaDewasaPutra extends CreateRecord
{
    protected static string $resource = GandaDewasaPutraResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Otomatis set kategori & harga saat create dari page ini
        $data['kategori'] = 'ganda-dewasa-putra';
        $data['harga']    = $data['harga'] ?? 400000;
        return $data;
    }
}

// ── ViewGandaDewasaPutra ──────────────────────────────────────────────────────
class ViewGandaDewasaPutra extends ViewRecord
{
    protected static string $resource = GandaDewasaPutraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

// ── EditGandaDewasaPutra ──────────────────────────────────────────────────────
class EditGandaDewasaPutra extends EditRecord
{
    protected static string $resource = GandaDewasaPutraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}