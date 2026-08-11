<?php

namespace App\Filament\Exports;

// Dipakai di resource kategori ganda: GandaDewasaPutraResource,
// GandaDewasaPutriResource, GandaVeteranPutraResource.
// Hanya 2 kolom pemain karena kategori ganda selalu 2 pemain.
class GandaExporter extends RegistrationExporter
{
    protected static int $maxPemainColumns = 2;
}