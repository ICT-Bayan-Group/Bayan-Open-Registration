{{--
    TEMPLATE INI DIPAKAI UNTUK:
    - Ganda Dewasa Putra  → route: registration.ganda-dewasa-putra
    - Ganda Dewasa Putri  → route: registration.ganda-dewasa-putri
    - Beregu              → route: registration.beregu

    Bedakan dengan variable $kategori yang di-pass dari controller:
    'ganda-dewasa-putra' | 'ganda-dewasa-putri' | 'beregu'

    Controller contoh:
    public function showGandaDewasaPutra() {
        return view('registration.form-dewasa', [
            'kategori'       => 'ganda-dewasa-putra',
            'label'          => 'Ganda Dewasa Putra',
            'harga'          => 150000,
            'maxPemain'      => 2,
            'minPemain'      => 2,
            'showBeregu'     => false,
        ]);
    }
--}}

@extends('layouts.app')

@section('title', 'Pendaftaran ' . ($label ?? 'Ganda Dewasa') . ' — Bayan Open 2026')

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
    .form-section { animation: fadeSlideUp 0.4s ease both; }
    .form-section:nth-child(1) { animation-delay: 0.05s; }
    .form-section:nth-child(2) { animation-delay: 0.10s; }
    .form-section:nth-child(3) { animation-delay: 0.15s; }
    .form-section:nth-child(4) { animation-delay: 0.20s; }
    .form-section:nth-child(5) { animation-delay: 0.25s; }
    .form-section:nth-child(6) { animation-delay: 0.30s; }
</style>
@endpush

