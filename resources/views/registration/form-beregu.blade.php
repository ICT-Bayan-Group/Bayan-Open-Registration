@extends('layouts.app')

@section('title', 'Pendaftaran Beregu — Bayan Open 2026')

@push('styles')
<style>
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes shimmerScan {
        0%   { background-position: -200% center; }
        100% { background-position:  200% center; }
    }

    .form-section              { animation: fadeSlideUp .45s ease both; }
    .form-section:nth-child(1) { animation-delay: .06s; }
    .form-section:nth-child(2) { animation-delay: .12s; }
    .form-section:nth-child(3) { animation-delay: .18s; }
    .form-section:nth-child(4) { animation-delay: .24s; }
    .form-section:nth-child(5) { animation-delay: .30s; }

    /* ── Grid anggota 2 kolom ────────────────────────────────── */
    #memberSlots {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    @media (max-width: 600px) {
        #memberSlots { grid-template-columns: 1fr; }
    }

    /* ── Member card compact ─────────────────────────────────── */
    .member-card {
        border-radius: 14px;
        border: 1.5px solid rgba(249,115,22,.18);
        background: rgba(20,10,4,.75);
        padding: 14px;
        transition: border-color .3s, background .3s, box-shadow .3s;
    }
    .member-card.scanned {
        border-color: rgba(16,185,129,.45);
        background:   rgba(4,20,12,.75);
        box-shadow:   0 0 0 1px rgba(16,185,129,.1) inset;
    }
    .member-card.city-invalid {
        border-color: rgba(239,68,68,.4);
        box-shadow:   0 0 0 1px rgba(239,68,68,.06) inset;
    }

    /* ── KTP Dropzone compact ────────────────────────────────── */
    .ktp-dropzone {
        border: 1.5px dashed rgba(249,115,22,.22);
        border-radius: 10px;
        background: rgba(249,115,22,.018);
        cursor: pointer;
        transition: border-color .2s, background .2s;
        min-height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .ktp-dropzone:hover,
    .ktp-dropzone.drag-over {
        border-color: rgba(249,115,22,.6);
        background: rgba(249,115,22,.04);
    }

    /* ── KTP Data Card ───────────────────────────────────────── */
    .ktp-data-card {
        border-radius: 10px;
        background:    rgba(255,255,255,.025);
        border:        1px solid rgba(255,255,255,.07);
        padding:       10px 12px;
        margin-top:    10px;
        display:       none;
    }
    .ktp-data-card.show       { display: block; animation: fadeSlideUp .3s ease both; }
    .ktp-data-card.valid-card { background: rgba(249,115,22,.04); border-color: rgba(249,115,22,.22); }

    /* ── KTP Row compact ─────────────────────────────────────── */
    .ktp-row {
        display:     flex;
        align-items: center;
        gap:         6px;
        padding:     3px 0;
        border-bottom: 1px solid rgba(255,255,255,.04);
        min-height:  26px;
    }
    .ktp-row:last-child { border-bottom: none; padding-bottom: 0; }
    .ktp-label {
        font-size:      9px;
        font-weight:    700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color:          rgba(255,255,255,.28);
        min-width:      58px;
        flex-shrink:    0;
    }

    /* ── Inline value display ────────────────────────────────── */
    .ktp-value {
        flex:        1;
        font-size:   11px;
        color:       rgba(255,255,255,.75);
        line-height: 1.3;
        word-break:  break-word;
        padding:     2px 6px;
        border-radius: 5px;
        border:      1px solid transparent;
        cursor:      pointer;
        transition:  background .15s, border-color .15s, color .15s;
        min-width: 0;
    }
    .ktp-value.hl  { color: #fff; font-weight: 600; }
    .ktp-value:hover {
        background:   rgba(249,115,22,.08);
        border-color: rgba(249,115,22,.28);
        color:        #fff;
    }
    .ktp-value:hover::after { content: ' ✏'; font-size: 8px; opacity: .45; }

    /* ── Inline input ────────────────────────────────────────── */
    .ktp-inline-input {
        flex:        1;
        background:  rgba(249,115,22,.07);
        border:      1.5px solid rgba(249,115,22,.5);
        border-radius: 5px;
        color:       #fff;
        font-size:   11px;
        font-weight: 600;
        padding:     2px 7px;
        outline:     none;
        min-width:   0;
        transition:  border-color .15s, box-shadow .15s;
    }
    .ktp-inline-input:focus {
        border-color: rgba(249,115,22,.9);
        box-shadow:   0 0 0 2px rgba(249,115,22,.14);
    }
    .ktp-inline-input.was-edited {
        border-color: rgba(234,179,8,.65);
        background:   rgba(234,179,8,.07);
    }

    /* ── Usia display ────────────────────────────────────────── */
    .usia-display {
        flex:        1;
        font-size:   11px;
        font-weight: 700;
        padding:     2px 6px;
        border-radius: 5px;
        cursor:      default;
        transition:  color .25s, background .25s;
        min-width: 0;
    }
    .usia-display.has-value {
        color:       #34d399;
        background:  rgba(16,185,129,.07);
        border:      1px solid rgba(16,185,129,.18);
    }
    .usia-display.no-value {
        color:       rgba(255,255,255,.25);
        font-style:  italic;
        font-weight: 400;
        background:  transparent;
        border:      1px solid transparent;
    }

    /* ── City badge compact ──────────────────────────────────── */
    .city-badge {
        display:     inline-flex;
        align-items: center;
        gap:         4px;
        padding:     4px 8px;
        border-radius: 6px;
        font-size:   10px;
        font-weight: 700;
        margin-top:  8px;
        width: 100%;
        box-sizing: border-box;
    }
    .city-badge.valid   { background: rgba(16,185,129,.1);  border: 1px solid rgba(16,185,129,.3);  color: #34d399; }
    .city-badge.invalid { background: rgba(239,68,68,.1);   border: 1px solid rgba(239,68,68,.3);   color: #f87171; }
    .city-badge.empty   { background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.1); color: rgba(255,255,255,.35); }

    /* ── Scan loading bar ────────────────────────────────────── */
    .scan-loading-bar { height: 2px; border-radius: 99px; overflow: hidden; background: rgba(249,115,22,.1); margin-top: 6px; }
    .scan-loading-bar-inner {
        height: 100%; width: 40%;
        background:      linear-gradient(90deg, transparent, #f97316, transparent);
        background-size: 200% 100%;
        animation:       shimmerScan 1.2s ease infinite;
    }

    /* ── Scan badge ──────────────────────────────────────────── */
    .scan-badge {
        display:     none;
        align-items: center;
        gap:         3px;
        padding:     1px 6px;
        border-radius: 99px;
        font-size:   9px;
        font-weight: 700;
        background:  rgba(16,185,129,.1);
        border:      1px solid rgba(16,185,129,.3);
        color:       #34d399;
    }

    /* ── Counter bar ─────────────────────────────────────────── */
    .counter-bar {
        height: 5px;
        border-radius: 99px;
        background: rgba(255,255,255,.07);
        overflow: hidden;
        margin-top: 5px;
    }
    .counter-fill {
        height: 100%;
        border-radius: 99px;
        transition: width .4s ease, background .4s;
    }

    /* ── Select dark ─────────────────────────────────────────── */
    select.input-field {
        color: rgba(255,255,255,.85) !important;
        background-color: #0d1117 !important;
        cursor: pointer;
    }
    select.input-field option { background-color: #0d1117; color: rgba(255,255,255,.85); }
    select.input-field:disabled { opacity: .4 !important; cursor: not-allowed; }

    /* ── Preview image compact ───────────────────────────────── */
    .preview-img-compact {
        max-h: 52px;
        max-height: 52px;
        max-width: 100%;
        border-radius: 6px;
        object-fit: contain;
    }

    /* ── Edit hint small ─────────────────────────────────────── */
    .ktp-edit-hint {
        font-size:  8.5px;
        color:      rgba(249,115,22,.38);
        text-align: right;
        font-style: italic;
        margin-top: 2px;
        margin-bottom: 4px;
    }
</style>
@endpush

@section('content')
<section class="min-h-screen py-20 px-4">
<div class="max-w-3xl mx-auto">

    {{-- ── HEADER ──────────────────────────────────────────────── --}}
    <div class="text-center mb-10 form-section">
        <a href="{{ route('registration.index') }}"
           class="inline-flex items-center gap-2 text-white/30 text-xs hover:text-white/60 transition mb-6">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Ganti kategori
        </a>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-500/40
                    bg-brand-500/10 text-brand-300 text-xs font-semibold uppercase tracking-widest mb-4">
            Pendaftaran Online · Bayan Open 2026
        </div>
        <h1 class="font-display text-3xl font-bold mb-3">Formulir Beregu</h1>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-3"
             style="background:rgba(249,115,22,.1);border:1px solid rgba(249,115,22,.3);">
            <svg width="13" height="13" viewBox="0 0 20 20" fill="rgba(251,146,60,1)">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
            </svg>
            <span class="text-brand-400 text-xs font-bold uppercase tracking-widest">Beregu · 6–8 Anggota</span>
        </div>
        <p class="text-white/40 text-sm mt-2">
            Minimal <strong class="text-white/70">6</strong> dari
            <strong class="text-white/70">8</strong> anggota harus ber-KTP
            <strong class="text-brand-400">Kota Balikpapan</strong>
        </p>
    </div>

    {{-- ── ERROR BOX ───────────────────────────────────────────── --}}
    @if($errors->any())
    <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-5 mb-6 form-section">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-red-400 text-sm font-semibold">Terdapat kesalahan:</p>
        </div>
        <ul class="text-red-300/80 text-sm space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('registration.store') }}" method="POST"
          enctype="multipart/form-data" id="regForm" novalidate>
    @csrf
    <input type="hidden" name="kategori" value="beregu">

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 1 — DATA TIM & KONTAK
    ═══════════════════════════════════════════════════════════ --}}
    <div class="card-glass rounded-2xl p-8 mb-6 form-section">
        <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs font-black">1</span>
            Data Tim &amp; Kontak
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Nama Tim / PB <span class="text-brand-400">*</span>
                </label>
                <input type="text" name="tim_pb" value="{{ old('tim_pb') }}"
                    placeholder="Contoh: PB Garuda Sakti"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('tim_pb') border-red-500 @enderror" required>
                @error('tim_pb')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Nama Ketua Tim / PIC <span class="text-brand-400">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                    placeholder="Nama lengkap ketua tim / penanggung jawab"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('nama') border-red-500 @enderror" required>
                @error('nama')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Email <span class="text-brand-400">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="email@contoh.com"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('email') border-red-500 @enderror" required>
                <p class="text-white/25 text-xs mt-1">Link pembayaran dikirim ke email ini setelah diverifikasi</p>
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Nomor WhatsApp <span class="text-brand-400">*</span>
                </label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                    placeholder="Contoh: 08123456789"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('no_hp') border-red-500 @enderror" required>
                @error('no_hp')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Provinsi <span class="text-brand-400">*</span>
                </label>
                <div class="relative">
                    <select id="selectProvinsi" name="provinsi"
                        onchange="WILAYAH.onProvinsiChange(this)"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm appearance-none
                               @error('provinsi') border-red-500 @enderror" required>
                        <option value="">-- Pilih Provinsi --</option>
                    </select>
                    <div id="loadingProvinsi" class="hidden absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg class="animate-spin w-4 h-4 text-white/30" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </div>
                </div>
                @error('provinsi')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Kota / Kabupaten <span class="text-brand-400">*</span>
                </label>
                <div class="relative">
                    <select id="selectKota" name="kota" disabled
                        class="input-field w-full px-4 py-3 rounded-xl text-sm appearance-none opacity-40
                               @error('kota') border-red-500 @enderror" required>
                        <option value="">-- Pilih Provinsi dulu --</option>
                    </select>
                    <div id="loadingKota" class="hidden absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg class="animate-spin w-4 h-4 text-white/30" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </div>
                </div>
                @error('kota')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 2 — DATA PELATIH
    ═══════════════════════════════════════════════════════════ --}}
    <div class="card-glass rounded-2xl p-8 mb-6 form-section">
        <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs font-black">2</span>
            Data Pelatih
            <span class="text-white/28 text-xs font-normal normal-case ml-1">(opsional)</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">Nama Pelatih</label>
                <input type="text" name="nama_pelatih" value="{{ old('nama_pelatih') }}"
                    placeholder="Nama lengkap pelatih"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">No. HP Pelatih</label>
                <input type="text" name="no_hp_pelatih" value="{{ old('no_hp_pelatih') }}"
                    placeholder="Contoh: 08123456789"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 3 — ANGGOTA TIM (2-kolom compact)
    ═══════════════════════════════════════════════════════════ --}}
    <div class="rounded-2xl p-6 mb-6 form-section"
         style="background:rgba(249,115,22,.03);border:1.5px solid rgba(249,115,22,.15);">

        <h2 class="font-display text-sm font-bold mb-1 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black"
                  style="background:rgba(249,115,22,.9);color:#000;">3</span>
            Data Anggota Tim
        </h2>
        <p class="text-white/32 text-xs mb-1 ml-9">
            Upload KTP lalu klik <strong class="text-brand-400/80">SCAN</strong> — data terisi otomatis &amp; bisa diedit.
        </p>
        <p class="text-white/22 text-xs mb-5 ml-9">
            Minimal <strong class="text-white/50">6 anggota</strong> harus ber-KTP Kota Balikpapan.
        </p>

        {{-- ── Counter KTP Valid ─────────────────────────────── --}}
        <div class="ml-9 mb-5 p-3 rounded-xl" style="background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.06);">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-white/45 text-xs font-semibold uppercase tracking-wide">KTP Balikpapan Valid</span>
                <span id="counterText" class="text-xs font-bold text-white/50">0 / 0 anggota</span>
            </div>
            <div class="counter-bar">
                <div id="counterFill" class="counter-fill" style="width:0%;background:#f97316;"></div>
            </div>
            <p id="counterNote" class="text-white/28 text-xs mt-1.5">Tambah anggota dan scan KTP untuk melihat progress</p>
        </div>

        {{-- ── Slot container — 2 kolom ─────────────────────── --}}
        <div id="memberSlots"></div>

        {{-- ── Tombol tambah ────────────────────────────────── --}}
        <button type="button" id="addMemberBtn"
            onclick="BEREGU.addMember()"
            class="mt-5 flex items-center gap-2 text-brand-400/80 hover:text-brand-400
                   text-xs font-bold transition disabled:opacity-30 disabled:cursor-not-allowed">
            <span class="w-6 h-6 rounded-full border-2 border-brand-400/40
                         flex items-center justify-center text-brand-400 text-lg leading-none font-light">+</span>
            Tambah Anggota
        </button>

        @error('pemain')   <p class="text-red-400 text-xs mt-3">{{ $message }}</p>@enderror
        @error('pemain.*') <p class="text-red-400 text-xs mt-2">{{ $message }}</p>@enderror
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 4 — RINGKASAN BIAYA
    ═══════════════════════════════════════════════════════════ --}}
    <div class="card-glass rounded-2xl p-6 mb-6 form-section">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-white/40 text-xs mb-1">Kategori</p>
                <p class="font-display text-white font-bold text-sm">Beregu</p>
                <p class="text-white/28 text-xs mt-0.5">6–8 anggota · Syarat KTP Balikpapan</p>
            </div>
            <div class="text-right">
                <p class="text-white/40 text-xs mb-1">Total Pembayaran</p>
                <p class="font-display text-brand-400 font-bold text-2xl">Rp 200.000</p>
            </div>
        </div>
    </div>

    {{-- ── Info proses verifikasi ──────────────────────────── --}}
    <div class="rounded-xl p-4 mb-6 form-section"
         style="background:rgba(234,179,8,.05);border:1px solid rgba(234,179,8,.2);">
        <div class="flex items-start gap-3">
            <svg class="w-4 h-4 text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-yellow-400 text-xs font-bold mb-1">Proses Verifikasi Admin</p>
                <p class="text-white/40 text-xs leading-relaxed">
                    Pendaftaran beregu akan diverifikasi oleh admin terlebih dahulu.
                    Setelah disetujui, link pembayaran akan dikirim ke email yang didaftarkan.
                </p>
            </div>
        </div>
    </div>

    <button type="submit" id="submitBtn"
        class="btn-primary w-full py-4 rounded-xl font-display text-sm font-bold text-white tracking-wide form-section">
        KIRIM PENDAFTARAN &rarr;
    </button>
    <p class="text-white/25 text-xs text-center mt-4 form-section">
        Dengan mendaftar, Anda menyetujui syarat &amp; ketentuan Bayan Open 2026
    </p>

    </form>
