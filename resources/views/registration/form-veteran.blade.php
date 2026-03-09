@extends('layouts.app')

@section('title', 'Pendaftaran Ganda Veteran Putra — Bayan Open 2026')

@push('styles')
<style>
    @keyframes pulseDot {
        0%,100% { opacity:1; box-shadow:0 0 0 0 rgba(249,115,22,0.5); }
        50%      { opacity:0.7; box-shadow:0 0 0 4px rgba(249,115,22,0); }
    }
    @keyframes fadeSlideUp {
        from { opacity:0; transform:translateY(16px); }
        to   { opacity:1; transform:translateY(0); }
    }
    @keyframes shimmerScan {
        0%   { background-position: -200% center; }
        100% { background-position:  200% center; }
    }
    .form-section { animation: fadeSlideUp 0.4s ease both; }
    .form-section:nth-child(1) { animation-delay:0.05s; }
    .form-section:nth-child(2) { animation-delay:0.10s; }
    .form-section:nth-child(3) { animation-delay:0.15s; }
    .form-section:nth-child(4) { animation-delay:0.20s; }
    .form-section:nth-child(5) { animation-delay:0.25s; }

    .age-badge-valid   { background:rgba(16,185,129,0.12); border-color:rgba(16,185,129,0.4); color:#34d399; }
    .age-badge-invalid { background:rgba(239,68,68,0.12);  border-color:rgba(239,68,68,0.4);  color:#f87171; }
    .age-badge-pending { background:rgba(255,255,255,0.04); border-color:rgba(255,255,255,0.1); color:rgba(255,255,255,0.3); }

    .pemain-ocr-card {
        border-radius:16px;
        border:1.5px solid rgba(234,179,8,0.2);
        background:rgba(20,16,4,0.7);
        padding:22px;
        transition:border-color 0.25s, background 0.25s;
    }
    .pemain-ocr-card.scanned     { border-color:rgba(16,185,129,0.45); background:rgba(4,20,12,0.7); }
    .pemain-ocr-card.invalid-age { border-color:rgba(239,68,68,0.45);  background:rgba(20,4,4,0.7); }

    /* Card data KTP hasil scan */
    .ktp-data-card {
        border-radius:12px;
        background:rgba(255,255,255,0.03);
        border:1px solid rgba(255,255,255,0.08);
        padding:14px 16px;
        margin-top:12px;
        display:none;
    }
    .ktp-data-card.show { display:block; animation: fadeSlideUp 0.3s ease both; }
    .ktp-data-card.valid-card   { background:rgba(16,185,129,0.05); border-color:rgba(16,185,129,0.2); }
    .ktp-data-card.invalid-card { background:rgba(239,68,68,0.05);  border-color:rgba(239,68,68,0.2); }

    .ktp-row {
        display:flex; align-items:flex-start; gap:10px;
        padding:5px 0;
        border-bottom:1px solid rgba(255,255,255,0.04);
    }
    .ktp-row:last-child { border-bottom:none; padding-bottom:0; }
    .ktp-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:rgba(255,255,255,0.3); min-width:90px; flex-shrink:0; padding-top:1px; }
    .ktp-value { font-size:12px; color:rgba(255,255,255,0.75); line-height:1.4; word-break:break-word; }
    .ktp-value.highlight { color:#fff; font-weight:600; }

    /* ── Select dropdown dark theme ── */
    select.input-field {
        color: rgba(255,255,255,0.85) !important;
        background-color: #0d1117 !important;
        cursor: pointer;
    }
    select.input-field option {
        background-color: #0d1117;
        color: rgba(255,255,255,0.85);
        padding: 8px 12px;
    }
    select.input-field option:disabled {
        color: rgba(255,255,255,0.3);
    }
    select.input-field option:hover,
    select.input-field option:checked {
        background-color: #1e2a3a;
        color: #fff;
    }
    select.input-field:disabled {
        opacity: 0.4 !important;
        cursor: not-allowed;
    }

    .scan-loading-bar {
        height:3px; border-radius:99px; overflow:hidden;
        background:rgba(234,179,8,0.1); margin-top:10px;
    }
    .scan-loading-bar-inner {
        height:100%; width:40%;
        background:linear-gradient(90deg,transparent,#eab308,transparent);
        background-size:200% 100%;
        animation: shimmerScan 1.2s ease infinite;
    }
</style>
@endpush

@section('content')
<section class="min-h-screen py-20 px-6">
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="text-center mb-10 form-section">
        <a href="{{ route('registration.index') }}" class="inline-flex items-center gap-2 text-white/30 text-xs hover:text-white/60 transition mb-6">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Ganti kategori
        </a>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-500/40 bg-brand-500/10 text-brand-300 text-xs font-semibold uppercase tracking-widest mb-4">
            Pendaftaran Online · Bayan Open 2026
        </div>
        <h1 class="font-display text-3xl font-bold mb-2">Formulir Pendaftaran</h1>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-3" style="background:rgba(234,179,8,0.1);border:1px solid rgba(234,179,8,0.3);">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(250,204,21,1)" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <span class="text-yellow-400 text-xs font-bold uppercase tracking-widest">Ganda Veteran Putra</span>
        </div>
        <div class="mt-3 mx-auto max-w-md p-3 rounded-xl text-xs" style="background:rgba(234,179,8,0.06);border:1px solid rgba(234,179,8,0.15);">
            <p class="text-yellow-400/80 font-semibold mb-1">⚡ Regulasi Usia</p>
            <p class="text-white/40 leading-relaxed">
                Kedua pemain <strong class="text-white/60">wajib berusia minimal 30 tahun</strong> per tanggal
                <strong class="text-white/60">24 Agustus 2026</strong> —
                artinya lahir pada atau sebelum <strong class="text-white/60">24 Agustus 1995</strong>.
            </p>
        </div>
        <p class="text-white/40 text-sm mt-4">Isi semua data dengan benar dan lengkap</p>
    </div>

    @if($errors->any())
    <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 form-section">
        <p class="text-red-400 text-sm font-semibold mb-2">Terdapat kesalahan:</p>
        <ul class="text-red-300 text-sm space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('registration.store') }}" method="POST" enctype="multipart/form-data" id="regForm">
    @csrf
    <input type="hidden" name="kategori" value="ganda-veteran-putra">

    {{-- ===== SECTION 1: DATA TIM ===== --}}
    <div class="card-glass rounded-2xl p-8 mb-6 form-section">
        <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs">1</span>
            Data Tim & Kontak
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">Nama Ketua Tim / PIC <span class="text-brand-400">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap ketua tim"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('nama') border-red-500 @enderror" required>
                @error('nama')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">Nama Tim / PB <span class="text-brand-400">*</span></label>
                <input type="text" name="tim_pb" value="{{ old('tim_pb') }}" placeholder="Contoh: PB Garuda Sakti"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('tim_pb') border-red-500 @enderror" required>
                @error('tim_pb')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">Email <span class="text-brand-400">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('email') border-red-500 @enderror" required>
                <p class="text-white/25 text-xs mt-1">Receipt dikirim ke email ini</p>
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">Nomor WhatsApp / HP <span class="text-brand-400">*</span></label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08123456789"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('no_hp') border-red-500 @enderror" required>
                @error('no_hp')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            {{-- Provinsi (wilayah.id) --}}
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Provinsi <span class="text-brand-400">*</span>
                </label>
                <div class="relative">
                    <select id="selectProvinsi" name="provinsi" onchange="onProvinsiChange(this)"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm appearance-none @error('provinsi') border-red-500 @enderror" required>
                        <option value="">-- Pilih Provinsi --</option>
                    </select>
                    <div id="loadingProvinsi" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                        <svg class="animate-spin w-4 h-4 text-white/30" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    </div>
                </div>
                <input type="hidden" name="provinsi_code" id="provinsiCode">
                @error('provinsi')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Kabupaten/Kota (wilayah.id, cascade dari provinsi) --}}
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Kabupaten / Kota <span class="text-brand-400">*</span>
                </label>
                <div class="relative">
                    <select id="selectKota" name="kota" disabled
                        class="input-field w-full px-4 py-3 rounded-xl text-sm appearance-none opacity-50 @error('kota') border-red-500 @enderror" required>
                        <option value="">-- Pilih Provinsi dulu --</option>
                    </select>
                    <div id="loadingKota" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                        <svg class="animate-spin w-4 h-4 text-white/30" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    </div>
                </div>
                <input type="hidden" name="kota_code" id="kotaCode">
                @error('kota')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- ===== SECTION 2: DATA PELATIH ===== --}}
    <div class="card-glass rounded-2xl p-8 mb-6 form-section">
        <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs">2</span>
            Data Pelatih <span class="text-white/30 text-xs font-normal normal-case ml-1">(opsional)</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">Nama Pelatih</label>
                <input type="text" name="nama_pelatih" value="{{ old('nama_pelatih') }}" placeholder="Nama lengkap pelatih" class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">No. HP Pelatih</label>
                <input type="text" name="no_hp_pelatih" value="{{ old('no_hp_pelatih') }}" placeholder="08123456789" class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
        </div>
    </div>

    {{-- ===== SECTION 3: SCAN KTP ===== --}}
    <div class="rounded-2xl p-8 mb-6 form-section" style="background:rgba(234,179,8,0.04);border:1.5px solid rgba(234,179,8,0.18);">

        <h2 class="font-display text-sm font-bold mb-1 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black" style="background:rgba(234,179,8,0.85);color:#000;">3</span>
            Scan KTP & Verifikasi Usia Pemain
        </h2>
        <p class="text-white/35 text-xs mb-7 ml-9">Upload foto KTP masing-masing pemain, lalu klik <strong class="text-yellow-400/70">SCAN KTP</strong>. Data akan terisi otomatis.</p>

        {{-- ─────────────── PEMAIN 1 ─────────────── --}}
        <div id="ocr_card_0" class="pemain-ocr-card mb-5">

            {{-- Header pemain --}}
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background:rgba(234,179,8,0.15);border:1px solid rgba(234,179,8,0.3);">
                        <span class="text-yellow-400 text-xs font-black">1</span>
                    </div>
                    <span class="text-white/80 text-sm font-bold">Pemain 1</span>
                </div>
                <div id="age_badge_0" class="age-badge-pending px-3 py-1 rounded-full text-xs font-bold border">Belum scan</div>
            </div>

            {{-- Upload KTP --}}
            <div class="mb-4">
                <label class="block text-white/50 text-xs font-semibold uppercase tracking-wide mb-2">Foto KTP <span class="text-brand-400">*</span></label>
                <div id="ktpDropzone_0"
                    onclick="document.getElementById('ktpInput_0').click()"
                    class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all duration-200"
                    style="border-color:rgba(234,179,8,0.25);background:rgba(234,179,8,0.02);"
                    ondragover="event.preventDefault();this.style.borderColor='rgba(234,179,8,0.6)'"
                    ondragleave="this.style.borderColor='rgba(234,179,8,0.25)'"
                    ondrop="handleDrop(event,0)">
                    <div id="ktpPreview_0" class="hidden">
                        <div class="relative inline-block mb-2">
                            <img id="ktpPreviewImg_0" src="" alt="" class="max-h-32 mx-auto rounded-lg object-contain" style="box-shadow:0 4px 16px rgba(0,0,0,0.5);">
                            <button type="button" onclick="resetOcrSlot(event,0)"
                                class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 flex items-center justify-center transition">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <p class="text-white/30 text-xs">Klik untuk ganti foto</p>
                    </div>
                    <div id="ktpDefault_0" class="flex flex-col items-center py-2">
                        <div class="w-10 h-10 rounded-xl bg-yellow-500/10 flex items-center justify-center mb-2">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(250,204,21,0.6)" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
                        </div>
                        <p class="text-white/55 text-sm font-medium">Klik atau seret foto KTP</p>
                        <p class="text-white/25 text-xs mt-0.5">JPG, PNG · Maks 5MB</p>
                    </div>
                </div>
                <input type="file" id="ktpInput_0" name="ktp_files[]" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="handleFileSelect(this,0)">

                {{-- Scan button --}}
                <button type="button" id="scanBtn_0" onclick="scanKtp(0)"
                    class="hidden mt-3 w-full py-2.5 rounded-xl font-display text-xs font-bold text-white tracking-wider flex items-center justify-center gap-2"
                    style="background:linear-gradient(135deg,#eab308,#b45309);box-shadow:0 4px 16px rgba(234,179,8,0.25);">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                    SCAN KTP & VERIFIKASI USIA
                </button>

                {{-- Loading --}}
                <div id="scanLoading_0" class="hidden mt-3 text-center py-2">
                    <p class="text-yellow-400 text-xs font-semibold mb-1">Membaca KTP dengan AI...</p>
                    <div class="scan-loading-bar"><div class="scan-loading-bar-inner"></div></div>
                </div>
            </div>

            {{-- ★ CARD DATA KTP HASIL SCAN ★ --}}
            <div id="ktpDataCard_0" class="ktp-data-card">
                <p class="text-xs font-bold text-white/40 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
                    Data KTP Terbaca
                </p>
                <div id="ktpDataRows_0"></div>
            </div>

            {{-- Nama pemain (dari OCR) --}}
            <div class="mt-4">
                <label class="block text-white/50 text-xs font-semibold uppercase tracking-wide mb-1.5">
                    Nama Pemain <span class="text-brand-400">*</span>
                    <span class="text-white/25 font-normal normal-case ml-1">— terisi otomatis dari KTP</span>
                </label>
                <input type="text" name="pemain[]" id="pemain_name_0" value="{{ old('pemain.0') }}"
                    placeholder="Nama akan terisi otomatis setelah scan KTP"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm" required>
                <p id="tgl_info_0" class="hidden text-xs mt-1.5 font-medium"></p>
            </div>

            {{-- Hidden fields --}}
            <input type="hidden" name="tgl_lahir[]"   id="tgl_lahir_0"   value="{{ old('tgl_lahir.0') }}">
            <input type="hidden" name="usia_valid[]"  id="usia_valid_0"  value="0">
            <input type="hidden" name="usia_hitung[]" id="usia_hitung_0" value="">
        </div>

        {{-- ─────────────── PEMAIN 2 ─────────────── --}}
        <div id="ocr_card_1" class="pemain-ocr-card">

            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background:rgba(234,179,8,0.15);border:1px solid rgba(234,179,8,0.3);">
                        <span class="text-yellow-400 text-xs font-black">2</span>
                    </div>
                    <span class="text-white/80 text-sm font-bold">Pemain 2</span>
                </div>
                <div id="age_badge_1" class="age-badge-pending px-3 py-1 rounded-full text-xs font-bold border">Belum scan</div>
            </div>

            <div class="mb-4">
                <label class="block text-white/50 text-xs font-semibold uppercase tracking-wide mb-2">Foto KTP <span class="text-brand-400">*</span></label>
                <div id="ktpDropzone_1"
                    onclick="document.getElementById('ktpInput_1').click()"
                    class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all duration-200"
                    style="border-color:rgba(234,179,8,0.25);background:rgba(234,179,8,0.02);"
                    ondragover="event.preventDefault();this.style.borderColor='rgba(234,179,8,0.6)'"
                    ondragleave="this.style.borderColor='rgba(234,179,8,0.25)'"
                    ondrop="handleDrop(event,1)">
                    <div id="ktpPreview_1" class="hidden">
                        <div class="relative inline-block mb-2">
                            <img id="ktpPreviewImg_1" src="" alt="" class="max-h-32 mx-auto rounded-lg object-contain" style="box-shadow:0 4px 16px rgba(0,0,0,0.5);">
                            <button type="button" onclick="resetOcrSlot(event,1)"
                                class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 flex items-center justify-center transition">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <p class="text-white/30 text-xs">Klik untuk ganti foto</p>
                    </div>
                    <div id="ktpDefault_1" class="flex flex-col items-center py-2">
                        <div class="w-10 h-10 rounded-xl bg-yellow-500/10 flex items-center justify-center mb-2">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(250,204,21,0.6)" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
                        </div>
                        <p class="text-white/55 text-sm font-medium">Klik atau seret foto KTP</p>
                        <p class="text-white/25 text-xs mt-0.5">JPG, PNG · Maks 5MB</p>
                    </div>
                </div>
                <input type="file" id="ktpInput_1" name="ktp_files[]" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="handleFileSelect(this,1)">

                <button type="button" id="scanBtn_1" onclick="scanKtp(1)"
                    class="hidden mt-3 w-full py-2.5 rounded-xl font-display text-xs font-bold text-white tracking-wider flex items-center justify-center gap-2"
                    style="background:linear-gradient(135deg,#eab308,#b45309);box-shadow:0 4px 16px rgba(234,179,8,0.25);">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                    SCAN KTP & VERIFIKASI USIA
                </button>

                <div id="scanLoading_1" class="hidden mt-3 text-center py-2">
                    <p class="text-yellow-400 text-xs font-semibold mb-1">Membaca KTP dengan AI...</p>
                    <div class="scan-loading-bar"><div class="scan-loading-bar-inner"></div></div>
                </div>
            </div>

            <div id="ktpDataCard_1" class="ktp-data-card">
                <p class="text-xs font-bold text-white/40 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
                    Data KTP Terbaca
                </p>
                <div id="ktpDataRows_1"></div>
            </div>

            <div class="mt-4">
                <label class="block text-white/50 text-xs font-semibold uppercase tracking-wide mb-1.5">
                    Nama Pemain <span class="text-brand-400">*</span>
                    <span class="text-white/25 font-normal normal-case ml-1">— terisi otomatis dari KTP</span>
                </label>
                <input type="text" name="pemain[]" id="pemain_name_1" value="{{ old('pemain.1') }}"
                    placeholder="Nama akan terisi otomatis setelah scan KTP"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm" required>
                <p id="tgl_info_1" class="hidden text-xs mt-1.5 font-medium"></p>
            </div>

            <input type="hidden" name="tgl_lahir[]"   id="tgl_lahir_1"   value="{{ old('tgl_lahir.1') }}">
            <input type="hidden" name="usia_valid[]"  id="usia_valid_1"  value="0">
            <input type="hidden" name="usia_hitung[]" id="usia_hitung_1" value="">
        </div>

        {{-- Summary kedua pemain --}}
        <div id="veteranSummary" class="hidden mt-5 p-4 rounded-xl flex items-center gap-3" style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.3);">
            <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="#34d399"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <div>
                <p class="text-emerald-400 text-xs font-bold">Kedua pemain memenuhi syarat usia veteran!</p>
                <p class="text-emerald-400/50 text-xs">Anda dapat melanjutkan pendaftaran.</p>
            </div>
        </div>

    </div>

    {{-- ===== SECTION 4: RINGKASAN BIAYA ===== --}}
    <div class="card-glass rounded-2xl p-6 mb-6 form-section">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-white/40 text-xs mb-1">Kategori</p>
                <p class="font-display text-white font-bold text-sm">Ganda Veteran Putra</p>
            </div>
            <div class="text-right">
                <p class="text-white/40 text-xs mb-1">Total Pembayaran</p>
                <p class="font-display text-brand-400 font-bold text-2xl">Rp 150.000</p>
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <button type="submit" id="submitBtn" class="btn-primary w-full py-4 rounded-xl font-display text-sm font-bold text-white tracking-wide form-section">
        DAFTAR & BAYAR SEKARANG →
    </button>

    <div id="submitWarning" class="hidden mt-3 p-3 rounded-xl text-center text-xs text-yellow-400 font-medium" style="background:rgba(234,179,8,0.08);border:1px solid rgba(234,179,8,0.2);">
        ⚠️ Harap scan KTP kedua pemain terlebih dahulu untuk verifikasi usia
    </div>

    <p class="text-white/25 text-xs text-center mt-4">Dengan mendaftar, Anda menyetujui syarat & ketentuan Bayan Open 2026</p>

    </form>

