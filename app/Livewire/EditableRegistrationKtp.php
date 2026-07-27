<?php

namespace App\Livewire;

use App\Models\Registration;
use Filament\Notifications\Notification;
use Livewire\Component;

class EditableRegistrationKtp extends Component
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
        $pasporNum = $this->record->paspor_number ?? [];
        $tglLahir  = $this->record->tgl_lahir ?? [];
        $usia      = $this->record->usia_pemain ?? [];
        $ktpType   = $this->record->ktp_type ?? [];
        $ktpData   = $this->record->ktp_data ?? [];

        $this->anggota = [];
        foreach ($pemain as $i => $nama) {
            $extra = $ktpData[$i] ?? [];
            $this->anggota[$i] = [
                'nama'          => $nama,
                'ktp_type'      => $ktpType[$i] ?? 'ktp',
                'nik'           => $nik[$i] ?? '',
                'paspor_number' => $pasporNum[$i] ?? '',
                'tgl_lahir'     => $tglLahir[$i] ?? '',
                'usia'          => $usia[$i] ?? '',
                'jenis_kelamin' => $extra['jenis_kelamin'] ?? '',
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
        $this->loadData();
    }

    public function save(int $index): void
    {
        $this->validate([
            "anggota.$index.nama" => 'required|string|max:100',
            "anggota.$index.nik"  => 'nullable|string|max:20',
            "anggota.$index.paspor_number" => 'nullable|string|max:30',
            "anggota.$index.usia" => 'nullable|numeric|min:0|max:120',
        ], [], [
            "anggota.$index.nama" => 'Nama',
            "anggota.$index.nik"  => 'NIK',
            "anggota.$index.paspor_number" => 'No. Paspor',
            "anggota.$index.usia" => 'Usia',
        ]);

        $pemain    = $this->record->pemain ?? [];
        $nik       = $this->record->nik ?? [];
        $pasporNum = $this->record->paspor_number ?? [];
        $tglLahir  = $this->record->tgl_lahir ?? [];
        $usia      = $this->record->usia_pemain ?? [];
        $ktpType   = $this->record->ktp_type ?? [];
        $ktpData   = $this->record->ktp_data ?? [];

        $item = $this->anggota[$index];

        $pemain[$index]    = $item['nama'];
        $nik[$index]       = $item['nik'];
        $pasporNum[$index] = $item['paspor_number'];
        $tglLahir[$index]  = $item['tgl_lahir'];
        $usia[$index]      = $item['usia'];
        $ktpType[$index]   = $item['ktp_type'];

        $ktpData[$index] = array_merge($ktpData[$index] ?? [], [
            'jenis_kelamin' => $item['jenis_kelamin'],
        ]);

        $this->record->update([
            'pemain'         => $pemain,
            'nik'            => $nik,
            'paspor_number'  => $pasporNum,
            'tgl_lahir'      => $tglLahir,
            'usia_pemain'    => $usia,
            'ktp_type'       => $ktpType,
            'ktp_data'       => $ktpData,
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
        return view('livewire.editable-registration-ktp');
    }
}