</div>
</section>
@endsection

@push('scripts')
<script>
/* ================================================================
   WILAYAH CASCADE
================================================================ */
(function () {
'use strict';

var OLD_PROVINSI = @json(old('provinsi', ''));
var OLD_KOTA     = @json(old('kota', ''));

async function loadProvinsi() {
    var sel  = document.getElementById('selectProvinsi');
    var spin = document.getElementById('loadingProvinsi');
    if (!sel) return;
    if (spin) spin.classList.remove('hidden');
    try {
        var res  = await fetch('/wilayah/provinces');
        var data = await res.json();
        data.forEach(function (p) {
            var opt = new Option(p.name || p.nama, p.name || p.nama);
            opt.dataset.code = p.id;
            sel.appendChild(opt);
        });
        if (OLD_PROVINSI) {
            var found = Array.from(sel.options).find(function (o) {
                return o.value.toUpperCase() === OLD_PROVINSI.toUpperCase();
            });
            if (found) {
                found.selected = true;
                await loadKota(found.dataset.code, OLD_KOTA);
            }
        }
    } catch (e) {
        sel.innerHTML = '<option value="">Gagal memuat — refresh</option>';
    } finally {
        if (spin) spin.classList.add('hidden');
    }
}

async function onProvinsiChange(sel) {
    var opt  = sel.options[sel.selectedIndex];
    var code = opt ? (opt.dataset.code || '') : '';
    var kotaSel = document.getElementById('selectKota');
    if (kotaSel) {
        kotaSel.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        kotaSel.disabled  = true;
        kotaSel.classList.add('opacity-40');
    }
    if (code) await loadKota(code, '');
}

async function loadKota(provId, selectedName) {
    var sel  = document.getElementById('selectKota');
    var spin = document.getElementById('loadingKota');
    if (!sel) return;
    sel.disabled  = true;
    sel.classList.add('opacity-40');
    sel.innerHTML = '<option value="">Memuat...</option>';
    if (spin) spin.classList.remove('hidden');
    try {
        var res  = await fetch('/wilayah/regencies/' + encodeURIComponent(provId));
        var data = await res.json();
        sel.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        data.forEach(function (k) {
            var label = k.name || k.nama;
            var opt   = new Option(label, label);
            if (selectedName && label.toUpperCase() === selectedName.toUpperCase()) opt.selected = true;
            sel.appendChild(opt);
        });
        sel.disabled = false;
        sel.classList.remove('opacity-40');
    } catch (e) {
        sel.innerHTML = '<option value="">Gagal memuat kota</option>';
        sel.disabled  = false;
        sel.classList.remove('opacity-40');
    } finally {
        if (spin) spin.classList.add('hidden');
    }
}

window.WILAYAH = { onProvinsiChange: onProvinsiChange };
document.addEventListener('DOMContentLoaded', loadProvinsi);
})();
</script>

