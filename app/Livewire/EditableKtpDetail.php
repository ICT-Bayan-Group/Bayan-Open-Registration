<?php

namespace App\Livewire;

use App\Models\Registration;
use Filament\Notifications\Notification;
use Livewire\Component;

class EditableKtpDetail extends Component
{
    public Registration $record;
    public array $anggota = [];
    public ?int $editingIndex = null;

    public function mount(Registration $record): void
    {
        $this->record = $record;
        $this->loadData();
    }

    public function loadData(): void
    {
        $pemain    = $this->record->pemain ?? [];
        $nik       = $this->record->nik ?? [];
        $tglLahir  = $this->record->tgl_lahir ?? [];
        $usia      = $this->record->usia_pemain ?? [];
        $ktpType   = $this->record->ktp_type ?? [];
        $cityValid = $this->record->ktp_city_valid ?? [];

        $this->anggota = [];
        foreach ($pemain as $i => $nama) {
            $this->anggota[$i] = [
                'nama'      => $nama,
                'nik'       => $nik[$i] ?? '',
                'tgl_lahir' => $tglLahir[$i] ?? '',
                'usia'      => $usia[$i] ?? '',
                'ktp_type'  => $ktpType[$i] ?? 'ktp',
                'city_raw'  => $cityValid[$i]['city_raw'] ?? '',
                'valid'     => (bool) ($cityValid[$i]['valid'] ?? false),
            ];
        }
    }

    public function edit(int $index): void
    {
        $this->editingIndex = $index;
    }

    public function cancel(): void
    {
        $this->editingIndex = null;
        $this->loadData(); // buang perubahan yang belum disimpan
    }

    public function save(int $index): void
    {
        $this->validate([
            "anggota.$index.nama" => 'required|string|max:100',
            "anggota.$index.nik"  => 'nullable|string|max:20',
            "anggota.$index.usia" => 'nullable|numeric|min:0|max:120',
        ], [], [
            "anggota.$index.nama" => 'Nama',
            "anggota.$index.nik"  => 'NIK',
            "anggota.$index.usia" => 'Usia',
        ]);

        $pemain    = $this->record->pemain ?? [];
        $nik       = $this->record->nik ?? [];
        $tglLahir  = $this->record->tgl_lahir ?? [];
        $usia      = $this->record->usia_pemain ?? [];
        $ktpType   = $this->record->ktp_type ?? [];
        $cityValid = $this->record->ktp_city_valid ?? [];

        $item = $this->anggota[$index];

        $pemain[$index]    = $item['nama'];
        $nik[$index]       = $item['nik'];
        $tglLahir[$index]  = $item['tgl_lahir'];
        $usia[$index]      = $item['usia'];
        $ktpType[$index]   = $item['ktp_type'];
        $cityValid[$index] = [
            'index'    => $index + 1,
            'nama'     => $item['nama'],
            'city_raw' => $item['city_raw'],
            'valid'    => (bool) $item['valid'],
        ];

        $this->record->update([
            'pemain'         => $pemain,
            'nik'            => $nik,
            'tgl_lahir'      => $tglLahir,
            'usia_pemain'    => $usia,
            'ktp_type'       => $ktpType,
            'ktp_city_valid' => $cityValid,
        ]);

        $this->editingIndex = null;
        $this->record->refresh();
        $this->loadData();

        Notification::make()
            ->title('✅ Data ' . $item['nama'] . ' berhasil diperbarui')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.editable-ktp-detail');
    }
}