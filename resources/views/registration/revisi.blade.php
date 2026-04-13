@extends('layouts.app')

@section('title', 'Perbaiki Data Pendaftaran — Bayan Open 2026')

@push('styles')
<style>
    /* ── Animations ── */
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes shimmerScan {
        0%   { background-position: -200% center; }
        100% { background-position:  200% center; }
    }
    @keyframes pulseAmber {
        0%, 100% { box-shadow: 0 0 0 0 rgba(245,158,11,.4); }
        50%       { box-shadow: 0 0 0 8px rgba(245,158,11,0); }
    }
    @keyframes spinLoader { to { transform: rotate(360deg); } }
    @keyframes sheetIn {
        from { transform: translateY(100%); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }
    @keyframes sheetOut {
        from { transform: translateY(0);    opacity: 1; }
        to   { transform: translateY(100%); opacity: 0; }
    }
    @keyframes backdropIn  { from { opacity:0; } to { opacity:1; } }
    @keyframes backdropOut { from { opacity:1; } to { opacity:0; } }
    @keyframes shake {
        0%,100% { transform: translateX(0); }
        20%     { transform: translateX(-6px); }
        40%     { transform: translateX(6px); }
        60%     { transform: translateX(-4px); }
        80%     { transform: translateX(4px); }
    }

    .form-section              { animation: fadeSlideUp .45s ease both; }
    .form-section:nth-child(1) { animation-delay: .04s; }
    .form-section:nth-child(2) { animation-delay: .10s; }
    .form-section:nth-child(3) { animation-delay: .16s; }
    .form-section:nth-child(4) { animation-delay: .22s; }

    /* ── Input fields ── */
    .input-field {
        background: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.15) !important;
        color: #2c2c2a !important;
        border-radius: 12px;
        transition: border-color .2s, box-shadow .2s;
        width: 100%; padding: 12px 16px; font-size: 14px;
    }
    .input-field:focus {
        outline: none;
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 3px rgba(245,158,11,.12) !important;
    }
    .input-field::placeholder { color: #b4b2a9 !important; }
    .field-label {
        display: block; font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: #888780; margin-bottom: 6px;
    }

    /* ── Admin notes banner ── */
    .admin-notes-banner {
        background: linear-gradient(135deg,rgba(188,126,2,.97),rgba(217,119,6,.89));
        border: 1.5px solid rgba(245,158,11,.25);
        border-left: 4px solid #f59e0b;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 28px;
    }

    /* ── Member card ── */
    .member-card {
        background: #ffffff; border-radius: 14px;
        border: 0.5px solid rgba(0,0,0,.1);
        padding: 16px; box-shadow: 0 1px 4px rgba(0,0,0,.06);
        transition: border-color .3s, background .3s;
    }
    .member-card.scanned      { border-color: rgba(16,185,129,.45); }
    .member-card.city-valid   { border-color: rgba(16,185,129,.4); }
    .member-card.city-invalid { border-color: rgba(239,68,68,.35); }
    .member-card.gender-error {
        border-color: rgba(239,68,68,.55) !important;
        background: rgba(239,68,68,.04) !important;
        animation: shake .4s ease;
    }

    #memberSlots {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    @media (max-width: 600px) { #memberSlots { grid-template-columns: 1fr; } }

    /* ── KTP Dropzone ── */
    .ktp-dropzone {
        border: 1.5px dashed rgba(245,158,11,.3); border-radius: 10px;
        background: rgba(245,158,11,.03); cursor: pointer;
        transition: border-color .2s, background .2s;
        min-height: 64px; display: flex; align-items: center; justify-content: center;
    }
    .ktp-dropzone:hover, .ktp-dropzone.drag-over {
        border-color: rgba(245,158,11,.6); background: rgba(245,158,11,.07);
    }

    /* ── KTP Data Card ── */
    .ktp-data-card {
        border-radius: 10px; background: #f8f7f4;
        border: 0.5px solid rgba(0,0,0,.08);
        padding: 10px 12px; margin-top: 10px; display: none;
    }
    .ktp-data-card.show       { display: block; animation: fadeSlideUp .3s ease both; }
    .ktp-data-card.valid-card { background: rgba(245,158,11,.03); border-color: rgba(245,158,11,.2); }

    /* ── KTP existing preview (old style) ── */
    .ktp-existing-preview {
        border-radius: 8px; overflow: hidden;
        border: 1px solid rgba(0,0,0,.08);
        background: #f9f9f9; position: relative;
    }
    .ktp-existing-preview img {
        width: 100%; max-height: 120px; object-fit: cover; display: block;
    }
    .ktp-existing-label {
        position: absolute; top: 4px; left: 4px;
        background: rgba(0,0,0,.6); color: #fff;
        font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 4px;
        letter-spacing: .04em;
    }

    /* ── KTP Row compact ── */
    .ktp-row {
        display: flex; align-items: center; gap: 6px;
        padding: 3px 0; border-bottom: 1px solid rgba(0,0,0,.05); min-height: 26px;
    }
    .ktp-row:last-child { border-bottom: none; padding-bottom: 0; }
    .ktp-label {
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: #888780;
        min-width: 58px; flex-shrink: 0;
    }
    .ktp-value {
        flex: 1; font-size: 11px; color: #444441; line-height: 1.3;
        word-break: break-word; padding: 2px 6px; border-radius: 5px;
        border: 1px solid transparent; cursor: pointer; min-width: 0;
        transition: background .15s, border-color .15s;
        font-weight: 600;
    }
    .ktp-value:hover {
        background: rgba(245,158,11,.08); border-color: rgba(245,158,11,.28); color: #1a1a1a;
    }
    .ktp-value:hover::after { content: ' ✏'; font-size: 8px; opacity: .45; }

    /* ── Inline input ── */
    .ktp-inline-input {
        flex: 1; background: #fff;
        border: 1.5px solid rgba(245,158,11,.5); border-radius: 5px;
        color: #2c2c2a; font-size: 11px; font-weight: 600;
        padding: 2px 7px; outline: none; min-width: 0;
        transition: border-color .15s, box-shadow .15s;
    }
    .ktp-inline-input:focus { border-color: rgba(245,158,11,.9); box-shadow: 0 0 0 2px rgba(245,158,11,.14); }
    .ktp-inline-input.was-edited { border-color: rgba(234,179,8,.65); background: rgba(234,179,8,.05); }

    /* ── Usia display ── */
    .usia-display {
        flex: 1; font-size: 11px; font-weight: 700; padding: 2px 6px;
        border-radius: 5px; cursor: default; transition: color .25s, background .25s; min-width: 0;
    }
    .usia-display.has-value { color: #059669; background: rgba(16,185,129,.08); border: 1px solid rgba(16,185,129,.2); }
    .usia-display.no-value  { color: #b4b2a9; font-style: italic; font-weight: 400; background: transparent; border: 1px solid transparent; }

    /* ── City badge ── */
    .city-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 8px; border-radius: 5px; font-size: 10px; font-weight: 700;
        margin-top: 6px;
    }
    .city-badge.valid   { background: rgba(16,185,129,.08); border: 1px solid rgba(16,185,129,.25); color: #059669; }
    .city-badge.invalid { background: rgba(239,68,68,.08);  border: 1px solid rgba(239,68,68,.25);  color: #dc2626; }
    .city-badge.empty   { background: rgba(0,0,0,.04);      border: 1px solid rgba(0,0,0,.08);      color: #888780; }

    /* ── Scan badge ── */
    .scan-badge {
        display: none; align-items: center; gap: 3px;
        padding: 1px 6px; border-radius: 99px; font-size: 9px; font-weight: 700;
        background: rgba(16,185,129,.1); border: 0.5px solid rgba(16,185,129,.3); color: #059669;
    }

    /* ── Scan loading bar ── */
    .scan-loading-bar { height: 2px; border-radius: 99px; overflow: hidden; background: rgba(245,158,11,.1); margin-top: 6px; }
    .scan-loading-bar-inner {
        height: 100%; width: 40%;
        background: linear-gradient(90deg, transparent, #f59e0b, transparent);
        background-size: 200% 100%; animation: shimmerScan 1.2s ease infinite;
    }

    /* ── Scan Semua button ── */
    #scanAllBtn {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 10px 16px; border-radius: 10px; border: none; cursor: pointer;
        font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #ffffff;
        transition: opacity .2s, transform .1s;
        margin-bottom: 14px;
    }
    #scanAllBtn:hover  { opacity: .9; }
    #scanAllBtn:active { transform: scale(.98); }
    #scanAllBtn:disabled { opacity: .45; cursor: not-allowed; transform: none; }

    /* ── Submit btn ── */
    #submitBtn { transition: background .3s, box-shadow .3s, opacity .2s; }
    #submitBtn.btn-ready {
        background: linear-gradient(135deg,#f59e0b,#d97706);
        animation: pulseAmber 2.5s ease infinite;
    }

    /* ── Progress counter ── */
    .progress-bar-fill { transition: width .5s ease, background .4s; }

    /* Error banner */
    #errorBanner { display:none; border-radius:14px; padding:14px 18px; margin-bottom:20px;
        background:rgba(239,68,68,.06); border:1.5px solid rgba(239,68,68,.25); }
    #errorBanner.show { display:block; animation:fadeSlideUp .3s ease; }

    /* Submit overlay */
    #submitOverlay {
        display:none; position:fixed; inset:0; z-index:9999;
        background:rgba(0,0,0,.6); backdrop-filter:blur(4px);
        align-items:center; justify-content:center; flex-direction:column; gap:16px;
    }
    #submitOverlay.show { display:flex; }

    /* ── Upload Bottom Sheet ── */
    .upload-sheet-backdrop {
        display: none; position: fixed; inset: 0; z-index: 88888;
        background: rgba(0,0,0,.45); backdrop-filter: blur(3px);
    }
    .upload-sheet-backdrop.show   { display: block; animation: backdropIn .2s ease both; }
    .upload-sheet-backdrop.hiding { animation: backdropOut .2s ease both; }

    .upload-sheet {
        display: none; position: fixed; bottom: 0; left: 0; right: 0; z-index: 88889;
        background: #ffffff; border-top: 1.5px solid rgba(245,158,11,.22);
        border-radius: 22px 22px 0 0; padding: 0 0 calc(env(safe-area-inset-bottom,0px) + 16px);
        box-shadow: 0 -20px 60px rgba(0,0,0,.15); max-width: 540px; margin: 0 auto;
    }
    .upload-sheet.show   { display: block; animation: sheetIn .28s cubic-bezier(.34,1.3,.64,1) both; }
    .upload-sheet.hiding { animation: sheetOut .2s ease both; }

    .upload-sheet-handle { width: 40px; height: 4px; border-radius: 99px; background: rgba(0,0,0,.12); margin: 12px auto 18px; }
    .upload-sheet-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: #888780; text-align: center; margin-bottom: 16px; }
    .upload-sheet-options { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; padding: 0 16px; }

    .upload-opt-btn {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 8px; padding: 16px 8px; border-radius: 16px; border: 1.5px solid; cursor: pointer;
        font-size: 11px; font-weight: 700; line-height: 1.3; text-align: center;
        transition: background .15s, border-color .15s, transform .1s;
    }
    .upload-opt-btn:active { transform: scale(.95); }
    .upload-opt-icon { width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; }

    .upload-opt-btn.opt-camera { color: #d97706; background: rgba(245,158,11,.08); border-color: rgba(245,158,11,.3); }
    .upload-opt-btn.opt-camera:hover { background: rgba(245,158,11,.15); border-color: rgba(245,158,11,.55); }
    .upload-opt-btn.opt-camera .upload-opt-icon { background: rgba(245,158,11,.12); }

    .upload-opt-btn.opt-foto { color: #b45309; background: rgba(245,158,11,.06); border-color: rgba(245,158,11,.22); }
    .upload-opt-btn.opt-foto:hover { background: rgba(245,158,11,.12); border-color: rgba(245,158,11,.45); }
    .upload-opt-btn.opt-foto .upload-opt-icon { background: rgba(245,158,11,.1); }

    .upload-opt-btn.opt-file { color: #92400e; background: rgba(245,158,11,.04); border-color: rgba(245,158,11,.18); }
    .upload-opt-btn.opt-file:hover { background: rgba(245,158,11,.1); border-color: rgba(245,158,11,.35); }
    .upload-opt-btn.opt-file .upload-opt-icon { background: rgba(245,158,11,.08); }

    .upload-sheet-cancel {
        display: block; width: calc(100% - 32px); margin: 14px 16px 0; padding: 13px;
        border-radius: 14px; border: 1px solid rgba(0,0,0,.1); background: #f5f4f0;
        color: #888780; font-size: 13px; font-weight: 700;
        text-align: center; cursor: pointer; transition: background .15s, color .15s;
    }
    .upload-sheet-cancel:hover { background: #e8e6e1; color: #444441; }
</style>
@endpush

@section('content')
<section class="min-h-screen py-16 px-4">
<div class="max-w-3xl mx-auto">

    {{-- ── HEADER ── --}}
    <div class="text-center mb-10 form-section">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-4"
             style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);">
            <span class="text-yellow-400 text-xs font-bold uppercase tracking-widest">✏ Perbaiki Data Pendaftaran</span>
        </div>
        <h1 class="font-display text-3xl font-bold mb-2">Revisi Pendaftaran</h1>
        <p class="text-white/40 text-sm">
            Tim <strong class="text-white/70">{{ $registration->tim_pb }}</strong> ·
            Revisi ke-<strong class="text-white/70">{{ $registration->revision_count }}</strong>
        </p>
        <p class="text-white/30 text-xs mt-1">
            Link aktif hingga
            <span class="text-yellow-500/70">{{ $registration->revision_token_expires_at?->format('d M Y, H:i') }} WIB</span>
        </p>
    </div>

    {{-- ── CATATAN ADMIN ── --}}
    <div class="admin-notes-banner form-section">
        <div class="flex items-start gap-3">
            <span class="text-2xl flex-shrink-0">💬</span>
            <div>
                <p class="font-bold text-yellow-400 text-sm mb-2">Catatan dari Admin</p>
                <p class="text-white/65 text-sm leading-relaxed whitespace-pre-line">{{ $registration->revision_notes }}</p>
            </div>
        </div>
    </div>

    {{-- Error Banner --}}
    <div id="errorBanner">
        <p class="text-red-400 text-sm font-bold mb-2">Harap perbaiki:</p>
        <ul id="errorList" class="text-red-400 text-xs space-y-1 list-disc list-inside"></ul>
    </div>

    <form id="revisiForm" novalidate>
        @csrf
        @method('PUT')

        {{-- ═══ SECTION 1 — DATA TIM ═══ --}}
        <div class="card-glass rounded-2xl p-8 mb-6 form-section">
            <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-yellow-500 flex items-center justify-center text-xs font-black text-white">1</span>
                Data Tim & Kontak
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="field-label">Nama Tim / PB <span class="text-yellow-400">*</span></label>
                    <input type="text" name="tim_pb" value="{{ $registration->tim_pb }}" class="input-field" required>
                </div>
                <div class="md:col-span-2">
                    <label class="field-label">Nama Ketua / PIC <span class="text-yellow-400">*</span></label>
                    <input type="text" name="nama" value="{{ $registration->nama }}" class="input-field" required>
                </div>
                <div>
                    <label class="field-label">Email <span class="text-yellow-400">*</span></label>
                    <input type="email" name="email" value="{{ $registration->email }}" class="input-field" required>
                </div>
                <div>
                    <label class="field-label">WhatsApp <span class="text-yellow-400">*</span></label>
                    <input type="text" name="no_hp" value="{{ $registration->no_hp }}" class="input-field" required>
                </div>
                <div>
                    <label class="field-label">Provinsi <span class="text-yellow-400">*</span></label>
                    <input type="text" name="provinsi" value="{{ $registration->provinsi }}" class="input-field" required>
                    <p class="text-white/25 text-xs mt-1">Tidak perlu diubah kecuali salah</p>
                </div>
                <div>
                    <label class="field-label">Kota / Kabupaten <span class="text-yellow-400">*</span></label>
                    <input type="text" name="kota" value="{{ $registration->kota }}" class="input-field" required>
                </div>
            </div>
        </div>

        {{-- ═══ SECTION 2 — PELATIH ═══ --}}
        <div class="card-glass rounded-2xl p-8 mb-6 form-section">
            <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-yellow-500 flex items-center justify-center text-xs font-black text-white">2</span>
                Data Pelatih
                <span class="text-white/40 text-xs font-normal">(opsional)</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="field-label">Nama Pelatih</label>
                    <input type="text" name="nama_pelatih" value="{{ $registration->nama_pelatih }}" class="input-field">
                </div>
                <div>
                    <label class="field-label">No. HP Pelatih</label>
                    <input type="text" name="no_hp_pelatih" value="{{ $registration->no_hp_pelatih }}" class="input-field">
                </div>
            </div>
        </div>

        {{-- ═══ SECTION 3 — ANGGOTA ═══ --}}
        <div class="card-glass rounded-2xl p-6 mb-6 form-section">
            <h2 class="font-display text-sm font-bold mb-2 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-yellow-500 flex items-center justify-center text-xs font-black text-white">3</span>
                Data Anggota Tim
            </h2>
            <p class="text-xs text-white/40 ml-8 mb-1">
                Upload KTP lalu klik <strong class="text-yellow-400">SCAN</strong> — data terisi otomatis & bisa diedit.
            </p>
            <p class="text-xs text-white/25 ml-8 mb-5">
                Data yang tidak diubah akan tetap menggunakan nilai lama.
            </p>

            {{-- Counter --}}
            <div class="mb-4 p-4 rounded-xl" style="background:#fff;border:0.5px solid rgba(0,0,0,.1);box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-semibold uppercase tracking-wide" style="color:#888780;">KTP Balikpapan Valid</span>
                    <span id="counterText" class="text-xs font-bold" style="color:#444441;">
                        {{ collect($registration->ktp_city_valid)->where('valid', true)->count() }} / {{ count($registration->pemain ?? []) }} valid
                    </span>
                </div>
                <div style="background:rgba(0,0,0,.08);border-radius:99px;height:5px;overflow:hidden;">
                    @php $validCount = collect($registration->ktp_city_valid)->where('valid', true)->count(); @endphp
                    <div id="counterFill" class="progress-bar-fill"
                         style="width:{{ min(100, round($validCount/6*100)) }}%;background:{{ $validCount >= 6 ? '#10b981' : '#f59e0b' }};height:100%;border-radius:99px;"></div>
                </div>
                <p id="counterNote" class="text-xs mt-1.5" style="color:{{ $validCount >= 6 ? '#059669' : '#f59e0b' }};">
                    @if($validCount >= 6) ✓ Syarat terpenuhi @else Butuh {{ 6 - $validCount }} KTP Balikpapan lagi @endif
                </p>
            </div>

            {{-- Scan All Button --}}
            <button type="button" id="scanAllBtn" onclick="REV.scanAll()" disabled>
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                </svg>
                <span id="scanAllText">SCAN SEMUA KTP</span>
            </button>

            <div id="memberSlots">
                @php
                    $pemain    = $registration->pemain         ?? [];
                    $nik       = $registration->nik            ?? [];
                    $tglLahir  = $registration->tgl_lahir      ?? [];
                    $kotaKtp   = $registration->kota_ktp       ?? [];
                    $ktpFiles  = $registration->ktp_files      ?? [];
                    $cityValid = $registration->ktp_city_valid ?? [];
                @endphp

                @foreach($pemain as $i => $nama)
                @php
                    $cv       = $cityValid[$i] ?? null;
                    $isValid  = $cv['valid'] ?? false;
                    $kotaRaw  = $cv['city_raw'] ?? ($kotaKtp[$i] ?? '');
                    $filePath = $ktpFiles[$i] ?? null;
                    $nikVal   = $nik[$i] ?? '';
                    $tglVal   = $tglLahir[$i] ?? '';
                @endphp
                <div class="member-card {{ $isValid ? 'city-valid' : 'city-invalid' }}" id="mc_{{ $i }}">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);">
                                <span class="font-black" style="font-size:9px;color:#f59e0b;">{{ $i+1 }}</span>
                            </div>
                            <span class="font-bold" style="font-size:11px;color:#444441;">Anggota {{ $i+1 }}</span>
                            <span id="scan_badge_{{ $i }}" class="scan-badge" style="display:none;">
                                <svg width="8" height="8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>Ter-scan
                            </span>
                        </div>
                        <span id="cityBadge_{{ $i }}" class="city-badge {{ $isValid ? 'valid' : 'invalid' }}">
                            {{ $isValid ? '✓ Balikpapan' : '✗ ' . ($kotaRaw ?: 'Perlu diupload ulang') }}
                        </span>
                    </div>

                    {{-- Dropzone / Preview --}}
                    <div id="dz_{{ $i }}" class="ktp-dropzone"
                         onclick="REV.showSheet({{ $i }})"
                         ondragover="event.preventDefault();this.classList.add('drag-over')"
                         ondragleave="this.classList.remove('drag-over')"
                         ondrop="REV.onDrop(event,{{ $i }})">

                        {{-- Existing KTP preview --}}
                        <div id="existingPrev_{{ $i }}" class="{{ $filePath ? '' : 'hidden' }} w-full flex items-center gap-2 px-3 py-2">
                            <div class="relative flex-shrink-0">
                                @if($filePath)
                                <img src="{{ route('registration.revision.ktp.preview', ['token' => $token, 'index' => $i]) }}"
                                     id="prevImg_{{ $i }}"
                                     alt="KTP {{ $nama }}"
                                     style="width:48px;height:36px;border-radius:6px;object-fit:cover;border:0.5px solid rgba(0,0,0,.1);"
                                     onerror="this.parentElement.parentElement.parentElement.classList.add('hidden')">
                                <span class="ktp-existing-label">KTP Lama</span>
                                @else
                                <img id="prevImg_{{ $i }}" src="" alt=""
                                     style="width:48px;height:36px;border-radius:6px;object-fit:cover;border:0.5px solid rgba(0,0,0,.1);">
                                @endif
                                <button type="button" onclick="REV.resetFile(event,{{ $i }})"
                                    class="absolute -top-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center"
                                    style="background:#ef4444;">
                                    <svg width="7" height="7" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <p style="font-size:10px;color:#b4b2a9;">Ketuk ganti</p>
                        </div>

                        {{-- Default dropzone prompt --}}
                        <div id="dzDefault_{{ $i }}" class="{{ $filePath ? 'hidden' : '' }} flex flex-col items-center py-2 gap-1">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(245,158,11,.45)" stroke-width="1.5">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <path d="M7 9h10M7 13h6"/>
                            </svg>
                            <p style="font-size:10px;color:#b4b2a9;">Ketuk upload KTP</p>
                        </div>
                    </div>

                    {{-- File inputs --}}
                    <input type="file" id="fiCam_{{ $i }}" accept="image/*" capture="environment" class="hidden" onchange="REV.onFileSelect(this,{{ $i }})">
                    <input type="file" id="fiFoto_{{ $i }}" accept="image/*" class="hidden" onchange="REV.onFileSelect(this,{{ $i }})">
                    <input type="file" id="fiFile_{{ $i }}" accept="image/*,.heic,.heif" class="hidden" onchange="REV.onFileSelect(this,{{ $i }})">
                    {{-- Keep original file reference for unchanged members --}}
                    <input type="hidden" id="keepFile_{{ $i }}" name="keep_ktp[{{ $i }}]" value="1">

                    {{-- Scan button --}}
                    <button type="button" id="scanBtn_{{ $i }}" onclick="REV.scan({{ $i }})"
                            class="{{ $filePath ? '' : 'hidden' }} mt-2 w-full flex items-center justify-center gap-1.5"
                            style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:6px 10px;font-size:10px;font-weight:800;border-radius:9px;border:none;cursor:pointer;letter-spacing:.06em;">
                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                        </svg>SCAN KTP
                    </button>

                    {{-- Scan loading --}}
                    <div id="scanLoading_{{ $i }}" class="hidden mt-2 text-center" style="padding:4px 0;">
                        <p style="font-size:10px;color:#f59e0b;font-weight:600;">Membaca KTP...</p>
                        <div class="scan-loading-bar"><div class="scan-loading-bar-inner"></div></div>
                    </div>

                    {{-- KTP Data Card (OCR result / existing data display) --}}
                    <div id="ktpDataCard_{{ $i }}" class="ktp-data-card {{ ($nikVal || $nama) ? 'show valid-card' : '' }}">
                        @if($nikVal || $nama)
                        <div id="ktpDataRows_{{ $i }}">
                            {{-- NIK --}}
                            <div class="ktp-row">
                                <span class="ktp-label">NIK <span style="color:#f59e0b">*</span></span>
                                <span id="kval_{{ $i }}_nik" class="ktp-value" title="Klik edit" onclick="REV.inlineEdit({{ $i }},'nik')">{{ $nikVal ?: '—' }}</span>
                                <input id="kinp_{{ $i }}_nik" type="text" class="ktp-inline-input" style="display:none"
                                       value="{{ $nikVal }}" placeholder="16 digit NIK"
                                       onkeydown="REV.inlineKey(event,{{ $i }},'nik')"
                                       onblur="REV.inlineSave({{ $i }},'nik')">
                                <input type="hidden" id="khid_{{ $i }}_nik" name="nik[{{ $i }}]" value="{{ $nikVal }}">
                            </div>
                            {{-- Nama --}}
                            <div class="ktp-row">
                                <span class="ktp-label">Nama <span style="color:#f59e0b">*</span></span>
                                <span id="kval_{{ $i }}_nama" class="ktp-value" title="Klik edit" onclick="REV.inlineEdit({{ $i }},'nama')">{{ $nama ?: '—' }}</span>
                                <input id="kinp_{{ $i }}_nama" type="text" class="ktp-inline-input" style="display:none"
                                       value="{{ $nama }}" placeholder="Nama sesuai KTP"
                                       onkeydown="REV.inlineKey(event,{{ $i }},'nama')"
                                       onblur="REV.inlineSave({{ $i }},'nama')">
                                <input type="hidden" id="khid_{{ $i }}_nama" name="pemain[{{ $i }}]" value="{{ $nama }}">
                            </div>
                            {{-- Tgl Lahir --}}
                            <div class="ktp-row">
                                <span class="ktp-label">Tgl Lhr <span style="color:#f59e0b">*</span></span>
                                <span id="kval_{{ $i }}_tanggal_lahir" class="ktp-value" title="Klik edit" onclick="REV.inlineEdit({{ $i }},'tanggal_lahir')">{{ $tglVal ?: '—' }}</span>
                                <input id="kinp_{{ $i }}_tanggal_lahir" type="text" class="ktp-inline-input" style="display:none"
                                       value="{{ $tglVal }}" placeholder="DD-MM-YYYY"
                                       onkeydown="REV.inlineKey(event,{{ $i }},'tanggal_lahir')"
                                       onblur="REV.inlineSave({{ $i }},'tanggal_lahir')">
                                <input type="hidden" id="khid_{{ $i }}_tanggal_lahir" name="tgl_lahir[{{ $i }}]" value="{{ $tglVal }}">
                            </div>
                            {{-- Kelamin (always L for beregu) --}}
                            <div class="ktp-row" style="margin-top:4px;">
                                <span class="ktp-label">Kelamin</span>
                                <span style="flex:1;font-size:11px;font-weight:700;padding:2px 8px;border-radius:5px;color:#1D4ED8;background:#EFF8FF;border:1px solid #BFDBFE;">♂ Laki-laki</span>
                                <span style="font-size:8px;color:#b4b2a9;flex-shrink:0;">dari KTP</span>
                                <input type="hidden" name="jenis_kelamin[{{ $i }}]" value="L">
                            </div>
                            {{-- Kota KTP hidden --}}
                            <input type="hidden" id="khid_{{ $i }}_kota" name="kota_ktp[{{ $i }}]" value="{{ $kotaRaw }}">
                            {{-- Usia --}}
                            <div class="ktp-row" style="margin-top:4px;padding-top:6px;border-top:1px solid rgba(245,158,11,.1);">
                                <span class="ktp-label" style="color:rgba(245,158,11,.6);">Usia</span>
                                <span id="usia_disp_{{ $i }}" class="usia-display {{ $tglVal ? 'has-value' : 'no-value' }}">
                                    @if($tglVal)
                                        @php
                                            try {
                                                $parts = explode('-', $tglVal);
                                                if(count($parts) === 3) {
                                                    $tglDate = \Carbon\Carbon::createFromDate($parts[2], $parts[1], $parts[0]);
                                                    $tourDate = \Carbon\Carbon::create(2026, 8, 24);
                                                    $age = $tglDate->diffInYears($tourDate);
                                                    echo $age . ' th (Ags 2026)';
                                                } else { echo '—'; }
                                            } catch(\Exception $e) { echo '—'; }
                                        @endphp
                                    @else — @endif
                                </span>
                                <span style="font-size:8px;color:#b4b2a9;flex-shrink:0;">auto</span>
                            </div>
                            {{-- City badge --}}
                            <div id="cityBadgeCard_{{ $i }}" class="city-badge {{ $isValid ? 'valid' : ($kotaRaw ? 'invalid' : 'empty') }}" style="width:100%;box-sizing:border-box;">
                                @if($isValid) ✓ KTP Balikpapan — Memenuhi Syarat
                                @elseif($kotaRaw) ✗ "{{ $kotaRaw }}" — Bukan Balikpapan
                                @else — Kota belum terbaca @endif
                            </div>
                        </div>
                        @else
                        <div id="ktpDataRows_{{ $i }}"></div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ═══ SECTION 4 — SUBMIT ═══ --}}
        <div class="form-section mb-2">
            <button type="submit" id="submitBtn"
                class="btn-ready w-full py-4 rounded-xl font-display text-sm font-bold tracking-wide text-white
                       flex items-center justify-center gap-3">
                <span id="submitBtnText">Kirim Perbaikan Data →</span>
                <svg id="submitSpinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
            </button>
            <p class="text-center text-white/25 text-xs mt-3">
                Setelah dikirim, admin akan meninjau ulang pendaftaran Anda.
                Tidak ada biaya tambahan.
            </p>
        </div>
    </form>

</div>
</section>

<div id="submitOverlay">
    <div style="width:48px;height:48px;border:3px solid rgba(245,158,11,.2);border-top-color:#f59e0b;border-radius:50%;animation:spin .8s linear infinite;"></div>
    <p class="text-white/50 text-sm font-semibold">Mengirim perbaikan data...</p>
</div>

{{-- ── UPLOAD BOTTOM SHEET ── --}}
<div id="uploadSheetBackdrop" class="upload-sheet-backdrop" onclick="_REVSHEET.close()"></div>
<div id="uploadSheet" class="upload-sheet" role="dialog">
    <div class="upload-sheet-handle"></div>
    <p class="upload-sheet-title">Pilih Sumber KTP</p>
    <div class="upload-sheet-options">
        <button type="button" class="upload-opt-btn opt-camera" onclick="_REVSHEET.pick('camera')">
            <div class="upload-opt-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
            </div>
            <span>Foto<br>Kamera</span>
        </button>
        <button type="button" class="upload-opt-btn opt-foto" onclick="_REVSHEET.pick('foto')">
            <div class="upload-opt-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#b45309" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
            </div>
            <span>Upload<br>Foto</span>
        </button>
        <button type="button" class="upload-opt-btn opt-file" onclick="_REVSHEET.pick('file')">
            <div class="upload-opt-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
                </svg>
            </div>
            <span>Upload<br>File</span>
        </button>
    </div>
    <button type="button" class="upload-sheet-cancel" onclick="_REVSHEET.close()">Batal</button>
</div>

@push('scripts')
<script>
/* ================================================================
   _REVSHEET — Upload Bottom Sheet (scoped for revision form)
================================================================ */
window._REVSHEET = (function () {
'use strict';
var _activeId = null, _isAnimating = false;
function open(id) {
    if (_isAnimating) return;
    _activeId = id;
    var bd = document.getElementById('uploadSheetBackdrop'), sh = document.getElementById('uploadSheet');
    if (!bd||!sh) return;
    bd.classList.remove('hiding'); sh.classList.remove('hiding');
    bd.classList.add('show'); sh.classList.add('show');
    document.body.style.overflow = 'hidden';
}
function close() {
    if (_isAnimating) return;
    var bd = document.getElementById('uploadSheetBackdrop'), sh = document.getElementById('uploadSheet');
    if (!bd||!sh) return;
    _isAnimating = true;
    bd.classList.add('hiding'); sh.classList.add('hiding');
    setTimeout(function () {
        bd.classList.remove('show','hiding');
        sh.classList.remove('show','hiding');
        document.body.style.overflow = '';
        _isAnimating = false;
    }, 210);
}
function pick(type) {
    var id = _activeId; close();
    setTimeout(function () {
        var map = { camera:'fiCam_', foto:'fiFoto_', file:'fiFile_' };
        var el = document.getElementById((map[type]||'fiFoto_') + id);
        if (el) el.click();
    }, 230);
}
var _ty0 = 0;
document.addEventListener('touchstart', function(e) {
    var sh = document.getElementById('uploadSheet');
    if (sh && sh.classList.contains('show')) _ty0 = e.touches[0].clientY;
}, {passive:true});
document.addEventListener('touchend', function(e) {
    var sh = document.getElementById('uploadSheet');
    if (!sh || !sh.classList.contains('show')) return;
    if (e.changedTouches[0].clientY - _ty0 > 70) close();
}, {passive:true});
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') close(); });
return { open: open, close: close, pick: pick };
})();
</script>

<script>
/* ================================================================
   REV — Revision form with full OCR scanning
================================================================ */
window.REV = (function () {
'use strict';

// ── State ──────────────────────────────────────────────────────
var TOTAL_MEMBERS = {{ count($registration->pemain ?? []) }};

// Track per-slot: has new file, scanned, cityValid
// Pre-populate from server data
var slotState = {};
@php
    $pemainList = $registration->pemain ?? [];
    $ktpFilesArr = $registration->ktp_files ?? [];
    $cityValidArr = $registration->ktp_city_valid ?? [];
@endphp
@foreach($pemainList as $i => $p)
slotState[{{ $i }}] = {
    hasNewFile: false,
    memberFile: null,
    scanned: false,
    // Pre-populate city valid from server — existing data counts
    cityValid: {{ ($cityValidArr[$i]['valid'] ?? false) ? 'true' : 'false' }},
    hasExistingFile: {{ isset($ktpFilesArr[$i]) && $ktpFilesArr[$i] ? 'true' : 'false' }},
};
@endforeach

var TOURNAMENT_DATE = new Date(2026, 7, 24);

// Inline-editable fields per slot
var CARD_FIELDS = [
    { l:'NIK',     k:'nik',            placeholder:'16 digit NIK' },
    { l:'Nama',    k:'nama',           placeholder:'Nama sesuai KTP' },
    { l:'Tgl Lhr', k:'tanggal_lahir',  placeholder:'DD-MM-YYYY' },
];

// ── Utilities ──────────────────────────────────────────────────
function esc(s) {
    return String(s)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function show(id) { var el=document.getElementById(id); if(el) el.classList.remove('hidden'); }
function hide(id) { var el=document.getElementById(id); if(el) el.classList.add('hidden'); }

function hitungUsia(str) {
    if (!str||!str.trim()) return null;
    str = str.trim();
    var m1 = str.match(/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{4})$/);
    var m2 = str.match(/^(\d{4})[-\/\.](\d{1,2})[-\/\.](\d{1,2})$/);
    var tgl = m1 ? new Date(+m1[3],+m1[2]-1,+m1[1]) : m2 ? new Date(+m2[1],+m2[2]-1,+m2[3]) : null;
    if (!tgl||isNaN(tgl.getTime())) return null;
    var usia = TOURNAMENT_DATE.getFullYear() - tgl.getFullYear();
    var bm   = TOURNAMENT_DATE.getMonth() - tgl.getMonth();
    if (bm<0||(bm===0&&TOURNAMENT_DATE.getDate()<tgl.getDate())) usia--;
    if (usia<0||usia>120) return null;
    return usia;
}

function updateUsiaRow(idx, tglValue) {
    var usia = hitungUsia(tglValue);
    var el = document.getElementById('usia_disp_'+idx);
    if (!el) return;
    if (usia !== null) { el.className = 'usia-display has-value'; el.textContent = usia + ' th (Ags 2026)'; }
    else               { el.className = 'usia-display no-value';  el.textContent = '—'; }
}

function normalizeGender(raw) {
    if (!raw) return '';
    raw = raw.toUpperCase().trim();
    if (raw.indexOf('PEREMPUAN') !== -1 || raw.indexOf('WANITA') !== -1) return 'P';
    if (raw.indexOf('LAKI') !== -1 || raw.indexOf('PRIA') !== -1) return 'L';
    if (['P','PR'].indexOf(raw) !== -1) return 'P';
    if (['L','LK'].indexOf(raw) !== -1) return 'L';
    return '';
}

function convertToJpeg(file, callback) {
    var MAX_SIZE_KB=300, MAX_PIXEL=1600, QUALITY_STEP=0.10, MIN_QUALITY=0.35;
    var reader = new FileReader();
    reader.onload = function(e) {
        var img = new Image();
        img.onload = function() {
            var w=img.naturalWidth, h=img.naturalHeight;
            if (w>MAX_PIXEL||h>MAX_PIXEL) {
                if (w>h) { h=Math.round(h*MAX_PIXEL/w); w=MAX_PIXEL; }
                else      { w=Math.round(w*MAX_PIXEL/h); h=MAX_PIXEL; }
            }
            var canvas=document.createElement('canvas');
            canvas.width=w; canvas.height=h;
            var ctx=canvas.getContext('2d');
            ctx.drawImage(img,0,0,w,h);
            function tryCompress(quality) {
                canvas.toBlob(function(blob) {
                    if (!blob) { callback(file, e.target.result); return; }
                    if (blob.size/1024>MAX_SIZE_KB && quality-QUALITY_STEP>=MIN_QUALITY) {
                        tryCompress(parseFloat((quality-QUALITY_STEP).toFixed(2))); return;
                    }
                    var converted = new File([blob], (file.name||'ktp').replace(/\.[^.]+$/,'')+'.jpg', {type:'image/jpeg',lastModified:Date.now()});
                    callback(converted, URL.createObjectURL(blob));
                }, 'image/jpeg', quality);
            }
            tryCompress(0.82);
        };
        img.onerror = function() { toast('Format foto tidak didukung.','warn'); callback(null,null); };
        img.src = e.target.result;
    };
    reader.onerror = function() { toast('Gagal membaca file.','error'); callback(null,null); };
    reader.readAsDataURL(file);
}

var _toastTimer = null;
function toast(msg, type) {
    var el = document.getElementById('_revToast');
    if (!el) {
        el = document.createElement('div');
        el.id = '_revToast';
        el.style.cssText = 'position:fixed;top:88px;right:20px;z-index:99999;max-width:360px;padding:10px 14px;border-radius:11px;font-size:11px;line-height:1.5;font-weight:600;box-shadow:0 8px 36px rgba(0,0,0,.15);transition:opacity .3s,transform .3s;pointer-events:none;';
        document.body.appendChild(el);
    }
    var styles = {
        success:'background:#f0fdf4;border:1px solid rgba(16,185,129,.3);color:#059669;',
        warn:'background:#fffbeb;border:1px solid rgba(234,179,8,.3);color:#d97706;',
        error:'background:#fef2f2;border:1px solid rgba(239,68,68,.3);color:#dc2626;'
    };
    el.style.cssText += (styles[type]||styles.warn)+'opacity:1;transform:translateY(0);';
    el.textContent = msg;
    if (_toastTimer) clearTimeout(_toastTimer);
    _toastTimer = setTimeout(function(){ el.style.opacity='0'; el.style.transform='translateY(-8px)'; }, 5000);
}

// ── Counter ────────────────────────────────────────────────────
function updateCounter() {
    var validCount = Object.values(slotState).filter(function(s){ return s.cityValid; }).length;
    var total = TOTAL_MEMBERS;
    var pct = Math.min(100, Math.round((validCount/6)*100));

    var textEl = document.getElementById('counterText');
    var fillEl = document.getElementById('counterFill');
    var noteEl = document.getElementById('counterNote');
    if (textEl) textEl.textContent = validCount + ' / ' + total + ' valid';
    if (fillEl) { fillEl.style.width = pct+'%'; fillEl.style.background = validCount>=6?'#10b981':'#f59e0b'; }
    if (noteEl) {
        if (validCount >= 6) { noteEl.textContent = '✓ Syarat terpenuhi'; noteEl.style.color = '#059669'; }
        else { noteEl.textContent = 'Butuh '+(6-validCount)+' KTP Balikpapan lagi'; noteEl.style.color = '#f59e0b'; }
    }

    updateScanAllBtn();
}

function updateScanAllBtn() {
    var hasPending = Object.keys(slotState).some(function(idx) {
        var s = slotState[idx];
        return s.hasNewFile && !s.scanned;
    });
    var btn = document.getElementById('scanAllBtn');
    if (!btn) return;
    btn.disabled = !hasPending;
}

// ── File handling ──────────────────────────────────────────────
function showSheet(idx) { _REVSHEET.open(idx); }

function onFileSelect(input, idx) {
    if (input.files && input.files[0]) processFile(input.files[0], idx);
}

function onDrop(e, idx) {
    e.preventDefault();
    document.getElementById('dz_'+idx).classList.remove('drag-over');
    var file = e.dataTransfer && e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) processFile(file, idx);
}

function processFile(file, idx) {
    if (file.size > 10*1024*1024) { toast('File terlalu besar. Maks 10MB.','error'); return; }
    convertToJpeg(file, function(converted, previewUrl) {
        if (!converted) return;
        var s = slotState[idx];
        s.memberFile = converted;
        s.hasNewFile = true;
        s.scanned = false;
        s.cityValid = false;

        // Show preview
        var prevImg = document.getElementById('prevImg_'+idx);
        var existPrev = document.getElementById('existingPrev_'+idx);
        if (prevImg) prevImg.src = previewUrl;
        if (existPrev) existPrev.classList.remove('hidden');
        hide('dzDefault_'+idx);

        // Remove "KTP Lama" label if replacing
        var oldLabel = existPrev ? existPrev.querySelector('.ktp-existing-label') : null;
        if (oldLabel) { oldLabel.textContent = 'KTP Baru ✓'; oldLabel.style.background = 'rgba(16,185,129,.7)'; }

        show('scanBtn_'+idx);
        hide('scanLoading_'+idx);

        // Mark keep=0 since we have a new file
        var keepInp = document.getElementById('keepFile_'+idx);
        if (keepInp) keepInp.value = '0';

        // Reset card UI
        resetCardDisplay(idx);
        updateCounter();
        updateScanAllBtn();
    });
}

function resetFile(e, idx) {
    e.stopPropagation();
    var s = slotState[idx];
    s.memberFile = null;
    s.hasNewFile = false;
    s.scanned = false;
    // Restore server-side validity
    s.cityValid = {{ json_encode(array_map(fn($cv) => $cv['valid'] ?? false, $cityValidArr)) }}[idx] || false;

    // Reset file inputs
    ['fiCam_','fiFoto_','fiFile_'].forEach(function(p) {
        var el = document.getElementById(p+idx); if (el) el.value = '';
    });
    var keepInp = document.getElementById('keepFile_'+idx);
    if (keepInp) keepInp.value = '1';

    // Reset preview to existing or empty
    var existPrev = document.getElementById('existingPrev_'+idx);
    var dzDef = document.getElementById('dzDefault_'+idx);
    @foreach($ktpFilesArr as $fi => $fp)
    if (idx === {{ $fi }}) {
        @if($fp)
        if (existPrev) existPrev.classList.remove('hidden');
        var lbl = existPrev ? existPrev.querySelector('.ktp-existing-label') : null;
        if (lbl) { lbl.textContent = 'KTP Lama'; lbl.style.background = ''; }
        if (dzDef) dzDef.classList.add('hidden');
        @else
        if (existPrev) existPrev.classList.add('hidden');
        if (dzDef) dzDef.classList.remove('hidden');
        @endif
    }
    @endforeach

    hide('scanBtn_'+idx);
    // Re-show scan button only if existing file
    @foreach($ktpFilesArr as $fi => $fp)
    @if($fp)
    if (idx === {{ $fi }}) show('scanBtn_'+idx);
    @endif
    @endforeach

    // Restore existing card data display
    var card = document.getElementById('ktpDataCard_'+idx);
    @foreach($pemainList as $i => $p)
    if (idx === {{ $i }}) {
        @if(isset($ktpFilesArr[$i]) || $p)
        if (card) { card.classList.add('show','valid-card'); }
        @endif
        // Re-sync badge
        var topBadge = document.getElementById('cityBadge_'+idx);
        @if($cityValidArr[$i]['valid'] ?? false)
        if (topBadge) { topBadge.className='city-badge valid'; topBadge.textContent='✓ Balikpapan'; }
        @else
        if (topBadge) { topBadge.className='city-badge invalid'; topBadge.textContent='✗ {{ $cityValidArr[$i]["city_raw"] ?? "Perlu upload ulang" }}'; }
        @endif
        var mc = document.getElementById('mc_'+idx);
        if (mc) {
            mc.classList.remove('scanned','gender-error');
            @if($cityValidArr[$i]['valid'] ?? false)
            mc.classList.add('city-valid'); mc.classList.remove('city-invalid');
            @else
            mc.classList.add('city-invalid'); mc.classList.remove('city-valid');
            @endif
        }
    }
    @endforeach

    updateCounter();
    updateScanAllBtn();
}

function resetCardDisplay(idx) {
    var badge = document.getElementById('scan_badge_'+idx);
    var mc = document.getElementById('mc_'+idx);
    if (badge) badge.style.display = 'none';
    if (mc) mc.classList.remove('scanned','city-valid','city-invalid','gender-error');

    var topBadge = document.getElementById('cityBadge_'+idx);
    if (topBadge) { topBadge.className = 'city-badge empty'; topBadge.textContent = '— Belum di-scan'; }

    var card = document.getElementById('ktpDataCard_'+idx);
    if (card) { card.className = 'ktp-data-card'; }
    var rows = document.getElementById('ktpDataRows_'+idx);
    if (rows) rows.innerHTML = '';
}

// ── OCR Scanning ───────────────────────────────────────────────
function scan(idx) {
    scanWithCallback(idx, function() {
        var s = slotState[idx];
        if (s && s.scanned) {
            toast(s.cityValid
                ? '✓ Anggota '+(idx+1)+' — KTP Balikpapan valid'
                : '⚠ Anggota '+(idx+1)+' — Kota bukan Balikpapan',
                s.cityValid ? 'success' : 'warn');
        }
    });
}

function scanAll() {
    var toScan = Object.keys(slotState).filter(function(idx) {
        var s = slotState[idx];
        return s.hasNewFile && !s.scanned;
    });

    if (toScan.length === 0) {
        toast('Semua KTP yang diupload sudah di-scan.','warn'); return;
    }

    var btn = document.getElementById('scanAllBtn');
    var txt = document.getElementById('scanAllText');
    if (btn) btn.disabled = true;
    if (txt) txt.textContent = 'Scanning '+toScan.length+' KTP...';

    var i = 0;
    function next() {
        if (i >= toScan.length) {
            if (txt) txt.textContent = 'SCAN SEMUA KTP';
            updateScanAllBtn(); return;
        }
        var idx = parseInt(toScan[i++]);
        if (txt) txt.textContent = 'Scanning '+i+' / '+toScan.length+'...';
        scanWithCallback(idx, function() { setTimeout(next, 400); });
    }
    next();
}

function scanWithCallback(idx, cb) {
    var s = slotState[idx];
    if (!s || !s.memberFile) { if (cb) cb(); return; }

    hide('scanBtn_'+idx); show('scanLoading_'+idx);
    resetCardDisplay(idx);

    var fd = new FormData();
    fd.append('image', s.memberFile, s.memberFile.name||'ktp.jpg');
    var csrf = (document.querySelector('meta[name="csrf-token"]')||{}).content||'';

    fetch('/ocr/ktp', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: fd,
    })
    .then(function(r) {
        hide('scanLoading_'+idx); show('scanBtn_'+idx);
        return r.json().then(function(j) { return { ok: r.ok, j: j }; });
    })
    .then(function(res) {
        if (!res.ok || !res.j.success) {
            toast('Gagal membaca KTP. Coba foto ulang lebih jelas.','error');
            if (cb) cb(); return;
        }

        var data = res.j.data;
        var genderNorm = normalizeGender(data.jenis_kelamin||'');

        // Beregu = only Laki-laki
        if (genderNorm === 'P') {
            toast('⚠ KTP Anggota '+(idx+1)+' terdeteksi Perempuan. Upload KTP Laki-laki.','error');
            s.scanned = false; s.cityValid = false;
            var mc = document.getElementById('mc_'+idx);
            if (mc) { mc.classList.add('gender-error'); setTimeout(function(){ mc.classList.remove('gender-error'); },600); }
            if (cb) cb(); return;
        }

        // Mark scanned
        s.scanned = true;
        s.cityValid = !!data.city_valid;

        // Render OCR card
        renderOcrCard(idx, data);

        // Scan badge
        var badge = document.getElementById('scan_badge_'+idx);
        if (badge) badge.style.display = 'inline-flex';

        // Card class
        var mc = document.getElementById('mc_'+idx);
        if (mc) {
            mc.classList.remove('gender-error','city-valid','city-invalid');
            mc.classList.add(data.city_valid ? 'scanned city-valid' : 'city-invalid');
        }

        // Top badge
        var topBadge = document.getElementById('cityBadge_'+idx);
        if (topBadge) {
            topBadge.className = 'city-badge '+(data.city_valid?'valid':'invalid');
            topBadge.textContent = data.city_valid ? '✓ Balikpapan' : '✗ '+(data.kota||'Bukan Balikpapan');
        }

        updateCounter();
        if (cb) cb();
    })
    .catch(function() {
        hide('scanLoading_'+idx); show('scanBtn_'+idx);
        toast('Koneksi ke server OCR gagal. Coba lagi.','error');
        if (cb) cb();
    });
}

function renderOcrCard(idx, data) {
    var card = document.getElementById('ktpDataCard_'+idx);
    var rows = document.getElementById('ktpDataRows_'+idx);
    if (!card || !rows) return;
    rows.innerHTML = '';

    var fieldMap = { nik: data.nik||'', nama: data.nama||'', tanggal_lahir: (data.tanggal_lahir||data.tgl_lahir||'') };

    CARD_FIELDS.forEach(function(f) {
        var v = (fieldMap[f.k]||'').trim();
        var valId='kval_'+idx+'_'+f.k, inpId='kinp_'+idx+'_'+f.k, hidId='khid_'+idx+'_'+f.k;
        var nameMap = { nik:'nik['+idx+']', nama:'pemain['+idx+']', tanggal_lahir:'tgl_lahir['+idx+']' };
        var valContent = v ? esc(v) : '<span style="color:#b4b2a9;font-style:italic">—</span>';
        rows.innerHTML +=
            '<div class="ktp-row">'
            +'<span class="ktp-label">'+esc(f.l)+' <span style="color:#f59e0b">*</span></span>'
            +'<span id="'+valId+'" class="ktp-value" title="Klik edit" onclick="REV.inlineEdit('+idx+',\''+f.k+'\')">'+valContent+'</span>'
            +'<input id="'+inpId+'" type="text" class="ktp-inline-input" style="display:none"'
            +' value="'+esc(v)+'" placeholder="'+esc(f.placeholder||'')+'"'
            +' onkeydown="REV.inlineKey(event,'+idx+',\''+f.k+'\')"'
            +' onblur="REV.inlineSave('+idx+',\''+f.k+'\')">'
            +'<input type="hidden" id="'+hidId+'" name="'+nameMap[f.k]+'" value="'+esc(v)+'">'
            +'</div>';
    });

    // Gender row
    rows.innerHTML +=
        '<div class="ktp-row" style="margin-top:4px;">'
        +'<span class="ktp-label">Kelamin</span>'
        +'<span style="flex:1;font-size:11px;font-weight:700;padding:2px 8px;border-radius:5px;color:#1D4ED8;background:#EFF8FF;border:1px solid #BFDBFE;">♂ Laki-laki</span>'
        +'<span style="font-size:8px;color:#b4b2a9;flex-shrink:0;">dari KTP</span>'
        +'<input type="hidden" name="jenis_kelamin['+idx+']" value="L">'
        +'</div>';

    // Kota hidden
    rows.innerHTML += '<input type="hidden" id="khid_'+idx+'_kota" name="kota_ktp['+idx+']" value="'+esc((data.kota||'').trim())+'">';

    // Usia row
    var tglVal = (fieldMap['tanggal_lahir']||'').trim();
    var usia = hitungUsia(tglVal);
    rows.innerHTML +=
        '<div class="ktp-row" style="margin-top:4px;padding-top:6px;border-top:1px solid rgba(245,158,11,.1);">'
        +'<span class="ktp-label" style="color:rgba(245,158,11,.6);">Usia</span>'
        +'<span id="usia_disp_'+idx+'" class="'+(usia!==null?'usia-display has-value':'usia-display no-value')+'">'
        +(usia!==null?usia+' th (Ags 2026)':'—')+'</span>'
        +'<span style="font-size:8px;color:#b4b2a9;flex-shrink:0;">auto</span>'
        +'</div>';

    // City badge in card
    rows.innerHTML += data.city_valid
        ? '<div id="cityBadgeCard_'+idx+'" class="city-badge valid" style="width:100%;box-sizing:border-box;">✓ KTP Balikpapan — Memenuhi Syarat</div>'
        : (data.kota
            ? '<div id="cityBadgeCard_'+idx+'" class="city-badge invalid" style="width:100%;box-sizing:border-box;">✗ "'+esc(data.kota)+'" — Bukan Balikpapan</div>'
            : '<div id="cityBadgeCard_'+idx+'" class="city-badge empty" style="width:100%;box-sizing:border-box;">— Kota belum terbaca</div>');

    card.className = 'ktp-data-card show valid-card';
}

// ── Inline editing ─────────────────────────────────────────────
function inlineEdit(idx, key) {
    var v = document.getElementById('kval_'+idx+'_'+key);
    var i = document.getElementById('kinp_'+idx+'_'+key);
    if (!v||!i) return;
    v.style.display = 'none'; i.style.display = ''; i.focus(); if(i.select) i.select();
}

function inlineSave(idx, key) {
    var v = document.getElementById('kval_'+idx+'_'+key);
    var i = document.getElementById('kinp_'+idx+'_'+key);
    var h = document.getElementById('khid_'+idx+'_'+key);
    if (!v||!i) return;
    var nv = i.value.trim();
    if (h) h.value = nv;
    v.innerHTML = nv ? esc(nv) : '<span style="color:#b4b2a9;font-style:italic">—</span>';
    v.style.color = '#d97706';
    v.title = 'Diedit manual';
    if (i.classList) i.classList.add('was-edited');
    i.style.display = 'none'; v.style.display = '';
    if (key === 'tanggal_lahir') updateUsiaRow(idx, nv);
}

function inlineKey(e, idx, key) {
    if (e.key === 'Enter')  { e.preventDefault(); inlineSave(idx, key); }
    if (e.key === 'Escape') {
        var i = document.getElementById('kinp_'+idx+'_'+key);
        var v = document.getElementById('kval_'+idx+'_'+key);
        if (i) i.style.display = 'none'; if (v) v.style.display = '';
    }
}

// ── Form Submit ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    updateCounter();

    var form = document.getElementById('revisiForm');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        var banner = document.getElementById('errorBanner');
        var list   = document.getElementById('errorList');
        banner.classList.remove('show');
        list.innerHTML = '';

        var btn     = document.getElementById('submitBtn');
        var txt     = document.getElementById('submitBtnText');
        var spinner = document.getElementById('submitSpinner');
        var overlay = document.getElementById('submitOverlay');

        btn.disabled = true;
        txt.textContent = 'Mengirim...';
        spinner.classList.remove('hidden');
        overlay.classList.add('show');

        var fd = new FormData(form);

        // Append new KTP files (override any <input name="ktp_files[i]">)
        Object.keys(slotState).forEach(function(idx) {
            var s = slotState[idx];
            if (s.hasNewFile && s.memberFile) {
                fd.append('ktp_files['+idx+']', s.memberFile, s.memberFile.name||('ktp-anggota-'+(parseInt(idx)+1)+'.jpg'));
            }
        });

        var csrf = (document.querySelector('meta[name="csrf-token"]')||{}).content||'';

        try {
            var response = await fetch("{{ route('registration.revision.update', ['token' => $token]) }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: fd,
            });

            var data = await response.json();

            if (response.status === 422) {
                overlay.classList.remove('show');
                btn.disabled = false;
                txt.textContent = 'Kirim Perbaikan Data →';
                spinner.classList.add('hidden');

                var errors = [];
                Object.values(data.errors || {}).forEach(function(msgs) {
                    errors = errors.concat(Array.isArray(msgs) ? msgs : [msgs]);
                });
                errors.forEach(function(m) {
                    var li = document.createElement('li'); li.textContent = m; list.appendChild(li);
                });
                banner.classList.add('show');
                banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }

            if (!response.ok) {
                overlay.classList.remove('show');
                btn.disabled = false;
                txt.textContent = 'Kirim Perbaikan Data →';
                spinner.classList.add('hidden');
                var li = document.createElement('li');
                li.textContent = (data && data.message) || 'Terjadi kesalahan server.';
                list.appendChild(li);
                banner.classList.add('show');
                return;
            }

            // Success
            window.location.href = data.redirect || '/';

        } catch (err) {
            overlay.classList.remove('show');
            btn.disabled = false;
            txt.textContent = 'Kirim Perbaikan Data →';
            spinner.classList.add('hidden');
            var li = document.createElement('li');
            li.textContent = 'Koneksi gagal. Periksa internet dan coba lagi.';
            list.appendChild(li);
            banner.classList.add('show');
        }
    });
});

return {
    showSheet: showSheet, onFileSelect: onFileSelect, onDrop: onDrop, resetFile: resetFile,
    scan: scan, scanAll: scanAll,
    inlineEdit: inlineEdit, inlineSave: inlineSave, inlineKey: inlineKey,
};
})();
</script>
<style>@keyframes spin { to { transform: rotate(360deg); } }</style>
@endpush
@endsection