<script>
/* ================================================================
   BEREGU — 2-KOLOM COMPACT dengan KTP OCR + Inline Edit
================================================================ */
(function () {
'use strict';

var MIN_MEMBERS   = 6;
var MAX_MEMBERS   = 8;
var MIN_KTP_VALID = 6;

var members  = [];
var nextId   = 0;
var cardData = {};

var TOURNAMENT_DATE = new Date(2026, 7, 24); /* 24 Agustus 2026 */
var VALID_CITY      = ['BALIKPAPAN'];

var CARD_FIELDS = [
    { l: 'NIK',     k: 'nik',           n: 'nik[]',       placeholder: '16 digit NIK' },
    { l: 'Nama',    k: 'nama',          n: 'pemain[]',    placeholder: 'Nama sesuai KTP' },
    { l: 'Tgl Lhr', k: 'tanggal_lahir', n: 'tgl_lahir[]', placeholder: 'DD-MM-YYYY' },
];

/* ── Hitung usia ────────────────────────────────────────────── */
function hitungUsia(str) {
    if (!str || !str.trim()) return null;
    str = str.trim();
    var tgl = null;
    var m1  = str.match(/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{4})$/);
    var m2  = str.match(/^(\d{4})[-\/\.](\d{1,2})[-\/\.](\d{1,2})$/);
    if (m1)      tgl = new Date(+m1[3], +m1[2] - 1, +m1[1]);
    else if (m2) tgl = new Date(+m2[1], +m2[2] - 1, +m2[3]);
    else         tgl = new Date(str);
    if (!tgl || isNaN(tgl.getTime())) return null;
    var usia = TOURNAMENT_DATE.getFullYear() - tgl.getFullYear();
    var bm   = TOURNAMENT_DATE.getMonth() - tgl.getMonth();
    if (bm < 0 || (bm === 0 && TOURNAMENT_DATE.getDate() < tgl.getDate())) usia--;
    if (usia < 0 || usia > 120) return null;
    return usia;
}

function updateUsiaRow(id, tglValue) {
    var usia   = hitungUsia(tglValue);
    var dispEl = document.getElementById('usia_disp_' + id);
    if (!dispEl) return;
    if (usia !== null) {
        dispEl.className   = 'usia-display has-value';
        dispEl.textContent = usia + ' th (Ags 2026)';
    } else {
        dispEl.className   = 'usia-display no-value';
        dispEl.textContent = '—';
    }
}

function isCityValid(city) {
    if (!city) return false;
    city = city.toUpperCase().trim();
    return VALID_CITY.some(function (k) { return city.indexOf(k) !== -1; });
}

/* ── Init ───────────────────────────────────────────────────── */
function init() {
    for (var i = 0; i < MIN_MEMBERS; i++) addMember();
    updateCounter();
}

/* ── Tambah member ──────────────────────────────────────────── */
function addMember() {
    if (members.length >= MAX_MEMBERS) {
        toast('Maksimal ' + MAX_MEMBERS + ' anggota per tim.', 'warn');
        return;
    }
    var id = nextId++;
    members.push({ id: id, file: null, scanned: false, cityValid: null, data: {} });
    cardData[id] = {};
    renderSlot(id);
    updateAddBtn();
    updateCounter();
}

/* ── Hapus member ───────────────────────────────────────────── */
function removeMember(id) {
    var card = document.getElementById('mc_' + id);
    if (card) card.remove();
    members = members.filter(function (m) { return m.id !== id; });
    delete cardData[id];
    updateAddBtn();
    updateCounter();
    renumber();
}

function renumber() {
    var cards = document.querySelectorAll('.member-card');
    cards.forEach(function (c, i) {
        var numEl = c.querySelector('.member-num');
        var lblEl = c.querySelector('.member-lbl');
        if (numEl) numEl.textContent = i + 1;
        if (lblEl) lblEl.textContent = 'Anggota ' + (i + 1);
    });
}

/* ── Render slot compact ────────────────────────────────────── */
function renderSlot(id) {
    var idx       = members.length - 1;
    var deletable = members.length > MIN_MEMBERS;
    var container = document.getElementById('memberSlots');

    var delBtn = deletable
        ? '<button type="button" onclick="BEREGU.remove(' + id + ')"'
          + ' class="w-6 h-6 rounded-md flex items-center justify-center transition flex-shrink-0"'
          + ' style="background:rgba(239,68,68,.18);" title="Hapus">'
          + '<svg width="10" height="10" fill="none" stroke="#f87171" viewBox="0 0 24 24" stroke-width="2.5">'
          + '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>'
          + '</svg></button>'
        : '';

    var html =
        '<div id="mc_' + id + '" class="member-card" data-id="' + id + '">'

        /* ── Header compact ── */
        + '<div class="flex items-center justify-between mb-3">'
        +   '<div class="flex items-center gap-2">'
        +     '<div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0"'
        +          ' style="background:rgba(249,115,22,.14);border:1px solid rgba(249,115,22,.3);">'
        +       '<span class="text-brand-400 font-black member-num" style="font-size:9px;">' + (idx + 1) + '</span>'
        +     '</div>'
        +     '<span class="member-lbl text-white/75 font-bold" style="font-size:11px;">Anggota ' + (idx + 1) + '</span>'
        +     '<span id="scan_badge_' + id + '" class="scan-badge">'
        +       '<svg width="8" height="8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">'
        +         '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>'
        +       '</svg>'
        +       'Ter-scan'
        +     '</span>'
        +   '</div>'
        +   delBtn
        + '</div>'

        /* ── Upload area compact ── */
        + '<div id="dz_' + id + '" class="ktp-dropzone"'
        +     ' onclick="document.getElementById(\'fi_' + id + '\').click()"'
        +     ' ondragover="event.preventDefault();this.classList.add(\'drag-over\')"'
        +     ' ondragleave="this.classList.remove(\'drag-over\')"'
        +     ' ondrop="BEREGU.onDrop(event,' + id + ')">'

        /* Preview */
        +   '<div id="prev_' + id + '" class="hidden w-full flex items-center gap-2 px-3 py-2">'
        +     '<div class="relative flex-shrink-0">'
        +       '<img id="prevImg_' + id + '" src="" alt="" class="preview-img-compact">'
        +       '<button type="button" onclick="BEREGU.resetFile(event,' + id + ')"'
        +               ' class="absolute -top-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center"'
        +               ' style="background:#ef4444;">'
        +         '<svg width="7" height="7" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="3">'
        +           '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>'
        +         '</svg>'
        +       '</button>'
        +     '</div>'
        +     '<p class="text-white/35" style="font-size:10px;">Klik ganti</p>'
        +   '</div>'

        /* Placeholder compact */
        +   '<div id="dzDefault_' + id + '" class="flex flex-col items-center py-2 gap-1">'
        +     '<svg width="18" height="18" viewBox="0 0 24 24" fill="none"'
        +          ' stroke="rgba(249,115,22,.5)" stroke-width="1.5">'
        +       '<rect x="3" y="5" width="18" height="14" rx="2"/>'
        +       '<path d="M7 9h10M7 13h6"/>'
        +     '</svg>'
        +     '<p class="text-white/40" style="font-size:10px;">Upload KTP</p>'
        +   '</div>'
        + '</div>' /* /dropzone */

        /* Hidden file input */
        + '<input type="file" id="fi_' + id + '" name="ktp_files[]"'
        +        ' accept="image/jpeg,image/png,image/webp" class="hidden"'
        +        ' onchange="BEREGU.onFileSelect(this,' + id + ')">'

        /* Scan button compact */
        + '<button type="button" id="scanBtn_' + id + '"'
        +         ' onclick="BEREGU.scan(' + id + ')"'
        +         ' class="hidden mt-2 w-full rounded-lg font-display font-bold text-white'
        +         ' tracking-wider flex items-center justify-center gap-1.5"'
        +         ' style="background:linear-gradient(135deg,#f97316,#c2410c);'
        +         'padding:6px 10px;font-size:10px;">'
        +   '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">'
        +     '<path stroke-linecap="round" stroke-linejoin="round"'
        +          ' d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>'
        +   '</svg>'
        +   'SCAN KTP'
        + '</button>'

        /* Loading compact */
        + '<div id="scanLoading_' + id + '" class="hidden mt-2 text-center" style="padding:4px 0;">'
        +   '<p class="text-brand-400 font-semibold" style="font-size:10px;">Membaca KTP...</p>'
        +   '<div class="scan-loading-bar"><div class="scan-loading-bar-inner"></div></div>'
        + '</div>'

        /* ── KTP Data Card — inline editable ── */
        + '<div id="ktpDataCard_' + id + '" class="ktp-data-card">'
        +   '<p class="ktp-edit-hint">✏ Klik untuk edit</p>'
        +   '<div id="ktpDataRows_' + id + '"></div>'
        + '</div>'

        + '</div>'; /* /member-card */

    container.insertAdjacentHTML('beforeend', html);
}

/* ── File handling ──────────────────────────────────────────── */
function onFileSelect(input, id) {
    if (input.files && input.files[0]) processFile(input.files[0], id);
}

function onDrop(e, id) {
    e.preventDefault();
    document.getElementById('dz_' + id).classList.remove('drag-over');
    var file = e.dataTransfer && e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        var dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('fi_' + id).files = dt.files;
        processFile(file, id);
    }
}

