<?php

namespace App\Filament\Pages;

use App\Models\Registration;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RevenueReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Revenue Report';
    protected static ?string $title           = 'Laporan Revenue';
    protected static ?int $navigationSort     = 2;
    protected static string $view             = 'filament.pages.revenue-report';

    public function getViewData(): array
    {
        return [
            'totalRevenue'   => Registration::paid()->sum('harga'),
            'revenueRegu'    => Registration::paid()->regu()->sum('harga'),
            'revenueOpen'    => Registration::paid()->open()->sum('harga'),
            'totalPaid'      => Registration::paid()->count(),
            'avgOrderValue'  => Registration::paid()->avg('harga'),
            'todayRevenue'   => Registration::paid()->whereDate('payment_time', today())->sum('harga'),
            'thisMonthRevenue' => Registration::paid()->whereMonth('payment_time', now()->month)->sum('harga'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Registration::query()->paid()->orderBy('payment_time', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('midtrans_order_id')
                    ->label('Order ID')
                    ->fontFamily('mono')
                    ->copyable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama'),

                Tables\Columns\TextColumn::make('tim_pb')
                    ->label('Tim'),

                Tables\Columns\BadgeColumn::make('kategori')
                    ->colors(['primary' => 'regu', 'success' => 'open']),

                Tables\Columns\TextColumn::make('harga')
                    ->label('Nominal')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Metode'),

                Tables\Columns\TextColumn::make('payment_time')
                    ->label('Waktu Bayar')
                    ->dateTime('d M Y H:i'),
            ]);
    }
}