@section('content')
<section class="min-h-screen py-20 px-6">
    <div class="max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-10 form-section">
            {{-- Back link --}}
            <a href="{{ route('registration.index') }}" class="inline-flex items-center gap-2 text-white/30 text-xs hover:text-white/60 transition mb-6">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Ganti kategori
            </a>

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-500/40 bg-brand-500/10 text-brand-300 text-xs font-semibold uppercase tracking-widest mb-4 block">
                Pendaftaran Online · Bayan Open 2026
            </div>

            <h1 class="font-display text-3xl font-bold mb-2">Formulir Pendaftaran</h1>

            {{-- Kategori badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-3"
                style="background:rgba(249,115,22,0.1);border:1px solid rgba(249,115,22,0.3);">
                @if(($kategori ?? '') === 'beregu')
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="rgba(251,146,60,1)"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                @else
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(251,146,60,1)" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                @endif
                <span class="text-brand-400 text-xs font-bold uppercase tracking-widest">{{ $label ?? 'Ganda Dewasa' }}</span>
            </div>

            <p class="text-white/50 text-sm">Isi semua data dengan benar dan lengkap</p>
        </div>

        {{-- Error Box --}}
        @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 form-section">
            <p class="text-red-400 text-sm font-semibold mb-2">Terdapat kesalahan:</p>
            <ul class="text-red-300 text-sm space-y-1 list-disc list-inside">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('registration.store') }}" method="POST" enctype="multipart/form-data" id="regForm">
        @csrf
        <input type="hidden" name="kategori" value="{{ $kategori ?? '' }}">

        {{-- ===== SECTION 1: DATA TIM ===== --}}
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
                    <input type="text" name="nama" id="field_nama" value="{{ old('nama') }}"
                        placeholder="Nama lengkap ketua tim / penanggung jawab"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm @error('nama') border-red-500 @enderror" required>
                    @error('nama') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Nama Tim / PB <span class="text-brand-400">*</span>
                    </label>
                    <input type="text" name="tim_pb" value="{{ old('tim_pb') }}"
                        placeholder="Contoh: PB Garuda Sakti"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm @error('tim_pb') border-red-500 @enderror" required>
                    @error('tim_pb') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Email <span class="text-brand-400">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        placeholder="email@contoh.com"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm @error('email') border-red-500 @enderror" required>
                    <p class="text-white/30 text-xs mt-1">Receipt dikirim ke email ini</p>
                    @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Nomor WhatsApp / HP <span class="text-brand-400">*</span>
                    </label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                        placeholder="Contoh: 08123456789"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm @error('no_hp') border-red-500 @enderror" required>
                    @error('no_hp') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Provinsi <span class="text-brand-400">*</span>
                    </label>
                    <select name="provinsi" id="field_provinsi"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm @error('provinsi') border-red-500 @enderror" required>
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach(['Aceh','Bali','Banten','Bengkulu','DI Yogyakarta','DKI Jakarta','Gorontalo','Jambi','Jawa Barat','Jawa Tengah','Jawa Timur','Kalimantan Barat','Kalimantan Selatan','Kalimantan Tengah','Kalimantan Timur','Kalimantan Utara','Kepulauan Bangka Belitung','Kepulauan Riau','Lampung','Maluku','Maluku Utara','Nusa Tenggara Barat','Nusa Tenggara Timur','Papua','Papua Barat','Riau','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tengah','Sulawesi Tenggara','Sulawesi Utara','Sumatera Barat','Sumatera Selatan','Sumatera Utara'] as $prov)
                            <option value="{{ $prov }}" {{ old('provinsi') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                    @error('provinsi') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Kota / Kabupaten <span class="text-brand-400">*</span>
                    </label>
                    <input type="text" name="kota" value="{{ old('kota') }}"
                        placeholder="Nama kota atau kabupaten"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm @error('kota') border-red-500 @enderror" required>
                    @error('kota') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Alamat Lengkap <span class="text-brand-400">*</span>
                    </label>
                    <textarea name="alamat" rows="2"
                        placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm resize-none @error('alamat') border-red-500 @enderror"
                        required>{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- ===== SECTION 2: DATA PELATIH ===== --}}
        <div class="card-glass rounded-2xl p-8 mb-6 form-section">
            <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs">2</span>
                Data Pelatih
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Nama Pelatih <span class="text-white/30 font-normal normal-case">(opsional)</span>
                    </label>
                    <input type="text" name="nama_pelatih" value="{{ old('nama_pelatih') }}"
                        placeholder="Nama lengkap pelatih" class="input-field w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        No. HP Pelatih <span class="text-white/30 font-normal normal-case">(opsional)</span>
                    </label>
                    <input type="text" name="no_hp_pelatih" value="{{ old('no_hp_pelatih') }}"
                        placeholder="Contoh: 08123456789" class="input-field w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>
        </div>

        {{-- ===== SECTION 3: DAFTAR PEMAIN ===== --}}
        <div class="card-glass rounded-2xl p-8 mb-6 form-section">
            <h2 class="font-display text-sm font-bold mb-2 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs">3</span>
                Daftar Nama Pemain
            </h2>

            @if(($kategori ?? '') === 'beregu')
                <p class="text-white/40 text-xs mb-6">Minimal 3 pemain. Maksimal 10 pemain per tim.</p>
            @else
                <p class="text-white/40 text-xs mb-6">Masukkan 2 nama pemain (pasangan ganda).</p>
            @endif

            <div id="pemainContainer" class="space-y-3">
                @php $minPemain = ($kategori ?? '') === 'beregu' ? 3 : 2; @endphp
                @for($i = 0; $i < $minPemain; $i++)
                <div class="pemain-row flex gap-3 items-center">
                    <div class="w-8 h-8 rounded-lg bg-brand-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-400 font-bold text-xs pemain-number">{{ $i + 1 }}</span>
                    </div>
                    <input type="text" name="pemain[]" value="{{ old('pemain.' . $i) }}"
                        placeholder="Nama pemain {{ $i + 1 }}"
                        class="input-field flex-1 px-4 py-3 rounded-xl text-sm" required>
                    <div class="w-8"></div>
                </div>
                @endfor
            </div>

            @if(($kategori ?? '') === 'beregu')
            <button type="button" id="tambahPemain" onclick="tambahPemain()"
                class="mt-4 flex items-center gap-2 text-brand-400 text-sm font-semibold hover:text-brand-300 transition">
                <span class="w-6 h-6 rounded-full border-2 border-brand-400/50 flex items-center justify-center text-brand-400 text-lg leading-none">+</span>
                Tambah Pemain
            </button>
            @endif

            @error('pemain')   <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
            @error('pemain.*') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- ===== SECTION 4: UPLOAD KTP ===== --}}
        <div class="rounded-2xl p-8 mb-6 form-section" style="background:rgba(59,130,246,0.05);border:1px solid rgba(59,130,246,0.2);">
            <h2 class="font-display text-sm font-bold mb-2 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-xs">4</span>
                Upload KTP Pemain
            </h2>
            <p class="text-white/40 text-xs mb-5 ml-9">
                Upload foto/scan KTP untuk masing-masing pemain. Format JPG, PNG, atau PDF.
                Pastikan foto <strong class="text-white/60">jelas dan tidak terpotong</strong>.
            </p>

            <div id="ktpUploadContainer" class="space-y-4">
                {{-- KTP slots akan diisi JS sesuai jumlah pemain --}}
            </div>

            @error('ktp_files')   <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
            @error('ktp_files.*') <p class="text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- ===== SECTION 5: RINGKASAN BIAYA ===== --}}
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

        {{-- Submit --}}
        <button type="submit" class="btn-primary w-full py-4 rounded-xl font-display text-sm font-bold text-white tracking-wide form-section">
            DAFTAR & BAYAR SEKARANG →
        </button>

        <p class="text-white/30 text-xs text-center mt-4 form-section">
            Dengan mendaftar, Anda menyetujui syarat & ketentuan Bayan Open 2026
        </p>

        </form>

        {{-- Footer badges --}}
        <div class="flex justify-center gap-6 mt-6 text-white/30 text-xs form-section">
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                SSL Secured
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/></svg>
                Midtrans Payment
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Receipt Otomatis
            </span>
        </div>

    </div>
