@extends('layouts.app')

@section('title', 'Form Pendaftaran')

@section('content')

<section class="min-h-screen py-20 px-6">
    <div class="max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-500/40 bg-brand-500/10 text-brand-300 text-xs font-semibold uppercase tracking-widest mb-6">
                Pendaftaran Online
            </div>
            <h1 class="font-display text-3xl font-bold mb-3">Formulir Pendaftaran</h1>
            <p class="text-white/50 text-sm">Bayan Open 2026 — Isi semua data dengan benar dan lengkap</p>
        </div>

        {{-- Error Box --}}
        @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6">
            <p class="text-red-400 text-sm font-semibold mb-2">Terdapat kesalahan:</p>
            <ul class="text-red-300 text-sm space-y-1 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('registration.store') }}" method="POST" id="regForm">
        @csrf

        {{-- ===== SECTION 1: DATA TIM ===== --}}
        <div class="card-glass rounded-2xl p-8 mb-6">
            <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs">1</span>
                Data Tim & Kontak
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Nama Ketua Tim / PIC --}}
                <div class="md:col-span-2">
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Nama Ketua Tim / PIC <span class="text-brand-400">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama') }}"
                        placeholder="Nama lengkap ketua tim / penanggung jawab"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm @error('nama') border-red-500 @enderror"
                        required
                    >
                    @error('nama') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Nama Tim / PB --}}
                <div class="md:col-span-2">
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Nama Tim / PB (Persatuan Bulutangkis) <span class="text-brand-400">*</span>
                    </label>
                    <input
                        type="text"
                        name="tim_pb"
                        value="{{ old('tim_pb') }}"
                        placeholder="Contoh: PB Garuda Sakti"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm @error('tim_pb') border-red-500 @enderror"
                        required
                    >
                    @error('tim_pb') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Email <span class="text-brand-400">*</span>
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="email@contoh.com"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm @error('email') border-red-500 @enderror"
                        required
                    >
                    <p class="text-white/30 text-xs mt-1">Receipt akan dikirim ke email ini</p>
                    @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Nomor WhatsApp / HP <span class="text-brand-400">*</span>
                    </label>
                    <input
                        type="text"
                        name="no_hp"
                        value="{{ old('no_hp') }}"
                        placeholder="Contoh: 08123456789"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm @error('no_hp') border-red-500 @enderror"
                        required
                    >
                    @error('no_hp') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Provinsi --}}
                <div>
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Provinsi <span class="text-brand-400">*</span>
                    </label>
                    <select
                        name="provinsi"
                        id="provinsiSelect"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm @error('provinsi') border-red-500 @enderror"
                        required
                    >
                        <option value="">-- Pilih Provinsi --</option>
                        <option value="Aceh" {{ old('provinsi') == 'Aceh' ? 'selected' : '' }}>Aceh</option>
                        <option value="Bali" {{ old('provinsi') == 'Bali' ? 'selected' : '' }}>Bali</option>
                        <option value="Banten" {{ old('provinsi') == 'Banten' ? 'selected' : '' }}>Banten</option>
                        <option value="Bengkulu" {{ old('provinsi') == 'Bengkulu' ? 'selected' : '' }}>Bengkulu</option>
                        <option value="DI Yogyakarta" {{ old('provinsi') == 'DI Yogyakarta' ? 'selected' : '' }}>DI Yogyakarta</option>
                        <option value="DKI Jakarta" {{ old('provinsi') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                        <option value="Gorontalo" {{ old('provinsi') == 'Gorontalo' ? 'selected' : '' }}>Gorontalo</option>
                        <option value="Jambi" {{ old('provinsi') == 'Jambi' ? 'selected' : '' }}>Jambi</option>
                        <option value="Jawa Barat" {{ old('provinsi') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                        <option value="Jawa Tengah" {{ old('provinsi') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                        <option value="Jawa Timur" {{ old('provinsi') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                        <option value="Kalimantan Barat" {{ old('provinsi') == 'Kalimantan Barat' ? 'selected' : '' }}>Kalimantan Barat</option>
                        <option value="Kalimantan Selatan" {{ old('provinsi') == 'Kalimantan Selatan' ? 'selected' : '' }}>Kalimantan Selatan</option>
                        <option value="Kalimantan Tengah" {{ old('provinsi') == 'Kalimantan Tengah' ? 'selected' : '' }}>Kalimantan Tengah</option>
                        <option value="Kalimantan Timur" {{ old('provinsi') == 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                        <option value="Kalimantan Utara" {{ old('provinsi') == 'Kalimantan Utara' ? 'selected' : '' }}>Kalimantan Utara</option>
                        <option value="Kepulauan Bangka Belitung" {{ old('provinsi') == 'Kepulauan Bangka Belitung' ? 'selected' : '' }}>Kepulauan Bangka Belitung</option>
                        <option value="Kepulauan Riau" {{ old('provinsi') == 'Kepulauan Riau' ? 'selected' : '' }}>Kepulauan Riau</option>
                        <option value="Lampung" {{ old('provinsi') == 'Lampung' ? 'selected' : '' }}>Lampung</option>
                        <option value="Maluku" {{ old('provinsi') == 'Maluku' ? 'selected' : '' }}>Maluku</option>
                        <option value="Maluku Utara" {{ old('provinsi') == 'Maluku Utara' ? 'selected' : '' }}>Maluku Utara</option>
                        <option value="Nusa Tenggara Barat" {{ old('provinsi') == 'Nusa Tenggara Barat' ? 'selected' : '' }}>Nusa Tenggara Barat</option>
                        <option value="Nusa Tenggara Timur" {{ old('provinsi') == 'Nusa Tenggara Timur' ? 'selected' : '' }}>Nusa Tenggara Timur</option>
                        <option value="Papua" {{ old('provinsi') == 'Papua' ? 'selected' : '' }}>Papua</option>
                        <option value="Papua Barat" {{ old('provinsi') == 'Papua Barat' ? 'selected' : '' }}>Papua Barat</option>
                        <option value="Riau" {{ old('provinsi') == 'Riau' ? 'selected' : '' }}>Riau</option>
                        <option value="Sulawesi Barat" {{ old('provinsi') == 'Sulawesi Barat' ? 'selected' : '' }}>Sulawesi Barat</option>
                        <option value="Sulawesi Selatan" {{ old('provinsi') == 'Sulawesi Selatan' ? 'selected' : '' }}>Sulawesi Selatan</option>
                        <option value="Sulawesi Tengah" {{ old('provinsi') == 'Sulawesi Tengah' ? 'selected' : '' }}>Sulawesi Tengah</option>
                        <option value="Sulawesi Tenggara" {{ old('provinsi') == 'Sulawesi Tenggara' ? 'selected' : '' }}>Sulawesi Tenggara</option>
                        <option value="Sulawesi Utara" {{ old('provinsi') == 'Sulawesi Utara' ? 'selected' : '' }}>Sulawesi Utara</option>
                        <option value="Sumatera Barat" {{ old('provinsi') == 'Sumatera Barat' ? 'selected' : '' }}>Sumatera Barat</option>
                        <option value="Sumatera Selatan" {{ old('provinsi') == 'Sumatera Selatan' ? 'selected' : '' }}>Sumatera Selatan</option>
                        <option value="Sumatera Utara" {{ old('provinsi') == 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                    </select>
                    @error('provinsi') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Kota --}}
                <div>
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Kota / Kabupaten <span class="text-brand-400">*</span>
                    </label>
                    <input
                        type="text"
                        name="kota"
                        value="{{ old('kota') }}"
                        placeholder="Nama kota atau kabupaten"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm @error('kota') border-red-500 @enderror"
                        required
                    >
                    @error('kota') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Alamat Lengkap <span class="text-brand-400">*</span>
                    </label>
                    <textarea
                        name="alamat"
                        rows="2"
                        placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm resize-none @error('alamat') border-red-500 @enderror"
                        required
                    >{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- ===== SECTION 2: NAMA PELATIH ===== --}}
        <div class="card-glass rounded-2xl p-8 mb-6">
            <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs">2</span>
                Data Pelatih
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Nama Pelatih --}}
                <div>
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        Nama Pelatih <span class="text-white/30 font-normal normal-case">(opsional)</span>
                    </label>
                    <input
                        type="text"
                        name="nama_pelatih"
                        value="{{ old('nama_pelatih') }}"
                        placeholder="Nama lengkap pelatih"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm"
                    >
                </div>

                {{-- No HP Pelatih --}}
                <div>
                    <label class="block text-white/70 text-xs font-semibold uppercase tracking-wide mb-2">
                        No. HP Pelatih <span class="text-white/30 font-normal normal-case">(opsional)</span>
                    </label>
                    <input
                        type="text"
                        name="no_hp_pelatih"
                        value="{{ old('no_hp_pelatih') }}"
                        placeholder="Contoh: 08123456789"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm"
                    >
                </div>

            </div>
        </div>

        {{-- ===== SECTION 3: NAMA PEMAIN ===== --}}
        <div class="card-glass rounded-2xl p-8 mb-6">
            <h2 class="font-display text-sm font-bold mb-2 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs">3</span>
                Daftar Nama Pemain
            </h2>
            <p class="text-white/40 text-xs mb-6">Minimal 1 pemain. Maksimal 10 pemain per tim.</p>

            <div id="pemainContainer" class="space-y-3">

                {{-- Pemain 1 (wajib) --}}
                <div class="pemain-row flex gap-3 items-center">
                    <div class="w-8 h-8 rounded-lg bg-brand-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-400 font-bold text-xs pemain-number">1</span>
                    </div>
                    <input
                        type="text"
                        name="pemain[]"
                        value="{{ old('pemain.0') }}"
                        placeholder="Nama pemain 1"
                        class="input-field flex-1 px-4 py-3 rounded-xl text-sm"
                        required
                    >
                    <div class="w-8"></div>
                </div>

                {{-- Pemain 2 (wajib) --}}
                <div class="pemain-row flex gap-3 items-center">
                    <div class="w-8 h-8 rounded-lg bg-brand-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-brand-400 font-bold text-xs pemain-number">2</span>
                    </div>
                    <input
                        type="text"
                        name="pemain[]"
                        value="{{ old('pemain.1') }}"
                        placeholder="Nama pemain 2"
                        class="input-field flex-1 px-4 py-3 rounded-xl text-sm"
                        required
                    >
                    <div class="w-8"></div>
                </div>

            </div>

            {{-- Tombol Tambah Pemain --}}
            <button
                type="button"
                id="tambahPemain"
                onclick="tambahPemain()"
                class="mt-4 flex items-center gap-2 text-brand-400 text-sm font-semibold hover:text-brand-300 transition"
            >
                <span class="w-6 h-6 rounded-full border-2 border-brand-400/50 flex items-center justify-center text-brand-400 text-lg leading-none">+</span>
                Tambah Pemain
            </button>

            @error('pemain')
                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
            @enderror
            @error('pemain.*')
                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- ===== SECTION 4: KATEGORI ===== --}}
        <div class="card-glass rounded-2xl p-8 mb-6">
            <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs">4</span>
                Pilih Kategori
            </h2>

            <div class="grid grid-cols-2 gap-3" id="kategoriSelector">

                {{-- Regu --}}
                <label class="kategori-option cursor-pointer" data-harga="200000" data-value="regu">
                    <input type="radio" name="kategori" value="regu" class="sr-only" {{ old('kategori', request('kategori')) === 'regu' ? 'checked' : '' }}>
                    <div class="rounded-xl p-5 border-2 border-white/10 text-center transition hover:border-indigo-500/60 kategori-card">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-5 h-5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                        </div>
                        <p class="font-display text-sm font-bold text-white mb-1">REGU</p>
                        <p class="text-indigo-400 font-bold text-base">Rp 200.000</p>
                        <p class="text-white/30 text-xs mt-1">Pertandingan tim</p>
                    </div>
                </label>

                {{-- Open --}}
                <label class="kategori-option cursor-pointer" data-harga="150000" data-value="open">
                    <input type="radio" name="kategori" value="open" class="sr-only" {{ old('kategori', request('kategori')) === 'open' ? 'checked' : '' }}>
                    <div class="rounded-xl p-5 border-2 border-white/10 text-center transition hover:border-emerald-500/60 kategori-card">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="font-display text-sm font-bold text-white mb-1">OPEN</p>
                        <p class="text-emerald-400 font-bold text-base">Rp 150.000</p>
                        <p class="text-white/30 text-xs mt-1">Pertandingan individu</p>
                    </div>
                </label>

            </div>

            @error('kategori')
                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
            @enderror

            {{-- Harga Preview --}}
            <div id="hargaPreview" class="hidden mt-5 p-4 rounded-xl bg-brand-500/10 border border-brand-500/30">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-white/60 text-xs">Total Pembayaran</p>
                        <p class="font-display text-brand-400 font-bold text-2xl" id="hargaText">-</p>
                    </div>
                    <div class="text-right">
                        <p class="text-white/60 text-xs">Kategori</p>
                        <p class="text-white font-semibold text-sm" id="kategoriText">-</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- ===== SUBMIT ===== --}}
        <button
            type="submit"
            id="submitBtn"
            class="btn-primary w-full py-4 rounded-xl font-display text-sm font-bold text-white tracking-wide"
        >
            DAFTAR & BAYAR SEKARANG →
        </button>

        <p class="text-white/30 text-xs text-center mt-4">
            Dengan mendaftar, Anda menyetujui syarat & ketentuan Bayan Open 2026
        </p>

        </form>

        {{-- Trust badges --}}
        <div class="flex justify-center gap-6 mt-6 text-white/30 text-xs">
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
    // ===== KATEGORI SELECTOR =====
    const options = document.querySelectorAll('.kategori-option');
    const hargaPreview = document.getElementById('hargaPreview');
    const hargaText = document.getElementById('hargaText');
    const kategoriText = document.getElementById('kategoriText');

    function formatRupiah(amount) {
        return 'Rp ' + amount.toLocaleString('id-ID');
    }

    function updateSelected() {
        options.forEach(opt => {
            const card = opt.querySelector('.kategori-card');
            const input = opt.querySelector('input[type=radio]');
            const val = opt.dataset.value;

            if (input.checked) {
                const harga = parseInt(opt.dataset.harga);
                const label = val === 'regu' ? 'Regu' : 'Open';

                card.classList.remove('border-white/10');
                card.classList.add(val === 'regu' ? 'border-indigo-500' : 'border-emerald-500', 'bg-white/5');

                hargaText.textContent = formatRupiah(harga);
                kategoriText.textContent = label;
                hargaPreview.classList.remove('hidden');
            } else {
                card.classList.remove('border-indigo-500', 'border-emerald-500', 'bg-white/5');
                card.classList.add('border-white/10');
            }
        });
    }

    options.forEach(opt => {
        opt.addEventListener('click', function() {
            this.querySelector('input[type=radio]').checked = true;
            updateSelected();
        });
    });

    updateSelected();

    // ===== TAMBAH PEMAIN =====
    let jumlahPemain = 2;
    const maxPemain = 10;

    function tambahPemain() {
        if (jumlahPemain >= maxPemain) {
            alert('Maksimal ' + maxPemain + ' pemain per tim.');
            return;
        }

        jumlahPemain++;
        const container = document.getElementById('pemainContainer');

        const row = document.createElement('div');
        row.className = 'pemain-row flex gap-3 items-center';
        row.innerHTML = `
            <div class="w-8 h-8 rounded-lg bg-brand-500/20 flex items-center justify-center flex-shrink-0">
                <span class="text-brand-400 font-bold text-xs pemain-number">${jumlahPemain}</span>
            </div>
            <input
                type="text"
                name="pemain[]"
                placeholder="Nama pemain ${jumlahPemain}"
                class="input-field flex-1 px-4 py-3 rounded-xl text-sm"
            >
            <button type="button" onclick="hapusPemain(this)" class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center hover:bg-red-500/30 transition flex-shrink-0">
                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

        container.appendChild(row);
        updateTambahButton();
    }

    function hapusPemain(btn) {
        btn.closest('.pemain-row').remove();
        jumlahPemain--;
        renumberPemain();
        updateTambahButton();
    }

    function renumberPemain() {
        const rows = document.querySelectorAll('.pemain-row');
        rows.forEach((row, index) => {
            const num = row.querySelector('.pemain-number');
            const input = row.querySelector('input');
            if (num) num.textContent = index + 1;
            if (input) input.placeholder = 'Nama pemain ' + (index + 1);
        });
        jumlahPemain = rows.length;
    }

    function updateTambahButton() {
        const btn = document.getElementById('tambahPemain');
        if (jumlahPemain >= maxPemain) {
            btn.style.opacity = '0.4';
            btn.style.cursor = 'not-allowed';
        } else {
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
        }
    }
</script>
@endpush

@endsection