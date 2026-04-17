<?php

namespace App\Filament\Pages;

use App\Models\Registration;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class RevenueReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Revenue Report';
    protected static ?string $title           = 'Laporan Revenue';
    protected static ?int $navigationSort     = 2;
    protected static string $view             = 'filament.resources.admin-resource.pages.revenue-report';

    public function getViewData(): array
    {
        // Base query yang dipakai ulang — konsisten, tidak bisa typo scope
        $paid = fn() => Registration::where('status', 'paid');

        return [
            // ── Revenue ───────────────────────────────────────────
            'totalRevenue'      => $paid()->sum('harga'),

            // Beregu = kategori 'beregu' saja
            'revenueRegu'       => $paid()->where('kategori', 'beregu')->sum('harga'),

            // Open = semua NON-beregu (3 kategori ganda)
            'revenueOpen'       => $paid()->where('kategori', '!=', 'beregu')->sum('harga'),

            // Per kategori spesifik (akurasi maksimal, tidak ada yang terlewat)
            'revenueDewasaPutra'  => $paid()->where('kategori', 'ganda-dewasa-putra')->sum('harga'),
            'revenueDewasaPutri'  => $paid()->where('kategori', 'ganda-dewasa-putri')->sum('harga'),
            'revenueVeteran'      => $paid()->where('kategori', 'ganda-veteran-putra')->sum('harga'),

            // ── Count ─────────────────────────────────────────────
            'totalPaid'         => $paid()->count(),
            'totalPaidRegu'     => $paid()->where('kategori', 'beregu')->count(),
            'totalPaidOpen'     => $paid()->where('kategori', '!=', 'beregu')->count(),

            // ── Avg ───────────────────────────────────────────────
            // Dihitung manual agar tidak NULL saat count=0
            'avgOrderValue'     => $paid()->count() > 0
                                    ? $paid()->sum('harga') / $paid()->count()
                                    : 0,

            // ── Time-based ────────────────────────────────────────
            'todayRevenue'      => $paid()->whereDate('payment_time', today())->sum('harga'),
            'thisMonthRevenue'  => $paid()
                                    ->whereYear('payment_time', now()->year)   // ← tambah year agar tidak salah bulan tahun lalu
                                    ->whereMonth('payment_time', now()->month)
                                    ->sum('harga'),

            // ── Pending (potensi revenue belum masuk) ────────────
            'pendingRevenue'    => Registration::where('status', 'pending')->sum('harga'),
            'pendingCount'      => Registration::where('status', 'pending')->count(),
        ];
    }

   public function table(Table $table): Table
{
    return $table
        ->query(
            \App\Models\Registration::query()
                ->where('status', 'paid')
                ->orderBy('payment_time', 'desc')
        )
        ->columns([
            Tables\Columns\TextColumn::make('midtrans_order_id')
                ->label('Order ID')->fontFamily('mono')->copyable(),
            Tables\Columns\TextColumn::make('nama')->label('Nama'),
            Tables\Columns\TextColumn::make('tim_pb')->label('Tim'),
            Tables\Columns\BadgeColumn::make('kategori')
                ->colors([
                    'primary' => 'ganda-dewasa-putra',
                    'info'    => 'ganda-dewasa-putri',
                    'warning' => 'ganda-veteran-putra',
                    'success' => 'beregu',
                ])
                ->formatStateUsing(fn ($state) => match ($state) {
                    'ganda-dewasa-putra'  => 'Dewasa Putra',
                    'ganda-dewasa-putri'  => 'Dewasa Putri',
                    'ganda-veteran-putra' => 'Veteran Putra',
                    'beregu'              => 'Beregu',
                    default               => $state,
                }),
            Tables\Columns\TextColumn::make('harga')
                ->label('Nominal')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->sortable(),
            Tables\Columns\TextColumn::make('payment_type')
                ->label('Metode')
                ->formatStateUsing(fn ($state) => $state ?? '-'),
            Tables\Columns\TextColumn::make('payment_time')
                ->label('Waktu Bayar')->dateTime('d M Y, H:i')->sortable(),
        ])
        ->defaultSort('payment_time', 'desc');
}
}