</div>
</section>

@push('scripts')
<script>
// ─────────────────────────────────────────────────────────────
// WILAYAH.ID — Provinsi & Kabupaten/Kota Cascade
// ─────────────────────────────────────────────────────────────
// ─────────────────────────────────────────────────────────────
// API WILAYAH — emsifa.github.io (GitHub Pages, no CORS)
// Response: array langsung [ {id, name}, ... ]
// ─────────────────────────────────────────────────────────────
const WILAYAH_BASE = '/wilayah'; // Laravel proxy → emsifa (no CORS)
const OLD_PROVINSI = '{{ old("provinsi") }}';
const OLD_KOTA     = '{{ old("kota") }}';

async function loadProvinsi() {
    const sel  = document.getElementById('selectProvinsi');
    const spin = document.getElementById('loadingProvinsi');
    spin.classList.remove('hidden');
    try {
        const res  = await fetch(`${WILAYAH_BASE}/provinces`);
        const data = await res.json(); // array [{id, name}]
        data.forEach(p => {
            const opt = new Option(p.name, p.name);
            opt.dataset.code = p.id;
            if (OLD_PROVINSI && p.name === OLD_PROVINSI) opt.selected = true;
            sel.appendChild(opt);
        });
        // Restore old value dari session
        if (OLD_PROVINSI) {
            const found = [...sel.options].find(o => o.value === OLD_PROVINSI);
            if (found) {
                document.getElementById('provinsiCode').value = found.dataset.code;
                await loadKota(found.dataset.code, OLD_KOTA);
            }
        }
    } catch (e) { console.error('Gagal load provinsi:', e); }
    finally { spin.classList.add('hidden'); }
}