function processFile(file, id) {
    if (file.size > 5 * 1024 * 1024) { toast('File terlalu besar. Maks 5MB.', 'error'); return; }
    var m = getMember(id);
    if (m) { m.file = file; m.scanned = false; }
    var reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById('prevImg_' + id).src = e.target.result;
        show('prev_'      + id);
        hide('dzDefault_' + id);
        show('scanBtn_'   + id);
        resetCardUI(id);
    };
    reader.readAsDataURL(file);
}

function resetFile(e, id) {
    e.stopPropagation();
    var m = getMember(id);
    if (m) { m.file = null; m.scanned = false; m.cityValid = null; m.data = {}; }
    cardData[id] = {};
    document.getElementById('fi_' + id).value = '';
    hide('prev_'        + id);
    show('dzDefault_'   + id);
    hide('scanBtn_'     + id);
    hide('scanLoading_' + id);
    resetCardUI(id);
    updateCounter();
}

function resetCardUI(id) {
    var card  = document.getElementById('ktpDataCard_' + id);
    var rows  = document.getElementById('ktpDataRows_' + id);
    var badge = document.getElementById('scan_badge_'  + id);
    var mc    = document.getElementById('mc_'          + id);
    if (card)  card.className = 'ktp-data-card';
    if (rows)  rows.innerHTML = '';
    if (badge) badge.style.display = 'none';
    if (mc)    mc.classList.remove('scanned', 'city-invalid');
}

