@extends('layouts.app')

@section('title', 'Status Pembayaran')

@section('content')

<section class="min-h-screen py-20 px-6 flex items-center justify-center">
    <div class="max-w-lg w-full">

        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-green-500/20 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="font-display text-2xl font-bold mb-2">Terima Kasih!</h1>
            <p class="text-white/50 text-sm">Bukti pembayaran telah berhasil dikirim</p>
        </div>

        <div class="card-glass rounded-2xl p-8 text-center">

            <div class="mb-6">
                <h2 class="text-white font-semibold text-lg mb-2">Menunggu Verifikasi</h2>
                <p class="text-white/70 text-sm leading-relaxed">
                    Bukti pembayaran Anda sedang diperiksa oleh admin.
                    Proses verifikasi biasanya memakan waktu 1-2 hari kerja.
                </p>
            </div>

            <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 mb-6">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-blue-400 font-semibold">Email Notifikasi</span>
                </div>
                <p class="text-white/80 text-sm">
                    Hasil verifikasi akan dikirim ke email Anda:
                    <strong class="text-white">{{ $registration->email }}</strong>
                </p>
            </div>

            {{-- Status Info --}}
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-white/10">
                    <span class="text-white/60">ID Pendaftaran</span>
                    <span class="font-mono text-white">{{ $registration->uuid }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-white/10">
                    <span class="text-white/60">Status</span>
                    <span class="text-yellow-400 font-semibold">
                        @switch($registration->status)
                            @case('pending')
                                Belum Bayar
                                @break
                            @case('pending_verification')
                                Menunggu Verifikasi
                                @break
                            @case('paid')
                                Sudah Bayar
                                @break
                            @case('failed')
                                Pembayaran Ditolak
                                @break
                            @default
                                {{ ucfirst($registration->status) }}
                        @endswitch
                    </span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-white/60">Total Pembayaran</span>
                    <span class="font-display text-white font-bold">{{ $registration->harga_formatted }}</span>
                </div>
            </div>

            <div class="mt-8 space-y-3">
                <a href="{{ route('home') }}" class="btn-primary w-full block text-center">
                    Kembali ke Beranda
                </a>

                @if($registration->status === 'failed')
                <a href="{{ route('registration.payment.token', $registration->payment_token) }}"
                   class="btn-secondary w-full block text-center">
                    Upload Ulang Bukti
                </a>
                @endif
            </div>

        </div>

    </div>
</section>

@endsection