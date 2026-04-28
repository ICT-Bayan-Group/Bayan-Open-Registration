@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil — Cek Email Anda · Bayan Open 2026')

@push('styles')
<style>
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes popIn {
        0%   { transform: scale(0.7); opacity: 0; }
        70%  { transform: scale(1.08); }
        100% { transform: scale(1); opacity: 1; }
    }
    @keyframes pulse-soft {
        0%, 100% { box-shadow: 0 0 0 0 rgba(249,115,22,.3); }
        50%       { box-shadow: 0 0 0 14px rgba(249,115,22,0); }
    }
    @keyframes shimmer {
        0%   { background-position: -200% center; }
        100% { background-position:  200% center; }
    }

    .anim-1 { animation: fadeSlideUp .5s ease both .05s; }
    .anim-2 { animation: fadeSlideUp .5s ease both .15s; }
    .anim-3 { animation: fadeSlideUp .5s ease both .25s; }
    .anim-4 { animation: fadeSlideUp .5s ease both .35s; }
    .anim-5 { animation: fadeSlideUp .5s ease both .45s; }

    .icon-success {
        animation: popIn .55s cubic-bezier(.34,1.56,.64,1) both .1s;
        animation-fill-mode: both;
    }
    .pulse-ring {
        animation: pulse-soft 2.2s ease-in-out infinite;
    }

    .email-card {
        background: rgba(249,115,22,.04);
        border: 1.5px solid rgba(249,115,22,.2);
        border-radius: 18px;
        padding: 24px;
        transition: border-color .3s;
    }
    .email-card:hover { border-color: rgba(249,115,22,.4); }

    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,.04);
    }
    .step-item:last-child { border-bottom: none; }
    .step-num {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: rgba(249,115,22,.15);
        border: 1.5px solid rgba(249,115,22,.35);
        color: #f97316;
        font-size: 12px;
        font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255,255,255,.04);
        font-size: 13px;
    }
    .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
    .detail-label { color: rgba(0, 0, 0, 0.4); }
    .detail-value { color: #000000; font-weight: 600; text-align: right; max-width: 60%; }
    .detail-value.mono { font-family: monospace; color: #fb923c; font-size: 12px; }

    .shimmer-bar {
        height: 2px;
        border-radius: 99px;
        background: linear-gradient(90deg, transparent, rgba(249,115,22,.6), transparent);
        background-size: 200% 100%;
        animation: shimmer 2s ease infinite;
    }

    .tip-box {
        background: rgba(234,179,8,.06);
        border: 1px solid rgba(234,179,8,.2);
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 12.5px;
        color: rgba(234,179,8,.85);
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
<section class="min-h-screen py-20 px-6 flex items-center">
<div class="max-w-lg mx-auto w-full">

    {{-- ── SUCCESS ICON ──────────────────────────────────────────── --}}
    <div class="text-center mb-8 anim-1">
        <div class="relative inline-flex items-center justify-center mb-6">
            {{-- Pulse ring --}}
            <div class="absolute w-28 h-28 rounded-full pulse-ring"
                 style="background:rgba(249,115,22,.06);border:2px solid rgba(249,115,22,.12);"></div>
            {{-- Icon --}}
            <div class="icon-success relative w-20 h-20 rounded-full flex items-center justify-center"
                 style="background:linear-gradient(135deg,rgba(249,115,22,.2),rgba(234,88,12,.1));
                        border:2px solid rgba(249,115,22,.35);
                        box-shadow:0 8px 32px rgba(249,115,22,.18);">
                {{-- Envelope icon --}}
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                     stroke="rgba(249,115,22,.9)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            {{-- Check badge --}}
            <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full flex items-center justify-center"
                 style="background:#16a34a;border:2px solid #0f172a;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                     stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <h1 class="font-display text-2xl font-bold mb-2">Pendaftaran Berhasil!</h1>
        <p class="text-black/45 text-sm">
            Link pembayaran telah dikirim ke email Anda
        </p>
    </div>

    {{-- ── EMAIL CARD ────────────────────────────────────────────── --}}
    <div class="email-card mb-5 anim-2">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                 style="background:rgba(249,115,22,.12);border:1px solid rgba(249,115,22,.25);">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                     stroke="rgba(249,115,22,.8)" stroke-width="1.8">
                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-white/80 text-sm font-bold">Cek Email Anda</p>
                <p class="text-white/35 text-xs">Link pembayaran dikirim ke:</p>
            </div>
        </div>

        <div class="px-3 py-2.5 rounded-xl mb-4"
             style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);">
            <p class="text-brand-400 font-bold text-sm text-center">{{ $registration->email }}</p>
        </div>

        <div class="shimmer-bar mb-4"></div>

        {{-- Steps --}}
        <div>
            <div class="step-item">
                <div class="step-num">1</div>
                <div>
                    <p class="text-black/75 text-sm font-semibold">Buka email dari Bayan Open 2026</p>
                    <p class="text-black/35 text-xs mt-0.5">Subject: <em>"✅ Pendaftaran Diterima — Selesaikan Pembayaran"</em></p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">2</div>
                <div>
                    <p class="text-black/75 text-sm font-semibold">Klik tombol <span class="text-brand-400">Bayar Sekarang</span></p>
                    <p class="text-black/35 text-xs mt-0.5">Link aktif selama <strong class="text-yellow-400/70">24 jam</strong></p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">3</div>
                <div>
                    <p class="text-black/75 text-sm font-semibold">Selesaikan pembayaran</p>
                    <p class="text-black/35 text-xs mt-0.5">Transfer Bank, QRIS, atau metode lainnya</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">4</div>
                <div>
                    <p class="text-black/75 text-sm font-semibold">Receipt PDF dikirim otomatis</p>
                    <p class="text-black/35 text-xs mt-0.5">Bukti pendaftaran resmi Anda di Bayan Open 2026</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── DATA RINGKASAN ────────────────────────────────────────── --}}
    <div class="card-glass rounded-2xl p-6 mb-5 anim-3">
        <p class="text-black/35 text-xs font-bold uppercase tracking-widest mb-4">Ringkasan Pendaftaran</p>

        <div class="detail-row">
            <span class="detail-label">Order ID</span>
            <span class="detail-value mono">{{ $registration->midtrans_order_id }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tim / PB</span>
            <span class="detail-value">{{ $registration->tim_pb }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Kategori</span>
            <span class="detail-value">{{ $registration->kategori_label }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Jumlah Pemain</span>
            <span class="detail-value">{{ count($registration->pemain ?? []) }} orang</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Total Tagihan</span>
            <span class="detail-value" style="color:#f97316;">{{ $registration->harga_formatted }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Link Berlaku</span>
            <span class="detail-value" style="color:rgba(234,179,8,.8);font-size:12px;">
                s/d {{ $registration->payment_token_expires_at?->format('d M Y, H:i') ?? '-' }} WIB
            </span>
        </div>
    </div>

    {{-- ── TIP ──────────────────────────────────────────────────── --}}
    <div class="tip-box mb-6 anim-4">
        ⚠️ <strong>Email tidak masuk?</strong> Cek folder <strong>Spam / Junk</strong> Anda.
        Jika dalam 5 menit belum ada email, hubungi panitia di
        <strong>admin@bayanopen.com</strong> atau WhatsApp panitia dengan menyebutkan
        Order ID Anda.
    </div>

    {{-- ── ACTIONS ──────────────────────────────────────────────── --}}
    <div class="flex flex-col gap-3 anim-5">
        <a href="{{ route('registration.index') }}"
           class="btn-primary w-full py-3.5 rounded-xl font-display text-sm font-bold text-white text-center tracking-wide">
            Daftar Kategori Lain
        </a>
    </div>

    {{-- ── FOOTER NOTE ──────────────────────────────────────────── --}}
    <div class="flex justify-center gap-6 mt-8 text-white/25 text-xs">
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
            </svg>SSL Secured
        </span>
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
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
@endsection