async function onProvinsiChange(sel) {
    const opt  = sel.options[sel.selectedIndex];
    const code = opt ? opt.dataset.code : '';
    document.getElementById('provinsiCode').value = code || '';

    const kotaSel = document.getElementById('selectKota');
    kotaSel.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
    kotaSel.disabled  = true;
    kotaSel.classList.add('opacity-50');
    document.getElementById('kotaCode').value = '';
    if (!code) return;
    await loadKota(code);
}

async function loadKota(provId, selectedName = '') {
    const sel  = document.getElementById('selectKota');
    const spin = document.getElementById('loadingKota');
    spin.classList.remove('hidden');
    sel.disabled = true;
    sel.classList.add('opacity-50');
    sel.innerHTML = '<option value="">-- Memuat data... --</option>';
    try {
        const res  = await fetch(`${WILAYAH_BASE}/regencies/${provId}`);
        const data = await res.json(); // array [{id, province_id, name}]
        sel.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        data.forEach(k => {
            const opt = new Option(k.name, k.name);
            opt.dataset.code = k.id;
            if (selectedName && k.name === selectedName) opt.selected = true;
            sel.appendChild(opt);
        });
        sel.disabled = false;
        sel.classList.remove('opacity-50');
        if (selectedName) {
            const found = [...sel.options].find(o => o.value === selectedName);
            if (found) document.getElementById('kotaCode').value = found.dataset.code;
        }
    } catch (e) {
        sel.innerHTML = '<option value="">Gagal memuat data</option>';
        console.error('Gagal load kota:', e);
    } finally { spin.classList.add('hidden'); }
}

document.addEventListener('DOMContentLoaded', () => {
    loadProvinsi();
    document.getElementById('selectKota').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        document.getElementById('kotaCode').value = opt ? (opt.dataset.code || '') : '';
    });
});

// ─────────────────────────────────────────────────────────────
// KONSTANTA
// ─────────────────────────────────────────────────────────────
const TOURNAMENT_DATE = new Date(2026, 7, 24);   // 24 Agustus 2026
const MAX_BIRTH_DATE  = new Date(1995, 7, 24);   // 24 Agustus 1995 (batas maks lahir)
const MIN_AGE         = 30;
const ktpFiles        = [null, null];
const scanStatus      = [false, false];

// ─────────────────────────────────────────────────────────────
// HITUNG USIA
// ─────────────────────────────────────────────────────────────
function hitungUsia(str) {
    if (!str) return null;
    let tgl = null;

    const m1 = str.match(/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{4})$/);
    const m2 = str.match(/^(\d{4})[-\/\.](\d{1,2})[-\/\.](\d{1,2})$/);

    if (m1) tgl = new Date(+m1[3], +m1[2]-1, +m1[1]);
    else if (m2) tgl = new Date(+m2[1], +m2[2]-1, +m2[3]);
    else tgl = new Date(str);

    if (!tgl || isNaN(tgl.getTime())) return null;

    let usia = TOURNAMENT_DATE.getFullYear() - tgl.getFullYear();
    const bd = TOURNAMENT_DATE.getMonth() - tgl.getMonth();
    if (bd < 0 || (bd === 0 && TOURNAMENT_DATE.getDate() < tgl.getDate())) usia--;

    return { usia, tgl };
}

