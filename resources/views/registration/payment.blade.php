@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')

<section class="min-h-screen py-20 px-6 flex items-center justify-center">
    <div class="max-w-lg w-full">

        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-brand-500/20 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <h1 class="font-display text-2xl font-bold mb-2">Selesaikan Pembayaran</h1>
            <p class="text-white/50 text-sm">Transfer ke rekening berikut dan upload bukti pembayaran</p>
        </div>

        <div class="card-glass rounded-2xl p-8">

            {{-- Order Summary --}}
            <div class="mb-6 space-y-3">
                <h3 class="text-white/60 text-xs font-semibold uppercase tracking-wide mb-4">Ringkasan Pesanan</h3>

                <div class="flex justify-between text-sm">
                    <span class="text-white/60">Nama</span>
                    <span class="font-semibold">{{ $registration->nama }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-white/60">Tim / PB</span>
                    <span class="font-semibold">{{ $registration->tim_pb }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-white/60">Kategori</span>
                    <span class="font-semibold capitalize">{{ $registration->kategori_label }}</span>
                </div>

                <div class="border-t border-white/10 pt-3 mt-3">
                    <div class="flex justify-between">
                        <span class="text-white/60 font-semibold">Total Bayar</span>
                        <span class="font-display text-xl text-brand-400 font-bold">{{ $registration->harga_formatted }}</span>
                    </div>
                </div>
            </div>

            {{-- Bank Transfer Info --}}
            <div class="bg-white/5 p-4 rounded-xl mb-6">
                <h3 class="text-white font-semibold mb-3">Transfer ke Rekening:</h3>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-white/60">Bank</span>
                        <span class="font-semibold text-white">BCA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">No Rekening</span>
                        <span class="font-mono text-brand-400 font-semibold">1234567890</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Atas Nama</span>
                        <span class="font-semibold text-white">PT Contoh Event</span>
                    </div>
                </div>
            </div>

            {{-- Upload Form --}}
            <form method="POST" action="{{ route('registration.upload-payment', $registration->uuid) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="payment_proof" class="block text-sm font-medium text-white/80 mb-2">
                        Upload Bukti Transfer
                    </label>
                    <input
                        type="file"
                        id="payment_proof"
                        name="payment_proof"
                        required
                        accept="image/*"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-400 file:text-white hover:file:bg-brand-500"
                    >
                    <p class="text-white/50 text-xs mt-1">Format: JPG, PNG, WebP. Maksimal 5MB.</p>
                </div>

                <button
                    type="submit"
                    class="btn-primary w-full py-4 rounded-xl font-display text-sm font-bold text-white tracking-wide"
                >
                    KIRIM BUKTI PEMBAYARAN →
                </button>
            </form>

            <p class="text-white/30 text-xs text-center mt-4">
                Bukti pembayaran akan diverifikasi oleh admin dalam 1-2 hari kerja.
            </p>
        </div>

    </div>
</section>

@endsection