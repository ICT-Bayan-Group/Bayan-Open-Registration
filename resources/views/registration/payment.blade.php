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
            <p class="text-white/50 text-sm">Klik tombol di bawah untuk membuka halaman pembayaran</p>
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
                <div class="flex justify-between text-sm">
                    <span class="text-white/60">Order ID</span>
                    <span class="font-mono text-brand-400 text-xs">{{ $registration->midtrans_order_id }}</span>
                </div>

                <div class="border-t border-white/10 pt-3 mt-3">
                    <div class="flex justify-between">
                        <span class="text-white/60 font-semibold">Total Bayar</span>
                        <span class="font-display text-xl text-brand-400 font-bold">{{ $registration->harga_formatted }}</span>
                    </div>
                </div>
            </div>

            {{-- Pay Button --}}
            <button
                id="payBtn"
                onclick="triggerPayment()"
                class="btn-primary w-full py-4 rounded-xl font-display text-sm font-bold text-white tracking-wide"
            >
                BAYAR SEKARANG →
            </button>

            <p class="text-white/30 text-xs text-center mt-4">
                Pembayaran aman via Midtrans. Dukung transfer bank, QRIS, e-wallet, dan lainnya.
            </p>
        </div>

    </div>
</section>

@push('head')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endpush

@push('scripts')
<script>
    function triggerPayment() {
        const btn = document.getElementById('payBtn');
        btn.textContent = 'Membuka payment...';
        btn.disabled = true;

        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = "{{ route('registration.success') }}?order_id={{ $registration->midtrans_order_id }}";
            },
            onPending: function(result) {
                window.location.href = "{{ route('registration.pending') }}?order_id={{ $registration->midtrans_order_id }}";
            },
            onError: function(result) {
                window.location.href = "{{ route('registration.error') }}";
            },
            onClose: function() {
                btn.textContent = 'BAYAR SEKARANG →';
                btn.disabled = false;
            }
        });
    }
</script>
@endpush

@endsection