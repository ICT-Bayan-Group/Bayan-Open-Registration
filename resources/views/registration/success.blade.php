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
                @if($registration)
                    @if($registration->pdf_receipt_path)
                        {{-- PDF sudah tersedia: tampilkan tombol langsung --}}
                        <a href="{{ route('registration.receipt', $registration->uuid) }}"
                           id="btn-download"
                           class="btn-primary py-3 rounded-xl text-sm font-semibold text-white">
                            Download Receipt PDF
                        </a>
                    @else
                        {{-- PDF belum tersedia: tampilkan loader, polling di background --}}
                        <div id="receipt-loader" class="flex items-center justify-center gap-3 py-3 px-4 rounded-xl bg-white/5 text-white/60 text-sm">
                            <svg class="w-4 h-4 animate-spin text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            <span id="receipt-loader-text">Menyiapkan receipt PDF…</span>
                        </div>

                        {{-- Tombol download: tersembunyi, muncul saat polling berhasil --}}
                        <a href="{{ route('registration.receipt', $registration->uuid) }}"
                           id="btn-download"
                           class="btn-primary py-3 rounded-xl text-sm font-semibold text-white hidden">
                            Download Receipt PDF
                        </a>
                    @endif
                @endif

                <a href="{{ url('/') }}" class="border border-white/20 py-3 rounded-xl text-sm text-white/70 hover:text-white transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>

@if($registration && !$registration->pdf_receipt_path)
{{-- Polling: cek ketersediaan PDF setiap 3 detik, maks 90 detik --}}
<script>
(function () {
    const statusUrl  = '{{ route('registration.receipt.status', $registration->uuid) }}';
    const loader     = document.getElementById('receipt-loader');
    const btnDownload = document.getElementById('btn-download');
    const loaderText = document.getElementById('receipt-loader-text');

    let attempts  = 0;
    const maxAttempts = 30;   // 30 × 3s = 90 detik
    const interval    = 3000; // 3 detik

    const poll = setInterval(async () => {
        attempts++;

        try {
            const res  = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();

            if (data.ready) {
                clearInterval(poll);
                // Sembunyikan loader, tampilkan tombol
                if (loader)      loader.classList.add('hidden');
                if (btnDownload) btnDownload.classList.remove('hidden');
                return;
            }
        } catch (e) {
            // Network error — terus polling
        }

        // Timeout: PDF tidak juga tersedia, tampilkan pesan fallback
        if (attempts >= maxAttempts) {
            clearInterval(poll);
            if (loaderText) {
                loaderText.textContent = 'Receipt akan dikirim via email. Silakan cek kotak masuk Anda.';
            }
            const spinner = loader?.querySelector('svg');
            if (spinner) spinner.classList.add('hidden');
        }
    }, interval);
})();
</script>
@endif
@endsection