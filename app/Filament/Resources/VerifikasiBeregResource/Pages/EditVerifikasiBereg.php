<?php

namespace App\Filament\Resources\VerifikasiBeregResource\Pages;

use App\Filament\Resources\VerifikasiBeregResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVerifikasiBereg extends EditRecord
{
    protected static string $resource = VerifikasiBeregResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}