/* ── SCAN OCR ───────────────────────────────────────────────── */
function scan(id) {
    var m = getMember(id);
    if (!m || !m.file) return;

    hide('scanBtn_'     + id);
    show('scanLoading_' + id);
    resetCardUI(id);

    var fd   = new FormData();
    fd.append('image', m.file);
    var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

    fetch('/ocr/ktp', {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body:    fd,
    })
    .then(function (r) {
        hide('scanLoading_' + id);
        show('scanBtn_'     + id);
        return r.json().then(function (json) { return { ok: r.ok, json: json }; });
    })
    .then(function (res) {
        if (!res.ok || !res.json.success) {
            toast(res.json.message || 'Gagal scan. Coba foto lebih jelas.', 'error');
            return;
        }
        renderKtpCard(id, res.json.data);
        m.scanned   = true;
        m.cityValid = res.json.data.city_valid;
        m.data      = res.json.data;

        var badge = document.getElementById('scan_badge_' + id);
        if (badge) badge.style.display = 'inline-flex';

        var mc = document.getElementById('mc_' + id);
        if (mc) {
            mc.classList.toggle('scanned',      m.cityValid);
            mc.classList.toggle('city-invalid', !m.cityValid);
        }
        updateCounter();

        var msg = m.cityValid
            ? '✓ Anggota ' + (getIndexById(id) + 1) + ' — KTP Balikpapan valid'
            : '⚠ Anggota ' + (getIndexById(id) + 1) + ' — Kota bukan Balikpapan';
        toast(msg, m.cityValid ? 'success' : 'warn');
    })
    .catch(function () {
        hide('scanLoading_' + id);
        show('scanBtn_'     + id);
        toast('Tidak bisa konek ke OCR service.', 'error');
    });
}

