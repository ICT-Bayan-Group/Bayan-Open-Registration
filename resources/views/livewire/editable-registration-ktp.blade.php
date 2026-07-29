<div style="display:grid;grid-template-columns:1fr;gap:24px;">

    @if (empty($anggota))
        <p class="txt-muted" style="font-size:14px;">Belum ada data pemain.</p>
    @endif

    @php
        $isVeteran = $record->kategori === 'ganda-veteran-putra';
    @endphp

    @foreach ($anggota as $i => $item)
        @php
            $isPaspor  = $item['ktp_type'] === 'paspor';
            $isEditing = $editingIndex === $i;
            $nilUsia   = $item['usia'] !== '' ? (int) $item['usia'] : null;

            if ($isEditing) {
                $stateClass = 'ktp-state-edit';
            } elseif ($isPaspor) {
                $stateClass = 'ktp-state-paspor';
            } elseif ($isVeteran && $nilUsia !== null) {
                $stateClass = $nilUsia >= 45 ? 'ktp-state-valid' : 'ktp-state-invalid';
            } else {
                $stateClass = 'ktp-state-neutral';
            }

            $ktpFiles    = $record->ktp_files ?? [];
            $pasporFiles = $record->paspor_files ?? [];
            $files       = $isPaspor ? $pasporFiles : $ktpFiles;
            $filePath    = $files[$i] ?? (count($files) === 1 ? reset($files) : null);
            $fotoUrl     = $filePath
                ? route($isPaspor ? 'admin.paspor.serve' : 'admin.ktp.serve', ['uuid' => $record->uuid, 'filename' => basename($filePath)])
                : null;
        @endphp

        <div class="ktp-card {{ $stateClass }}" wire:key="reganggota-{{ $i }}">

            {{-- HEADER --}}
            <div class="ktp-header">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="ktp-avatar">{{ $i + 1 }}</div>
                    <span class="ktp-name">{{ $item['nama'] }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    @if (!$isEditing)
                        <span class="{{ $isPaspor ? 'badge-paspor' : 'badge-ktp' }}">
                            {{ $isPaspor ? '🛂 PASPOR' : '🪪 KTP' }}
                        </span>
                        <button wire:click="edit({{ $i }})" type="button" title="Koreksi data" class="btn-edit-small">
                            ✏ Koreksi
                        </button>
                    @else
                        <span class="ktp-edit-mode-label">Mode Edit</span>
                    @endif
                </div>
            </div>

            <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:24px;">

                @if (!$isEditing)
                    {{-- ── READ MODE ── --}}
                    <div>
                        <p class="ktp-section-label">{{ $isPaspor ? '📋 Data Paspor' : '📋 Data KTP' }}</p>

                        @if ($isPaspor)
                            <x-registration-ktp-row label="No. Paspor" :value="$item['paspor_number'] ?: '—'" mono weight="bold" />
                        @else
                            <x-registration-ktp-row label="NIK" :value="$item['nik'] ?: '—'" mono weight="bold" />
                        @endif
                        <x-registration-ktp-row label="Nama" :value="$item['nama']" weight="600" />
                        <x-registration-ktp-row label="Tgl Lahir" :value="$item['tgl_lahir'] ?: '—'" />

                        @if ($nilUsia !== null)
                            @if ($isVeteran)
                                @php
                                    $ok = $nilUsia >= 45;
                                    $usiaLabel = ($ok ? '✓ ' : '✗ ') . $nilUsia . ' tahun — ' . ($ok ? 'Memenuhi syarat (≥ 45 thn)' : 'Tidak memenuhi syarat (min. 45 thn)');
                                @endphp
                                <x-registration-ktp-row label="Usia" :value="$usiaLabel" :color="$ok ? 'txt-ok' : 'txt-bad'" weight="bold" />
                            @else
                                <x-registration-ktp-row label="Usia" :value="$nilUsia . ' tahun'" />
                            @endif
                        @endif

                        @if ($item['jenis_kelamin'])
                            @php
                                $genderLabel = $item['jenis_kelamin'] === 'L' ? '♂ Laki-laki' : ($item['jenis_kelamin'] === 'P' ? '♀ Perempuan' : $item['jenis_kelamin']);
                                $genderColor = $item['jenis_kelamin'] === 'L' ? 'txt-blue' : 'txt-pink';
                            @endphp
                            <x-registration-ktp-row label="Kelamin" :value="$genderLabel" :color="$genderColor" weight="bold" />
                        @endif
                    </div>
                @else
                    {{-- ── EDIT MODE ── --}}
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <div>
                            <label class="ktp-field-label">Nama</label>
                            <input type="text" wire:model="anggota.{{ $i }}.nama" class="ktp-input">
                            @error("anggota.$i.nama") <span class="ktp-error">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="ktp-field-label">Tipe Dokumen</label>
                            <select wire:model="anggota.{{ $i }}.ktp_type" class="ktp-select">
                                <option value="ktp">KTP</option>
                                <option value="paspor">Paspor</option>
                            </select>
                        </div>

                        @if ($item['ktp_type'] === 'paspor')
                            <div>
                                <label class="ktp-field-label">No. Paspor</label>
                                <input type="text" wire:model="anggota.{{ $i }}.paspor_number" class="ktp-input mono">
                                @error("anggota.$i.paspor_number") <span class="ktp-error">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div>
                                <label class="ktp-field-label">NIK</label>
                                <input type="text" wire:model="anggota.{{ $i }}.nik" class="ktp-input mono">
                                @error("anggota.$i.nik") <span class="ktp-error">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                            <div>
                                <label class="ktp-field-label">Tgl Lahir</label>
                                <input type="text" wire:model="anggota.{{ $i }}.tgl_lahir" placeholder="DD-MM-YYYY" class="ktp-input">
                            </div>
                            <div>
                                <label class="ktp-field-label">Usia</label>
                                <input type="number" wire:model="anggota.{{ $i }}.usia" class="ktp-input">
                                @error("anggota.$i.usia") <span class="ktp-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="ktp-field-label">Jenis Kelamin</label>
                            <select wire:model="anggota.{{ $i }}.jenis_kelamin" class="ktp-select">
                                <option value="">—</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div style="display:flex;gap:8px;margin-top:6px;">
                            <button wire:click="save({{ $i }})" type="button" class="btn-save">✓ Simpan</button>
                            <button wire:click="cancel" type="button" class="btn-cancel">Batal</button>
                        </div>
                    </div>
                @endif

                {{-- FOTO --}}
                <div>
                    <p class="ktp-section-label">{{ $isPaspor ? '📷 Foto Paspor' : '📷 Foto KTP' }}</p>
                    @if ($fotoUrl)
                        <a href="{{ $fotoUrl }}" target="_blank">
                            <img src="{{ $fotoUrl }}" alt="{{ $isPaspor ? 'Paspor' : 'KTP' }}" class="ktp-photo" style="max-height:192px;">
                        </a>
                        <p class="ktp-photo-caption" style="margin-top:4px;">{{ basename($filePath) }} · Klik untuk buka fullsize</p>
                    @else
                        <p class="txt-muted" style="font-size:12px;font-style:italic;">File tidak ditemukan.</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>