<div style="display:flex;flex-direction:column;gap:16px;">

    @if (empty($anggota))
        <p style="color:#6b7280;font-size:14px;">Belum ada data pemain.</p>
    @endif

    @foreach ($anggota as $i => $item)
        @php
            $isPaspor = $item['ktp_type'] === 'paspor';
            $ok       = $item['valid'];
            $isEditing = $editingIndex === $i;

            $borderC  = $isEditing ? '#2563eb' : ($ok ? '#059669' : '#dc2626');
            $headerBg = $isEditing ? '#eff6ff' : ($ok ? '#f0fdf4' : '#fef2f2');
            $badgeBg  = $ok ? '#dcfce7' : '#fee2e2';
            $badgeBr  = $ok ? '#86efac' : '#fca5a5';
            $badgeTc  = $ok ? '#15803d' : '#b91c1c';
            $cityLbl  = $ok ? '✓ Balikpapan' : ('✗ ' . ($item['city_raw'] ?: 'Kota tidak terbaca'));

            $ktpFiles    = $record->ktp_files ?? [];
            $pasporFiles = $record->paspor_files ?? [];
            $filePath    = $isPaspor ? ($pasporFiles[$i] ?? null) : ($ktpFiles[$i] ?? null);
            $fotoUrl     = $filePath
                ? route($isPaspor ? 'admin.paspor.serve' : 'admin.ktp.serve', ['uuid' => $record->uuid, 'filename' => basename($filePath)])
                : null;
        @endphp

        <div style="border:1.5px solid {{ $borderC }};border-radius:12px;overflow:hidden;background:#fff;" wire:key="anggota-{{ $i }}">

            {{-- HEADER --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:{{ $headerBg }};border-bottom:1.5px solid {{ $borderC }};">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:28px;height:28px;border-radius:50%;background:#e0e7ff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#4338ca;">{{ $i + 1 }}</div>
                    <span style="font-weight:700;font-size:14px;color:#111827;">{{ $item['nama'] }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    @if (!$isEditing)
                        <span style="{{ $isPaspor ? 'color:#8b5cf6;background:#f3e8ff;border-color:#c4b5fd;' : 'color:#f97316;background:#fed7aa;border-color:#fdba74;' }}font-size:11px;font-weight:700;border:1px solid;border-radius:99px;padding:4px 12px;">
                            {{ $isPaspor ? '🛂 Paspor' : '🪪 KTP' }}
                        </span>
                        @if (!$isPaspor)
                            <span style="font-size:11px;font-weight:700;color:{{ $badgeTc }};background:{{ $badgeBg }};border:1px solid {{ $badgeBr }};border-radius:99px;padding:4px 12px;">{{ $cityLbl }}</span>
                        @endif
                        <button wire:click="edit({{ $i }})" type="button" title="Koreksi data" style="border:none;background:#e5e7eb;color:#374151;border-radius:8px;padding:6px 10px;font-size:12px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:4px;">
                            ✏ Koreksi
                        </button>
                    @else
                        <span style="font-size:11px;font-weight:700;color:#2563eb;">Mode Edit</span>
                    @endif
                </div>
            </div>

            {{-- BODY --}}
            <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;background:#fff;">

                @if (!$isEditing)
                    {{-- ── READ MODE ── --}}
                    <div style="display:flex;flex-direction:column;gap:4px;">
                        <div style="display:flex;gap:8px;padding:6px 0;border-bottom:1px solid #f3f4f6;">
                            <span style="font-size:10px;font-weight:700;text-transform:uppercase;color:#9ca3af;min-width:76px;flex-shrink:0;">NIK</span>
                            <span style="font-size:12px;font-family:monospace;font-weight:700;color:#374151;">{{ $item['nik'] ?: '—' }}</span>
                        </div>
                        <div style="display:flex;gap:8px;padding:6px 0;border-bottom:1px solid #f3f4f6;">
                            <span style="font-size:10px;font-weight:700;text-transform:uppercase;color:#9ca3af;min-width:76px;flex-shrink:0;">Nama</span>
                            <span style="font-size:12px;font-weight:600;color:#374151;">{{ $item['nama'] }}</span>
                        </div>
                        <div style="display:flex;gap:8px;padding:6px 0;border-bottom:1px solid #f3f4f6;">
                            <span style="font-size:10px;font-weight:700;text-transform:uppercase;color:#9ca3af;min-width:76px;flex-shrink:0;">Tgl Lahir</span>
                            <span style="font-size:12px;font-weight:600;color:#374151;">{{ $item['tgl_lahir'] ?: '—' }}</span>
                        </div>
                        <div style="display:flex;gap:8px;padding:6px 0;border-bottom:1px solid #f3f4f6;">
                            <span style="font-size:10px;font-weight:700;text-transform:uppercase;color:#9ca3af;min-width:76px;flex-shrink:0;">Usia</span>
                            <span style="font-size:12px;font-weight:600;color:#374151;">{{ $item['usia'] !== '' ? $item['usia'] . ' tahun' : '—' }}</span>
                        </div>
                        @if (!$isPaspor)
                            <div style="display:flex;gap:8px;padding:6px 0;">
                                <span style="font-size:10px;font-weight:700;text-transform:uppercase;color:#9ca3af;min-width:76px;flex-shrink:0;">Kota KTP</span>
                                <span style="font-size:12px;font-weight:600;color:{{ $badgeTc }};">{{ $cityLbl }}</span>
                            </div>
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
                            <label style="font-size:10px;font-weight:700;text-transform:uppercase;color:#6b7280;">NIK</label>
                            <input type="text" wire:model="anggota.{{ $i }}.nik" style="width:100%;margin-top:2px;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;font-family:monospace;">
                            @error("anggota.$i.nik") <span style="color:#dc2626;font-size:11px;">{{ $message }}</span> @enderror
                        </div>
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
                            <label style="font-size:10px;font-weight:700;text-transform:uppercase;color:#6b7280;">Tipe Dokumen</label>
                            <select wire:model="anggota.{{ $i }}.ktp_type" style="width:100%;margin-top:2px;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                                <option value="ktp">KTP</option>
                                <option value="paspor">Paspor</option>
                            </select>
                        </div>
                        @if ($item['ktp_type'] === 'ktp')
                            <div>
                                <label style="font-size:10px;font-weight:700;text-transform:uppercase;color:#6b7280;">Kota (hasil baca KTP)</label>
                                <input type="text" wire:model="anggota.{{ $i }}.city_raw" style="width:100%;margin-top:2px;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;">
                            </div>
                            <label style="display:flex;align-items:center;gap:8px;font-size:12px;font-weight:600;color:#374151;">
                                <input type="checkbox" wire:model="anggota.{{ $i }}.valid" style="width:16px;height:16px;">
                                Valid KTP Balikpapan?
                            </label>
                        @endif

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

                {{-- FOTO (selalu tampil, baik read maupun edit mode) --}}
                <div>
                    @if ($fotoUrl)
                        <a href="{{ $fotoUrl }}" target="_blank">
                            <img src="{{ $fotoUrl }}" alt="{{ $isPaspor ? 'Paspor' : 'KTP' }}" style="width:100%;max-height:180px;object-fit:contain;border-radius:8px;border:1px solid #e5e7eb;background:#f9fafb;cursor:pointer;">
                        </a>
                    @else
                        <div style="height:100px;display:flex;align-items:center;justify-content:center;border:1px dashed #d1d5db;border-radius:8px;background:#f9fafb;">
                            <p style="color:#9ca3af;font-size:12px;font-style:italic;">{{ $isPaspor ? '🛂 Foto paspor tidak tersedia' : '🪪 Foto KTP tidak tersedia' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>