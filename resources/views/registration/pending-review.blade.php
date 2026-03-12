@extends('layouts.app')
@section('title', 'Menunggu Verifikasi Admin — Bayan Open 2026')

@section('content')
<section class="min-h-screen py-20 px-6 flex items-center justify-center">
<div class="max-w-lg mx-auto text-center">

    {{-- Icon --}}
    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-8"
         style="background:rgba(234,179,8,.12);border:2px solid rgba(234,179,8,.3);">
        <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>

    {{-- Badge --}}
    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-6"
         style="background:rgba(234,179,8,.1);border:1px solid rgba(234,179,8,.3);">
        <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
        <span class="text-yellow-400 text-xs font-bold uppercase tracking-widest">Menunggu Verifikasi Admin</span>
    </div>

    <h1 class="font-display text-3xl font-bold mb-4">Pendaftaran Berhasil Dikirim!</h1>

    <p class="text-white/55 text-base leading-relaxed mb-8">
        Pendaftaran tim <strong class="text-white">{{ $registration->tim_pb }}</strong> sedang
        menunggu verifikasi dari panitia. Admin akan memeriksa data KTP seluruh anggota tim Anda.
    </p>

    {{-- Info card --}}
    <div class="card-glass rounded-2xl p-6 mb-8 text-left space-y-3">
        <div class="flex justify-between items-center py-2 border-b border-white/5">
            <span class="text-white/40 text-xs font-semibold uppercase tracking-wide">Order ID</span>
            <span class="font-mono text-white text-sm font-bold">{{ $registration->midtrans_order_id }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-white/5">
            <span class="text-white/40 text-xs font-semibold uppercase tracking-wide">Nama Tim</span>
            <span class="text-white text-sm font-semibold">{{ $registration->tim_pb }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-white/5">
            <span class="text-white/40 text-xs font-semibold uppercase tracking-wide">Jumlah Anggota</span>
            <span class="text-white text-sm">{{ $registration->jumlah_pemain }} orang</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-white/5">
            <span class="text-white/40 text-xs font-semibold uppercase tracking-wide">KTP Valid (Balikpapan)</span>
            <span class="text-sm font-bold {{ $registration->validCityCount() >= 6 ? 'text-green-400' : 'text-red-400' }}">
                {{ $registration->validCityCount() }} / {{ $registration->jumlahPemain }}
            </span>
        </div>
        <div class="flex justify-between items-center py-2">
            <span class="text-white/40 text-xs font-semibold uppercase tracking-wide">Total Pembayaran</span>
            <span class="text-brand-400 font-bold text-lg">{{ $registration->harga_formatted }}</span>
        </div>
    </div>

    {{-- Steps --}}
    <div class="rounded-2xl p-6 mb-8 text-left"
         style="background:rgba(249,115,22,.04);border:1px solid rgba(249,115,22,.15);">
        <p class="text-white/50 text-xs font-bold uppercase tracking-widest mb-4">Langkah Selanjutnya</p>
        <div class="space-y-3">
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="text-xs font-black text-white">1</span>
                </div>
                <p class="text-white/60 text-sm leading-relaxed">
                    Admin panitia akan <strong class="text-white/80">memverifikasi</strong>
                    data KTP seluruh anggota tim Anda.
                </p>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="text-xs font-black text-white/50">2</span>
                </div>
                <p class="text-white/60 text-sm leading-relaxed">
                    Jika disetujui, <strong class="text-white/80">link pembayaran</strong>
                    akan dikirim ke email <strong class="text-brand-400">{{ $registration->email }}</strong>.
                </p>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="text-xs font-black text-white/50">3</span>
                </div>
                <p class="text-white/60 text-sm leading-relaxed">
                    Selesaikan pembayaran melalui link tersebut untuk
                    <strong class="text-white/80">mengonfirmasi pendaftaran</strong> tim Anda.
                </p>
            </div>
        </div>
    </div>

    <a href="{{ route('registration.index') }}"
       class="inline-flex items-center gap-2 text-white/40 text-sm hover:text-white/70 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke halaman pendaftaran
    </a>

</div>
</section>
@endsection