// ─────────────────────────────────────────────────────────────
// RENDER CARD DATA KTP
// ─────────────────────────────────────────────────────────────
function renderKtpCard(idx, data, usia, valid) {
    const card = document.getElementById('ktpDataCard_' + idx);
    const rows = document.getElementById('ktpDataRows_' + idx);

    // Field yang ingin ditampilkan (label → key dari OCR)
    const fields = [
        { label: 'NIK',        key: 'nik' },
        { label: 'Nama',       key: 'nama' },
        { label: 'Tgl Lahir',  key: 'tanggal_lahir' },
        { label: 'Usia',       key: '__usia__' },
        { label: 'Jenis Kel.', key: 'jenis_kelamin' },
        { label: 'Alamat',     key: 'alamat' },
        { label: 'Kel/Desa',   key: 'kelurahan' },
        { label: 'Kecamatan',  key: 'kecamatan' },
        { label: 'Kota',       key: 'kota' },
        { label: 'Provinsi',   key: 'provinsi' },
        { label: 'Pekerjaan',  key: 'pekerjaan' },
    ];

    rows.innerHTML = '';
    fields.forEach(({ label, key }) => {
        let val = '';
        if (key === '__usia__') {
            if (usia !== null && usia !== '') {
                const ok = valid;
                val = `<span style="color:${ok ? '#34d399' : '#f87171'};font-weight:700;">${usia} tahun per 24 Ags 2026 — ${ok ? '✓ Memenuhi syarat' : '✗ Tidak memenuhi syarat'}</span>`;
            }
        } else {
            val = (data[key] || '').toString().trim();
        }
        if (!val) return;

        const isHighlight = ['nik','nama','tanggal_lahir','__usia__'].includes(key);
        rows.innerHTML += `
            <div class="ktp-row">
                <span class="ktp-label">${label}</span>
                <span class="ktp-value ${isHighlight && key!=='__usia__' ? 'highlight' : ''}">${key==='__usia__' ? val : escHtml(val)}</span>
            </div>`;
    });

    card.className = `ktp-data-card show ${valid ? 'valid-card' : 'invalid-card'}`;
}

