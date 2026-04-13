@extends('layouts.app')
@section('title', 'Revisi Dikirim — Bayan Open 2026')
@section('content')
<section class="min-h-screen flex items-center justify-center px-4 py-20">
<div class="max-w-lg mx-auto text-center">
    <div class="card-glass rounded-3xl p-12">
        <div class="text-6xl mb-6">✅</div>
        <h1 class="font-display text-2xl font-bold mb-3">Revisi Berhasil Dikirim!</h1>
        <p class="text-white/50 text-sm leading-relaxed mb-6">
            Perbaikan data pendaftaran tim <strong class="text-white/80">{{ $registration->tim_pb }}</strong>
            telah kami terima. Admin akan meninjau ulang dan menghubungi Anda via email dalam
            <strong class="text-brand-400">1–3 hari kerja</strong>.
        </p>
        <div class="rounded-xl p-4 mb-8 text-left" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);">
            <p class="text-xs text-white/30 uppercase font-bold tracking-wider mb-2">Selanjutnya</p>
            <ul class="text-sm text-white/50 space-y-2">
                <li>📧 Cek email untuk notifikasi hasil verifikasi</li>
                <li>✅ Jika disetujui, link pembayaran dikirim via email</li>
                <li>🔄 Jika masih ada perbaikan, link revisi baru dikirimkan</li>
            </ul>
        </div>
        <a href="{{ route('home') }}"
           class="inline-block px-8 py-3 rounded-xl font-bold text-sm text-white"
           style="background:linear-gradient(135deg,#f59e0b,#d97706);">
            Kembali ke Beranda
        </a>
    </div>
</div>
</section>
@endsection