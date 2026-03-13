@extends('layouts.app')

@section('title', 'Pendaftaran Ganda Veteran Putra — Bayan Open 2026')

@push('styles')
<style>
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0);    }
    }
    @keyframes shimmerScan {
        0%   { background-position: -200% center; }
        100% { background-position:  200% center; }
    }

    .form-section                 { animation: fadeSlideUp .45s ease both; }
    .form-section:nth-child(1)    { animation-delay: .06s; }
    .form-section:nth-child(2)    { animation-delay: .12s; }
    .form-section:nth-child(3)    { animation-delay: .18s; }
    .form-section:nth-child(4)    { animation-delay: .24s; }
    .form-section:nth-child(5)    { animation-delay: .30s; }

    .age-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 99px;
        font-size: 11px; font-weight: 700; border: 1px solid;
        transition: background .25s, border-color .25s, color .25s;
    }
    .age-badge.pending { background: rgba(255,255,255,.04); border-color: rgba(255,255,255,.1);  color: rgba(255,255,255,.28); }
    .age-badge.valid   { background: rgba(16,185,129,.1);  border-color: rgba(16,185,129,.38);  color: #34d399; }
    .age-badge.invalid { background: rgba(239,68,68,.1);   border-color: rgba(239,68,68,.38);   color: #f87171; }

    .pemain-ocr-card {
        border-radius: 18px; border: 1.5px solid rgba(234,179,8,.18);
        background: rgba(20,16,4,.78); padding: 22px;
        transition: border-color .3s, background .3s, box-shadow .3s;
    }
    .pemain-ocr-card.scanned    { border-color: rgba(16,185,129,.42); background: rgba(4,20,12,.78); box-shadow: 0 0 0 1px rgba(16,185,129,.09) inset; }
    .pemain-ocr-card.invalid-age{ border-color: rgba(239,68,68,.42);  background: rgba(20,4,4,.78); }

    .ktp-data-card {
        border-radius: 13px; background: rgba(255,255,255,.025);
        border: 1px solid rgba(255,255,255,.07);
        padding: 14px 16px; margin-top: 14px; display: none;
    }
    .ktp-data-card.show         { display: block; animation: fadeSlideUp .3s ease both; }
    .ktp-data-card.valid-card   { background: rgba(16,185,129,.04); border-color: rgba(16,185,129,.2); }
    .ktp-data-card.invalid-card { background: rgba(239,68,68,.04);  border-color: rgba(239,68,68,.2); }

    .ktp-row {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 5px 0; border-bottom: 1px solid rgba(255,255,255,.04); min-height: 30px;
    }
    .ktp-row:last-child { border-bottom: none; padding-bottom: 0; }
    .ktp-label {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: rgba(255,255,255,.28);
        min-width: 82px; flex-shrink: 0; padding-top: 1px;
    }
    .ktp-value           { font-size: 12px; color: rgba(255,255,255,.72); line-height: 1.4; word-break: break-word; }
    .ktp-value.highlight { color: #fff; font-weight: 600; }

    .ktp-locked-note {
        display: flex; align-items: center; gap: 6px;
        margin-top: 10px; padding-top: 10px;
        border-top: 1px solid rgba(255,255,255,.05);
        font-size: 10px; color: rgba(255,255,255,.18); font-style: italic;
    }

    .scan-loading-bar { height: 3px; border-radius: 99px; overflow: hidden; background: rgba(234,179,8,.1); margin-top: 10px; }
    .scan-loading-bar-inner {
        height: 100%; width: 40%;
        background: linear-gradient(90deg, transparent, #eab308, transparent);
        background-size: 200% 100%; animation: shimmerScan 1.2s ease infinite;
    }

    .veteran-summary { border-radius: 14px; padding: 14px 16px; margin-top: 20px; display: none; }
    .veteran-summary.show { display: flex; align-items: flex-start; gap: 12px; animation: fadeSlideUp .3s ease both; }
    .veteran-summary.ok  { background: rgba(16,185,129,.07); border: 1px solid rgba(16,185,129,.28); }
    .veteran-summary.bad { background: rgba(239,68,68,.07);  border: 1px solid rgba(239,68,68,.28); }

    .total-usia-box { border-radius: 10px; padding: 10px 14px; margin-top: 14px; display: none; border: 1px solid; }
    .total-usia-box.show { display: block; animation: fadeSlideUp .25s ease both; }
    .total-usia-box.ok   { background: rgba(16,185,129,.05); border-color: rgba(16,185,129,.22); }
    .total-usia-box.bad  { background: rgba(239,68,68,.05);  border-color: rgba(239,68,68,.22); }

    .submit-warning {
        display: none; border-radius: 12px; padding: 12px 16px; margin-top: 12px;
        font-size: 12px; font-weight: 600; color: #fbbf24;
        background: rgba(234,179,8,.07); border: 1px solid rgba(234,179,8,.2);
    }

    select.input-field { color: rgba(255,255,255,.85) !important; background-color: #0d1117 !important; cursor: pointer; }
    select.input-field option          { background-color: #0d1117; color: rgba(255,255,255,.85); }
    select.input-field option:disabled { color: rgba(255,255,255,.3); }
    select.input-field:disabled        { opacity: .4 !important; cursor: not-allowed; }

    .regulasi-box {
        border-radius: 12px; padding: 12px 16px;
        background: rgba(234,179,8,.05); border: 1px solid rgba(234,179,8,.14); margin-top: 12px;
    }
</style>
@endpush

@section('content')
<section class="min-h-screen py-20 px-6">
<div class="max-w-2xl mx-auto">

    {{-- HEADER --}}
    <div class="text-center mb-10 form-section">
        <a href="{{ route('registration.index') }}"
           class="inline-flex items-center gap-2 text-white/30 text-xs hover:text-white/60 transition mb-6">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Ganti kategori
        </a>

        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-500/40
                    bg-brand-500/10 text-brand-300 text-xs font-semibold uppercase tracking-widest mb-4">
            Pendaftaran Online · Bayan Open 2026
        </div>

        <h1 class="font-display text-3xl font-bold mb-3">Formulir Pendaftaran</h1>

        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-3"
             style="background:rgba(234,179,8,.1);border:1px solid rgba(234,179,8,.3);">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(250,204,21,1)" stroke-width="2">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.86L12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            <span class="text-yellow-400 text-xs font-bold uppercase tracking-widest">Ganda Veteran Putra</span>
        </div>

        {{-- Regulasi usia — tanpa embel-embel tanggal turnamen --}}
        <div class="regulasi-box max-w-md mx-auto text-left mt-3">
            <p class="text-yellow-400/85 text-xs font-bold mb-2 flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Regulasi Usia Veteran
            </p>
            <ul class="text-white/40 text-xs space-y-1 leading-relaxed list-none">
                <li>&#9654; Setiap pemain <strong class="text-white/60">wajib berusia &ge; 45 tahun</strong></li>
                <li>&#9654; <strong class="text-white/60">Total usia</strong> kedua pemain <strong class="text-white/60">wajib &ge; 95 tahun</strong></li>
                <li>&#9654; Verifikasi otomatis via scan KTP &mdash; data dikunci setelah scan</li>
            </ul>
        </div>

        <p class="text-white/38 text-sm mt-4">Isi semua data dengan benar dan lengkap</p>
    </div>

    {{-- ERROR BOX --}}
    @if($errors->any())
    <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-5 mb-6 form-section">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="text-red-400 text-sm font-semibold">Terdapat kesalahan pada form:</p>
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
    <input type="hidden" name="kategori" value="ganda-veteran-putra">

    {{-- SECTION 1 — DATA TIM & KONTAK --}}
    <div class="card-glass rounded-2xl p-8 mb-6 form-section">
        <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs font-black">1</span>
            Data Tim &amp; Kontak
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Nama Ketua Tim / PIC <span class="text-brand-400">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                    placeholder="Nama lengkap ketua tim / penanggung jawab"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('nama') border-red-500 @enderror" required>
                @error('nama')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Nama Tim / PB <span class="text-brand-400">*</span>
                </label>
                <input type="text" name="tim_pb" value="{{ old('tim_pb') }}"
                    placeholder="Contoh: PB Garuda Sakti"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('tim_pb') border-red-500 @enderror" required>
                @error('tim_pb')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Email <span class="text-brand-400">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="email@contoh.com"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('email') border-red-500 @enderror" required>
                <p class="text-white/25 text-xs mt-1">Receipt dikirim ke email ini</p>
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Nomor WhatsApp / HP <span class="text-brand-400">*</span>
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
                        class="input-field w-full px-4 py-3 rounded-xl text-sm appearance-none @error('provinsi') border-red-500 @enderror" required>
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
                        class="input-field w-full px-4 py-3 rounded-xl text-sm appearance-none opacity-40 @error('kota') border-red-500 @enderror" required>
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

    {{-- SECTION 2 — DATA PELATIH --}}
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
                    placeholder="Nama lengkap pelatih" class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">No. HP Pelatih</label>
                <input type="text" name="no_hp_pelatih" value="{{ old('no_hp_pelatih') }}"
                    placeholder="Contoh: 08123456789" class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
        </div>
    </div>

    {{-- SECTION 3 — SCAN KTP & VERIFIKASI USIA --}}
    <div class="rounded-2xl p-8 mb-6 form-section"
         style="background:rgba(234,179,8,.035);border:1.5px solid rgba(234,179,8,.16);">

        <h2 class="font-display text-sm font-bold mb-1 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black"
                  style="background:rgba(234,179,8,.9);color:#000;">3</span>
            Scan KTP &amp; Verifikasi Usia
        </h2>
        <p class="text-white/30 text-xs mb-1 ml-9">
            Upload foto KTP lalu klik <strong class="text-yellow-400/75">SCAN KTP</strong> untuk verifikasi usia otomatis.
        </p>
        <p class="text-white/20 text-xs mb-7 ml-9">
            Data KTP dikunci setelah scan. Jika ada kesalahan baca, reset dan scan ulang dengan foto lebih jelas.
        </p>

        {{-- PEMAIN 1 --}}
        <div id="ocr_card_0" class="pemain-ocr-card mb-5">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                         style="background:rgba(234,179,8,.14);border:1px solid rgba(234,179,8,.3);">
                        <span class="text-yellow-400 text-xs font-black">1</span>
                    </div>
                    <span class="text-white/80 text-sm font-bold">Pemain 1</span>
                </div>
                <div id="age_badge_0" class="age-badge pending">Belum scan</div>
            </div>
            <div class="mb-4">
                <label class="block text-white/45 text-xs font-semibold uppercase tracking-wide mb-2">
                    Foto KTP <span class="text-brand-400">*</span>
                    <span class="text-white/22 font-normal normal-case">&mdash; JPG, PNG &middot; Maks 5MB</span>
                </label>
                <div id="ktpDropzone_0" onclick="document.getElementById('ktpInput_0').click()"
                    class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all"
                    style="border-color:rgba(234,179,8,.22);background:rgba(234,179,8,.018);"
                    ondragover="event.preventDefault();this.style.borderColor='rgba(234,179,8,.6)'"
                    ondragleave="this.style.borderColor='rgba(234,179,8,.22)'"
                    ondrop="VET.drop(event,0)">
                    <div id="ktpPreview_0" class="hidden">
                        <div class="relative inline-block mb-2">
                            <img id="ktpPreviewImg_0" src="" alt="" class="max-h-32 mx-auto rounded-lg object-contain" style="box-shadow:0 4px 20px rgba(0,0,0,.55);">
                            <button type="button" onclick="VET.reset(event,0)" class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 flex items-center justify-center transition">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <p class="text-white/28 text-xs">Reset &amp; scan ulang jika data salah</p>
                    </div>
                    <div id="ktpDefault_0" class="flex flex-col items-center py-3">
                        <div class="w-11 h-11 rounded-xl bg-yellow-500/10 flex items-center justify-center mb-3">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="rgba(250,204,21,.55)" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
                        </div>
                        <p class="text-white/50 text-sm font-medium">Klik atau seret foto KTP</p>
                        <p class="text-white/22 text-xs mt-0.5">JPG, PNG &middot; Maks 5MB</p>
                    </div>
                </div>
                <input type="file" id="ktpInput_0" name="ktp_files[]" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="VET.fileSelect(this,0)">
                <button type="button" id="scanBtn_0" onclick="VET.scan(0)"
                    class="hidden mt-3 w-full py-2.5 rounded-xl font-display text-xs font-bold text-white tracking-wider flex items-center justify-center gap-2"
                    style="background:linear-gradient(135deg,#eab308,#b45309);box-shadow:0 4px 16px rgba(234,179,8,.22);">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                    SCAN KTP &amp; VERIFIKASI USIA
                </button>
                <div id="scanLoading_0" class="hidden mt-3 text-center py-2">
                    <p class="text-yellow-400 text-xs font-semibold mb-1">Membaca KTP dengan AI...</p>
                    <div class="scan-loading-bar"><div class="scan-loading-bar-inner"></div></div>
                </div>
            </div>
            <div id="ktpDataCard_0" class="ktp-data-card">
                <p class="text-xs font-bold text-white/35 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
                    Data KTP Terbaca
                </p>
                <div id="ktpDataRows_0"></div>
            </div>
            <p id="tgl_info_0" class="hidden text-xs mt-2 font-medium"></p>
            <input type="hidden" name="pemain[]"      id="pemain_0"      value="{{ old('pemain.0') }}">
            <input type="hidden" name="nik[]"         id="nik_0"         value="{{ old('nik.0') }}">
            <input type="hidden" name="tgl_lahir[]"   id="tgl_lahir_0"   value="{{ old('tgl_lahir.0') }}">
            <input type="hidden" name="usia_valid[]"  id="usia_valid_0"  value="0">
            <input type="hidden" name="usia_hitung[]" id="usia_hitung_0" value="">
        </div>

        {{-- PEMAIN 2 --}}
        <div id="ocr_card_1" class="pemain-ocr-card">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                         style="background:rgba(234,179,8,.14);border:1px solid rgba(234,179,8,.3);">
                        <span class="text-yellow-400 text-xs font-black">2</span>
                    </div>
                    <span class="text-white/80 text-sm font-bold">Pemain 2</span>
                </div>
                <div id="age_badge_1" class="age-badge pending">Belum scan</div>
            </div>
            <div class="mb-4">
                <label class="block text-white/45 text-xs font-semibold uppercase tracking-wide mb-2">
                    Foto KTP <span class="text-brand-400">*</span>
                    <span class="text-white/22 font-normal normal-case">&mdash; JPG, PNG &middot; Maks 5MB</span>
                </label>
                <div id="ktpDropzone_1" onclick="document.getElementById('ktpInput_1').click()"
                    class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all"
                    style="border-color:rgba(234,179,8,.22);background:rgba(234,179,8,.018);"
                    ondragover="event.preventDefault();this.style.borderColor='rgba(234,179,8,.6)'"
                    ondragleave="this.style.borderColor='rgba(234,179,8,.22)'"
                    ondrop="VET.drop(event,1)">
                    <div id="ktpPreview_1" class="hidden">
                        <div class="relative inline-block mb-2">
                            <img id="ktpPreviewImg_1" src="" alt="" class="max-h-32 mx-auto rounded-lg object-contain" style="box-shadow:0 4px 20px rgba(0,0,0,.55);">
                            <button type="button" onclick="VET.reset(event,1)" class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 flex items-center justify-center transition">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <p class="text-white/28 text-xs">Reset &amp; scan ulang jika data salah</p>
                    </div>
                    <div id="ktpDefault_1" class="flex flex-col items-center py-3">
                        <div class="w-11 h-11 rounded-xl bg-yellow-500/10 flex items-center justify-center mb-3">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="rgba(250,204,21,.55)" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
                        </div>
                        <p class="text-white/50 text-sm font-medium">Klik atau seret foto KTP</p>
                        <p class="text-white/22 text-xs mt-0.5">JPG, PNG &middot; Maks 5MB</p>
                    </div>
                </div>
                <input type="file" id="ktpInput_1" name="ktp_files[]" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="VET.fileSelect(this,1)">
                <button type="button" id="scanBtn_1" onclick="VET.scan(1)"
                    class="hidden mt-3 w-full py-2.5 rounded-xl font-display text-xs font-bold text-white tracking-wider flex items-center justify-center gap-2"
                    style="background:linear-gradient(135deg,#eab308,#b45309);box-shadow:0 4px 16px rgba(234,179,8,.22);">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                    SCAN KTP &amp; VERIFIKASI USIA
                </button>
                <div id="scanLoading_1" class="hidden mt-3 text-center py-2">
                    <p class="text-yellow-400 text-xs font-semibold mb-1">Membaca KTP dengan AI...</p>
                    <div class="scan-loading-bar"><div class="scan-loading-bar-inner"></div></div>
                </div>
            </div>
            <div id="ktpDataCard_1" class="ktp-data-card">
                <p class="text-xs font-bold text-white/35 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
                    Data KTP Terbaca
                </p>
                <div id="ktpDataRows_1"></div>
            </div>
            <p id="tgl_info_1" class="hidden text-xs mt-2 font-medium"></p>
            <input type="hidden" name="pemain[]"      id="pemain_1"      value="{{ old('pemain.1') }}">
            <input type="hidden" name="nik[]"         id="nik_1"         value="{{ old('nik.1') }}">
            <input type="hidden" name="tgl_lahir[]"   id="tgl_lahir_1"   value="{{ old('tgl_lahir.1') }}">
            <input type="hidden" name="usia_valid[]"  id="usia_valid_1"  value="0">
            <input type="hidden" name="usia_hitung[]" id="usia_hitung_1" value="">
        </div>

        <div id="totalUsiaBox" class="total-usia-box">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/45">Total Usia 2 Pemain</span>
                <span id="totalUsiaValue" class="text-sm font-bold text-white/40">—</span>
            </div>
        </div>

        <div id="veteranSummary" class="veteran-summary">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" id="summaryIcon"></div>
            <div>
                <p id="summaryTitle"  class="text-xs font-bold"></p>
                <p id="summaryDetail" class="text-xs mt-0.5 opacity-60"></p>
            </div>
        </div>
    </div>

    {{-- SECTION 4 — RINGKASAN BIAYA --}}
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

    <button type="submit" id="submitBtn"
        class="btn-primary w-full py-4 rounded-xl font-display text-sm font-bold text-white tracking-wide form-section">
        DAFTAR SEKARANG &rarr;
    </button>

    <div id="submitWarning" class="submit-warning">
        &#9888; Harap scan KTP kedua pemain terlebih dahulu untuk verifikasi usia.
    </div>

    <p class="text-white/25 text-xs text-center mt-4">
        Dengan mendaftar, Anda menyetujui syarat &amp; ketentuan Bayan Open 2026
    </p>

    </form>
</div>
</section>

@push('scripts')
<script>
(function () {
'use strict';

var OLD_PROVINSI  = @json(old('provinsi', ''));
var OLD_KOTA      = @json(old('kota', ''));
var _provinsiCode = '';

async function loadProvinsi() {
    var sel  = document.getElementById('selectProvinsi');
    var spin = document.getElementById('loadingProvinsi');
    if (!sel) return;
    spin && spin.classList.remove('hidden');
    try {
        var res  = await fetch('/wilayah/provinces');
        var data = await res.json();
        data.forEach(function (p) {
            var opt = new Option(p.name, p.name);
            opt.dataset.code = p.id;
            if (OLD_PROVINSI && p.name === OLD_PROVINSI) opt.selected = true;
            sel.appendChild(opt);
        });
        if (OLD_PROVINSI) {
            var found = Array.from(sel.options).find(function (o) { return o.value === OLD_PROVINSI; });
            if (found) { _provinsiCode = found.dataset.code; await loadKota(_provinsiCode, OLD_KOTA); }
        }
    } catch (e) { console.error('Gagal load provinsi:', e); }
    finally { spin && spin.classList.add('hidden'); }
}

async function onProvinsiChange(sel) {
    var opt = sel.options[sel.selectedIndex];
    _provinsiCode = opt ? (opt.dataset.code || '') : '';
    var kotaSel = document.getElementById('selectKota');
    if (kotaSel) { kotaSel.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>'; kotaSel.disabled = true; kotaSel.classList.add('opacity-40'); }
    if (!_provinsiCode) return;
    await loadKota(_provinsiCode, '');
}

async function loadKota(provId, selectedName) {
    var sel  = document.getElementById('selectKota');
    var spin = document.getElementById('loadingKota');
    if (!sel) return;
    sel.disabled = true; sel.classList.add('opacity-40');
    sel.innerHTML = '<option value="">-- Memuat data... --</option>';
    spin && spin.classList.remove('hidden');
    try {
        var res  = await fetch('/wilayah/regencies/' + provId);
        var data = await res.json();
        sel.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        data.forEach(function (k) {
            var opt = new Option(k.name, k.name);
            opt.dataset.code = k.id;
            if (selectedName && k.name === selectedName) opt.selected = true;
            sel.appendChild(opt);
        });
        sel.disabled = false; sel.classList.remove('opacity-40');
    } catch (e) { sel.innerHTML = '<option value="">Gagal memuat data</option>'; console.error(e); }
    finally { spin && spin.classList.add('hidden'); }
}

window.WILAYAH = { onProvinsiChange: onProvinsiChange };
document.addEventListener('DOMContentLoaded', loadProvinsi);
})();
</script>

<script>
(function () {
'use strict';

// ================================================================
// KONSTANTA — usia dihitung dari selisih tahun saja (tanpa tanggal turnamen)
// ================================================================
var MIN_AGE_EACH  = 45;
var MIN_AGE_TOTAL = 95;

var ktpFiles   = [null, null];
var scanStatus = [false, false];
var usiaArr    = [null, null];

// ================================================================
// HITUNG USIA — selisih tahun lahir ke tahun sekarang
// Tidak mempertimbangkan bulan/tanggal sama sekali
// ================================================================
function hitungUsia(str) {
    if (!str) return null;
    var tgl = null;

    /* DD-MM-YYYY atau DD/MM/YYYY */
    var m1 = str.match(/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{4})$/);
    /* YYYY-MM-DD */
    var m2 = str.match(/^(\d{4})[-\/\.](\d{1,2})[-\/\.](\d{1,2})$/);

    if (m1)      tgl = new Date(+m1[3], +m1[2] - 1, +m1[1]);
    else if (m2) tgl = new Date(+m2[1], +m2[2] - 1, +m2[3]);
    else         tgl = new Date(str);

    if (!tgl || isNaN(tgl.getTime())) return null;

    /* Usia = selisih tahun saja */
    var usia = new Date().getFullYear() - tgl.getFullYear();
    if (usia < 0 || usia > 120) return null;

    return { usia: usia, tgl: tgl };
}

function isValidVeteran(usia) {
    return usia >= MIN_AGE_EACH;
}

// ================================================================
// UPDATE BADGE
// ================================================================
function updateAgeBadge(idx, tglStr) {
    var badge     = document.getElementById('age_badge_'   + idx);
    var ocrCard   = document.getElementById('ocr_card_'    + idx);
    var hiddenV   = document.getElementById('usia_valid_'  + idx);
    var hiddenAge = document.getElementById('usia_hitung_' + idx);
    var infoEl    = document.getElementById('tgl_info_'    + idx);

    if (!tglStr) {
        setBadge(badge, 'pending', 'Belum scan');
        if (hiddenV)   hiddenV.value   = '0';
        if (hiddenAge) hiddenAge.value = '';
        usiaArr[idx] = null;
        return;
    }

    var result = hitungUsia(tglStr);
    if (!result) {
        setBadge(badge, 'invalid', 'Tgl lahir tidak valid');
        if (hiddenV) hiddenV.value = '0';
        usiaArr[idx] = null;
        return;
    }

    var usia  = result.usia;
    var tgl   = result.tgl;
    var valid = isValidVeteran(usia);
    var fmt   = tgl.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

    if (hiddenV)   hiddenV.value   = valid ? '1' : '0';
    if (hiddenAge) hiddenAge.value = usia;
    usiaArr[idx] = usia;

    if (valid) {
        setBadge(badge, 'valid', '&#10003; ' + usia + ' thn &mdash; Memenuhi syarat');
        if (ocrCard) { ocrCard.classList.add('scanned'); ocrCard.classList.remove('invalid-age'); }
    } else {
        setBadge(badge, 'invalid', '&#10007; ' + usia + ' thn &mdash; Min. 45 tahun');
        if (ocrCard) { ocrCard.classList.add('invalid-age'); ocrCard.classList.remove('scanned'); }
    }

    if (infoEl) {
        /* Tampilkan tgl lahir + usia — tanpa embel-embel tanggal turnamen */
        infoEl.textContent = 'Lahir: ' + fmt + ' \u00b7 ' + usia + ' tahun';
        infoEl.className   = 'text-xs mt-2 font-medium ' + (valid ? 'text-emerald-400' : 'text-red-400');
        infoEl.classList.remove('hidden');
    }

    updateSummary();
}

function setBadge(el, state, html) {
    if (!el) return;
    el.className = 'age-badge ' + state;
    el.innerHTML = html;
}

// ================================================================
// SUMMARY
// ================================================================
function updateSummary() {
    if (!scanStatus[0] || !scanStatus[1]) return;

    var v0      = document.getElementById('usia_valid_0').value === '1';
    var v1      = document.getElementById('usia_valid_1').value === '1';
    var u0      = usiaArr[0] || 0;
    var u1      = usiaArr[1] || 0;
    var total   = u0 + u1;
    var totalOk = total >= MIN_AGE_TOTAL;

    var totalBox = document.getElementById('totalUsiaBox');
    var totalVal = document.getElementById('totalUsiaValue');
    if (totalBox && totalVal) {
        totalBox.className = 'total-usia-box show ' + (totalOk ? 'ok' : 'bad');
        totalVal.innerHTML = total + ' tahun '
            + (totalOk
                ? '<span style="color:#34d399">&#10003; memenuhi</span>'
                : '<span style="color:#f87171">&#10007; kurang ' + (MIN_AGE_TOTAL - total) + ' tahun</span>');
    }

    var summary   = document.getElementById('veteranSummary');
    var sumIcon   = document.getElementById('summaryIcon');
    var sumTitle  = document.getElementById('summaryTitle');
    var sumDetail = document.getElementById('summaryDetail');
    var warning   = document.getElementById('submitWarning');
    var allOk     = v0 && v1 && totalOk;

    if (allOk) {
        summary.className = 'veteran-summary show ok';
        if (sumIcon)   sumIcon.innerHTML   = '<svg width="16" height="16" viewBox="0 0 20 20" fill="#34d399"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
        if (sumTitle)  { sumTitle.className  = 'text-xs font-bold text-emerald-400'; sumTitle.textContent  = 'Kedua pemain memenuhi syarat veteran!'; }
        if (sumDetail) { sumDetail.className = 'text-xs mt-0.5 text-emerald-400/55'; sumDetail.textContent = 'Usia individual \u2265 45 thn \u00b7 Total usia ' + total + ' thn \u2265 95 thn'; }
        warning.style.display = 'none';
    } else {
        summary.className = 'veteran-summary show bad';
        if (sumIcon) sumIcon.innerHTML = '<svg width="16" height="16" viewBox="0 0 20 20" fill="#f87171"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';
        if (sumTitle) { sumTitle.className = 'text-xs font-bold text-red-400'; sumTitle.textContent = 'Syarat usia belum terpenuhi'; }
        var detail = '';
        if (!v0 && !v1)    detail = 'Kedua pemain tidak memenuhi syarat usia (min. 45 tahun).';
        else if (!v0)      detail = 'Pemain 1 tidak memenuhi syarat usia (min. 45 tahun).';
        else if (!v1)      detail = 'Pemain 2 tidak memenuhi syarat usia (min. 45 tahun).';
        else if (!totalOk) detail = 'Total usia ' + total + ' tahun, kurang ' + (MIN_AGE_TOTAL - total) + ' tahun dari minimum 95 tahun.';
        if (sumDetail) { sumDetail.className = 'text-xs mt-0.5 text-red-400/55'; sumDetail.textContent = detail; }
        warning.style.display = 'block';
        warning.textContent   = '\u26a0 ' + detail;
    }
}

// ================================================================
// RENDER CARD KTP — READ ONLY
// ================================================================
function renderCard(idx, data, usia, valid) {
    var card = document.getElementById('ktpDataCard_' + idx);
    var rows = document.getElementById('ktpDataRows_' + idx);
    if (!card || !rows) return;
    rows.innerHTML = '';

    var fields = [
        { label: 'NIK',       key: 'nik',          hl: true  },
        { label: 'Nama',      key: 'nama',          hl: true  },
        { label: 'Tgl Lahir', key: '__tgl_lahir__', hl: true  },
        { label: 'Usia',      key: '__usia__',      hl: false },
        { label: 'Jenis Kel.',key: 'jenis_kelamin', hl: false },
        { label: 'Kel/Desa',  key: 'kelurahan',     hl: false },
        { label: 'Kecamatan', key: 'kecamatan',     hl: false },
    ];

    var tglNorm = (data.tanggal_lahir || data.tgl_lahir || '').trim();

    fields.forEach(function (f) {
        var valHtml = '';
        if (f.key === '__usia__') {
            if (!usia && usia !== 0) return;
            var warna = valid ? '#34d399' : '#f87171';
            var icon  = valid ? '&#10003;' : '&#10007;';
            /* Label usia tanpa embel-embel tanggal turnamen */
            valHtml = '<span style="color:' + warna + ';font-weight:700;font-size:12px;">'
                + icon + ' ' + usia + ' tahun &mdash; '
                + (valid ? 'Memenuhi syarat' : 'Tidak memenuhi syarat')
                + '</span>';
        } else if (f.key === '__tgl_lahir__') {
            if (!tglNorm) return;
            valHtml = '<span class="ktp-value' + (f.hl ? ' highlight' : '') + '">' + escHtml(tglNorm) + '</span>';
        } else {
            var v = ((data[f.key] || '') + '').trim();
            if (!v) return;
            valHtml = '<span class="ktp-value' + (f.hl ? ' highlight' : '') + '">' + escHtml(v) + '</span>';
        }
        rows.innerHTML += '<div class="ktp-row"><span class="ktp-label">' + f.label + '</span>' + valHtml + '</div>';
    });

    rows.innerHTML +=
        '<div class="ktp-locked-note">'
        + '<svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">'
        + '<path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>'
        + '</svg>Data dikunci. Reset &amp; scan ulang jika ada kesalahan.</div>';

    card.className = 'ktp-data-card show ' + (valid ? 'valid-card' : 'invalid-card');
}

// ================================================================
// FILE HANDLING
// ================================================================
function fileSelect(input, idx) {
    if (input.files && input.files[0]) processFile(input.files[0], idx);
}

function drop(e, idx) {
    e.preventDefault();
    var dz = document.getElementById('ktpDropzone_' + idx);
    if (dz) dz.style.borderColor = 'rgba(234,179,8,.22)';
    var file = e.dataTransfer && e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        var dt = new DataTransfer(); dt.items.add(file);
        var inp = document.getElementById('ktpInput_' + idx);
        if (inp) inp.files = dt.files;
        processFile(file, idx);
    }
}

function processFile(file, idx) {
    if (file.size > 5 * 1024 * 1024) { showToast('File terlalu besar. Maks 5MB.', 'error'); return; }
    ktpFiles[idx] = file;
    var reader = new FileReader();
    reader.onload = function (e) {
        var img = document.getElementById('ktpPreviewImg_' + idx);
        if (img) img.src = e.target.result;
        toggleEl('ktpPreview_' + idx, true);
        toggleEl('ktpDefault_' + idx, false);
        toggleEl('scanBtn_'    + idx, true);
        var card = document.getElementById('ktpDataCard_' + idx);
        var rows = document.getElementById('ktpDataRows_' + idx);
        if (card) card.className = 'ktp-data-card';
        if (rows) rows.innerHTML = '';
    };
    reader.readAsDataURL(file);
}

function resetSlot(e, idx) {
    e.stopPropagation();
    ktpFiles[idx]   = null;
    scanStatus[idx] = false;
    usiaArr[idx]    = null;

    var inp = document.getElementById('ktpInput_' + idx);
    if (inp) inp.value = '';

    toggleEl('ktpPreview_'  + idx, false);
    toggleEl('ktpDefault_'  + idx, true);
    toggleEl('scanBtn_'     + idx, false);
    toggleEl('scanLoading_' + idx, false);

    setHid('pemain_'      + idx, '');
    setHid('nik_'         + idx, '');
    setHid('tgl_lahir_'   + idx, '');
    setHid('usia_valid_'  + idx, '0');
    setHid('usia_hitung_' + idx, '');

    var card    = document.getElementById('ktpDataCard_' + idx);
    var rows    = document.getElementById('ktpDataRows_' + idx);
    var ocrCard = document.getElementById('ocr_card_'    + idx);
    var infoEl  = document.getElementById('tgl_info_'    + idx);
    if (card)    card.className = 'ktp-data-card';
    if (rows)    rows.innerHTML = '';
    if (ocrCard) ocrCard.classList.remove('scanned', 'invalid-age');
    if (infoEl)  { infoEl.textContent = ''; infoEl.classList.add('hidden'); }

    updateAgeBadge(idx, null);

    var summary  = document.getElementById('veteranSummary');
    var warning  = document.getElementById('submitWarning');
    var totalBox = document.getElementById('totalUsiaBox');
    if (summary)  summary.className     = 'veteran-summary';
    if (warning)  warning.style.display = 'none';
    if (totalBox) totalBox.className    = 'total-usia-box';
}

// ================================================================
// SCAN OCR
// ================================================================
function scan(idx) {
    if (!ktpFiles[idx]) return;
    toggleEl('scanBtn_'     + idx, false);
    toggleEl('scanLoading_' + idx, true);
    var card = document.getElementById('ktpDataCard_' + idx);
    if (card) card.className = 'ktp-data-card';

    var fd   = new FormData();
    fd.append('image', ktpFiles[idx]);
    var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

    fetch('/ocr/ktp', {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body:    fd,
    })
    .then(function (resp) {
        toggleEl('scanLoading_' + idx, false);
        toggleEl('scanBtn_'     + idx, true);
        if (!resp.ok) {
            return resp.json().catch(function () { return {}; }).then(function (err) {
                showToast(err.message || ('HTTP ' + resp.status + ' — coba lagi.'), 'error');
            });
        }
        return resp.json().then(function (result) {
            if (!result.success) { showToast(result.message || 'Gagal membaca KTP. Coba foto lebih jelas.', 'error'); return; }

            var data     = result.data;
            var tglLahir = ((data.tanggal_lahir || data.tgl_lahir || '') + '').trim();

            if (!tglLahir) {
                showToast('\u26a0 KTP terbaca tapi tanggal lahir tidak terdeteksi. Coba foto lebih jelas.', 'warn');
                scanStatus[idx] = true;
                return;
            }

            setHid('pemain_'    + idx, data.nama || '');
            setHid('nik_'       + idx, data.nik  || '');
            setHid('tgl_lahir_' + idx, tglLahir);

            updateAgeBadge(idx, tglLahir);

            var usia  = parseInt(document.getElementById('usia_hitung_' + idx).value, 10) || 0;
            var valid = document.getElementById('usia_valid_' + idx).value === '1';

            scanStatus[idx] = true;
            renderCard(idx, data, usia, valid);
            toggleEl('scanBtn_' + idx, false);

            if (valid) {
                showToast('\u2705 Pemain ' + (idx + 1) + ' — ' + (data.nama || '') + ' \u00b7 ' + usia + ' tahun \u00b7 Memenuhi syarat veteran!', 'success');
            } else {
                showToast('\u26a0 Pemain ' + (idx + 1) + ' — ' + usia + ' tahun \u00b7 TIDAK memenuhi syarat (min. 45 tahun).', 'warn');
            }
        });
    })
    .catch(function (err) {
        toggleEl('scanLoading_' + idx, false);
        toggleEl('scanBtn_'     + idx, true);
        showToast('\u274c Tidak bisa konek ke OCR service.', 'error');
        console.error('OCR error:', err);
    });
}

// ================================================================
// HELPERS
// ================================================================
function toggleEl(id, show) {
    var el = document.getElementById(id);
    if (!el) return;
    if (show) el.classList.remove('hidden'); else el.classList.add('hidden');
}
function setHid(id, val) { var el = document.getElementById(id); if (el) el.value = val; }
function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ================================================================
// TOAST
// ================================================================
var _toastTimer = null;
function showToast(msg, type) {
    type = type || 'success';
    var el = document.getElementById('_vetToast');
    if (!el) {
        el = document.createElement('div');
        el.id = '_vetToast';
        el.style.cssText = 'position:fixed;top:88px;right:20px;z-index:99999;max-width:380px;padding:12px 16px;border-radius:13px;font-size:12px;line-height:1.5;font-weight:600;box-shadow:0 8px 36px rgba(0,0,0,.45);transition:opacity .3s,transform .3s;pointer-events:none;';
        document.body.appendChild(el);
    }
    var styles = {
        success: 'background:rgba(6,30,18,.97);border:1px solid rgba(16,185,129,.38);color:#34d399;',
        warn:    'background:rgba(30,22,4,.97);border:1px solid rgba(234,179,8,.38);color:#fbbf24;',
        error:   'background:rgba(30,6,6,.97);border:1px solid rgba(239,68,68,.38);color:#f87171;',
    };
    el.style.cssText += (styles[type] || styles.error) + 'opacity:1;transform:translateY(0);';
    el.innerHTML = msg;
    if (_toastTimer) clearTimeout(_toastTimer);
    _toastTimer = setTimeout(function () { el.style.opacity = '0'; el.style.transform = 'translateY(-8px)'; }, 5500);
}

// ================================================================
// SUBMIT GUARD
// ================================================================
document.getElementById('regForm').addEventListener('submit', function (e) {
    if (!scanStatus[0] || !scanStatus[1]) {
        e.preventDefault();
        var w = document.getElementById('submitWarning');
        w.style.display = 'block';
        w.textContent   = '\u26a0 Harap scan KTP kedua pemain terlebih dahulu untuk verifikasi usia.';
        w.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    var v0      = document.getElementById('usia_valid_0').value === '1';
    var v1      = document.getElementById('usia_valid_1').value === '1';
    var u0      = parseInt(document.getElementById('usia_hitung_0').value, 10) || 0;
    var u1      = parseInt(document.getElementById('usia_hitung_1').value, 10) || 0;
    var total   = u0 + u1;
    var totalOk = total >= MIN_AGE_TOTAL;

    if (!v0 || !v1 || !totalOk) {
        e.preventDefault();
        var w   = document.getElementById('submitWarning');
        var msg = '\u26a0 ';
        if (!v0 && !v1)    msg += 'Kedua pemain tidak memenuhi syarat usia (min. 45 tahun per pemain).';
        else if (!v0)      msg += 'Pemain 1 tidak memenuhi syarat usia (min. 45 tahun).';
        else if (!v1)      msg += 'Pemain 2 tidak memenuhi syarat usia (min. 45 tahun).';
        else if (!totalOk) msg += 'Total usia ' + total + ' tahun (min. 95 tahun dari 2 pemain).';
        w.style.display = 'block';
        w.textContent   = msg;
        w.scrollIntoView({ behavior: 'smooth', block: 'center' });
        showToast(msg, 'warn');
    }
});

window.VET = { fileSelect: fileSelect, drop: drop, reset: resetSlot, scan: scan };
})();
</script>
@endpush

@endsection