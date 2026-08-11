<?php

namespace App\Filament\Exports;

// Dipakai khusus di BeregResource. Beregu bisa punya 6-8 pemain
// (lihat validasi $minPemain/$maxPemain di RegistrationController::store()),
// jadi disiapkan 8 kolom pemain.
class BeregExporter extends RegistrationExporter
{
    protected static int $maxPemainColumns = 8;
}