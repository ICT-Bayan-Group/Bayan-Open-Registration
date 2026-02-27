{{-- SUCCESS --}}
@extends('layouts.app')
@section('title', 'Pembayaran Berhasil')
@section('content')
<section class="min-h-screen flex items-center justify-center py-20 px-6">
    <div class="max-w-md w-full text-center">
        <div class="card-glass rounded-2xl p-10">
            <div class="w-20 h-20 rounded-full bg-emerald-500/20 flex items-center justify-center mx-auto mb-6 animate-float">
                <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="font-display text-2xl font-bold text-emerald-400 mb-3">Pembayaran Berhasil!</h1>
            <p class="text-white/60 text-sm mb-6">Terima kasih telah mendaftar Bayan Open 2026. Receipt akan dikirim via email.</p>

            @if($registration)
            <div class="bg-white/5 rounded-xl p-4 text-left text-sm space-y-2 mb-6">
                <div class="flex justify-between">
                    <span class="text-white/50">Nama</span>
                    <span class="font-semibold">{{ $registration->nama }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/50">Order ID</span>
                    <span class="font-mono text-brand-400 text-xs">{{ $registration->midtrans_order_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/50">Status</span>
                    <span class="text-emerald-400 font-semibold">✅ PAID</span>
                </div>
            </div>
            @endif

            <div class="flex flex-col gap-3">
                @if($registration && $registration->pdf_receipt_path)
                <a href="{{ route('registration.receipt', $registration->uuid) }}" class="btn-primary py-3 rounded-xl text-sm font-semibold text-white">
                    Download Receipt PDF
                </a>
                @endif
                <a href="{{ url('/') }}" class="border border-white/20 py-3 rounded-xl text-sm text-white/70 hover:text-white transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>
@endsection