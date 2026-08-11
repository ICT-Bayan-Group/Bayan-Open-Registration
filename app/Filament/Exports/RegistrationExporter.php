<?php

namespace App\Filament\Exports;

use App\Models\Registration;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class RegistrationExporter extends Exporter
{
    protected static ?string $model = Registration::class;

    // Dipakai di halaman utama (RegistrationResource) yang menampilkan
    // SEMUA kategori campur (termasuk Beregu), jadi disiapkan 8 kolom
    // pemain supaya data Beregu tidak terpotong.
    // Untuk export khusus per kategori, lihat GandaExporter & BeregExporter.
    protected static int $maxPemainColumns = 8;

    public static function getColumns(): array
    {
        return [
            ...static::getCommonColumns(),
            ...static::getPemainColumns(),
            ...static::getTrailingColumns(),
        ];
    }

    // ============================================================
    // Kolom umum (identik untuk semua exporter kategori)
    // ============================================================

    protected static function getCommonColumns(): array
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
        ];
    }

    protected static function getTrailingColumns(): array
    {
        return [
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

    // ============================================================
    // Kolom Pemain — jumlah kolom ditentukan oleh static::$maxPemainColumns
    // (di-override di subclass GandaExporter / BeregExporter)
    // ============================================================

    protected static function getPemainColumns(): array
    {
        $columns = [];

        for ($i = 0; $i < static::$maxPemainColumns; $i++) {
            $index = $i; // closure capture

            $columns[] = ExportColumn::make("pemain_{$index}")
                ->label('Pemain ' . ($index + 1))
                ->getStateUsing(function (Registration $record) use ($index) {
                    $pemain = $record->pemain ?? [];
                    $nama   = $pemain[$index] ?? null;

                    return $nama ? ucwords(strtolower($nama)) : null;
                });
        }

        return $columns;
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