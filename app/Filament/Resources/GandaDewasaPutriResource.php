<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GandaDewasaPutriResource\Pages;
use App\Filament\Resources\GandaDewasaPutriResource\Widgets\GandaDewasaPutriStats;
use App\Models\Registration;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GandaDewasaPutriResource extends RegistrationResource
{
    protected static ?string $model            = Registration::class;
    protected static ?string $navigationIcon   = 'heroicon-o-user';
    protected static ?string $navigationLabel  = 'Dewasa Putri';
    protected static ?string $modelLabel       = 'Peserta Dewasa Putri';
    protected static ?string $pluralModelLabel = 'Ganda Dewasa Putri';
    protected static ?string $navigationGroup  = 'Kategori';
    protected static ?int    $navigationSort   = 11;
    protected static ?string $slug             = 'ganda-dewasa-putri';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('kategori', 'ganda-dewasa-putri');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('kategori', 'ganda-dewasa-putri')
            ->where('status', 'pending')->count();
        return $count ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->heading('Ganda Dewasa Putri')
            ->description('Daftar peserta kategori Ganda Dewasa Putri');
    }

            public static function getWidgets(): array
        {
            return [
                GandaDewasaPutriStats::class,
            ];
        }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGandaDewasaPutri::route('/'),
            'create' => Pages\CreateGandaDewasaPutri::route('/create'),
            'view'   => Pages\ViewGandaDewasaPutri::route('/{record}'),
            'edit'   => Pages\EditGandaDewasaPutri::route('/{record}/edit'),
        ];
    }


    public static function getRelations(): array { return []; }
}