/* ── Render KTP Data Card ───────────────────────────────────── */
function renderKtpCard(id, data) {
    cardData[id] = {};

    var card = document.getElementById('ktpDataCard_' + id);
    var rows = document.getElementById('ktpDataRows_' + id);
    if (!card || !rows) return;
    rows.innerHTML = '';

    CARD_FIELDS.forEach(function (f) {
        var raw = f.k === 'tanggal_lahir'
            ? (data.tanggal_lahir || data.tgl_lahir || '')
            : (data[f.k] || '');
        var v = ('' + raw).trim();
        cardData[id][f.k] = v;

        var valId = 'kval_' + id + '_' + f.k;
        var inpId = 'kinp_' + id + '_' + f.k;
        var hidId = 'khid_' + id + '_' + f.k;

        var valContent = v
            ? esc(v)
            : '<span style="color:rgba(255,255,255,.22);font-style:italic">—</span>';

        rows.innerHTML +=
            '<div class="ktp-row">'
            + '<span class="ktp-label">' + f.l + ' <span style="color:#f97316">*</span></span>'
            + '<span id="' + valId + '" class="ktp-value hl"'
            +       ' title="Klik untuk edit"'
            +       ' onclick="BEREGU.inlineEdit(' + id + ',\'' + f.k + '\')">'
            +   valContent
            + '</span>'
            + '<input id="' + inpId + '" type="text"'
            +        ' class="ktp-inline-input" style="display:none"'
            +        ' value="' + esc(v) + '"'
            +        ' placeholder="' + esc(f.placeholder || '') + '"'
            +        ' onkeydown="BEREGU.inlineKey(event,' + id + ',\'' + f.k + '\')"'
            +        ' onblur="BEREGU.inlineSave(' + id + ',\'' + f.k + '\')">'
            + '<input type="hidden" id="' + hidId + '" name="' + f.n + '" value="' + esc(v) + '">'
            + '</div>';
    });

    /* Row kota hidden */
    var kotaVal = (data.kota || '').trim();
    rows.innerHTML +=
        '<input type="hidden" id="khid_' + id + '_kota" name="kota_ktp[]" value="' + esc(kotaVal) + '">';

    /* Row usia read-only compact */
    var tglVal   = cardData[id]['tanggal_lahir'] || '';
    var usia     = hitungUsia(tglVal);
    var usiaText = usia !== null ? usia + ' th (Ags 2026)' : '—';
    var uClass   = usia !== null ? 'usia-display has-value' : 'usia-display no-value';

    rows.innerHTML +=
        '<div class="ktp-row" style="margin-top:4px;padding-top:6px;border-top:1px solid rgba(249,115,22,.1);">'
        + '<span class="ktp-label" style="color:rgba(249,115,22,.45);">Usia</span>'
        + '<span id="usia_disp_' + id + '" class="' + uClass + '">' + usiaText + '</span>'
        + '<span style="font-size:8px;color:rgba(249,115,22,.3);flex-shrink:0;">auto</span>'
        + '</div>';

    /* City badge compact */
    var cityValid = data.city_valid;
    var kotaRaw   = data.kota || '';
    var badgeHtml;
    if (cityValid) {
        badgeHtml = '<div class="city-badge valid">✓ KTP Balikpapan — Memenuhi Syarat</div>';
    } else if (kotaRaw) {
        badgeHtml = '<div class="city-badge invalid">✗ "' + esc(kotaRaw) + '" — Bukan Balikpapan</div>';
    } else {
        badgeHtml = '<div class="city-badge empty">— Kota belum terbaca</div>';
    }
    rows.innerHTML += badgeHtml;

    card.className = 'ktp-data-card show valid-card';
}

