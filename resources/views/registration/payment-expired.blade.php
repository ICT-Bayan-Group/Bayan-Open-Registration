@extends('layouts.app')
@section('title', 'Link Pembayaran Kedaluwarsa — Bayan Open 2026')

@section('content')
<section class="min-h-screen py-20 px-6 flex items-center justify-center">
<div class="max-w-lg mx-auto text-center">

    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-8"
         style="background:rgba(239,68,68,.12);border:2px solid rgba(239,68,68,.3);">
        <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
    </div>

    <h1 class="font-display text-3xl font-bold mb-4">Link Pembayaran Kedaluwarsa</h1>

    <p class="text-white/55 text-base leading-relaxed mb-8">
        Link pembayaran untuk tim <strong class="text-white">{{ $registration->tim_pb }}</strong>
        sudah tidak berlaku. Silakan hubungi panitia untuk meminta link pembayaran baru.
    </p>

    <div class="card-glass rounded-2xl p-6 mb-8 text-left">
        <div class="flex justify-between items-center py-2 border-b border-white/5">
            <span class="text-white/40 text-xs font-semibold uppercase tracking-wide">Order ID</span>
            <span class="font-mono text-white text-sm">{{ $registration->uuid }}</span>
        </div>
        <div class="flex justify-between items-center py-2">
            <span class="text-white/40 text-xs font-semibold uppercase tracking-wide">Nama Tim</span>
            <span class="text-white text-sm font-semibold">{{ $registration->tim_pb }}</span>
        </div>
    </div>

    <p class="text-white/30 text-sm">
        Hubungi panitia dengan menyertakan Order ID di atas.<br>
        Email: <a href="mailto:admin@bayanopen.com" class="text-brand-400 hover:underline">admin@bayanopen.com</a>
    </p>

</div>
</section>
@endsection