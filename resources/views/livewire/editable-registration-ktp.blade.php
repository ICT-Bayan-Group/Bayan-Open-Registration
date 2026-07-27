<div style="display:grid;grid-template-columns:1fr;gap:24px;">

    @if (empty($anggota))
        <p style="color:#6b7280;font-size:14px;">Belum ada data pemain.</p>
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
                $cardBorder = '#2563eb'; $headerBg = '#eff6ff'; $badgeBg = '#dbeafe';
            } elseif ($isPaspor) {
                $cardBorder = '#c4b5fd'; $headerBg = '#f5f3ff'; $badgeBg = '#ede9fe';
            } elseif ($isVeteran && $nilUsia !== null) {
                $cardBorder = $nilUsia >= 45 ? '#86efac' : '#fca5a5';
                $headerBg   = $nilUsia >= 45 ? '#f0fdf4'  : '#fef2f2';
                $badgeBg    = $nilUsia >= 45 ? '#dcfce7'  : '#fee2e2';
            } else {
                $cardBorder = '#d1d5db'; $headerBg = '#f9fafb'; $badgeBg = '#f3f4f6';
            }

            $ktpFiles    = $record->ktp_files ?? [];
            $pasporFiles = $record->paspor_files ?? [];
            $files       = $isPaspor ? $pasporFiles : $ktpFiles;
            $filePath    = $files[$i] ?? (count($files) === 1 ? reset($files) : null);
            $fotoUrl     = $filePath
                ? route($isPaspor ? 'admin.paspor.serve' : 'admin.ktp.serve', ['uuid' => $record->uuid, 'filename' => basename($filePath)])
                : null;
        @endphp

        <div style="border-radius:12px;border:1.5px solid {{ $cardBorder }};overflow:hidden;background:#fff;" wire:key="reganggota-{{ $i }}">

            {{-- HEADER --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1.5px solid {{ $cardBorder }};background:{{ $headerBg }};">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:28px;height:28px;border-radius:50%;background:{{ $badgeBg }};display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#374151;border:1px solid {{ $cardBorder }};">{{ $i + 1 }}</div>
                    <span style="font-weight:600;font-size:14px;color:#111827;">{{ $item['nama'] }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    @if (!$isEditing)
                        <span style="{{ $isPaspor ? 'background:#ede9fe;color:#6d28d9;border-color:#c4b5fd;' : 'background:#fff7ed;color:#c2410c;border-color:#fed7aa;' }}display:inline-flex;align-items:center;gap:4px;padding:2px 10px;border-radius:99px;font-size:10px;font-weight:700;border:1px solid;">
                            {{ $isPaspor ? '🛂 PASPOR' : '🪪 KTP' }}
                        </span>
                        <button wire:click="edit({{ $i }})" type="button" title="Koreksi data" style="border:none;background:#e5e7eb;color:#374151;border-radius:8px;padding:6px 10px;font-size:12px;font-weight:600;cursor:pointer;">
                            ✏ Koreksi
                        </button>
                    @else
                        <span style="font-size:11px;font-weight:700;color:#2563eb;">Mode Edit</span>
                    @endif
                </div>
            </div>

            <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:24px;">

                @if (!$isEditing)
                    {{-- ── READ MODE ── --}}
                    <div>
                        <p style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">{{ $isPaspor ? '📋 Data Paspor' : '📋 Data KTP' }}</p>

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
                                <x-registration-ktp-row label="Usia" :value="$usiaLabel" :color="$ok ? '#15803d' : '#dc2626'" weight="bold" />
                            @else
                                <x-registration-ktp-row label="Usia" :value="$nilUsia . ' tahun'" />
                            @endif
                        @endif

                        @if ($item['jenis_kelamin'])
                            @php
                                $genderLabel = $item['jenis_kelamin'] === 'L' ? '♂ Laki-laki' : ($item['jenis_kelamin'] === 'P' ? '♀ Perempuan' : $item['jenis_kelamin']);
                                $genderColor = $item['jenis_kelamin'] === 'L' ? '#1d4ed8' : '#be185d';
                            @endphp
                            <x-registration-ktp-row label="Kelamin" :value="$genderLabel" :color="$genderColor" weight="bold" />
                        @endif
                    </div>
                @else
                    {{-- ── EDIT MODE ── --}}
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <div>
                            <label style="font-size:10px;font-weight:700;text-transform:uppercase;color:#6b7280;">Nama</label>
                            <input type="text" wire:model="anggota.{{ $i }}.nama" style="width:100%;margin-top:2px;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                            @error("anggota.$i.nama") <span style="color:#dc2626;font-size:11px;">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label style="font-size:10px;font-weight:700;text-transform:uppercase;color:#6b7280;">Tipe Dokumen</label>
                            <select wire:model="anggota.{{ $i }}.ktp_type" style="width:100%;margin-top:2px;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                                <option value="ktp">KTP</option>
                                <option value="paspor">Paspor</option>
                            </select>
                        </div>

                        @if ($item['ktp_type'] === 'paspor')
                            <div>
                                <label style="font-size:10px;font-weight:700;text-transform:uppercase;color:#6b7280;">No. Paspor</label>
                                <input type="text" wire:model="anggota.{{ $i }}.paspor_number" style="width:100%;margin-top:2px;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;font-family:monospace;">
                                @error("anggota.$i.paspor_number") <span style="color:#dc2626;font-size:11px;">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div>
                                <label style="font-size:10px;font-weight:700;text-transform:uppercase;color:#6b7280;">NIK</label>
                                <input type="text" wire:model="anggota.{{ $i }}.nik" style="width:100%;margin-top:2px;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;font-family:monospace;">
                                @error("anggota.$i.nik") <span style="color:#dc2626;font-size:11px;">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                            <div>
                                <label style="font-size:10px;font-weight:700;text-transform:uppercase;color:#6b7280;">Tgl Lahir</label>
                                <input type="text" wire:model="anggota.{{ $i }}.tgl_lahir" placeholder="DD-MM-YYYY" style="width:100%;margin-top:2px;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                            </div>
                            <div>
                                <label style="font-size:10px;font-weight:700;text-transform:uppercase;color:#6b7280;">Usia</label>
                                <input type="number" wire:model="anggota.{{ $i }}.usia" style="width:100%;margin-top:2px;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                                @error("anggota.$i.usia") <span style="color:#dc2626;font-size:11px;">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label style="font-size:10px;font-weight:700;text-transform:uppercase;color:#6b7280;">Jenis Kelamin</label>
                            <select wire:model="anggota.{{ $i }}.jenis_kelamin" style="width:100%;margin-top:2px;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                                <option value="">—</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div style="display:flex;gap:8px;margin-top:6px;">
                            <button wire:click="save({{ $i }})" type="button" style="flex:1;background:#059669;color:#fff;border:none;border-radius:8px;padding:8px 12px;font-size:12px;font-weight:700;cursor:pointer;">
                                ✓ Simpan
                            </button>
                            <button wire:click="cancel" type="button" style="flex:1;background:#e5e7eb;color:#374151;border:none;border-radius:8px;padding:8px 12px;font-size:12px;font-weight:700;cursor:pointer;">
                                Batal
                            </button>
                        </div>
                    </div>
                @endif

                {{-- FOTO --}}
                <div>
                    <p style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">{{ $isPaspor ? '📷 Foto Paspor' : '📷 Foto KTP' }}</p>
                    @if ($fotoUrl)
                        <a href="{{ $fotoUrl }}" target="_blank">
                            <img src="{{ $fotoUrl }}" alt="{{ $isPaspor ? 'Paspor' : 'KTP' }}" style="width:100%;max-height:192px;object-fit:contain;border-radius:8px;border:1px solid #d1d5db;background:#f9fafb;cursor:pointer;">
                        </a>
                        <p style="font-size:11px;color:#6b7280;margin-top:4px;">{{ basename($filePath) }} · Klik untuk buka fullsize</p>
                    @else
                        <p style="font-size:12px;color:#6b7280;font-style:italic;">File tidak ditemukan.</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>