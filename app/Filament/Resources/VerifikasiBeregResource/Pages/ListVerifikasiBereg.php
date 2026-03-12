<?php

namespace App\Filament\Resources\VerifikasiBeregResource\Pages;

use App\Filament\Resources\VerifikasiBeregResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListVerifikasiBereg extends ListRecords
{
    protected static string $resource = VerifikasiBeregResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * Tab filter di atas tabel: Semua | Pending | Approved | Rejected
     */
}