/* ── Inline edit ────────────────────────────────────────────── */
function inlineEdit(id, fieldKey) {
    var valEl = document.getElementById('kval_' + id + '_' + fieldKey);
    var inpEl = document.getElementById('kinp_' + id + '_' + fieldKey);
    if (!valEl || !inpEl) return;
    valEl.style.display = 'none';
    inpEl.style.display = '';
    inpEl.focus();
    if (inpEl.select) inpEl.select();
}

function inlineSave(id, fieldKey) {
    var valEl = document.getElementById('kval_' + id + '_' + fieldKey);
    var inpEl = document.getElementById('kinp_' + id + '_' + fieldKey);
    var hidEl = document.getElementById('khid_' + id + '_' + fieldKey);
    if (!valEl || !inpEl) return;

    var newVal  = inpEl.value.trim();
    var origVal = (cardData[id] && cardData[id][fieldKey]) || '';
    var edited  = (newVal !== origVal);

    if (hidEl) hidEl.value = newVal;
    if (cardData[id]) cardData[id][fieldKey] = newVal;

    if (newVal) {
        valEl.innerHTML   = esc(newVal);
        valEl.style.color = edited ? '#fbbf24' : '';
        valEl.title       = edited ? 'Diedit manual' : 'Klik untuk edit';
    } else {
        valEl.innerHTML   = '<span style="color:rgba(255,255,255,.22);font-style:italic">—</span>';
        valEl.style.color = '';
    }
    if (edited && inpEl.classList) inpEl.classList.add('was-edited');
    inpEl.style.display = 'none';
    valEl.style.display = '';

    if (fieldKey === 'tanggal_lahir') updateUsiaRow(id, newVal);
}

function inlineKey(e, id, fieldKey) {
    if (e.key === 'Enter')  { e.preventDefault(); inlineSave(id, fieldKey); }
    if (e.key === 'Escape') {
        var inpEl = document.getElementById('kinp_' + id + '_' + fieldKey);
        var valEl = document.getElementById('kval_' + id + '_' + fieldKey);
        if (inpEl) inpEl.style.display = 'none';
        if (valEl) valEl.style.display = '';
    }
}

