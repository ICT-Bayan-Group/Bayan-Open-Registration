{{-- PENDING --}}
@extends('layouts.app')
@section('title', 'Menunggu Pembayaran')
@section('content')
<section class="min-h-screen flex items-center justify-center py-20 px-6">
    <div class="max-w-md w-full text-center">
        <div class="card-glass rounded-2xl p-10">
            <div class="w-20 h-20 rounded-full bg-yellow-500/20 flex items-center justify-center mx-auto mb-6 animate-float">
                <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="font-display text-2xl font-bold text-yellow-400 mb-3">Menunggu Pembayaran</h1>
            <p class="text-white/60 text-sm mb-6">Pembayaran Anda sedang dalam proses verifikasi. Harap selesaikan pembayaran sesuai instruksi yang diberikan.</p>

            @if($registration)
            <div class="bg-white/5 rounded-xl p-4 text-left text-sm space-y-2 mb-6">
                <div class="flex justify-between">
                    <span class="text-white/50">Order ID</span>
                    <span class="font-mono text-brand-400 text-xs">{{ $registration->midtrans_order_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/50">Status</span>
                    <span class="text-yellow-400 font-semibold">⏳ PENDING</span>
                </div>
            </div>
            @endif

            <a href="{{ url('/') }}" class="border border-white/20 py-3 px-8 rounded-xl text-sm text-white/70 hover:text-white transition inline-block">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</section>
@endsection