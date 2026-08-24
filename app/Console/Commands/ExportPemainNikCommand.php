<?php

namespace App\Console\Commands;

use App\Models\Registration;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * php artisan export:pemain-nik
 *
 * Export nama + NIK/Paspor per pemain, untuk SEMUA kategori,
 * masing-masing kategori jadi 1 sheet Excel terpisah.
 *
 * Kenapa perlu command khusus:
 * RegistrationExporter (Filament) cuma export nama pemain,
 * tidak pecah NIK per pemain karena field `nik`, `ktp_type`,
 * `paspor_number` disimpan sebagai array index-based di kolom
 * yang sama, bukan kolom terpisah per pemain.
 */
class ExportPemainNikCommand extends Command
{
    protected $signature = 'export:pemain-nik
                            {--kategori= : Filter kategori tertentu (opsional)}
                            {--status= : Filter status pembayaran, mis. paid (opsional)}
                            {--output= : Nama file output (opsional)}';

    protected $description = 'Export nama & NIK/Paspor per pemain (semua kategori) ke Excel';

    protected array $kategoriLabels = [
        'ganda-dewasa-putra'  => 'Ganda Dewasa Putra',
        'ganda-dewasa-putri'  => 'Ganda Dewasa Putri',
        'ganda-veteran-putra' => 'Ganda Veteran Putra',
        'beregu'              => 'Beregu',
    ];

    public function handle(): int
    {
        $query = Registration::query();

        if ($kategori = $this->option('kategori')) {
            $query->where('kategori', $kategori);
        }

        if ($status = $this->option('status')) {
            $query->where('status', $status);
        }

        $registrations = $query->orderBy('kategori')->orderBy('id')->get();

        if ($registrations->isEmpty()) {
            $this->warn('Tidak ada data registrasi yang cocok dengan filter.');
            return self::SUCCESS;
        }

        // Kelompokkan per kategori
        $grouped = $registrations->groupBy('kategori');

        $spreadsheet = new Spreadsheet();
        // Hapus sheet default kosong, kita bikin manual per kategori
        $spreadsheet->removeSheetByIndex(0);

        $headerFill = [
            'fillType'   => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '1F2937'],
        ];
        $headerFont = ['bold' => true, 'color' => ['rgb' => 'FFFFFF']];

        $sheetIndex = 0;

        foreach ($grouped as $kategoriKey => $items) {
            $label = $this->kategoriLabels[$kategoriKey] ?? ucfirst($kategoriKey);

            // Nama sheet Excel max 31 karakter
            $sheetName = substr(preg_replace('/[\\\\\/\?\*\[\]:]/', '-', $label), 0, 31);

            $sheet = $spreadsheet->createSheet($sheetIndex);
            $sheet->setTitle($sheetName);

            $headers = [
                'ID Registrasi',
                'UUID',
                'Tim / PB',
                'PIC / Ketua Tim',
                'No. HP PIC',
                'Status Bayar',
                'Status Approval',
                'No. Urut Pemain',
                'Nama Pemain',
                'Tipe Dokumen',
                'NIK',
                'No. Paspor',
                'Tgl Lahir',
                'Usia',
                'Kota KTP Valid?',
            ];

            $sheet->fromArray($headers, null, 'A1');
            $sheet->getStyle('A1:' . $this->colLetter(count($headers)) . '1')
                ->applyFromArray(['fill' => $headerFill, 'font' => $headerFont]);
            $sheet->freezePane('A2');

            $row = 2;

            foreach ($items as $reg) {
                $ktpPerPemain = $reg->ktp_per_pemain; // pakai accessor yg sudah ada di model

                if (empty($ktpPerPemain)) {
                    // Registrasi tanpa data pemain sama sekali, tetap dicatat 1 baris
                    $sheet->fromArray([
                        $reg->id,
                        $reg->uuid,
                        $reg->tim_pb,
                        $reg->nama,
                        $reg->no_hp,
                        $reg->status,
                        $reg->approval_status,
                        '-', '-', '-', '-', '-', '-', '-', '-',
                    ], null, "A{$row}");
                    $row++;
                    continue;
                }

                foreach ($ktpPerPemain as $p) {
                    $cityValid = $p['city_valid']['valid'] ?? null;
                    $cityValidLabel = is_null($cityValid) ? '-' : ($cityValid ? 'Valid' : 'Tidak Valid');

                    $sheet->fromArray([
                        $reg->id,
                        $reg->uuid,
                        $reg->tim_pb,
                        $reg->nama,
                        $reg->no_hp,
                        $reg->status,
                        $reg->approval_status,
                        $p['index'],
                        $p['nama'],
                        $p['doc_type'] === 'paspor' ? 'Paspor' : 'KTP',
                        $p['nik'] ?? '-',
                        $p['paspor_number'] ?? '-',
                        $p['tgl_lahir'] ?? '-',
                        $p['usia'] ?? '-',
                        $cityValidLabel,
                    ], null, "A{$row}");

                    // Paksa kolom NIK & No. Paspor jadi teks (biar leading zero / angka panjang gak berubah)
                    $sheet->getCell("K{$row}")->setValueExplicit($p['nik'] ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->getCell("L{$row}")->setValueExplicit($p['paspor_number'] ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                    $row++;
                }
            }

            foreach (range('A', $this->colLetter(count($headers))) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            $sheet->getStyle("A1:{$this->colLetter(count($headers))}{$row}")
                ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $sheetIndex++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        $filename = $this->option('output') ?: 'export-nik-pemain-' . now()->format('Y-m-d_His') . '.xlsx';
        $path = storage_path('app/exports/' . $filename);

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        $this->info("Export selesai: {$path}");
        $this->info('Jumlah kategori: ' . $grouped->count() . ', total registrasi: ' . $registrations->count());

        return self::SUCCESS;
    }

    /**
     * Konversi nomor kolom (1-based) ke huruf kolom Excel (A, B, ... Z, AA, ...)
     */
    protected function colLetter(int $colNumber): string
    {
        $letter = '';
        while ($colNumber > 0) {
            $mod = ($colNumber - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $colNumber = intdiv($colNumber - $mod, 26) - 1;
        }
        return $letter;
    }
}