function escHtml(s) {
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// ─────────────────────────────────────────────────────────────
// UPDATE BADGE & INFO
// ─────────────────────────────────────────────────────────────
function updateAgeBadge(idx, tglStr) {
    const badge     = document.getElementById('age_badge_'   + idx);
    const card      = document.getElementById('ocr_card_'    + idx);
    const hiddenV   = document.getElementById('usia_valid_'  + idx);
    const hiddenAge = document.getElementById('usia_hitung_' + idx);
    const infoEl    = document.getElementById('tgl_info_'    + idx);

    if (!tglStr) {
        badge.className   = 'age-badge-pending px-3 py-1 rounded-full text-xs font-bold border';
        badge.textContent = 'Belum scan';
        hiddenV.value     = '0';
        return;
    }

    const result = hitungUsia(tglStr);
    if (!result) {
        badge.className   = 'age-badge-invalid px-3 py-1 rounded-full text-xs font-bold border';
        badge.textContent = 'Tgl lahir tidak valid';
        hiddenV.value     = '0';
        return;
    }

    const { usia, tgl } = result;
    const fmt = tgl.toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' });
    hiddenAge.value = usia;

    const valid = tgl <= MAX_BIRTH_DATE && usia >= MIN_AGE;
    hiddenV.value = valid ? '1' : '0';

    if (valid) {
        badge.className   = 'age-badge-valid px-3 py-1 rounded-full text-xs font-bold border';
        badge.textContent = `✓ ${usia} thn — Memenuhi syarat`;
        card.classList.add('scanned'); card.classList.remove('invalid-age');
    } else {
        badge.className   = 'age-badge-invalid px-3 py-1 rounded-full text-xs font-bold border';
        badge.textContent = `✗ ${usia} thn — Tidak memenuhi syarat`;
        card.classList.add('invalid-age'); card.classList.remove('scanned');
    }

    if (infoEl) {
        infoEl.textContent = `Lahir: ${fmt} · ${usia} tahun per 24 Agustus 2026`;
        infoEl.className   = `text-xs mt-1.5 font-medium ${valid ? 'text-emerald-400' : 'text-red-400'}`;
        infoEl.classList.remove('hidden');
    }

    checkBothScanned();
    return { usia, valid };
}

function checkBothScanned() {
    const v0 = document.getElementById('usia_valid_0').value === '1';
    const v1 = document.getElementById('usia_valid_1').value === '1';
    const summary = document.getElementById('veteranSummary');
    const warning = document.getElementById('submitWarning');

    if (!scanStatus[0] || !scanStatus[1]) return;

    if (v0 && v1) {
        summary.classList.remove('hidden');
        warning.classList.add('hidden');
    } else {
        summary.classList.add('hidden');
        warning.classList.remove('hidden');
        warning.textContent = '⚠️ Salah satu atau kedua pemain tidak memenuhi syarat usia veteran (lahir ≤ 24 Agustus 1995).';
    }
}

// ─────────────────────────────────────────────────────────────
// FILE HANDLING
// ─────────────────────────────────────────────────────────────
function handleFileSelect(input, idx) {
    if (input.files && input.files[0]) processFile(input.files[0], idx);
}

function handleDrop(e, idx) {
    e.preventDefault();
    document.getElementById('ktpDropzone_' + idx).style.borderColor = 'rgba(234,179,8,0.25)';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer(); dt.items.add(file);
        document.getElementById('ktpInput_' + idx).files = dt.files;
        processFile(file, idx);
    }
}

