<?php

namespace App\Filament\Pages;

use App\Models\Registration;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class IctPanel extends Page implements HasTable
{
    use InteractsWithTable;

    // ── Sembunyikan dari sidebar & navigasi ───────────────────────
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'registrations/ict';

    protected static string $view = 'filament.resources.admin-resource.pages.ict-panel';

    // ── Tidak perlu title di nav ──────────────────────────────────
    public static function getNavigationLabel(): string
    {
        return 'ICT Panel';
    }

    // ── Table ─────────────────────────────────────────────────────
    public function table(Table $table): Table
    {
        return $table
            ->query(Registration::query())
            ->columns([
                Tables\Columns\TextColumn::make('midtrans_order_id')
                    ->label('Order ID')->searchable()->copyable()
                    ->fontFamily('mono')->size('sm'),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama PIC')->searchable()->weight('bold'),

                Tables\Columns\TextColumn::make('tim_pb')
                    ->label('Tim / PB')->searchable(),

                Tables\Columns\BadgeColumn::make('kategori')
                    ->label('Kategori')
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
                        default               => strtoupper($state),
                    }),

                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger'  => 'failed',
                        'gray'    => 'expired',
                    ])
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')->searchable()->copyable(),

                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP')->copyable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Daftar')->dateTime('d M Y, H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid'    => 'Paid',
                        'failed'  => 'Failed',
                        'expired' => 'Expired',
                    ]),

                Tables\Filters\SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'ganda-dewasa-putra'  => 'Ganda Dewasa Putra',
                        'ganda-dewasa-putri'  => 'Ganda Dewasa Putri',
                        'ganda-veteran-putra' => 'Ganda Veteran Putra',
                        'beregu'              => 'Beregu',
                    ]),
            ])
            ->actions([
                // ── Tandai Paid ───────────────────────────────────
                Tables\Actions\Action::make('mark_paid')
                    ->label('Tandai Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Registration $r) => $r->status !== 'paid')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai sebagai Paid?')
                    ->modalDescription(fn (Registration $r) =>
                        'Peserta ' . $r->nama . ' (' . $r->tim_pb . ') akan ditandai PAID secara manual.'
                    )
                    ->action(function (Registration $r) {
                        $r->update([
                            'status'       => 'paid',
                            'payment_time' => now(),
                            'payment_type' => 'manual-ict',
                        ]);
                        Notification::make()
                            ->title('Status diubah ke PAID')
                            ->success()->send();
                    }),

                // ── Ubah Status ───────────────────────────────────
                Tables\Actions\Action::make('ubah_status')
                    ->label('Ubah Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form([
                        Select::make('status')
                            ->label('Status Baru')
                            ->options([
                                'pending' => 'Pending',
                                'paid'    => 'Paid',
                                'failed'  => 'Failed',
                                'expired' => 'Expired',
                            ])
                            ->required(),
                    ])
                    ->action(function (Registration $r, array $data) {
                        $r->update(['status' => $data['status']]);
                        Notification::make()
                            ->title('Status diubah ke ' . strtoupper($data['status']))
                            ->success()->send();
                    }),

                // ── Hapus ─────────────────────────────────────────
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                // ── Bulk Paid ─────────────────────────────────────
                Tables\Actions\BulkAction::make('bulk_paid')
                    ->label('Tandai Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (\Illuminate\Support\Collection $records) {
                        $records->each(fn ($r) => $r->update([
                            'status'       => 'paid',
                            'payment_time' => now(),
                            'payment_type' => 'manual-ict',
                        ]));
                        Notification::make()
                            ->title($records->count() . ' peserta ditandai PAID')
                            ->success()->send();
                    })
                    ->deselectRecordsAfterCompletion(),

                // ── Bulk Hapus ────────────────────────────────────
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Hapus Terpilih'),
            ])
            ->striped()
            ->paginated([25, 50, 100]);
    }

    // ── Header actions (tombol di atas halaman) ───────────────────
    protected function getHeaderActions(): array
    {
        return [
            Action::make('hapus_semua_pending')
                ->label('Hapus Semua Pending')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hapus SEMUA data Pending?')
                ->modalDescription('Tindakan ini tidak bisa dibatalkan. Semua data dengan status pending akan dihapus permanen.')
                ->action(function () {
                    $count = Registration::where('status', 'pending')->count();
                    Registration::where('status', 'pending')->delete();
                    Notification::make()
                        ->title("{$count} data pending dihapus")
                        ->success()->send();
                }),

            Action::make('hapus_semua_expired')
                ->label('Hapus Semua Expired')
                ->icon('heroicon-o-clock')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Hapus SEMUA data Expired?')
                ->modalDescription('Semua data dengan status expired akan dihapus permanen.')
                ->action(function () {
                    $count = Registration::where('status', 'expired')->count();
                    Registration::where('status', 'expired')->delete();
                    Notification::make()
                        ->title("{$count} data expired dihapus")
                        ->success()->send();
                }),
        ];
    }
}
