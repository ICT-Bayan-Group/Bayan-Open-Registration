<?php

namespace App\Filament\Exports;

use App\Models\Registration;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class RegistrationExporter extends Exporter
{
    protected static ?string $model = Registration::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('uuid')
                ->label('ID Pendaftaran'),

            ExportColumn::make('kategori')
                ->label('Kategori')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'ganda-dewasa-putra'  => 'Ganda Dewasa Putra',
                    'ganda-dewasa-putri'  => 'Ganda Dewasa Putri',
                    'ganda-veteran-putra' => 'Ganda Veteran Putra',
                    'beregu'              => 'Beregu',
                    default               => ucfirst($state),
                }),

            ExportColumn::make('tim_pb')
                ->label('Nama Tim / PB'),

            ExportColumn::make('nama')
                ->label('Ketua Tim / PIC'),

            ExportColumn::make('email')
                ->label('Email'),

            ExportColumn::make('no_hp')
                ->label('No. HP'),

            ExportColumn::make('provinsi')
                ->label('Provinsi'),

            ExportColumn::make('kota')
                ->label('Kota'),

            ExportColumn::make('nama_pelatih')
                ->label('Nama Pelatih')
                ->formatStateUsing(fn ($state) => $state ? ucwords(strtolower($state)) : $state),

            ExportColumn::make('no_hp_pelatih')
                ->label('No. HP Pelatih'),

            ExportColumn::make('jumlah_pemain')
                ->label('Jumlah Pemain'),

            ExportColumn::make('pemain_list')
                ->label('Nama Pemain')
                ->formatStateUsing(fn ($state) => $state ? ucwords(strtolower($state)) : $state),

            ExportColumn::make('harga')
                ->label('Harga')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

            ExportColumn::make('status')
                ->label('Status Pembayaran')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'pending'               => 'Belum Bayar',
                    'pending_verification'  => 'Menunggu Verifikasi',
                    'paid'                  => 'Sudah Bayar',
                    'failed'                => 'Pembayaran Ditolak',
                    'expired'               => 'Kadaluarsa',
                    default                 => $state,
                }),

            ExportColumn::make('approval_status')
                ->label('Status Approval')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'pending_review'    => 'Menunggu Review',
                    'approved'          => 'Disetujui',
                    'rejected'          => 'Ditolak',
                    'revision_required' => 'Perlu Revisi',
                    default             => $state,
                }),

            ExportColumn::make('payment_verified_at')
                ->label('Waktu Verifikasi Pembayaran'),

            ExportColumn::make('created_at')
                ->label('Waktu Daftar'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export peserta selesai — ' . number_format($export->successful_rows) . ' baris berhasil.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal di-export.';
        }

        return $body;
    }
}