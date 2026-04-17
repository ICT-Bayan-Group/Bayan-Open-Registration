<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GandaDewasaPutraResource\Pages;
use App\Filament\Resources\GandaDewasaPutraResource\Widgets\GandaDewasaPutraStats;
use App\Models\Registration;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GandaDewasaPutraResource extends RegistrationResource
{
    protected static ?string $model             = Registration::class;
    protected static ?string $navigationIcon    = 'heroicon-o-user';
    protected static ?string $navigationLabel   = 'Dewasa Putra';
    protected static ?string $modelLabel        = 'Peserta Dewasa Putra';
    protected static ?string $pluralModelLabel  = 'Ganda Dewasa Putra';
    protected static ?string $navigationGroup   = 'Kategori';
    protected static ?int    $navigationSort    = 10;
    protected static ?string $slug              = 'ganda-dewasa-putra';

    // ── Filter hanya kategori ini ─────────────────────────────────

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('kategori', 'ganda-dewasa-putra');
    }

    // ── Badge nav: hitung pending kategori ini ────────────────────

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('kategori', 'ganda-dewasa-putra')
            ->where('status', 'pending')
            ->count();

        return $count ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    // ── Form: sama persis dengan parent, tidak perlu override ─────
    // (inherited from RegistrationResource)

    // ── Infolist: inherited ───────────────────────────────────────

    // ── Table: inherited, tapi kita sembunyikan kolom 'kategori' karena sudah pasti ──

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->heading('Ganda Dewasa Putra')
            ->description('Daftar peserta kategori Ganda Dewasa Putra');
    }

    // ── Pages ─────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGandaDewasaPutra::route('/'),
            //'create' => Pages\CreateGandaDewasaPutra::route('/create'),
            'view'   => Pages\ViewGandaDewasaPutra::route('/{record}'),
            'edit'   => Pages\EditGandaDewasaPutra::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }
        public static function getWidgets(): array
        {
            return [
                GandaDewasaPutraStats::class,
            ];
        }
}