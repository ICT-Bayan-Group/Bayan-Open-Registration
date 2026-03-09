{{--
    TEMPLATE: form-dewasa.blade.php
    DIPAKAI UNTUK:
      - Ganda Dewasa Putra  → kategori: 'ganda-dewasa-putra'
      - Ganda Dewasa Putri  → kategori: 'ganda-dewasa-putri'
      - Beregu              → kategori: 'beregu'

    Controller contoh:
    public function showGandaDewasaPutra() {
        return view('registration.form-dewasa', [
            'kategori'  => 'ganda-dewasa-putra',
            'label'     => 'Ganda Dewasa Putra',
            'harga'     => 150000,
            'minPemain' => 2,
            'maxPemain' => 2,
        ]);
    }
--}}

@extends('layouts.app')

@section('title', 'Pendaftaran ' . ($label ?? 'Ganda Dewasa') . ' — Bayan Open 2026')

@push('styles')
<style>
    @keyframes fadeSlideUp {
        from { opacity:0; transform:translateY(16px); }
        to   { opacity:1; transform:translateY(0); }
    }
    @keyframes shimmerScan {
        0%   { background-position:-200% center; }
        100% { background-position: 200% center; }
    }
    .form-section { animation:fadeSlideUp .4s ease both; }
    .form-section:nth-child(1){animation-delay:.05s}
    .form-section:nth-child(2){animation-delay:.10s}
    .form-section:nth-child(3){animation-delay:.15s}
    .form-section:nth-child(4){animation-delay:.20s}
    .form-section:nth-child(5){animation-delay:.25s}
    .form-section:nth-child(6){animation-delay:.30s}

    .pemain-ocr-card{
        border-radius:16px;
        border:1.5px solid rgba(249,115,22,.2);
        background:rgba(20,10,4,.7);
        padding:22px;
        transition:border-color .25s,background .25s;
    }
    .pemain-ocr-card.scanned{
        border-color:rgba(16,185,129,.45);
        background:rgba(4,20,12,.7);
    }
    .ktp-data-card{
        border-radius:12px;
        background:rgba(255,255,255,.03);
        border:1px solid rgba(255,255,255,.08);
        padding:14px 16px;
        margin-top:12px;
        display:none;
    }
    .ktp-data-card.show{display:block;animation:fadeSlideUp .3s ease both}
    .ktp-data-card.valid-card{background:rgba(249,115,22,.05);border-color:rgba(249,115,22,.25)}
    .ktp-row{
        display:flex;align-items:center;gap:10px;
        padding:4px 0;border-bottom:1px solid rgba(255,255,255,.04);
        min-height:32px;
    }
    .ktp-row:last-child{border-bottom:none;padding-bottom:0}
    .ktp-label{
        font-size:10px;font-weight:700;text-transform:uppercase;
        letter-spacing:.06em;color:rgba(255,255,255,.3);
        min-width:90px;flex-shrink:0;
    }
    /* Nilai yang bisa diklik untuk edit inline */
    .ktp-value{
        flex:1;font-size:12px;color:rgba(255,255,255,.8);
        line-height:1.4;word-break:break-word;
        padding:3px 7px;border-radius:6px;
        border:1px solid transparent;
        cursor:pointer;transition:background .15s,border-color .15s,color .15s;
    }
    .ktp-value.hl{color:#fff;font-weight:600}
    .ktp-value:hover{
        background:rgba(249,115,22,.09);
        border-color:rgba(249,115,22,.3);
        color:#fff;
    }
    .ktp-value[title]:hover::after{
        content:' ✏';font-size:9px;opacity:.55;margin-left:3px;
    }
    /* Input muncul saat edit inline */
    .ktp-inline-input{
        flex:1;background:rgba(249,115,22,.09);
        border:1.5px solid rgba(249,115,22,.55);
        border-radius:6px;color:#fff;font-size:12px;font-weight:600;
        padding:3px 8px;outline:none;min-width:0;
        transition:border-color .15s,box-shadow .15s;
    }
    .ktp-inline-input:focus{
        border-color:rgba(249,115,22,.9);
        box-shadow:0 0 0 2px rgba(249,115,22,.18);
    }
    .ktp-inline-input.was-edited{
        border-color:rgba(234,179,8,.7);
        background:rgba(234,179,8,.09);
    }
    .ktp-inline-select{
        flex:1;background:#0d1117;
        border:1.5px solid rgba(249,115,22,.55);
        border-radius:6px;color:#fff;font-size:12px;
        padding:3px 8px;outline:none;cursor:pointer;
    }
    .ktp-inline-select:focus{
        border-color:rgba(249,115,22,.9);
        box-shadow:0 0 0 2px rgba(249,115,22,.18);
    }
    .ktp-inline-select option{background:#0d1117;color:#fff}
    .ktp-edit-hint{
        font-size:9px;color:rgba(249,115,22,.45);
        text-align:right;font-style:italic;margin-top:5px;
    }
    .scan-loading-bar{height:3px;border-radius:99px;overflow:hidden;background:rgba(249,115,22,.1);margin-top:10px}
    .scan-loading-bar-inner{height:100%;width:40%;background:linear-gradient(90deg,transparent,#f97316,transparent);background-size:200% 100%;animation:shimmerScan 1.2s ease infinite}
    select.input-field{color:rgba(255,255,255,.85)!important;background-color:#0d1117!important;cursor:pointer}
    select.input-field option{background-color:#0d1117;color:rgba(255,255,255,.85)}
    select.input-field option:disabled{color:rgba(255,255,255,.3)}
    select.input-field:disabled{opacity:.4!important;cursor:not-allowed}
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
        <h1 class="font-display text-3xl font-bold mb-2">Formulir Pendaftaran</h1>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-3"
             style="background:rgba(249,115,22,.1);border:1px solid rgba(249,115,22,.3);">
            @if(($kategori ?? '') === 'beregu')
                <svg width="14" height="14" viewBox="0 0 20 20" fill="rgba(251,146,60,1)">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                </svg>
            @else
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(251,146,60,1)" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                </svg>
            @endif
            <span class="text-brand-400 text-xs font-bold uppercase tracking-widest">{{ $label ?? 'Ganda Dewasa' }}</span>
        </div>
        <p class="text-white/50 text-sm mt-2">Isi semua data dengan benar dan lengkap</p>
    </div>

    {{-- ERROR BOX --}}
    @if($errors->any())
    <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 form-section">
        <p class="text-red-400 text-sm font-semibold mb-2">Terdapat kesalahan:</p>
        <ul class="text-red-300 text-sm space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('registration.store') }}" method="POST"
          enctype="multipart/form-data" id="regForm">
    @csrf
    <input type="hidden" name="kategori" value="{{ $kategori ?? '' }}">

    {{-- SECTION 1: DATA TIM --}}
    <div class="card-glass rounded-2xl p-8 mb-6 form-section">
        <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs">1</span>
            Data Tim & Kontak
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div class="md:col-span-2">
                <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                    Nama Ketua Tim / PIC <span class="text-brand-400">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                    placeholder="Nama lengkap ketua tim / penanggung jawab"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('nama') border-red-500 @enderror" required>
                @error('nama')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                    Nama Tim / PB <span class="text-brand-400">*</span>
                </label>
                <input type="text" name="tim_pb" value="{{ old('tim_pb') }}"
                    placeholder="Contoh: PB Garuda Sakti"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('tim_pb') border-red-500 @enderror" required>
                @error('tim_pb')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                    Email <span class="text-brand-400">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="email@contoh.com"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('email') border-red-500 @enderror" required>
                <p class="text-white/30 text-xs mt-1">Receipt dikirim ke email ini</p>
                @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                    Nomor WhatsApp / HP <span class="text-brand-400">*</span>
                </label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                    placeholder="Contoh: 08123456789"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('no_hp') border-red-500 @enderror" required>
                @error('no_hp')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                    Provinsi <span class="text-brand-400">*</span>
                </label>
                <select name="provinsi"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('provinsi') border-red-500 @enderror" required>
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach(['Aceh','Bali','Banten','Bengkulu','DI Yogyakarta','DKI Jakarta','Gorontalo','Jambi','Jawa Barat','Jawa Tengah','Jawa Timur','Kalimantan Barat','Kalimantan Selatan','Kalimantan Tengah','Kalimantan Timur','Kalimantan Utara','Kepulauan Bangka Belitung','Kepulauan Riau','Lampung','Maluku','Maluku Utara','Nusa Tenggara Barat','Nusa Tenggara Timur','Papua','Papua Barat','Riau','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tengah','Sulawesi Tenggara','Sulawesi Utara','Sumatera Barat','Sumatera Selatan','Sumatera Utara'] as $prov)
                        <option value="{{ $prov }}" {{ old('provinsi') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                    @endforeach
                </select>
                @error('provinsi')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                    Kota / Kabupaten <span class="text-brand-400">*</span>
                </label>
                <input type="text" name="kota" value="{{ old('kota') }}"
                    placeholder="Nama kota atau kabupaten"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm @error('kota') border-red-500 @enderror" required>
                @error('kota')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                    Alamat Lengkap <span class="text-brand-400">*</span>
                </label>
                <textarea name="alamat" rows="2"
                    placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm resize-none @error('alamat') border-red-500 @enderror"
                    required>{{ old('alamat') }}</textarea>
                @error('alamat')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

        </div>
    </div>

    {{-- SECTION 2: DATA PELATIH --}}
    <div class="card-glass rounded-2xl p-8 mb-6 form-section">
        <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs">2</span>
            Data Pelatih
            <span class="text-white/30 text-xs font-normal normal-case ml-1">(opsional)</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">Nama Pelatih</label>
                <input type="text" name="nama_pelatih" value="{{ old('nama_pelatih') }}"
                    placeholder="Nama lengkap pelatih" class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">No. HP Pelatih</label>
                <input type="text" name="no_hp_pelatih" value="{{ old('no_hp_pelatih') }}"
                    placeholder="Contoh: 08123456789" class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
        </div>
    </div>

    {{-- SECTION 3: SCAN KTP & DATA PEMAIN --}}
    <div class="rounded-2xl p-8 mb-6 form-section"
         style="background:rgba(249,115,22,.04);border:1.5px solid rgba(249,115,22,.18);">

        <h2 class="font-display text-sm font-bold mb-1 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black"
                  style="background:rgba(249,115,22,.85);color:#000;">3</span>
            Upload KTP &amp; Data Pemain
        </h2>
        <p class="text-white/35 text-xs mb-2 ml-9">
            Upload foto KTP, klik <strong class="text-brand-400">SCAN KTP</strong> — data terisi otomatis.
        </p>
        <p class="text-white/25 text-xs mb-7 ml-9">
            &#9998; Semua field bisa diedit manual jika hasil scan kurang akurat.
        </p>

        {{-- Slot diisi sepenuhnya oleh JS --}}
        <div id="ocrSlotsContainer" class="space-y-5"></div>

        @if(($kategori ?? '') === 'beregu')
        <button type="button" id="tambahPemain" onclick="tambahPemainOcr()"
            class="mt-5 flex items-center gap-2 text-brand-400 text-sm font-semibold hover:text-brand-300 transition">
            <span class="w-6 h-6 rounded-full border-2 border-brand-400/50
                         flex items-center justify-center text-brand-400 text-lg leading-none">+</span>
            Tambah Pemain
        </button>
        @endif

        @error('pemain')   <p class="text-red-400 text-xs mt-3">{{ $message }}</p>@enderror
        @error('pemain.*') <p class="text-red-400 text-xs mt-2">{{ $message }}</p>@enderror
        @error('ktp_files')<p class="text-red-400 text-xs mt-2">{{ $message }}</p>@enderror
    </div>

    {{-- SECTION 4: RINGKASAN BIAYA --}}
    <div class="card-glass rounded-2xl p-6 mb-6 form-section">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-white/50 text-xs mb-1">Kategori</p>
                <p class="font-display text-white font-bold text-sm">{{ $label ?? '-' }}</p>
            </div>
            <div class="text-right">
                <p class="text-white/50 text-xs mb-1">Total Pembayaran</p>
                <p class="font-display text-brand-400 font-bold text-2xl">
                    Rp {{ number_format($harga ?? 150000, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <button type="submit"
        class="btn-primary w-full py-4 rounded-xl font-display text-sm font-bold text-white tracking-wide form-section">
        DAFTAR &amp; BAYAR SEKARANG &rarr;
    </button>
    <p class="text-white/30 text-xs text-center mt-4 form-section">
        Dengan mendaftar, Anda menyetujui syarat &amp; ketentuan Bayan Open 2026
    </p>

    </form>

    <div class="flex justify-center gap-6 mt-6 text-white/30 text-xs form-section">
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
            </svg>SSL Secured
        </span>
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
            </svg>Midtrans Payment
        </span>
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>Receipt Otomatis
        </span>
    </div>

</div>
</section>

@push('scripts')
<script>
(function () {
    // ============================================================
    // CONFIG — nilai Blade diteruskan ke JS
    // ============================================================
    var KATEGORI    = @json($kategori ?? '');
    var IS_BEREGU   = KATEGORI === 'beregu';
    var MIN_PEMAIN  = IS_BEREGU ? {{ $minPemain ?? 3 }} : 2;
    var MAX_PEMAIN  = IS_BEREGU ? {{ $maxPemain ?? 10 }} : 2;
    var slotState   = {};
    var jumlahPemain = MIN_PEMAIN;

    // ============================================================
    // BUAT HTML SLOT (string concat, tidak ada template literal
    // agar tidak bentrok dengan sintaks Blade / PHP)
    // ============================================================
    function makeSlot(idx, deletable) {
        var del = deletable
            ? '<button type="button" onclick="window._dewasa.hapus(this,' + idx + ')"'
              + ' class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center hover:bg-red-500/30 transition">'
              + '<svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
              + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
              + '</svg></button>'
            : '<div class="w-8"></div>';

        var iconKtp = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"'
            + ' stroke="rgba(249,115,22,.6)" stroke-width="1.5">'
            + '<rect x="3" y="5" width="18" height="14" rx="2"/>'
            + '<path d="M7 9h10M7 13h6"/></svg>';

        var iconScan = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
            + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"'
            + ' d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9'
            + 'M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>';

        var iconCheck = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
            + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
            + '</svg>';

        var iconKtpCard = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none"'
            + ' stroke="currentColor" stroke-width="2">'
            + '<rect x="3" y="5" width="18" height="14" rx="2"/>'
            + '<path d="M7 9h10M7 13h6"/></svg>';

        return ''
            + '<div id="ocr_card_' + idx + '" class="pemain-ocr-card" data-idx="' + idx + '">'

            // — Header —
            + '<div class="flex items-center justify-between mb-5">'
            + '<div class="flex items-center gap-2">'
            + '<div class="w-8 h-8 rounded-full flex items-center justify-center"'
            + ' style="background:rgba(249,115,22,.15);border:1px solid rgba(249,115,22,.3);">'
            + '<span class="text-brand-400 text-xs font-black pemain-number">' + (idx + 1) + '</span>'
            + '</div>'
            + '<span class="text-white/80 text-sm font-bold">Pemain ' + (idx + 1) + '</span>'
            + '<span id="scan_badge_' + idx + '" class="hidden items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold"'
            + ' style="background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#34d399;">'
            + iconCheck + ' Ter-scan</span>'
            + '</div>'
            + del
            + '</div>'

            // — Upload area —
            + '<div class="mb-4">'
            + '<label class="block text-white/50 text-xs font-semibold uppercase tracking-wide mb-2">'
            + 'Foto KTP <span class="text-brand-400">*</span>'
            + ' <span class="text-white/25 font-normal normal-case">&#8212; JPG, PNG &middot; Maks 5MB</span>'
            + '</label>'

            + '<div id="ktpDropzone_' + idx + '"'
            + ' onclick="document.getElementById(\'ktpInput_' + idx + '\').click()"'
            + ' class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all"'
            + ' style="border-color:rgba(249,115,22,.25);background:rgba(249,115,22,.02);"'
            + ' ondragover="event.preventDefault();this.style.borderColor=\'rgba(249,115,22,.6)\'"'
            + ' ondragleave="this.style.borderColor=\'rgba(249,115,22,.25)\'"'
            + ' ondrop="window._dewasa.drop(event,' + idx + ')">'

            // preview
            + '<div id="ktpPreview_' + idx + '" class="hidden">'
            + '<div class="relative inline-block mb-2">'
            + '<img id="ktpPreviewImg_' + idx + '" src="" alt=""'
            + ' class="max-h-32 mx-auto rounded-lg object-contain"'
            + ' style="box-shadow:0 4px 16px rgba(0,0,0,.5);">'
            + '<button type="button" onclick="window._dewasa.reset(event,' + idx + ')"'
            + ' class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 flex items-center justify-center transition">'
            + '<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
            + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>'
            + '</svg></button>'
            + '</div>'
            + '<p class="text-white/30 text-xs">Klik untuk ganti foto</p>'
            + '</div>'

            // default placeholder
            + '<div id="ktpDefault_' + idx + '" class="flex flex-col items-center py-2">'
            + '<div class="w-10 h-10 rounded-xl bg-brand-500/10 flex items-center justify-center mb-2">'
            + iconKtp
            + '</div>'
            + '<p class="text-white/55 text-sm font-medium">Klik atau seret foto KTP</p>'
            + '<p class="text-white/25 text-xs mt-0.5">JPG, PNG &middot; Maks 5MB</p>'
            + '</div>'
            + '</div>' // end dropzone

            + '<input type="file" id="ktpInput_' + idx + '" name="ktp_files[]"'
            + ' accept="image/jpeg,image/png,image/webp" class="hidden"'
            + ' onchange="window._dewasa.fileSelect(this,' + idx + ')">'

            // scan button
            + '<button type="button" id="scanBtn_' + idx + '"'
            + ' onclick="window._dewasa.scan(' + idx + ')"'
            + ' class="hidden mt-3 w-full py-2.5 rounded-xl font-display text-xs font-bold text-white'
            + ' tracking-wider flex items-center justify-center gap-2"'
            + ' style="background:linear-gradient(135deg,#f97316,#c2410c);box-shadow:0 4px 16px rgba(249,115,22,.25);">'
            + iconScan + ' SCAN KTP &#8212; Isi Otomatis'
            + '</button>'

            // loading
            + '<div id="scanLoading_' + idx + '" class="hidden mt-3 text-center py-2">'
            + '<p class="text-brand-400 text-xs font-semibold mb-1">Membaca KTP dengan AI...</p>'
            + '<div class="scan-loading-bar"><div class="scan-loading-bar-inner"></div></div>'
            + '</div>'
            + '</div>' // end upload area

            // KTP data card — inline editable
            + '<div id="ktpDataCard_' + idx + '" class="ktp-data-card">'
            + '<div class="flex items-center justify-between mb-2">'
            + '<p class="text-xs font-bold text-white/40 uppercase tracking-widest flex items-center gap-2">'
            + iconKtpCard + ' Data KTP Terbaca</p>'
            + '</div>'
            + '<p class="ktp-edit-hint mb-2">Klik nilai mana saja untuk mengedit langsung</p>'
            + '<div id="ktpDataRows_' + idx + '"></div>'
            + '</div>'

            + '</div>'; // end ocr_card
    }

    // ============================================================
    // INIT
    // ============================================================
    function initSlots() {
        var container = document.getElementById('ocrSlotsContainer');
        container.innerHTML = '';
        for (var i = 0; i < MIN_PEMAIN; i++) {
            container.insertAdjacentHTML('beforeend', makeSlot(i, false));
            slotState[i] = { file: null, scanned: false };
        }
    }

    // ============================================================
    // TAMBAH / HAPUS (Beregu)
    // ============================================================
    function tambah() {
        if (jumlahPemain >= MAX_PEMAIN) { toast('Maksimal ' + MAX_PEMAIN + ' pemain per tim.', 'warn'); return; }
        var idx = jumlahPemain++;
        document.getElementById('ocrSlotsContainer').insertAdjacentHTML('beforeend', makeSlot(idx, true));
        slotState[idx] = { file: null, scanned: false };
        updateBtn();
    }

    function hapus(btn, idx) {
        btn.closest('.pemain-ocr-card').remove();
        delete slotState[idx];
        renumber();
        updateBtn();
    }

    function renumber() {
        var cards = document.querySelectorAll('.pemain-ocr-card');
        cards.forEach(function(card, i) {
            var n = card.querySelector('.pemain-number');
            if (n) n.textContent = i + 1;
        });
        jumlahPemain = cards.length;
    }

    function updateBtn() {
        var btn = document.getElementById('tambahPemain');
        if (!btn) return;
        btn.style.opacity = jumlahPemain >= MAX_PEMAIN ? '0.4' : '1';
        btn.style.cursor  = jumlahPemain >= MAX_PEMAIN ? 'not-allowed' : 'pointer';
    }

    // ============================================================
    // FILE HANDLING
    // ============================================================
    function fileSelect(input, idx) {
        if (input.files && input.files[0]) processFile(input.files[0], idx);
    }

    function drop(e, idx) {
        e.preventDefault();
        document.getElementById('ktpDropzone_' + idx).style.borderColor = 'rgba(249,115,22,.25)';
        var file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            var dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('ktpInput_' + idx).files = dt.files;
            processFile(file, idx);
        }
    }

    function processFile(file, idx) {
        if (file.size > 5 * 1024 * 1024) { toast('File terlalu besar. Maks 5MB.', 'error'); return; }
        slotState[idx] = slotState[idx] || {};
        slotState[idx].file    = file;
        slotState[idx].scanned = false;
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('ktpPreviewImg_' + idx).src = e.target.result;
            document.getElementById('ktpPreview_'   + idx).classList.remove('hidden');
            document.getElementById('ktpDefault_'   + idx).classList.add('hidden');
            document.getElementById('scanBtn_'      + idx).classList.remove('hidden');
            document.getElementById('ktpDataCard_'  + idx).className = 'ktp-data-card';
            document.getElementById('ktpDataRows_'  + idx).innerHTML = '';
            document.getElementById('scan_badge_'   + idx).classList.add('hidden');
            document.getElementById('ocr_card_'     + idx).classList.remove('scanned');
        };
        reader.readAsDataURL(file);
    }

    function resetSlot(e, idx) {
        e.stopPropagation();
        slotState[idx] = { file: null, scanned: false };
        document.getElementById('ktpInput_'    + idx).value = '';
        document.getElementById('ktpPreview_'  + idx).classList.add('hidden');
        document.getElementById('ktpDefault_'  + idx).classList.remove('hidden');
        document.getElementById('scanBtn_'     + idx).classList.add('hidden');
        document.getElementById('scanLoading_' + idx).classList.add('hidden');
        document.getElementById('ktpDataCard_' + idx).className = 'ktp-data-card';
        document.getElementById('ktpDataRows_' + idx).innerHTML = '';
        document.getElementById('scan_badge_'  + idx).classList.add('hidden');
        document.getElementById('ocr_card_'    + idx).classList.remove('scanned');
        clearFields(idx);
    }

    // ============================================================
    // SCAN OCR
    // ============================================================
    function scan(idx) {
        if (!slotState[idx] || !slotState[idx].file) return;
        document.getElementById('scanBtn_'     + idx).classList.add('hidden');
        document.getElementById('scanLoading_' + idx).classList.remove('hidden');
        document.getElementById('ktpDataCard_' + idx).className = 'ktp-data-card';

        var fd = new FormData();
        fd.append('image', slotState[idx].file);

        var csrfEl = document.querySelector('meta[name="csrf-token"]');
        var csrf   = csrfEl ? csrfEl.content : '';

        fetch('/ocr/ktp', {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body:    fd,
        })
        .then(function(resp) {
            document.getElementById('scanLoading_' + idx).classList.add('hidden');
            document.getElementById('scanBtn_'     + idx).classList.remove('hidden');
            if (!resp.ok) {
                return resp.json().catch(function() { return {}; }).then(function(err) {
                    toast((err.message || 'HTTP ' + resp.status), 'error');
                });
            }
            return resp.json().then(function(result) {
                if (!result.success) { toast(result.message || 'Gagal membaca KTP.', 'error'); return; }
                var d = result.data;
                renderCard(idx, d);
                slotState[idx].scanned = true;
                var badge = document.getElementById('scan_badge_' + idx);
                badge.classList.remove('hidden');
                badge.style.display = 'inline-flex';
                document.getElementById('ocr_card_' + idx).classList.add('scanned');
                toast('KTP Pemain ' + (idx + 1) + ' berhasil dibaca — klik nilai untuk mengedit.', 'success');
            });
        })
        .catch(function(err) {
            document.getElementById('scanLoading_' + idx).classList.add('hidden');
            document.getElementById('scanBtn_'     + idx).classList.remove('hidden');
            toast('Tidak bisa konek ke OCR service.', 'error');
            console.error(err);
        });
    }

    // ============================================================
    // RENDER CARD DATA KTP — inline editable
    // Field dengan name form disertakan sebagai hidden input,
    // nilai tampil sebagai .ktp-value yang bisa diklik jadi input
    // ============================================================

    // Definisi field: label, key OCR, name form, editable?, type
    var CARD_FIELDS = [
        { l:'NIK',          k:'nik',                n:'nik[]',              ed:true,  t:'text',   hl:true,  req:false },
        { l:'Nama',         k:'nama',               n:'pemain[]',           ed:true,  t:'text',   hl:true,  req:true  },
        { l:'Tempat Lahir', k:'tempat_lahir',       n:'tempat_lahir[]',     ed:true,  t:'text',   hl:true,  req:false },
        { l:'Tgl Lahir',    k:'tanggal_lahir',      n:'tgl_lahir[]',        ed:true,  t:'text',   hl:true,  req:false },
        { l:'Jenis Kel.',   k:'jenis_kelamin',      n:'jenis_kelamin[]',    ed:true,  t:'select', hl:false, req:false },
        { l:'Alamat',       k:'alamat',             n:'',                   ed:false, t:'text',   hl:false, req:false },
        { l:'Kel/Desa',     k:'kelurahan',          n:'',                   ed:false, t:'text',   hl:false, req:false },
        { l:'Kecamatan',    k:'kecamatan',          n:'',                   ed:false, t:'text',   hl:false, req:false },
        { l:'Kota',         k:'kota_ktp',           n:'',                   ed:false, t:'text',   hl:false, req:false },
        { l:'Provinsi',     k:'provinsi_ktp',       n:'',                   ed:false, t:'text',   hl:false, req:false },
        { l:'Agama',        k:'agama',              n:'',                   ed:false, t:'text',   hl:false, req:false },
        { l:'Pekerjaan',    k:'pekerjaan',          n:'',                   ed:false, t:'text',   hl:false, req:false },
        { l:'Status Kawin', k:'status_perkawinan',  n:'',                   ed:false, t:'text',   hl:false, req:false },
        { l:'Gol. Darah',   k:'golongan_darah',     n:'',                   ed:false, t:'text',   hl:false, req:false },
    ];

    // State nilai per slot: { fieldKey: currentValue }
    var cardData = {};

    function renderCard(idx, data) {
        // Normalkan key kota/provinsi (OCR bisa pakai key berbeda)
        data.kota_ktp     = data.kota     || data.kabupaten || '';
        data.provinsi_ktp = data.provinsi || '';

        cardData[idx] = {};
        var card = document.getElementById('ktpDataCard_' + idx);
        var rows = document.getElementById('ktpDataRows_' + idx);
        rows.innerHTML = '';

        CARD_FIELDS.forEach(function(f) {
            var v = ((data[f.k] || '') + '').trim();
            if (!v && !f.req) return; // skip kosong kecuali required
            cardData[idx][f.k] = v;

            var rowId  = 'krow_' + idx + '_' + f.k;
            var valId  = 'kval_' + idx + '_' + f.k;
            var inpId  = 'kinp_' + idx + '_' + f.k;
            var hidId  = 'khid_' + idx + '_' + f.k;

            // Hidden input untuk submit (hanya field yang punya name form)
            var hidHtml = f.n
                ? '<input type="hidden" id="' + hidId + '" name="' + f.n + '" value="' + esc(v) + '"'
                  + (f.req ? ' data-required="1"' : '') + '>'
                : '';

            // Value span (klikable jika editable)
            var valAttr = f.ed
                ? ' title="Klik untuk edit" onclick="window._dewasa.inlineEdit(\'' + idx + '\',\'' + f.k + '\')"'
                : '';
            var valSpan = '<span id="' + valId + '" class="ktp-value' + (f.hl ? ' hl' : '') + '"' + valAttr + '>'
                + (v ? esc(v) : '<span style="color:rgba(255,255,255,.25);font-style:italic">— kosong</span>')
                + '</span>';

            // Select options untuk jenis kelamin
            var inpHtml = '';
            if (f.ed && f.t === 'select') {
                var opts = [
                    { v:'',          label:'-- Pilih --' },
                    { v:'LAKI-LAKI', label:'Laki-laki'   },
                    { v:'PEREMPUAN', label:'Perempuan'   },
                ];
                inpHtml = '<select id="' + inpId + '" class="ktp-inline-select" style="display:none"'
                    + ' onchange="window._dewasa.inlineSave(\'' + idx + '\',\'' + f.k + '\')"'
                    + ' onblur="window._dewasa.inlineSave(\'' + idx + '\',\'' + f.k + '\')">';
                opts.forEach(function(o) {
                    inpHtml += '<option value="' + o.v + '"' + (v === o.v ? ' selected' : '') + '>' + o.label + '</option>';
                });
                inpHtml += '</select>';
            } else if (f.ed) {
                inpHtml = '<input id="' + inpId + '" type="text" class="ktp-inline-input" style="display:none"'
                    + ' value="' + esc(v) + '"'
                    + ' onkeydown="window._dewasa.inlineKey(event,\'' + idx + '\',\'' + f.k + '\')"'
                    + ' onblur="window._dewasa.inlineSave(\'' + idx + '\',\'' + f.k + '\')">';
            }

            rows.innerHTML +=
                '<div id="' + rowId + '" class="ktp-row">'
                + '<span class="ktp-label">' + f.l + (f.req ? ' <span style="color:#f97316">*</span>' : '') + '</span>'
                + valSpan
                + inpHtml
                + hidHtml
                + '</div>';
        });

        card.className = 'ktp-data-card show valid-card';
    }

    // ── Mulai edit inline ──────────────────────────────────────
    function inlineEdit(idx, fieldKey) {
        var valEl = document.getElementById('kval_' + idx + '_' + fieldKey);
        var inpEl = document.getElementById('kinp_' + idx + '_' + fieldKey);
        if (!valEl || !inpEl) return;
        valEl.style.display = 'none';
        inpEl.style.display = '';
        inpEl.focus();
        if (inpEl.select) inpEl.select();
    }

    // ── Simpan & kembali ke tampilan ──────────────────────────
    function inlineSave(idx, fieldKey) {
        var valEl = document.getElementById('kval_' + idx + '_' + fieldKey);
        var inpEl = document.getElementById('kinp_' + idx + '_' + fieldKey);
        var hidEl = document.getElementById('khid_' + idx + '_' + fieldKey);
        if (!valEl || !inpEl) return;

        var newVal = (inpEl.value || inpEl.options && inpEl.options[inpEl.selectedIndex] && inpEl.value || '').trim();
        var origVal = (cardData[idx] && cardData[idx][fieldKey]) || '';
        var wasEdited = newVal !== origVal;

        // Update hidden input
        if (hidEl) hidEl.value = newVal;
        // Update cardData
        if (cardData[idx]) cardData[idx][fieldKey] = newVal;

        // Update display
        if (newVal) {
            valEl.innerHTML = esc(newVal);
            if (wasEdited) {
                valEl.style.color = '#fbbf24'; // kuning = diedit
                valEl.title = 'Diedit — klik untuk ubah lagi';
            } else {
                valEl.style.color = '';
            }
        } else {
            valEl.innerHTML = '<span style="color:rgba(255,255,255,.25);font-style:italic">— kosong</span>';
        }
        if (wasEdited && inpEl.classList) inpEl.classList.add('was-edited');

        inpEl.style.display = 'none';
        valEl.style.display = '';
    }

    // ── Enter = simpan, Escape = batal ────────────────────────
    function inlineKey(e, idx, fieldKey) {
        if (e.key === 'Enter')  { e.preventDefault(); inlineSave(idx, fieldKey); }
        if (e.key === 'Escape') {
            var valEl = document.getElementById('kval_' + idx + '_' + fieldKey);
            var inpEl = document.getElementById('kinp_' + idx + '_' + fieldKey);
            if (inpEl) inpEl.style.display = 'none';
            if (valEl) valEl.style.display  = '';
        }
    }

    // ── Ambil nilai final dari card untuk validasi submit ─────
    function getCardValue(idx, fieldKey) {
        var hidEl = document.getElementById('khid_' + idx + '_' + fieldKey);
        return hidEl ? hidEl.value.trim() : '';
    }

    // ── Autofill: langsung isi cardData + hidden input
    //    (dipanggil setelah OCR — tapi sekarang renderCard sudah handle semua)
    // Fungsi ini tetap tersedia sebagai fallback
    function autofill(idx, key, val) { /* sudah ditangani renderCard */ }
    function showBadge(idx, key)     { /* sudah tidak dipakai */ }
    function fieldEdit(idx, key)     { /* sudah tidak dipakai */ }
    function clearFields(idx) {
        if (cardData[idx]) cardData[idx] = {};
    }

    // ============================================================
    // ESCAPE HTML
    // ============================================================
    function esc(s) {
        return String(s)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ============================================================
    // TOAST
    // ============================================================
    var _timer = null;
    function toast(msg, type) {
        type = type || 'success';
        var el = document.getElementById('_dewasaToast');
        if (!el) {
            el = document.createElement('div');
            el.id = '_dewasaToast';
            el.style.cssText =
                'position:fixed;top:88px;right:20px;z-index:99999;max-width:380px;'
                + 'padding:12px 16px;border-radius:12px;font-size:12px;line-height:1.5;'
                + 'font-weight:600;box-shadow:0 8px 32px rgba(0,0,0,.4);'
                + 'transition:opacity .3s,transform .3s;pointer-events:none;';
            document.body.appendChild(el);
        }
        var S = {
            success:'background:rgba(6,30,18,.97);border:1px solid rgba(16,185,129,.4);color:#34d399;',
            warn:   'background:rgba(30,22,4,.97);border:1px solid rgba(234,179,8,.4);color:#fbbf24;',
            error:  'background:rgba(30,6,6,.97);border:1px solid rgba(239,68,68,.4);color:#f87171;',
        };
        el.style.cssText += (S[type] || S.error) + 'opacity:1;transform:translateY(0);';
        el.textContent = msg;
        if (_timer) clearTimeout(_timer);
        _timer = setTimeout(function() {
            el.style.opacity   = '0';
            el.style.transform = 'translateY(-8px)';
        }, 5000);
    }

    // ============================================================
    // EXPOSE ke window (dipanggil dari inline onclick HTML)
    // ============================================================
    window._dewasa = {
        fileSelect:  fileSelect,
        drop:        drop,
        reset:       resetSlot,
        scan:        scan,
        edit:        fieldEdit,
        hapus:       hapus,
        tambah:      tambah,
        inlineEdit:  inlineEdit,
        inlineSave:  inlineSave,
        inlineKey:   inlineKey,
    };
    window.tambahPemainOcr = tambah;

    // Validasi submit: pastikan field 'pemain[]' (Nama) terisi di semua slot
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('regForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                var cards = document.querySelectorAll('.pemain-ocr-card');
                var missing = false;
                cards.forEach(function(card) {
                    var idx = card.dataset.idx;
                    // Cek hidden input name="pemain[]" (nama)
                    var namaHid = document.getElementById('khid_' + idx + '_nama');
                    if (namaHid && !namaHid.value.trim()) {
                        missing = true;
                        namaHid.closest('.ktp-row').style.background = 'rgba(239,68,68,.08)';
                    }
                });
                if (missing) {
                    e.preventDefault();
                    toast('Nama pemain wajib diisi. Klik kolom Nama di card KTP untuk mengisi.', 'error');
                }
            });
        }
        initSlots();
    });
})();
</script>
@endpush

@endsection