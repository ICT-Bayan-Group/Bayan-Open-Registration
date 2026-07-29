<div style="display:flex;flex-direction:column;gap:16px;">

    @if (empty($anggota))
        <p class="txt-muted" style="font-size:14px;">Belum ada data pemain.</p>
    @endif

    @foreach ($anggota as $i => $item)
        @php
            $isPaspor  = $item['ktp_type'] === 'paspor';
            $ok        = $item['valid'];
            $isEditing = $editingIndex === $i;

            $stateClass = $isEditing ? 'ktp-state-edit' : ($ok ? 'ktp-state-valid' : 'ktp-state-invalid');
            $cityClass  = $ok ? 'badge-city-ok' : 'badge-city-bad';
            $cityLbl    = $ok ? '✓ Balikpapan' : ('✗ ' . ($item['city_raw'] ?: 'Kota tidak terbaca'));

            $ktpFiles    = $record->ktp_files ?? [];
            $pasporFiles = $record->paspor_files ?? [];
            $filePath    = $isPaspor ? ($pasporFiles[$i] ?? null) : ($ktpFiles[$i] ?? null);
            $fotoUrl     = $filePath
                ? route($isPaspor ? 'admin.paspor.serve' : 'admin.ktp.serve', ['uuid' => $record->uuid, 'filename' => basename($filePath)])
                : null;
        @endphp

        <div class="ktp-card {{ $stateClass }}" wire:key="anggota-{{ $i }}">

            {{-- HEADER --}}
            <div class="ktp-header">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="ktp-avatar">{{ $i + 1 }}</div>
                    <span class="ktp-name">{{ $item['nama'] }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    @if (!$isEditing)
                        <span class="{{ $isPaspor ? 'badge-paspor' : 'badge-ktp' }}">
                            {{ $isPaspor ? '🛂 Paspor' : '🪪 KTP' }}
                        </span>
                        @if (!$isPaspor)
                            <span class="{{ $cityClass }}">{{ $cityLbl }}</span>
                        @endif
                        <button wire:click="edit({{ $i }})" type="button" title="Koreksi data" class="btn-edit-small">
                            ✏ Koreksi
                        </button>
                    @else
                        <span class="ktp-edit-mode-label">Mode Edit</span>
                    @endif
                </div>
            </div>

            {{-- BODY --}}
            <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                @if (!$isEditing)
                    {{-- ── READ MODE ── --}}
                    <div style="display:flex;flex-direction:column;gap:4px;">
                        <div class="ktp-row">
                            <span class="ktp-row-label">NIK</span>
                            <span class="ktp-row-value mono">{{ $item['nik'] ?: '—' }}</span>
                        </div>
                        <div class="ktp-row">
                            <span class="ktp-row-label">Nama</span>
                            <span class="ktp-row-value">{{ $item['nama'] }}</span>
                        </div>
                        <div class="ktp-row">
                            <span class="ktp-row-label">Tgl Lahir</span>
                            <span class="ktp-row-value">{{ $item['tgl_lahir'] ?: '—' }}</span>
                        </div>
                        <div class="ktp-row">
                            <span class="ktp-row-label">Usia</span>
                            <span class="ktp-row-value">{{ $item['usia'] !== '' ? $item['usia'] . ' tahun' : '—' }}</span>
                        </div>
                        @if (!$isPaspor)
                            <div class="ktp-row" style="border-bottom:none;">
                                <span class="ktp-row-label">Kota KTP</span>
                                <span class="ktp-row-value {{ $ok ? 'txt-ok' : 'txt-bad' }}">{{ $cityLbl }}</span>
                            </div>
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
                            <label class="ktp-field-label">NIK</label>
                            <input type="text" wire:model="anggota.{{ $i }}.nik" class="ktp-input mono">
                            @error("anggota.$i.nik") <span class="ktp-error">{{ $message }}</span> @enderror
                        </div>
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
                            <label class="ktp-field-label">Tipe Dokumen</label>
                            <select wire:model="anggota.{{ $i }}.ktp_type" class="ktp-select">
                                <option value="ktp">KTP</option>
                                <option value="paspor">Paspor</option>
                            </select>
                        </div>
                        @if ($item['ktp_type'] === 'ktp')
                            <div>
                                <label class="ktp-field-label">Kota (hasil baca KTP)</label>
                                <input type="text" wire:model="anggota.{{ $i }}.city_raw" class="ktp-input">
                            </div>
                            <label class="ktp-checkbox-label">
                                <input type="checkbox" wire:model="anggota.{{ $i }}.valid" style="width:16px;height:16px;">
                                Valid KTP Balikpapan?
                            </label>
                        @endif

                        <div style="display:flex;gap:8px;margin-top:6px;">
                            <button wire:click="save({{ $i }})" type="button" class="btn-save">✓ Simpan</button>
                            <button wire:click="cancel" type="button" class="btn-cancel">Batal</button>
                        </div>
                    </div>
                @endif

                {{-- FOTO (selalu tampil, baik read maupun edit mode) --}}
                <div>
                    @if ($fotoUrl)
                        <a href="{{ $fotoUrl }}" target="_blank">
                            <img src="{{ $fotoUrl }}" alt="{{ $isPaspor ? 'Paspor' : 'KTP' }}" class="ktp-photo">
                        </a>
                    @else
                        <div class="ktp-photo-empty">
                            <p>{{ $isPaspor ? '🛂 Foto paspor tidak tersedia' : '🪪 Foto KTP tidak tersedia' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>