function processFile(file, idx) {
    if (file.size > 5 * 1024 * 1024) { alert('File terlalu besar. Maks 5MB.'); return; }
    ktpFiles[idx] = file;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('ktpPreviewImg_' + idx).src = e.target.result;
        document.getElementById('ktpPreview_'    + idx).classList.remove('hidden');
        document.getElementById('ktpDefault_'    + idx).classList.add('hidden');
        document.getElementById('scanBtn_'       + idx).classList.remove('hidden');
        // Reset card data sebelumnya
        document.getElementById('ktpDataCard_'   + idx).className = 'ktp-data-card';
        document.getElementById('ktpDataRows_'   + idx).innerHTML = '';
    };
    reader.readAsDataURL(file);
}

function resetOcrSlot(e, idx) {
    e.stopPropagation();
    ktpFiles[idx] = null; scanStatus[idx] = false;
    ['ktpInput_','ktpPreview_','scanBtn_','scanLoading_'].forEach(p => {
        const el = document.getElementById(p + idx);
        if (p === 'ktpInput_') el.value = '';
        else el.classList.add('hidden');
    });
    document.getElementById('ktpDefault_'    + idx).classList.remove('hidden');
    document.getElementById('tgl_lahir_'     + idx).value = '';
    document.getElementById('usia_valid_'    + idx).value = '0';
    document.getElementById('usia_hitung_'   + idx).value = '';
    document.getElementById('pemain_name_'   + idx).value = '';
    document.getElementById('ktpDataCard_'   + idx).className = 'ktp-data-card';
    document.getElementById('ktpDataRows_'   + idx).innerHTML = '';
    document.getElementById('ocr_card_'      + idx).classList.remove('scanned','invalid-age');
    const infoEl = document.getElementById('tgl_info_' + idx);
    if (infoEl) { infoEl.textContent=''; infoEl.classList.add('hidden'); }
    updateAgeBadge(idx, null);
    document.getElementById('veteranSummary').classList.add('hidden');
    document.getElementById('submitWarning').classList.add('hidden');
}

// ─────────────────────────────────────────────────────────────
// SCAN OCR — FIXED
// ─────────────────────────────────────────────────────────────
async function scanKtp(idx) {
    if (!ktpFiles[idx]) return;

    document.getElementById('scanBtn_'     + idx).classList.add('hidden');
    document.getElementById('scanLoading_' + idx).classList.remove('hidden');
    document.getElementById('ktpDataCard_' + idx).className = 'ktp-data-card'; // reset

    const formData = new FormData();
    formData.append('image', ktpFiles[idx]);  // key 'image' sesuai Python API & controller

    try {
        const response = await fetch('/ocr/ktp', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept':       'application/json',   // WAJIB — agar Laravel return JSON bukan redirect
            },
            body: formData,
        });

        document.getElementById('scanLoading_' + idx).classList.add('hidden');
        document.getElementById('scanBtn_'     + idx).classList.remove('hidden');

        if (!response.ok) {
            const err = await response.json().catch(() => ({}));
            showToast('❌ ' + (err.message || `HTTP ${response.status} — coba lagi.`), 'error');
            return;
        }

        const result = await response.json();

        if (!result.success) {
            showToast('❌ ' + (result.message || 'Gagal membaca KTP. Coba foto ulang lebih jelas.'), 'error');
            return;
        }

        const data = result.data;

        // ── 1. Isi nama pemain ───────────────────────
        if (data.nama) {
            document.getElementById('pemain_name_' + idx).value = data.nama;
            highlightField('pemain_name_' + idx);
        }

        // ── 2. Isi tanggal lahir + hitung usia ───────
        const tglLahir = (data.tanggal_lahir || data.tgl_lahir || '').trim();
        if (!tglLahir) {
            showToast('⚠️ KTP terbaca tapi tanggal lahir tidak terdeteksi. Coba foto lebih jelas.', 'warn');
            scanStatus[idx] = true;
            return;
        }

        document.getElementById('tgl_lahir_' + idx).value = tglLahir;
        const badgeResult = updateAgeBadge(idx, tglLahir);
        const usia  = document.getElementById('usia_hitung_' + idx).value;
        const valid = document.getElementById('usia_valid_'  + idx).value === '1';

        scanStatus[idx] = true;

        // ── 3. Render card data KTP ───────────────────
        renderKtpCard(idx, data, usia, valid);

        // ── 4. Toast ringkasan ────────────────────────
        if (valid) {
            showToast(`✅ Pemain ${idx+1} — ${data.nama || ''} · ${usia} tahun · Memenuhi syarat veteran!`, 'success');
        } else {
            showToast(`⚠️ Pemain ${idx+1} — ${data.nama || ''} · ${usia} tahun · TIDAK memenuhi syarat (lahir harus ≤ 24 Ags 1995).`, 'warn');
        }

    } catch (err) {
        document.getElementById('scanLoading_' + idx).classList.add('hidden');
        document.getElementById('scanBtn_'     + idx).classList.remove('hidden');
        showToast('❌ Tidak bisa konek ke OCR service. Pastikan Python API & ngrok berjalan.', 'error');
        console.error('OCR error:', err);
    }
}

// ─────────────────────────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────────────────────────
function highlightField(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.borderColor     = '#10b981';
    el.style.backgroundColor = 'rgba(16,185,129,0.09)';
    setTimeout(() => { el.style.borderColor=''; el.style.backgroundColor=''; }, 2500);
}

// Toast notification di pojok kanan atas
let toastTimer = null;
function showToast(msg, type = 'success') {
    let toast = document.getElementById('ocrToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'ocrToast';
        toast.style.cssText = 'position:fixed;top:88px;right:20px;z-index:99999;max-width:360px;padding:12px 16px;border-radius:12px;font-size:12px;line-height:1.5;font-family:"DM Sans",sans-serif;font-weight:600;box-shadow:0 8px 32px rgba(0,0,0,0.4);transition:opacity 0.3s,transform 0.3s;';
        document.body.appendChild(toast);
    }
    const styles = {
        success: 'background:rgba(6,30,18,0.97);border:1px solid rgba(16,185,129,0.4);color:#34d399;',
        warn:    'background:rgba(30,22,4,0.97);border:1px solid rgba(234,179,8,0.4);color:#fbbf24;',
        error:   'background:rgba(30,6,6,0.97);border:1px solid rgba(239,68,68,0.4);color:#f87171;',
    };
    toast.style.cssText += styles[type] || styles.error;
    toast.textContent = msg;
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-8px)';
    }, 5000);
}

// ─────────────────────────────────────────────────────────────
// SUBMIT GUARD
// ─────────────────────────────────────────────────────────────
document.getElementById('regForm').addEventListener('submit', function(e) {
    const v0 = document.getElementById('usia_valid_0').value === '1';
    const v1 = document.getElementById('usia_valid_1').value === '1';
    if (!v0 || !v1) {
        e.preventDefault();
        const w = document.getElementById('submitWarning');
        w.classList.remove('hidden');
        w.textContent = '⚠️ Harap scan KTP kedua pemain. Kedua pemain wajib memenuhi syarat usia veteran (lahir ≤ 24 Agustus 1995).';
        w.scrollIntoView({ behavior:'smooth', block:'center' });
    }
});
</script>
@endpush

@endsection