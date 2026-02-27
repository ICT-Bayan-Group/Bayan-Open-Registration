@extends('layouts.app')
@section('title', 'Pembayaran Gagal')
@section('content')
<section class="min-h-screen flex items-center justify-center py-20 px-6">
    <div class="max-w-md w-full text-center">
        <div class="card-glass rounded-2xl p-10">
            <div class="w-20 h-20 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="font-display text-2xl font-bold text-red-400 mb-3">Pembayaran Gagal</h1>
            <p class="text-white/60 text-sm mb-6">Maaf, pembayaran Anda tidak berhasil diproses. Silakan coba lagi atau hubungi panitia.</p>
            <div class="flex flex-col gap-3">
                <a href="{{ route('registration.create') }}" class="btn-primary py-3 rounded-xl text-sm font-semibold text-white">
                    Coba Daftar Lagi
                </a>
                <a href="{{ url('/') }}" class="border border-white/20 py-3 rounded-xl text-sm text-white/70 hover:text-white transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>
@endsection