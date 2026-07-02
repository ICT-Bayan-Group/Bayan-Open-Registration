@extends('layouts.app')

@section('title', 'Pembayaran')

@push('styles')
<style>
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0);    }
    }

    #payDeadlineBanner {
        display:       none;
        border-radius: 18px;
        padding:       28px 24px;
        margin-bottom: 24px;
        text-align:    center;
        background:    rgb(223, 39, 39);
        border:        1.5px solid rgba(239,68,68,.28);
        animation:     fadeSlideUp .4s ease both;
    }
    #payDeadlineBanner.show { display: block; }
    .pay-deadline-icon  { font-size: 2.25rem; margin-bottom: 10px; display: block; }
    .pay-deadline-title {
        font-size: 1.05rem; font-weight: 800; color: #ffffff;
        margin: 0 0 6px; text-transform: uppercase; letter-spacing: .05em;
    }
    .pay-deadline-sub { font-size: .8rem; color: rgba(255,255,255,.7); line-height: 1.6; }
    .pay-deadline-sub strong { color: #fff; }

    #payCountdown {
        display:       none;
        margin-bottom: 20px;
        border-radius: 16px;
        padding:       14px 20px;
        animation:     fadeSlideUp .35s ease both;
    }
    #payCountdown.show { display: block; }
    .pay-cd-label {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .1em; color: #d97706; text-align: center; margin-bottom: 12px;
    }
    .pay-cd-grid { display: flex; align-items: center; justify-content: center; gap: 6px; }
    .pay-cd-unit { display: flex; flex-direction: column; align-items: center; gap: 4px; }
    .pay-cd-num {
        font-size: 1.5rem; font-weight: 900; color: #c2410c;
        min-width: 48px; text-align: center;
        background: rgba(249,115,22,.08); border: 1px solid rgba(249,115,22,.25);
        border-radius: 10px; padding: 6px 4px 4px; line-height: 1; transition: color .2s;
    }
    .pay-cd-num.urgent { color: #dc2626; }
    .pay-cd-unit-label {
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .08em; color: #b45309;
    }
    .pay-cd-sep { font-size: 1.3rem; font-weight: 900; color: rgba(249,115,22,.35); margin-bottom: 12px; }

    .payform-wrap-relative { position: relative; }
    #payFormDisabledMask {
        display: none; position: absolute; inset: 0; z-index: 500;
        border-radius: 20px; background: rgba(0,0,0,.5);
        backdrop-filter: blur(2px); -webkit-backdrop-filter: blur(2px); cursor: not-allowed;
    }
</style>
@endpush

@section('content')

<section class="min-h-screen py-20 px-6 flex items-center justify-center">
    <div class="max-w-lg w-full">

        <div class="text-center mb-8">
            <h1 class="font-display text-2xl font-bold mb-2">Selesaikan Pembayaran</h1>
            <p class="text-white/50 text-sm">Transfer ke rekening berikut dan upload bukti pembayaran</p>
        </div>

        {{-- ── DEADLINE BANNER (shown when closed) ──────────────────── --}}
        <div id="payDeadlineBanner">
            <p class="pay-deadline-title">Batas Waktu Pembayaran Terlewati</p>
            <p class="pay-deadline-sub">
                Batas pendaftaran &amp; pembayaran <strong>Bayan Open 2026</strong><br>
                telah berakhir pada <strong>19 Agustus 2026, 00:00 WITA</strong>.<br><br>
                Upload bukti pembayaran tidak dapat lagi dilakukan. Silakan hubungi panitia.
            </p>
        </div>

        {{-- ── COUNTDOWN (shown when still open) ─────────────────────── --}}
        <div id="payCountdown">
            <p class="pay-cd-label">Sisa Waktu Pembayaran</p>
            <div class="pay-cd-grid">
                <div class="pay-cd-unit">
                    <span class="pay-cd-num" id="pcd_hari">--</span>
                    <span class="pay-cd-unit-label">Hari</span>
                </div>
                <span class="pay-cd-sep">:</span>
                <div class="pay-cd-unit">
                    <span class="pay-cd-num" id="pcd_jam">--</span>
                    <span class="pay-cd-unit-label">Jam</span>
                </div>
                <span class="pay-cd-sep">:</span>
                <div class="pay-cd-unit">
                    <span class="pay-cd-num" id="pcd_menit">--</span>
                    <span class="pay-cd-unit-label">Menit</span>
                </div>
                <span class="pay-cd-sep">:</span>
                <div class="pay-cd-unit">
                    <span class="pay-cd-num" id="pcd_detik">--</span>
                    <span class="pay-cd-unit-label">Detik</span>
                </div>
            </div>
        </div>

        <div class="card-glass rounded-2xl p-8" style="position: relative; overflow: hidden;">
        <div id="payFormDisabledMask"></div>

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
                        <span class="font-semibold text-white">Bank Mandiri</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">No Rekening</span>
                        <span class="font-mono text-brand-400 font-semibold">1490018978967</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Atas Nama</span>
                        <span class="font-semibold text-white">SWANDHARU, S.KOM</span>
                    </div>
                </div>
            </div>

            {{-- Upload Form --}}
            <form id="paymentForm" method="POST" action="{{ route('registration.upload-payment', $registration->uuid) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Error Messages --}}
                @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-red-400 mb-1">Gagal Upload</h3>
                            <ul class="text-red-300 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

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
                    id="paySubmitBtn"
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

