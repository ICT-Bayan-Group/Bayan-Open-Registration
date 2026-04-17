<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeregResource\Pages;
use App\Filament\Resources\BeregResource\Widgets\BeregStats;
use App\Models\Registration;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BeregResource extends RegistrationResource
{
    protected static ?string $model            = Registration::class;
    protected static ?string $navigationIcon   = 'heroicon-o-user-group';
    protected static ?string $navigationLabel  = 'Beregu';
    protected static ?string $modelLabel       = 'Peserta Beregu';
    protected static ?string $pluralModelLabel = 'Beregu';
    protected static ?string $navigationGroup  = 'Kategori';
    protected static ?int    $navigationSort   = 13;
    protected static ?string $slug             = 'beregu';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('kategori', 'beregu');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('kategori', 'beregu')
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
            ->heading('Beregu')
            ->description('Daftar peserta kategori Beregu — harga Rp 1.000.000');
    }

        public static function getWidgets(): array
        {
            return [
                BeregStats::class,
            ];
        }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBereg::route('/'),
            'create' => Pages\CreateBereg::route('/create'),
            'view'   => Pages\ViewBereg::route('/{record}'),
            'edit'   => Pages\EditBereg::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array { return []; }
}