</section>

@push('scripts')
<script>
// ============================================================
// UPLOAD KTP SLOTS — mengikuti jumlah pemain yang diisi
// ============================================================

const kategori  = '{{ $kategori ?? "" }}';
const isBeregu  = kategori === 'beregu';
const minPemain = isBeregu ? 3 : 2;
let   totalKtp  = minPemain;

function renderKtpSlots() {
    const container = document.getElementById('ktpUploadContainer');
    const pemainRows = document.querySelectorAll('.pemain-row input');
    totalKtp = pemainRows.length;
    container.innerHTML = '';

    for (let i = 0; i < totalKtp; i++) {
        const nama = pemainRows[i] ? pemainRows[i].value.trim() : '';
        const label = nama || `Pemain ${i + 1}`;
        container.innerHTML += `
            <div class="ktp-slot" id="ktpSlot${i}">
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    KTP ${label} <span class="text-brand-400">*</span>
                </label>
                <div class="ktp-dropzone relative border-2 border-dashed border-blue-500/20 rounded-xl p-4 cursor-pointer transition-all duration-200 hover:border-blue-500/50"
                    style="background:rgba(59,130,246,0.03);"
                    onclick="document.getElementById('ktpFile${i}').click()"
                    ondragover="event.preventDefault();this.style.borderColor='rgba(59,130,246,0.6)'"
                    ondragleave="this.style.borderColor='rgba(59,130,246,0.2)'"
                    ondrop="handleKtpSlotDrop(event,${i})"
                >
                    <div id="ktpSlotPreview${i}" class="hidden text-center">
                        <div class="flex items-center justify-center gap-3">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(52,211,153,1)" stroke-width="2"><path d="M9 12l2 2 4-4"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span id="ktpFileName${i}" class="text-emerald-400 text-sm font-semibold truncate max-w-xs"></span>
                            <button type="button" onclick="resetKtpSlot(event,${i})"
                                class="text-white/30 hover:text-red-400 transition ml-auto">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                    <div id="ktpSlotDefault${i}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(96,165,250,0.8)" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
                        </div>
                        <div>
                            <p class="text-white/60 text-sm font-medium">Klik atau seret file KTP</p>
                            <p class="text-white/25 text-xs">JPG, PNG, PDF • Maks 5MB</p>
                        </div>
                    </div>
                </div>
                <input type="file" id="ktpFile${i}" name="ktp_files[]"
                    accept="image/jpeg,image/png,application/pdf" class="hidden"
                    onchange="handleKtpSlotChange(this,${i})">
            </div>
        `;
    }
}