@push('scripts')
<script>
/* ================================================================
   PAYMENT DEADLINE CHECK — Server Time based (anti manipulasi jam lokal)
   Deadline: 19 Agustus 2026 00:00:00 WITA (UTC+8) = 2026-08-18 16:00:00 UTC
================================================================ */
(function () {
'use strict';

var DEADLINE_UTC_MS = Date.UTC(2026, 7, 18, 16, 0, 0);

var _offsetMs       = 0;
var _deadlinePassed = false;
var _cdInterval     = null;

async function initDeadline() {
    try {
        var fetchStart = Date.now();
        var res = await fetch('/server-time?_=' + fetchStart, { cache: 'no-store' });
        var fetchEnd = Date.now();
        if (!res.ok) throw new Error('HTTP ' + res.status);
        var json = await res.json();

        var latencyMs = (fetchEnd - fetchStart) / 2;
        var serverMs  = (json.timestamp * 1000) + latencyMs;
        _offsetMs     = serverMs - fetchEnd;
    } catch (e) {
        console.warn('[PAY DEADLINE] Gagal fetch /server-time, fallback ke client time:', e);
        _offsetMs = 0;
    }

    checkAndRender();
    _cdInterval = setInterval(tick, 1000);
}

function nowCorrected() { return Date.now() + _offsetMs; }

function checkAndRender() {
    var remaining = DEADLINE_UTC_MS - nowCorrected();
    if (remaining <= 0) { _deadlinePassed = true; showClosed(); }
    else                { showCountdown(remaining); }
}

function tick() {
    var remaining = DEADLINE_UTC_MS - nowCorrected();
    if (remaining <= 0) {
        _deadlinePassed = true;
        clearInterval(_cdInterval);
        showClosed();
        return;
    }
    updateCountdownDisplay(remaining);
}

function showClosed() {
    var banner = document.getElementById('payDeadlineBanner');
    if (banner) banner.classList.add('show');

    var cd = document.getElementById('payCountdown');
    if (cd) cd.classList.remove('show');

    var mask = document.getElementById('payFormDisabledMask');
    if (mask) mask.style.display = 'block';

    var form = document.getElementById('paymentForm');
    if (form) {
        form.querySelectorAll('input, select, textarea, button').forEach(function (el) {
            el.disabled = true;
        });
    }
}

function showCountdown(remainingMs) {
    var cd = document.getElementById('payCountdown');
    if (cd && !cd.classList.contains('show')) cd.classList.add('show');
    updateCountdownDisplay(remainingMs);
}

function updateCountdownDisplay(remainingMs) {
    var totalSec = Math.max(0, Math.floor(remainingMs / 1000));
    var hari     = Math.floor(totalSec / 86400);
    var jam      = Math.floor((totalSec % 86400) / 3600);
    var menit    = Math.floor((totalSec % 3600) / 60);
    var detik    = totalSec % 60;
    var urgent   = (hari === 0);

    setCD('pcd_hari',  pad(hari),  urgent && jam === 0);
    setCD('pcd_jam',   pad(jam),   urgent);
    setCD('pcd_menit', pad(menit), urgent);
    setCD('pcd_detik', pad(detik), urgent);
}

function setCD(id, val, urgent) {
    var el = document.getElementById(id);
    if (!el) return;
    el.textContent = val;
    urgent ? el.classList.add('urgent') : el.classList.remove('urgent');
}

function pad(n) { return n < 10 ? '0' + n : String(n); }

document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('paymentForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        var passed = _deadlinePassed || (DEADLINE_UTC_MS - nowCorrected() <= 0);
        if (passed) {
            e.preventDefault();
            e.stopImmediatePropagation();
            showClosed();
        }
    }, true); // capture phase — cegah submit sebelum handler lain jalan
});

document.addEventListener('DOMContentLoaded', initDeadline);

})();
</script>
@endpush

@endsection