/* ── Update counter ─────────────────────────────────────────── */
function updateCounter() {
    var total      = members.length;
    var validCount = members.filter(function (m) { return m.cityValid === true; }).length;
    var pct        = total > 0 ? Math.round((validCount / Math.max(total, MIN_MEMBERS)) * 100) : 0;

    var textEl = document.getElementById('counterText');
    var fillEl = document.getElementById('counterFill');
    var noteEl = document.getElementById('counterNote');

    if (textEl) textEl.textContent = validCount + ' / ' + total + ' anggota KTP valid';
    if (fillEl) {
        fillEl.style.width      = pct + '%';
        fillEl.style.background = validCount >= MIN_KTP_VALID ? '#34d399' : '#f97316';
    }
    if (noteEl) {
        if (validCount === 0) {
            noteEl.textContent = 'Scan KTP anggota untuk melihat progress validasi';
            noteEl.style.color = 'rgba(255,255,255,.28)';
        } else if (validCount < MIN_KTP_VALID) {
            noteEl.textContent = 'Butuh ' + (MIN_KTP_VALID - validCount) + ' KTP Balikpapan lagi';
            noteEl.style.color = '#f97316';
        } else {
            noteEl.textContent = '✓ Syarat minimal terpenuhi — siap dikirim';
            noteEl.style.color = '#34d399';
        }
    }
}

/* ── Update tombol tambah ───────────────────────────────────── */
function updateAddBtn() {
    var btn   = document.getElementById('addMemberBtn');
    var maxed = members.length >= MAX_MEMBERS;
    if (!btn) return;
    btn.disabled      = maxed;
    btn.style.opacity = maxed ? '.3' : '1';
}

/* ── Validasi submit ────────────────────────────────────────── */
function validateSubmit(e) {
    var missing = [];

    members.forEach(function (m, i) {
        CARD_FIELDS.forEach(function (f) {
            var hidEl = document.getElementById('khid_' + m.id + '_' + f.k);
            if (hidEl && !hidEl.value.trim()) {
                missing.push('Anggota ' + (i + 1) + ': ' + f.l + ' wajib diisi');
                var row = hidEl.closest && hidEl.closest('.ktp-row');
                if (row) row.style.background = 'rgba(239,68,68,.09)';
            }
        });
    });

    if (missing.length > 0) {
        e.preventDefault();
        toast('Lengkapi: ' + missing[0] + (missing.length > 1 ? ' (+' + (missing.length - 1) + ' lainnya)' : ''), 'error');
        return;
    }

    if (members.length < MIN_MEMBERS) {
        e.preventDefault();
        toast('Minimal ' + MIN_MEMBERS + ' anggota harus didaftarkan.', 'error');
        return;
    }

    var validCount = members.filter(function (m) { return m.cityValid === true; }).length;
    if (validCount < MIN_KTP_VALID) {
        e.preventDefault();
        toast('Minimal ' + MIN_KTP_VALID + ' KTP Balikpapan diperlukan. Saat ini: ' + validCount + '.', 'error');
    }
}

/* ── Helpers ────────────────────────────────────────────────── */
function getMember(id)    { return members.find(function (m) { return m.id === id; }); }
function getIndexById(id) { return members.findIndex(function (m) { return m.id === id; }); }
function show(elId)       { var el = document.getElementById(elId); if (el) el.classList.remove('hidden'); }
function hide(elId)       { var el = document.getElementById(elId); if (el) el.classList.add('hidden'); }
function esc(s) {
    return String(s)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

/* ── Toast ──────────────────────────────────────────────────── */
var _tt = null;
function toast(msg, type) {
    var el = document.getElementById('_beregToast');
    if (!el) {
        el = document.createElement('div');
        el.id = '_beregToast';
        el.style.cssText = 'position:fixed;top:88px;right:20px;z-index:99999;max-width:360px;'
            + 'padding:10px 14px;border-radius:11px;font-size:11px;line-height:1.5;font-weight:600;'
            + 'box-shadow:0 8px 36px rgba(0,0,0,.45);transition:opacity .3s,transform .3s;pointer-events:none;';
        document.body.appendChild(el);
    }
    var styles = {
        success: 'background:rgba(6,30,18,.97);border:1px solid rgba(16,185,129,.38);color:#34d399;',
        warn:    'background:rgba(30,22,4,.97);border:1px solid rgba(234,179,8,.38);color:#fbbf24;',
        error:   'background:rgba(30,6,6,.97);border:1px solid rgba(239,68,68,.38);color:#f87171;',
    };
    el.style.cssText += (styles[type] || styles.error) + 'opacity:1;transform:translateY(0);';
    el.textContent = msg;
    if (_tt) clearTimeout(_tt);
    _tt = setTimeout(function () {
        el.style.opacity   = '0';
        el.style.transform = 'translateY(-8px)';
    }, 5000);
}

/* ── Expose ─────────────────────────────────────────────────── */
window.BEREGU = {
    addMember:   addMember,
    remove:      removeMember,
    onFileSelect: onFileSelect,
    onDrop:      onDrop,
    resetFile:   resetFile,
    scan:        scan,
    inlineEdit:  inlineEdit,
    inlineSave:  inlineSave,
    inlineKey:   inlineKey,
};

/* ── Bootstrap ──────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    init();
    var form = document.getElementById('regForm');
    if (form) form.addEventListener('submit', validateSubmit);
});

})();
</script>
@endpush