function handleKtpSlotChange(input, idx) {
    if (!input.files || !input.files[0]) return;
    setKtpSlotFile(input.files[0], idx);
}

function handleKtpSlotDrop(event, idx) {
    event.preventDefault();
    event.currentTarget.style.borderColor = 'rgba(59,130,246,0.2)';
    const file = event.dataTransfer.files[0];
    if (file) {
        document.getElementById('ktpFile' + idx).files; // reference
        setKtpSlotFile(file, idx);
        // Assign ke input
        const dt   = new DataTransfer();
        dt.items.add(file);
        document.getElementById('ktpFile' + idx).files = dt.files;
    }
}

function setKtpSlotFile(file, idx) {
    if (file.size > 5 * 1024 * 1024) { alert('File terlalu besar. Maksimal 5MB.'); return; }
    document.getElementById('ktpSlotDefault' + idx).classList.add('hidden');
    document.getElementById('ktpSlotPreview' + idx).classList.remove('hidden');
    document.getElementById('ktpFileName'   + idx).textContent = file.name;
}

function resetKtpSlot(event, idx) {
    event.stopPropagation();
    document.getElementById('ktpFile'        + idx).value = '';
    document.getElementById('ktpSlotPreview' + idx).classList.add('hidden');
    document.getElementById('ktpSlotDefault' + idx).classList.remove('hidden');
}

// Update KTP slots otomatis saat nama pemain berubah
document.addEventListener('input', function(e) {
    if (e.target && e.target.name === 'pemain[]') {
        clearTimeout(window._ktpRenderTimer);
        window._ktpRenderTimer = setTimeout(renderKtpSlots, 500);
    }
});

// Init
renderKtpSlots();

// ============================================================
// TAMBAH / HAPUS PEMAIN (Beregu only)
// ============================================================
@if(($kategori ?? '') === 'beregu')
let jumlahPemain = {{ $minPemain ?? 3 }};
const maxPemain  = 10;

function tambahPemain() {
    if (jumlahPemain >= maxPemain) { alert('Maksimal ' + maxPemain + ' pemain per tim.'); return; }
    jumlahPemain++;
    const row = document.createElement('div');
    row.className = 'pemain-row flex gap-3 items-center';
    row.innerHTML = `
        <div class="w-8 h-8 rounded-lg bg-brand-500/20 flex items-center justify-center flex-shrink-0">
            <span class="text-brand-400 font-bold text-xs pemain-number">${jumlahPemain}</span>
        </div>
        <input type="text" name="pemain[]" placeholder="Nama pemain ${jumlahPemain}"
            class="input-field flex-1 px-4 py-3 rounded-xl text-sm">
        <button type="button" onclick="hapusPemain(this)"
            class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center hover:bg-red-500/30 transition flex-shrink-0">
            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>`;
    document.getElementById('pemainContainer').appendChild(row);
    updateTambahButton();
    renderKtpSlots();
}

function hapusPemain(btn) {
    btn.closest('.pemain-row').remove();
    renumberPemain();
    updateTambahButton();
    renderKtpSlots();
}

function renumberPemain() {
    document.querySelectorAll('.pemain-row').forEach((row, i) => {
        const num   = row.querySelector('.pemain-number');
        const input = row.querySelector('input');
        if (num)   num.textContent   = i + 1;
        if (input) input.placeholder = 'Nama pemain ' + (i + 1);
    });
    jumlahPemain = document.querySelectorAll('.pemain-row').length;
}

function updateTambahButton() {
    const btn = document.getElementById('tambahPemain');
    if (!btn) return;
    btn.style.opacity = jumlahPemain >= maxPemain ? '0.4' : '1';
    btn.style.cursor  = jumlahPemain >= maxPemain ? 'not-allowed' : 'pointer';
}
@endif
</script>
@endpush

@endsection