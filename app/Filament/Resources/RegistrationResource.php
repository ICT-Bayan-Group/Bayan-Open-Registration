<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationResource\Pages;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Peserta';
    protected static ?string $modelLabel = 'Peserta';
    protected static ?string $pluralModelLabel = 'Data Peserta';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Peserta')
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama Peserta')
                        ->required()
                        ->maxLength(100),

                    Forms\Components\TextInput::make('tim_pb')
                        ->label('Tim / PB')
                        ->required()
                        ->maxLength(100),

                    Forms\Components\Select::make('kategori')
                        ->label('Kategori')
                        ->options([
                            'regu' => 'Regu (Rp 200.000)',
                            'open' => 'Open (Rp 150.000)',
                        ])
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            $set('harga', $state === 'regu' ? 200000 : 150000);
                        }),

                    Forms\Components\TextInput::make('harga')
                        ->label('Harga')
                        ->numeric()
                        ->prefix('Rp')
                        ->readOnly(),
                ])->columns(2),

            Forms\Components\Section::make('Status & Transaksi')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Pending',
                            'paid'    => 'Paid',
                            'failed'  => 'Failed',
                            'expired' => 'Expired',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('midtrans_order_id')
                        ->label('Order ID')
                        ->readOnly(),

                    Forms\Components\TextInput::make('midtrans_transaction_id')
                        ->label('Transaction ID')
                        ->readOnly(),

                    Forms\Components\TextInput::make('payment_type')
                        ->label('Metode Bayar')
                        ->readOnly(),

                    Forms\Components\DateTimePicker::make('payment_time')
                        ->label('Waktu Bayar')
                        ->readOnly(),

                    Forms\Components\TextInput::make('fraud_status')
                        ->label('Fraud Status')
                        ->readOnly(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('midtrans_order_id')
                    ->label('Order ID')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->size('sm'),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('tim_pb')
                    ->label('Tim / PB')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('kategori')
                    ->label('Kategori')
                    ->colors([
                        'primary' => 'regu',
                        'success' => 'open',
                    ])
                    ->formatStateUsing(fn($state) => strtoupper($state)),

                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger'  => 'failed',
                        'gray'    => 'expired',
                    ])
                    ->formatStateUsing(fn($state) => strtoupper($state)),

                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Metode')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Daftar')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
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
                    ->options([
                        'regu' => 'Regu',
                        'open' => 'Open',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['until'], fn($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('download_receipt')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->visible(fn(Registration $r) => $r->status === 'paid' && $r->pdf_receipt_path)
                    ->url(fn(Registration $r) => route('registration.receipt', $r->uuid))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('mark_paid')
                    ->label('Tandai Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(Registration $r) => in_array($r->status, ['pending', 'failed', 'expired']))
                    ->requiresConfirmation()
                    ->action(function (Registration $r) {
                        $r->update(['status' => 'paid', 'payment_time' => now()]);
                        \App\Jobs\ProcessPaidRegistration::dispatch($r);
                        Notification::make()
                            ->title('Status berhasil diupdate ke Paid')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('resend_email')
                    ->label('Resend Email')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->visible(fn(Registration $r) => $r->status === 'paid' && !empty($r->email))
                    ->action(function (Registration $r) {
                        \Illuminate\Support\Facades\Mail::to($r->email)
                            ->send(new \App\Mail\RegistrationPaid($r));
                        Notification::make()
                            ->title('Email berhasil dikirim ulang')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('export_excel')
                        ->label('Export Excel')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            // Export logic via Maatwebsite Excel
                        }),
                ]),
            ])
            ->striped()
            ->poll('30s');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'view'   => Pages\ViewRegistration::route('/{record}'),
            'edit'   => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}