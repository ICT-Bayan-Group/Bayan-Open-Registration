@if($r->hasPaymentProof())
<div class="text-center">
    <img src="{{ $r->getPaymentProofUrl() }}"
         alt="Bukti Pembayaran"
         class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
    <p class="text-sm text-gray-500 mt-2">
        Upload: {{ $r->updated_at->format('d M Y, H:i') }}
    </p>
</div>
@else
<div class="text-center text-gray-500">
    <p>Tidak ada bukti pembayaran.</p>
</div>
@endif