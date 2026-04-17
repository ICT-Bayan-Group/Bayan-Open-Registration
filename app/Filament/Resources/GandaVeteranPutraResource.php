<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GandaVeteranPutraResource\Pages;
use App\Filament\Resources\GandaVeteranPutraResource\Widgets\GandaVeteranPutraStats;
use App\Models\Registration;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GandaVeteranPutraResource extends RegistrationResource
{
    protected static ?string $model            = Registration::class;
    protected static ?string $navigationIcon   = 'heroicon-o-star';
    protected static ?string $navigationLabel  = 'Veteran Putra';
    protected static ?string $modelLabel       = 'Peserta Veteran Putra';
    protected static ?string $pluralModelLabel = 'Ganda Veteran Putra';
    protected static ?string $navigationGroup  = 'Kategori';
    protected static ?int    $navigationSort   = 12;
    protected static ?string $slug             = 'ganda-veteran-putra';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('kategori', 'ganda-veteran-putra');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('kategori', 'ganda-veteran-putra')
            ->where('status', 'pending')->count();
        return $count ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    // ── Veteran butuh extra kolom verifikasi di table ──────────────

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->heading('Ganda Veteran Putra')
            ->description('Daftar peserta kategori Ganda Veteran Putra — verifikasi usia wajib')
            // Tambahkan filter khusus: yang tidak lolos syarat veteran
            ->filters(array_merge(
                [],
                [
                    Tables\Filters\Filter::make('tidak_lolos_veteran')
                        ->label('⚠️ Belum Lolos Syarat Usia')
                        ->query(function (Builder $q) {
                            return $q->where('kategori', 'ganda-veteran-putra')
                                     ->whereNotNull('usia_pemain')
                                     ->whereRaw("(
                                         JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '$[0]')) + 0 < 45
                                         OR JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '$[1]')) + 0 < 45
                                         OR (JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '$[0]')) + JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '$[1]'))) < 95
                                     )");
                        }),

                    Tables\Filters\Filter::make('lolos_veteran')
                        ->label('✅ Lolos Syarat Usia')
                        ->query(function (Builder $q) {
                            return $q->where('kategori', 'ganda-veteran-putra')
                                     ->whereNotNull('usia_pemain')
                                     ->whereRaw("(
                                         JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '$[0]')) + 0 >= 45
                                         AND JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '$[1]')) + 0 >= 45
                                         AND (JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '$[0]')) + JSON_UNQUOTE(JSON_EXTRACT(usia_pemain, '$[1]'))) >= 95
                                     )");
                        }),
                ]
            ));
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGandaVeteranPutra::route('/'),
            'create' => Pages\CreateGandaVeteranPutra::route('/create'),
            'view'   => Pages\ViewGandaVeteranPutra::route('/{record}'),
            'edit'   => Pages\EditGandaVeteranPutra::route('/{record}/edit'),
        ];
    }

            public static function getWidgets(): array
        {
            return [
                GandaVeteranPutraStats::class,
            ];
        }

    public static function getRelations(): array { return []; }
}