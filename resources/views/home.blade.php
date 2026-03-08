@extends('layouts.app')

@section('title', 'Selamat Datang')

@section('content')

{{-- Hero Section --}}
<section class="min-h-screen flex items-center justify-center relative overflow-hidden">

    {{-- VIDEO BACKGROUND --}}
    <div class="absolute inset-0 z-0">
        <video
            autoplay
            muted
            loop
            playsinline
            class="w-full h-full object-cover"
            style="filter: brightness(0.35);"
        >
            <source src="https://res.cloudinary.com/djs5pi7ev/video/upload/v1769500972/202601271004_aepgij.mp4" type="video/mp4">
        </video>
        {{-- Overlay gradient supaya teks tetap terbaca --}}
        <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.05) 40%, rgba(0,0,0,0.6) 100%);"></div>
    </div>

    {{-- Konten Hero --}}
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">

        {{-- Logo --}}
 

        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-500/40 bg-brand-500/10 text-brand-300 text-xs font-semibold uppercase tracking-widest mb-6 animate-fade-up delay-100">
            <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse inline-block"></span>
            Pendaftaran Resmi Dibuka
        </div>

        {{-- Title --}}
            <img
                src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1767777416/LOGO_BO2025_resdzo.png"
                alt="Bayan Open 2026"
                class="h-28 md:h-40 mx-auto object-contain"
                style="filter: drop-shadow(0 0 40px rgba(249,115,22,0.5)) drop-shadow(0 4px 20px rgba(0,0,0,0.8));"
            >

        <p class="text-white/70 text-lg md:text-xl max-w-xl mx-auto mb-10 animate-fade-up delay-200 leading-relaxed" style="text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
            Turnamen bulutangkis bergengsi  Daftar sekarang dan buktikan kemampuan terbaikmu di lapangan!
        </p>

        <div class="flex flex-wrap gap-4 justify-center animate-fade-up delay-300">
            <a href="{{ route('registration.index') }}" class="btn-primary font-display text-sm font-bold px-8 py-4 rounded-xl text-white inline-block tracking-wide">
                DAFTAR SEKARANG →
            </a>
            <a href="#kategori" class="px-8 py-4 rounded-xl border border-white/30 text-white hover:border-white/60 hover:bg-white/10 transition font-medium text-sm backdrop-blur-sm">
                Lihat Kategori
            </a>
        </div>

        {{-- Stats --}}
        <div class="mt-14 grid grid-cols-3 gap-4 max-w-md mx-auto animate-fade-up delay-300">
            <div class="card-glass rounded-xl p-4 text-center">
                <p class="font-display text-2xl font-bold text-brand-400">2</p>
                <p class="text-white/50 text-xs mt-1">Kategori</p>
            </div>
            <div class="card-glass rounded-xl p-4 text-center">
                <p class="font-display text-2xl font-bold text-brand-400">150K</p>
                <p class="text-white/50 text-xs mt-1">Mulai dari</p>
            </div>
            <div class="card-glass rounded-xl p-4 text-center">
                <p class="font-display text-2xl font-bold text-brand-400">2026</p>
                <p class="text-white/50 text-xs mt-1">Season</p>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="mt-10 animate-bounce">
            <svg class="w-5 h-5 text-white/30 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

</section>

{{-- Kategori Section --}}
<section id="kategori" class="py-20 px-6">
    <div class="max-w-4xl mx-auto">

        <div class="text-center mb-12">
            <p class="text-brand-400 font-display text-xs tracking-widest uppercase mb-3">Pilihan Kategori</p>
            <h2 class="font-display text-3xl font-bold">Dua Kategori Bergengsi</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-6">

            {{-- Regu Card --}}
            <div class="card-glass rounded-2xl p-8 hover:border-brand-500/40 transition group">
                <div class="w-14 h-14 rounded-2xl bg-indigo-500/20 flex items-center justify-center mb-5 group-hover:scale-110 transition">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-xl font-bold mb-2">Kategori Regu</h3>
                <p class="text-white/50 text-sm mb-5">Bertanding secara tim, tunjukkan kerja sama dan strategi terbaik regu kalian.</p>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-white/40 text-xs">Biaya Pendaftaran</p>
                        <p class="font-display text-3xl font-bold text-indigo-400">200<span class="text-lg">.000</span></p>
                        <p class="text-white/40 text-xs">rupiah</p>
                    </div>
                    <a href="{{ route('registration.index') }}?kategori=regu" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold text-white">
                        Daftar →
                    </a>
                </div>
            </div>

            {{-- Open Card --}}
            <div class="card-glass rounded-2xl p-8 hover:border-brand-500/40 transition group">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 flex items-center justify-center mb-5 group-hover:scale-110 transition">
                    <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="font-display text-xl font-bold mb-2">Kategori Open</h3>
                <p class="text-white/50 text-sm mb-5">Kompetisi individu terbuka untuk semua kalangan, uji skill terbaikmu melawan lawan terkuat.</p>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-white/40 text-xs">Biaya Pendaftaran</p>
                        <p class="font-display text-3xl font-bold text-emerald-400">150<span class="text-lg">.000</span></p>
                        <p class="text-white/40 text-xs">rupiah</p>
                    </div>
                    <a href="{{ route('registration.index') }}?kategori=open" class="btn-primary px-5 py-2.5 rounded-xl text-sm font-semibold text-white">
                        Daftar →
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Flow Section --}}
<section class="py-16 px-6">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <p class="text-brand-400 font-display text-xs tracking-widest uppercase mb-3">Cara Daftar</p>
            <h2 class="font-display text-3xl font-bold">4 Langkah Mudah</h2>
        </div>

        <div class="grid md:grid-cols-4 gap-6 text-center">
            @foreach([
                ['1', 'Isi Form', 'Nama, tim, dan pilih kategori kamu'],
                ['2', 'Bayar Online', 'Via Midtrans — Transfer, QRIS, dll'],
                ['3', 'Konfirmasi', 'Email + PDF receipt otomatis terkirim'],
                ['4', 'Bertanding!', 'Tunjukkan kemampuan terbaikmu'],
            ] as $step)
            <div class="card-glass rounded-xl p-6">
                <div class="w-10 h-10 rounded-full bg-brand-500/20 flex items-center justify-center mx-auto mb-4">
                    <span class="font-display text-brand-400 font-bold text-sm">{{ $step[0] }}</span>
                </div>
                <h4 class="font-display text-sm font-bold mb-2">{{ $step[1] }}</h4>
                <p class="text-white/50 text-xs">{{ $step[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection