@extends('layouts.app')

@section('title', 'Pendaftaran ' . ($label ?? 'Ganda Dewasa') . ' — Bayan Open 2026')

@push('styles')
<style>
    /* ── Animasi ─────────────────────────────────────────────── */
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0);    }
    }
    @keyframes shimmerScan {
        0%   { background-position: -200% center; }
        100% { background-position:  200% center; }
    }
    @keyframes pulse-ring {
        0%   { box-shadow: 0 0 0 0   rgba(249,115,22,.45); }
        70%  { box-shadow: 0 0 0 8px rgba(249,115,22,0);   }
        100% { box-shadow: 0 0 0 0   rgba(249,115,22,0);   }
    }
    @keyframes modalBackdropIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes modalCardIn {
        from { opacity: 0; transform: scale(.92) translateY(16px); }
        to   { opacity: 1; transform: scale(1)   translateY(0);    }
    }
    @keyframes shake {
        0%,100% { transform: translateX(0); }
        20%     { transform: translateX(-6px); }
        40%     { transform: translateX(6px); }
        60%     { transform: translateX(-4px); }
        80%     { transform: translateX(4px); }
    }
    @keyframes spinLoader {
        to { transform: rotate(360deg); }
    }
    @keyframes progressBar {
        from { width: 0%; }
        to   { width: 100%; }
    }
    @keyframes toastIn {
        from { opacity: 0; transform: translateY(-12px) scale(.96); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* ── Form section stagger ────────────────────────────────── */
    .form-section                 { animation: fadeSlideUp .45s ease both; }
    .form-section:nth-child(1)    { animation-delay: .06s; }
    .form-section:nth-child(2)    { animation-delay: .12s; }
    .form-section:nth-child(3)    { animation-delay: .18s; }
    .form-section:nth-child(4)    { animation-delay: .24s; }
    .form-section:nth-child(5)    { animation-delay: .30s; }
    .form-section:nth-child(6)    { animation-delay: .36s; }

    /* ── OCR Card ────────────────────────────────────────────── */
    .pemain-ocr-card {
        border-radius: 18px;
        border: 1.5px solid rgba(249,115,22,.18);
        background: rgba(20,10,4,.75);
        padding: 22px;
        transition: border-color .3s, background .3s, box-shadow .3s;
    }
    .pemain-ocr-card.scanned {
        border-color: rgba(16,185,129,.45);
        background:   rgba(4,20,12,.75);
        box-shadow:   0 0 0 1px rgba(16,185,129,.1) inset;
    }
    .pemain-ocr-card.gender-error {
        border-color: rgba(239,68,68,.55);
        background:   rgba(20,4,4,.80);
        box-shadow:   0 0 0 1px rgba(239,68,68,.12) inset;
        animation:    shake .4s ease;
    }
    .pemain-ocr-card.has-field-error {
        border-color: rgba(239,68,68,.4);
    }

    /* ── KTP Data Card ───────────────────────────────────────── */
    .ktp-data-card {
        border-radius: 13px;
        background:    rgba(255,255,255,.025);
        border:        1px solid rgba(255,255,255,.07);
        padding:       14px 16px;
        margin-top:    14px;
        display:       none;
    }
    .ktp-data-card.show        { display: block; animation: fadeSlideUp .3s ease both; }
    .ktp-data-card.valid-card  { background: rgba(249,115,22,.04); border-color: rgba(249,115,22,.22); }

    /* ── KTP Row ─────────────────────────────────────────────── */
    .ktp-row {
        display:       flex;
        align-items:   center;
        gap:           10px;
        padding:       5px 0;
        border-bottom: 1px solid rgba(255,255,255,.04);
        min-height:    32px;
    }
    .ktp-row:last-child   { border-bottom: none; padding-bottom: 0; }
    .ktp-label {
        font-size:      10px;
        font-weight:    700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color:          rgba(255,255,255,.28);
        min-width:      82px;
        flex-shrink:    0;
    }

    /* ── Inline value display ────────────────────────────────── */
    .ktp-value {
        flex:        1;
        font-size:   12px;
        color:       rgba(255,255,255,.75);
        line-height: 1.4;
        word-break:  break-word;
        padding:     3px 8px;
        border-radius: 6px;
        border:      1px solid transparent;
        cursor:      pointer;
        transition:  background .15s, border-color .15s, color .15s;
    }
    .ktp-value.hl  { color: #fff; font-weight: 600; }
    .ktp-value:hover {
        background:   rgba(249,115,22,.08);
        border-color: rgba(249,115,22,.28);
        color:        #fff;
    }
    .ktp-value:hover::after { content: ' ✏'; font-size: 9px; opacity: .45; margin-left: 3px; }

    /* ── Jenis Kelamin row ───────────────────────────────────── */
    .ktp-gender-value {
        flex:         1;
        font-size:    12px;
        font-weight:  700;
        padding:      3px 10px;
        border-radius: 6px;
        border:       1px solid;
    }
    .ktp-gender-value.gender-l {
        color:           #60a5fa;
        background:      rgba(59,130,246,.08);
        border-color:    rgba(59,130,246,.25);
    }
    .ktp-gender-value.gender-p {
        color:           #f9a8d4;
        background:      rgba(236,72,153,.08);
        border-color:    rgba(236,72,153,.25);
    }
    .ktp-gender-value.gender-unknown {
        color:           rgba(255,255,255,.3);
        background:      transparent;
        border-color:    rgba(255,255,255,.08);
        font-style:      italic;
        font-weight:     400;
    }

    /* ── Inline input ────────────────────────────────────────── */
    .ktp-inline-input {
        flex:        1;
        background:  rgba(249,115,22,.07);
        border:      1.5px solid rgba(249,115,22,.5);
        border-radius: 6px;
        color:       #fff;
        font-size:   12px;
        font-weight: 600;
        padding:     3px 9px;
        outline:     none;
        min-width:   0;
        transition:  border-color .15s, box-shadow .15s;
    }
    .ktp-inline-input:focus {
        border-color: rgba(249,115,22,.9);
        box-shadow:   0 0 0 2px rgba(249,115,22,.16);
    }
    .ktp-inline-input.was-edited {
        border-color: rgba(234,179,8,.65);
        background:   rgba(234,179,8,.07);
    }

    /* ── Edit hint ───────────────────────────────────────────── */
    .ktp-edit-hint {
        font-size:   9.5px;
        color:       rgba(249,115,22,.38);
        text-align:  right;
        font-style:  italic;
        margin-top:  4px;
    }

    /* ── Usia display ────────────────────────────────────────── */
    .usia-display {
        flex:        1;
        font-size:   12px;
        font-weight: 700;
        line-height: 1.4;
        padding:     3px 8px;
        border-radius: 6px;
        cursor:      default;
        transition:  color .25s, background .25s;
    }
    .usia-display.has-value {
        color:       #34d399;
        background:  rgba(16,185,129,.07);
        border:      1px solid rgba(16,185,129,.2);
    }
    .usia-display.no-value {
        color:       rgba(255,255,255,.25);
        font-style:  italic;
        font-weight: 400;
        background:  transparent;
        border:      1px solid transparent;
    }

    /* ── Scan loading bar ────────────────────────────────────── */
    .scan-loading-bar { height: 3px; border-radius: 99px; overflow: hidden; background: rgba(249,115,22,.1); margin-top: 10px; }
    .scan-loading-bar-inner {
        height: 100%; width: 40%;
        background:      linear-gradient(90deg, transparent, #f97316, transparent);
        background-size: 200% 100%;
        animation:       shimmerScan 1.2s ease infinite;
    }

    /* ── Select dark theme ───────────────────────────────────── */
    select.input-field {
        color:            rgba(255,255,255,.85) !important;
        background-color: #0d1117 !important;
        cursor:           pointer;
    }
    select.input-field option          { background-color: #0d1117; color: rgba(255,255,255,.85); }
    select.input-field option:disabled { color: rgba(255,255,255,.3); }
    select.input-field:disabled        { opacity: .4 !important; cursor: not-allowed; }

    /* ── Scan badge ──────────────────────────────────────────── */
    .scan-badge {
        display:     none;
        align-items: center;
        gap:         4px;
        padding:     2px 8px;
        border-radius: 99px;
        font-size:   11px;
        font-weight: 700;
        background:  rgba(16,185,129,.1);
        border:      1px solid rgba(16,185,129,.3);
        color:       #34d399;
    }

    /* ════════════════════════════════════════════════════════════
       UPLOAD BOTTOM SHEET — muncul dari bawah saat ketuk dropzone
    ════════════════════════════════════════════════════════════ */
    @keyframes sheetIn {
        from { transform: translateY(100%); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }
    @keyframes sheetOut {
        from { transform: translateY(0);    opacity: 1; }
        to   { transform: translateY(100%); opacity: 0; }
    }
    @keyframes backdropIn  { from { opacity:0; } to { opacity:1; } }
    @keyframes backdropOut { from { opacity:1; } to { opacity:0; } }

    /* Backdrop gelap di belakang sheet */
    .upload-sheet-backdrop {
        display:    none;
        position:   fixed;
        inset:      0;
        z-index:    88888;
        background: rgba(0,0,0,.55);
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
    }
    .upload-sheet-backdrop.show {
        display:   block;
        animation: backdropIn .2s ease both;
    }
    .upload-sheet-backdrop.hiding {
        animation: backdropOut .2s ease both;
    }

    /* Sheet card */
    .upload-sheet {
        display:        none;
        position:       fixed;
        bottom:         0;
        left:           0;
        right:          0;
        z-index:        88889;
        background:     rgba(18, 9, 2, .98);
        border-top:     1.5px solid rgba(249,115,22,.22);
        border-radius:  22px 22px 0 0;
        padding:        0 0 calc(env(safe-area-inset-bottom, 0px) + 16px);
        box-shadow:     0 -20px 60px rgba(0,0,0,.6);
        max-width:      540px;
        margin:         0 auto;
    }
    .upload-sheet.show {
        display:   block;
        animation: sheetIn .28s cubic-bezier(.34,1.3,.64,1) both;
    }
    .upload-sheet.hiding {
        animation: sheetOut .2s ease both;
    }

    /* Drag handle bar di atas */
    .upload-sheet-handle {
        width:         40px;
        height:        4px;
        border-radius: 99px;
        background:    rgba(255,255,255,.18);
        margin:        12px auto 18px;
    }

    /* Label judul sheet */
    .upload-sheet-title {
        font-size:      11px;
        font-weight:    700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color:          rgba(255,255,255,.3);
        text-align:     center;
        margin-bottom:  16px;
    }

    /* Wrapper 3 pilihan */
    .upload-sheet-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap:     10px;
        padding: 0 16px;
    }

    /* Tiap tombol pilihan */
    .upload-opt-btn {
        display:         flex;
        flex-direction:  column;
        align-items:     center;
        justify-content: center;
        gap:             8px;
        padding:         16px 8px;
        border-radius:   16px;
        border:          1.5px solid;
        cursor:          pointer;
        font-size:       11px;
        font-weight:     700;
        line-height:     1.3;
        text-align:      center;
        transition:      background .15s, border-color .15s, transform .1s;
        -webkit-tap-highlight-color: transparent;
    }
    .upload-opt-btn:active { transform: scale(.95); }

    /* Ikon dalam lingkaran */
    .upload-opt-icon {
        width:           44px;
        height:          44px;
        border-radius:   14px;
        display:         flex;
        align-items:     center;
        justify-content: center;
        flex-shrink:     0;
    }

    /* Variasi warna tiap tombol */
    /* Semua 3 tombol pakai tema orange — konsisten dengan frontend */
    .upload-opt-btn.opt-camera {
        color:        #f97316;
        background:   rgba(249,115,22,.10);
        border-color: rgba(249,115,22,.35);
    }
    .upload-opt-btn.opt-camera:hover  { background: rgba(249,115,22,.18); border-color: rgba(249,115,22,.6); }
    .upload-opt-btn.opt-camera .upload-opt-icon { background: rgba(249,115,22,.15); }

    /* Foto: orange sedikit lebih terang */
    .upload-opt-btn.opt-foto {
        color:        #fb923c;
        background:   rgba(249,115,22,.07);
        border-color: rgba(249,115,22,.28);
    }
    .upload-opt-btn.opt-foto:hover  { background: rgba(249,115,22,.14); border-color: rgba(249,115,22,.5); }
    .upload-opt-btn.opt-foto .upload-opt-icon { background: rgba(249,115,22,.12); }

    /* File: orange paling soft / muted */
    .upload-opt-btn.opt-file {
        color:        #fdba74;
        background:   rgba(249,115,22,.05);
        border-color: rgba(249,115,22,.20);
    }
    .upload-opt-btn.opt-file:hover  { background: rgba(249,115,22,.12); border-color: rgba(249,115,22,.4); }
    .upload-opt-btn.opt-file .upload-opt-icon { background: rgba(249,115,22,.10); }

    /* Tombol batal */
    .upload-sheet-cancel {
        display:         block;
        width:           calc(100% - 32px);
        margin:          14px 16px 0;
        padding:         13px;
        border-radius:   14px;
        border:          1px solid rgba(255,255,255,.1);
        background:      rgba(255,255,255,.04);
        color:           rgba(255,255,255,.45);
        font-size:       13px;
        font-weight:     700;
        text-align:      center;
        cursor:          pointer;
        transition:      background .15s, color .15s;
    }
    .upload-sheet-cancel:hover { background: rgba(255,255,255,.08); color: rgba(255,255,255,.7); }

    /* ── Add player btn ──────────────────────────────────────── */
    .add-player-btn {
        display:     flex;
        align-items: center;
        gap:         8px;
        color:       rgba(249,115,22,.85);
        font-size:   13px;
        font-weight: 700;
        background:  none;
        border:      none;
        cursor:      pointer;
        padding:     8px 0;
        transition:  color .2s, opacity .2s;
    }
    .add-player-btn:hover { color: rgba(249,115,22,1); }
    .add-player-btn:disabled { opacity: .35; cursor: not-allowed; }

    /* ── Input field error state ─────────────────────────────── */
    .input-field.field-error {
        border-color: rgba(239,68,68,.6) !important;
        box-shadow: 0 0 0 2px rgba(239,68,68,.1);
    }
    .field-error-msg {
        color: #f87171;
        font-size: 11px;
        margin-top: 4px;
        display: none;
        animation: fadeSlideUp .2s ease both;
    }
    .field-error-msg.show { display: block; }

    /* ── Ajax Error Banner ───────────────────────────────────── */
    #ajaxErrorBanner {
        display: none;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 20px;
        background: rgba(239,68,68,.08);
        border: 1.5px solid rgba(239,68,68,.3);
        animation: fadeSlideUp .3s ease both;
    }
    #ajaxErrorBanner.show { display: block; }

    /* ── Submit loading overlay ──────────────────────────────── */
    #submitOverlay {
        display:         none;
        position:        fixed;
        inset:           0;
        z-index:         99998;
        background:      rgba(0,0,0,.65);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        align-items:     center;
        justify-content: center;
        flex-direction:  column;
        gap:             20px;
    }
    #submitOverlay.show { display: flex; animation: modalBackdropIn .2s ease both; }
    .submit-spinner {
        width: 52px; height: 52px;
        border: 3px solid rgba(249,115,22,.2);
        border-top-color: #f97316;
        border-radius: 50%;
        animation: spinLoader .8s linear infinite;
    }
    .submit-progress-bar {
        width: 240px; height: 4px;
        background: rgba(255,255,255,.1);
        border-radius: 99px;
        overflow: hidden;
    }
    .submit-progress-inner {
        height: 100%;
        background: linear-gradient(90deg, #f97316, #fb923c);
        border-radius: 99px;
        animation: progressBar 3s ease forwards;
    }

    /* ════════════════════════════════════════════════════════════
   GENDER ERROR MODAL
════════════════════════════════════════════════════════════ */
#genderErrorModal {
    display:         none;
    position:        fixed;
    inset:           0;
    z-index:         99999;
    align-items:     center;
    justify-content: center;
    padding:         1.5rem;
}
#genderErrorModal.show {
    display: flex;
    animation: modalBackdropIn .2s ease both;
}
.gem-backdrop {
    position:   absolute;
    inset:      0;
    background: rgba(0,0,0,.35);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}
.gem-card {
    position:      relative;
    z-index:       1;
    width:         100%;
    max-width:     440px;
    border-radius: 22px;
    background:    #ffffff;
    border:        1.5px solid rgba(220,38,38,.18);
    padding:       2rem 2rem 1.75rem;
    box-shadow:    0 8px 40px rgba(0,0,0,.08);
    animation:     modalCardIn .25s cubic-bezier(.34,1.56,.64,1) both;
}
.gem-icon {
    width:           60px; height: 60px;
    border-radius:   50%;
    margin:          0 auto 1.25rem;
display:         flex;
    align-items:     center;
    justify-content: center;
    background:      #FEE8E8;
    border:          1.5px solid rgba(220,38,38,.2);
    font-size:       1.75rem;
}
.gem-title {
    text-align:  center;
    font-size:   1.05rem;
    font-weight: 700;
    color:       #B91C1C;
    margin:      0 0 .5rem;
    line-height: 1.3;
}
.gem-subtitle {
    text-align: center;
    font-size:  .78rem;
    color:      #888;
    margin:     0 0 1.5rem;
}
.gem-subtitle strong { color: #444; }
.gem-info-grid {
    display:               grid;
    grid-template-columns: 1fr 1fr;
    gap:                   .6rem;
    margin-bottom:         1.4rem;
}
.gem-info-box {
    border-radius: 12px;
    padding:       .75rem 1rem;
    text-align:    center;
}
.gem-info-box.detected { background: #FFF5F5; border: 1px solid #FFCCCC; }
.gem-info-box.required { background: #EFF8FF; border: 1px solid #BFDBFE; }
.gem-info-label {
    font-size:      9px;
    font-weight:    700;
    text-transform: uppercase;
    letter-spacing: .07em;
    margin-bottom:  .3rem;
}
.gem-info-box.detected .gem-info-label { color: #E57373; }
.gem-info-box.required .gem-info-label { color: #3B82F6; }
.gem-info-value { font-size: .9rem; font-weight: 700; }
.gem-info-box.detected .gem-info-value { color: #B91C1C; }
.gem-info-box.required .gem-info-value { color: #1D4ED8; }
.gem-message {
    background:    #FAFAFA;
    border:        1px solid #EBEBEB;
    border-radius: 12px;
    padding:       .85rem 1rem;
    font-size:     .8rem;
    color:         #555;
    line-height:   1.6;
    margin-bottom: 1.4rem;
    text-align:    center;
}
.gem-message strong { color: #222; }
.gem-btn {
    width:         100%;
    padding:       .75rem 1rem;
    border-radius: 12px;
    border:        none;
    font-size:     .85rem;
    font-weight:   700;
    cursor:        pointer;
    transition:    opacity .15s, transform .1s;
    letter-spacing:.04em;
}
.gem-btn:hover  { opacity: .88; transform: translateY(-1px); }
.gem-btn:active { opacity: 1;   transform: translateY(0); }
.gem-btn-close {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color:      #fff;
    box-shadow: 0 4px 16px rgba(220,38,38,.25);
}
</style>
@endpush

@section('content')
<section class="min-h-screen py-20 px-6">
<div class="max-w-2xl mx-auto">

    {{-- ── HEADER ──────────────────────────────────────────────────── --}}
    <div class="text-center mb-10 form-section">
        <a href="{{ route('registration.index') }}"
           class="inline-flex items-center gap-2 text-white/30 text-xs hover:text-white/60 transition mb-6">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Ganti kategori
        </a>

        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-500/40
                    bg-brand-500/10 text-brand-300 text-xs font-semibold uppercase tracking-widest mb-4">
            Pendaftaran Online · Bayan Open 2026
        </div>

        <h1 class="font-display text-3xl font-bold mb-3">Formulir Pendaftaran</h1>

        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-3"
             style="background:rgba(249,115,22,.1);border:1px solid rgba(249,115,22,.3);">
            @if(($kategori ?? '') === 'beregu')
                <svg width="13" height="13" viewBox="0 0 20 20" fill="rgba(251,146,60,1)">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                </svg>
            @else
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(251,146,60,1)" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                </svg>
            @endif
            <span class="text-brand-400 text-xs font-bold uppercase tracking-widest">{{ $label ?? 'Ganda Dewasa' }}</span>
        </div>

        <p class="text-white/40 text-sm mt-2">Isi semua data dengan benar dan lengkap</p>
    </div>

    {{-- ── AJAX ERROR BANNER ───────────────────────────────────────── --}}
    <div id="ajaxErrorBanner">
        <div class="flex items-start gap-3">
            <svg class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-red-400 text-sm font-bold mb-2">Terdapat kesalahan — perbaiki dan coba lagi:</p>
                <ul id="ajaxErrorList" class="text-red-300/80 text-sm space-y-1 list-disc list-inside"></ul>
            </div>
        </div>
    </div>

    {{-- ── FORM ─────────────────────────────────────────────────────── --}}
    <form id="regForm" novalidate>
    @csrf
    <input type="hidden" name="kategori" value="{{ $kategori ?? '' }}">

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 1 — DATA TIM & KONTAK
    ═══════════════════════════════════════════════════════════ --}}
    <div class="card-glass rounded-2xl p-8 mb-6 form-section">
        <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs font-black">1</span>
            Data Tim &amp; Kontak
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div class="md:col-span-2">
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Nama Ketua Tim / PIC <span class="text-brand-400">*</span>
                </label>
                <input type="text" name="nama" id="field_nama"
                    placeholder="Nama lengkap ketua tim / penanggung jawab"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm" required>
                <p class="field-error-msg" id="err_nama"></p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Nama Tim / PB <span class="text-brand-400">*</span>
                </label>
                <input type="text" name="tim_pb" id="field_tim_pb"
                    placeholder="Contoh: PB Garuda Sakti"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm" required>
                <p class="field-error-msg" id="err_tim_pb"></p>
            </div>

            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Email <span class="text-brand-400">*</span>
                </label>
                <input type="email" name="email" id="field_email"
                    placeholder="email@contoh.com"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm" required>
                <p class="text-white/25 text-xs mt-1">Receipt dikirim ke email ini</p>
                <p class="field-error-msg" id="err_email"></p>
            </div>

            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Nomor WhatsApp / HP <span class="text-brand-400">*</span>
                </label>
                <input type="text" name="no_hp" id="field_no_hp"
                    placeholder="Contoh: 08123456789"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm" required>
                <p class="field-error-msg" id="err_no_hp"></p>
            </div>

            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Provinsi <span class="text-brand-400">*</span>
                </label>
                <div class="relative">
                    <select id="selectProvinsi" name="provinsi"
                        onchange="WILAYAH.onProvinsiChange(this)"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm appearance-none" required>
                        <option value="">-- Pilih Provinsi --</option>
                    </select>
                    <div id="loadingProvinsi" class="hidden absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg class="animate-spin w-4 h-4 text-white/30" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </div>
                </div>
                <p class="field-error-msg" id="err_provinsi"></p>
            </div>

            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">
                    Kota / Kabupaten <span class="text-brand-400">*</span>
                </label>
                <div class="relative">
                    <select id="selectKota" name="kota" disabled
                        class="input-field w-full px-4 py-3 rounded-xl text-sm appearance-none opacity-40" required>
                        <option value="">-- Pilih Provinsi dulu --</option>
                    </select>
                    <div id="loadingKota" class="hidden absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg class="animate-spin w-4 h-4 text-white/30" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </div>
                </div>
                <p class="field-error-msg" id="err_kota"></p>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 2 — DATA PELATIH
    ═══════════════════════════════════════════════════════════ --}}
    <div class="card-glass rounded-2xl p-8 mb-6 form-section">
        <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs font-black">2</span>
            Data Pelatih
            <span class="text-white/28 text-xs font-normal normal-case ml-1">(opsional)</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">Nama Pelatih</label>
                <input type="text" name="nama_pelatih" id="field_nama_pelatih"
                    placeholder="Nama lengkap pelatih"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">No. HP Pelatih</label>
                <input type="text" name="no_hp_pelatih" id="field_no_hp_pelatih"
                    placeholder="Contoh: 08123456789"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 3 — UPLOAD KTP & DATA PEMAIN
    ═══════════════════════════════════════════════════════════ --}}
    <div class="rounded-2xl p-8 mb-6 form-section"
         style="background:rgba(249,115,22,.035);border:1.5px solid rgba(249,115,22,.16);">

        <h2 class="font-display text-sm font-bold mb-1 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black"
                  style="background:rgba(249,115,22,.9);color:#000;">3</span>
            Upload KTP &amp; Data Pemain
        </h2>
        <p class="text-white/32 text-xs mb-1 ml-9">
            Upload foto KTP lalu klik <strong class="text-brand-400/80">SCAN KTP</strong> da ta terisi otomatis.
        </p>
        <p class="text-white/22 text-xs mb-7 ml-9">
            Field NIK, Nama, dan Tgl Lahir dapat diedit manual jika hasil scan kurang akurat.
        </p>

        <div id="ocrSlotsContainer" class="space-y-5"></div>

        @if(($kategori ?? '') === 'beregu')
        <button type="button" id="tambahPemainBtn"
            class="add-player-btn mt-6" onclick="window._dewasa.tambah()">
            <span class="w-7 h-7 rounded-full border-2 border-brand-400/45
                         flex items-center justify-center text-brand-400 text-xl leading-none font-light">+</span>
            Tambah Pemain
        </button>
        @endif

        <p class="field-error-msg" id="err_pemain"></p>
        <p class="field-error-msg" id="err_ktp_files"></p>
        <p class="field-error-msg" id="err_jenis_kelamin"></p>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 4 — RINGKASAN BIAYA
    ═══════════════════════════════════════════════════════════ --}}
    <div class="card-glass rounded-2xl p-6 mb-6 form-section">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-white/40 text-xs mb-1">Kategori</p>
                <p class="font-display text-white font-bold text-sm">{{ $label ?? '-' }}</p>
            </div>
            <div class="text-right">
                <p class="text-white/40 text-xs mb-1">Total Pembayaran</p>
                <p class="font-display text-brand-400 font-bold text-2xl">
                    Rp {{ number_format($harga ?? 400000, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <button type="submit" id="submitBtn"
        class="btn-primary w-full py-4 rounded-xl font-display text-sm font-bold text-white tracking-wide form-section
               flex items-center justify-center gap-3">
        <span id="submitBtnText">DAFTAR &amp; BAYAR SEKARANG →</span>
        <svg id="submitBtnSpinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
    </button>
    <p class="text-white/25 text-xs text-center mt-4 form-section">
        Dengan mendaftar, Anda menyetujui syarat &amp; ketentuan Bayan Open 2026
    </p>

    </form>

    {{-- ── FOOTER BADGE ─────────────────────────────────────────── --}}
    <div class="flex justify-center gap-6 mt-6 text-white/25 text-xs form-section">
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
            </svg>SSL Secured
        </span>
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
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

{{-- ── SUBMIT LOADING OVERLAY ──────────────────────────────────── --}}
<div id="submitOverlay">
    <div class="submit-spinner"></div>
    <p id="submitOverlayText" class="text-white/60 text-sm font-semibold">Mengirim data pendaftaran...</p>
    <div class="submit-progress-bar">
        <div class="submit-progress-inner" id="submitProgressInner"></div>
    </div>
</div>

{{-- ── GENDER ERROR MODAL ───────────────────────────────────────── --}}
<div id="genderErrorModal" role="dialog" aria-modal="true" aria-labelledby="gem-title">
    <div class="gem-backdrop" onclick="_GEM.close()"></div>
    <div class="gem-card">
        <div class="gem-icon">🚫</div>
        <h2 class="gem-title" id="gem-title">KTP Tidak Sesuai Kategori</h2>
        <p class="gem-subtitle" id="gem-subtitle">
            Pemain <span id="gem-player-no">1</span> &mdash; <span id="gem-player-name"></span>
        </p>
        <div class="gem-info-grid">
        </div>
        <div class="gem-message" id="gem-message">
            KTP yang diupload <strong id="gem-wrong-gender">Perempuan</strong>,
            sedangkan kategori <strong id="gem-kategori-label">Ganda Dewasa Putra</strong>
            hanya untuk pemain <strong id="gem-right-gender">Laki-laki</strong>.<br><br>
            Silakan upload KTP pemain <strong id="gem-right-gender-2">Laki-laki</strong> yang sesuai.
        </div>
        <button class="gem-btn gem-btn-close" onclick="_GEM.close()">
            ✕ &nbsp; Tutup &amp; Upload Ulang
        </button>
    </div>
</div>

@push('scripts')
<script>
/* ================================================================
   WILAYAH CASCADE
================================================================ */
(function () {
'use strict';

var OLD_PROVINSI = @json(old('provinsi', ''));
var OLD_KOTA     = @json(old('kota', ''));
var _provinsiCode = '';

async function loadProvinsi() {
    var sel  = document.getElementById('selectProvinsi');
    var spin = document.getElementById('loadingProvinsi');
    if (!sel) return;
    if (spin) spin.classList.remove('hidden');

    try {
        var res = await fetch('/wilayah/provinces');
        if (!res.ok) throw new Error('HTTP ' + res.status);
        var data = await res.json();
        if (!Array.isArray(data) || data.length === 0) throw new Error('Response kosong');

        data.forEach(function (p) {
            var label = p.name || p.nama || String(p.id);
            var opt   = new Option(label, label);
            opt.dataset.code = p.id;
            sel.appendChild(opt);
        });

        if (OLD_PROVINSI) {
            var found = Array.from(sel.options).find(function (o) {
                return o.value.toUpperCase() === OLD_PROVINSI.toUpperCase();
            });
            if (found) {
                found.selected = true;
                _provinsiCode  = found.dataset.code;
                await loadKota(_provinsiCode, OLD_KOTA);
            }
        }
    } catch (e) {
        console.error('[WILAYAH] Gagal load provinsi:', e);
        sel.innerHTML = '<option value="">Gagal memuat — coba refresh</option>';
    } finally {
        if (spin) spin.classList.add('hidden');
    }
}

async function onProvinsiChange(sel) {
    var opt       = sel.options[sel.selectedIndex];
    _provinsiCode = opt ? (opt.dataset.code || '') : '';
    var kotaSel   = document.getElementById('selectKota');
    if (kotaSel) {
        kotaSel.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        kotaSel.disabled  = true;
        kotaSel.classList.add('opacity-40');
    }
    if (_provinsiCode) await loadKota(_provinsiCode, '');
}

async function loadKota(provId, selectedName) {
    var sel  = document.getElementById('selectKota');
    var spin = document.getElementById('loadingKota');
    if (!sel) return;
    sel.disabled  = true;
    sel.classList.add('opacity-40');
    sel.innerHTML = '<option value="">-- Memuat data... --</option>';
    if (spin) spin.classList.remove('hidden');

    try {
        var res = await fetch('/wilayah/regencies/' + encodeURIComponent(provId));
        if (!res.ok) throw new Error('HTTP ' + res.status);
        var data = await res.json();
        if (!Array.isArray(data) || data.length === 0) throw new Error('Data kota kosong');

        sel.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        data.forEach(function (k) {
            var label = k.name || k.nama || String(k.id);
            var opt   = new Option(label, label);
            opt.dataset.code = k.id;
            if (selectedName && label.toUpperCase() === selectedName.toUpperCase()) opt.selected = true;
            sel.appendChild(opt);
        });
        sel.disabled = false;
        sel.classList.remove('opacity-40');
    } catch (e) {
        console.error('[WILAYAH] Gagal load kota:', e);
        sel.innerHTML = '<option value="">Gagal memuat data kota</option>';
        sel.disabled  = false;
        sel.classList.remove('opacity-40');
    } finally {
        if (spin) spin.classList.add('hidden');
    }
}

window.WILAYAH = { onProvinsiChange: onProvinsiChange };
document.addEventListener('DOMContentLoaded', loadProvinsi);
})();
</script>

<script>
/* ================================================================
   GENDER ERROR MODAL — _GEM namespace
================================================================ */
window._GEM = (function () {
    var RULES = {
        'ganda-dewasa-putra': { required: 'L', label: 'Laki-laki',  labelWrong: 'Perempuan'  },
        'ganda-dewasa-putri': { required: 'P', label: 'Perempuan',  labelWrong: 'Laki-laki'  },
    };

    var KATEGORI    = @json($kategori ?? '');
    var LABEL_KAT   = @json($label ?? '');
    var currentRule = RULES[KATEGORI] || null;

    function show(playerIdx, namaOcr, genderDetected) {
        if (!currentRule) return;
        var labelDetected = (genderDetected === 'L') ? 'Laki-laki' : 'Perempuan';
        _setTxt('gem-player-no',      playerIdx + 1);
        _setTxt('gem-player-name',    namaOcr || ('Pemain ' + (playerIdx + 1)));
        _setTxt('gem-detected',       labelDetected);
        _setTxt('gem-required',       currentRule.label);
        _setTxt('gem-wrong-gender',   labelDetected);
        _setTxt('gem-right-gender',   currentRule.label);
        _setTxt('gem-right-gender-2', currentRule.label);
        _setTxt('gem-kategori-label', LABEL_KAT);
        var modal = document.getElementById('genderErrorModal');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function showUnknown(playerIdx, namaOcr) {
        if (!currentRule) return;
        _setTxt('gem-player-no',      playerIdx + 1);
        _setTxt('gem-player-name',    namaOcr || ('Pemain ' + (playerIdx + 1)));
        _setTxt('gem-detected',       'Tidak terbaca');
        _setTxt('gem-required',       currentRule.label);
        var msgEl = document.getElementById('gem-message');
        if (msgEl) msgEl.innerHTML =
            'Jenis kelamin pada KTP Pemain <strong>' + (playerIdx + 1) + '</strong> '
            + '(<strong>' + _esc(namaOcr || '') + '</strong>) tidak berhasil terbaca.<br><br>'
            + 'Pastikan foto KTP jelas dan bagian <strong>Jenis Kelamin</strong> terlihat, '
            + 'lalu scan ulang.<br><br>'
            + 'Kategori <strong>' + _esc(LABEL_KAT) + '</strong> hanya untuk pemain '
            + '<strong>' + _esc(currentRule.label) + '</strong>.';
        var modal = document.getElementById('genderErrorModal');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function close() {
        var modal = document.getElementById('genderErrorModal');
        if (modal) modal.classList.remove('show');
        document.body.style.overflow = '';
        var msgEl = document.getElementById('gem-message');
        if (msgEl) msgEl.innerHTML =
            'KTP yang diupload <strong id="gem-wrong-gender"></strong>, '
            + 'sedangkan kategori <strong id="gem-kategori-label"></strong> '
            + 'hanya untuk pemain <strong id="gem-right-gender"></strong>.<br><br>'
            + 'Silakan upload KTP pemain <strong id="gem-right-gender-2"></strong> yang sesuai.';
    }

    function getRule()     { return currentRule; }
    function getKategori() { return KATEGORI;    }

    function _setTxt(id, val) { var el = document.getElementById(id); if (el) el.textContent = val; }
    function _esc(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
    return { show, showUnknown, close, getRule, getKategori };
})();
</script>

<script>
/* ================================================================
   OCR FORM LOGIC — _dewasa namespace
================================================================ */
(function () {
'use strict';

var KATEGORI     = @json($kategori ?? '');
var IS_BEREGU    = (KATEGORI === 'beregu');
var MIN_PEMAIN   = IS_BEREGU ? {{ (int)($minPemain ?? 3) }} : 2;
var MAX_PEMAIN   = IS_BEREGU ? {{ (int)($maxPemain ?? 10) }} : 2;
var jumlahPemain = MIN_PEMAIN;
var slotState    = {};
var cardData     = {};
var GENDER_RULE  = _GEM.getRule();

var CARD_FIELDS = [
    { l: 'NIK',       k: 'nik',           n: 'nik[]',        placeholder: '16 digit NIK sesuai KTP' },
    { l: 'Nama',      k: 'nama',          n: 'pemain[]',     placeholder: 'Nama lengkap sesuai KTP' },
    { l: 'Tgl Lahir', k: 'tanggal_lahir', n: 'tgl_lahir[]',  placeholder: 'DD-MM-YYYY'              },
];

var TOURNAMENT_DATE = new Date(2026, 7, 24);

/* ── Hitung usia ──────────────────────────────────────────── */
function hitungUsia(str) {
    if (!str || !str.trim()) return null;
    str = str.trim();
    var tgl = null;
    var m1  = str.match(/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{4})$/);
    var m2  = str.match(/^(\d{4})[-\/\.](\d{1,2})[-\/\.](\d{1,2})$/);
    if (m1)      tgl = new Date(+m1[3], +m1[2] - 1, +m1[1]);
    else if (m2) tgl = new Date(+m2[1], +m2[2] - 1, +m2[3]);
    else         tgl = new Date(str);
    if (!tgl || isNaN(tgl.getTime())) return null;
    var usia = TOURNAMENT_DATE.getFullYear() - tgl.getFullYear();
    var bm   = TOURNAMENT_DATE.getMonth() - tgl.getMonth();
    if (bm < 0 || (bm === 0 && TOURNAMENT_DATE.getDate() < tgl.getDate())) usia--;
    if (usia < 0 || usia > 120) return null;
    return usia;
}

function updateUsiaRow(idx, tglValue) {
    var usia   = hitungUsia(tglValue);
    var dispEl = document.getElementById('usia_disp_' + idx);
    if (!dispEl) return;
    if (usia !== null) {
        dispEl.className   = 'usia-display has-value';
        dispEl.textContent = usia + ' tahun (per 24 Ags 2026)';
    } else {
        dispEl.className   = 'usia-display no-value';
        dispEl.textContent = '— isi tgl lahir dulu';
    }
}

/* ── Normalize gender ─────────────────────────────────────── */
function normalizeGender(raw) {
    if (!raw) return '';
    raw = raw.toUpperCase().trim();
    if (['P','PR','WANITA','PEREMPUAN'].indexOf(raw) !== -1) return 'P';
    if (raw.indexOf('PEREMPUAN') !== -1 || raw.indexOf('WANITA') !== -1) return 'P';
    if (['L','LK','PRIA','LAKI','LAKI-LAKI'].indexOf(raw) !== -1) return 'L';
    if (raw.indexOf('LAKI') !== -1 || raw.indexOf('PRIA') !== -1) return 'L';
    return '';
}

/* ── Generate slot HTML ───────────────────────────────────── */
function makeSlot(idx, deletable) {
    var btnHapus = deletable
        ? ('<button type="button"'
           + ' onclick="window._dewasa.hapus(this,' + idx + ')"'
           + ' class="w-8 h-8 rounded-lg bg-red-500/20 hover:bg-red-500/30'
           + ' flex items-center justify-center transition"'
           + ' title="Hapus pemain ini">'
           + '<svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
           + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
           + '</svg></button>')
        : '<div class="w-8 h-8"></div>';

    return (
        '<div id="ocr_card_' + idx + '" class="pemain-ocr-card" data-idx="' + idx + '">'

        /* ── Header ── */
        + '<div class="flex items-center justify-between mb-5">'
        +   '<div class="flex items-center gap-3">'
        +     '<div class="w-8 h-8 rounded-full flex items-center justify-center"'
        +          ' style="background:rgba(249,115,22,.14);border:1px solid rgba(249,115,22,.3);">'
        +       '<span class="text-brand-400 text-xs font-black pemain-number">' + (idx + 1) + '</span>'
        +     '</div>'
        +     '<span class="text-white/80 text-sm font-bold">Pemain ' + (idx + 1) + '</span>'
        +     '<span id="scan_badge_' + idx + '" class="scan-badge">'
        +       '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
        +         '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>'
        +       '</svg>'
        +       ' Ter-scan'
        +     '</span>'
        +   '</div>'
        +   btnHapus
        + '</div>'

        /* ── Upload area ── */
        + '<div class="mb-4">'
        +   '<label class="block text-white/45 text-xs font-semibold uppercase tracking-wide mb-2">'
        +     'Foto KTP <span class="text-brand-400">*</span>'
        +     ' <span class="text-white/22 font-normal normal-case">&mdash; JPG · PNG · HEIC · WebP &middot; Maks 10MB</span>'
        +   '</label>'

        /* Dropzone */
        +   '<div id="ktpDropzone_' + idx + '"'
        +       ' onclick="window._dewasa.showUploadChoice(' + idx + ')"'
        +       ' class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all"'
        +       ' style="border-color:rgba(249,115,22,.22);background:rgba(249,115,22,.018);"'
        +       ' ondragover="event.preventDefault();this.style.borderColor=\'rgba(249,115,22,.6)\'"'
        +       ' ondragleave="this.style.borderColor=\'rgba(249,115,22,.22)\'"'
        +       ' ondrop="window._dewasa.drop(event,' + idx + ')">'

        /* Preview */
        +     '<div id="ktpPreview_' + idx + '" class="hidden">'
        +       '<div class="relative inline-block mb-2">'
        +         '<img id="ktpPreviewImg_' + idx + '" src="" alt=""'
        +              ' class="max-h-32 mx-auto rounded-lg object-contain"'
        +              ' style="box-shadow:0 4px 20px rgba(0,0,0,.55);">'
        +         '<button type="button" onclick="window._dewasa.reset(event,' + idx + ')"'
        +                 ' class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 hover:bg-red-600'
        +                 ' flex items-center justify-center transition">'
        +           '<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
        +             '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>'
        +           '</svg>'
        +         '</button>'
        +       '</div>'
        +       '<p class="text-white/28 text-xs">Ketuk untuk ganti foto</p>'
        +     '</div>'

        /* Default placeholder */
        +     '<div id="ktpDefault_' + idx + '" class="flex flex-col items-center py-3">'
        +       '<div class="w-11 h-11 rounded-xl bg-brand-500/10 flex items-center justify-center mb-3">'
        +         '<svg width="22" height="22" viewBox="0 0 24 24" fill="none"'
        +              ' stroke="rgba(249,115,22,.55)" stroke-width="1.5">'
        +           '<rect x="3" y="5" width="18" height="14" rx="2"/>'
        +           '<path d="M7 9h10M7 13h6"/>'
        +         '</svg>'
        +       '</div>'
        +       '<p class="text-white/50 text-sm font-medium">Ketuk untuk upload KTP</p>'
        +       '<p class="text-white/22 text-xs mt-0.5">Kamera · Galeri · File Manager &middot; Maks 10MB</p>'
        +     '</div>'
        +   '</div>'

        /* ── 3 File inputs (hidden) ── */
        /* 1. Kamera belakang HP */
        +   '<input type="file" id="ktpCamera_' + idx + '"'
        +          ' accept="image/*" capture="environment" class="hidden"'
        +          ' onchange="window._dewasa.fileSelect(this,' + idx + ')">'

        /* 2. Galeri / foto (gambar saja) */
        +   '<input type="file" id="ktpFoto_' + idx + '" name="ktp_files[]"'
        +          ' accept="image/*" class="hidden"'
        +          ' onchange="window._dewasa.fileSelect(this,' + idx + ')">'

        /* 3. File manager (gambar + PDF) */
        +   '<input type="file" id="ktpFile_' + idx + '"'
        +          ' accept="image/*,.heic,.heif" class="hidden"'
        +          ' onchange="window._dewasa.fileSelect(this,' + idx + ')">'

        /* ── Scan button ── */
        +   '<button type="button" id="scanBtn_' + idx + '"'
        +           ' onclick="window._dewasa.scan(' + idx + ')"'
        +           ' class="hidden mt-3 w-full py-2.5 rounded-xl font-display text-xs font-bold'
        +           ' text-white tracking-wider flex items-center justify-center gap-2"'
        +           ' style="background:linear-gradient(135deg,#f97316,#c2410c);'
        +           'box-shadow:0 4px 16px rgba(249,115,22,.22);">'
        +     '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
        +       '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"'
        +            ' d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4'
        +            'M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>'
        +     '</svg>'
        +     ' SCAN KTP — Isi Otomatis'
        +   '</button>'

        /* Loading */
        +   '<div id="scanLoading_' + idx + '" class="hidden mt-3 text-center py-2">'
        +     '<p class="text-brand-400 text-xs font-semibold mb-1">Membaca KTP dengan AI...</p>'
        +     '<div class="scan-loading-bar"><div class="scan-loading-bar-inner"></div></div>'
        +   '</div>'
        + '</div>'

        /* ── KTP Data Card ── */
        + '<div id="ktpDataCard_' + idx + '" class="ktp-data-card">'
        +   '<div class="flex items-center justify-between mb-1">'
        +     '<p class="text-xs font-bold text-white/35 uppercase tracking-widest flex items-center gap-2">'
        +       '<svg width="11" height="11" viewBox="0 0 24 24" fill="none"'
        +            ' stroke="currentColor" stroke-width="2">'
        +         '<rect x="3" y="5" width="18" height="14" rx="2"/>'
        +         '<path d="M7 9h10M7 13h6"/>'
        +       '</svg>'
        +       ' Data KTP'
        +     '</p>'
        +   '</div>'
        +   '<p class="ktp-edit-hint mb-3">&#9998; Klik NIK / Nama / Tgl Lahir untuk edit</p>'
        +   '<div id="ktpDataRows_' + idx + '"></div>'
        + '</div>'

        + '</div>'
    );
}

/* ── Init ─────────────────────────────────────────────────── */
function initSlots() {
    var container = document.getElementById('ocrSlotsContainer');
    if (!container) return;
    container.innerHTML = '';
    for (var i = 0; i < MIN_PEMAIN; i++) {
        container.insertAdjacentHTML('beforeend', makeSlot(i, false));
        slotState[i] = { file: null, scanned: false, genderValid: !GENDER_RULE };
        cardData[i]  = {};
    }
}

/* ── Tambah / hapus (beregu) ──────────────────────────────── */
function tambah() {
    if (jumlahPemain >= MAX_PEMAIN) { showToast('Maksimal ' + MAX_PEMAIN + ' pemain per tim.', 'warn'); return; }
    var idx = jumlahPemain++;
    document.getElementById('ocrSlotsContainer')
        .insertAdjacentHTML('beforeend', makeSlot(idx, true));
    slotState[idx] = { file: null, scanned: false, genderValid: !GENDER_RULE };
    cardData[idx]  = {};
    updateAddBtn();
}

function hapus(btn, idx) {
    var card = btn.closest('.pemain-ocr-card');
    if (card) card.remove();
    delete slotState[idx];
    delete cardData[idx];
    renumberSlots();
    updateAddBtn();
}

function renumberSlots() {
    var cards = document.querySelectorAll('.pemain-ocr-card');
    cards.forEach(function (card, i) {
        var numEl = card.querySelector('.pemain-number');
        var hdrEl = card.querySelector('.text-white\\/80.text-sm.font-bold');
        if (numEl) numEl.textContent = i + 1;
        if (hdrEl) hdrEl.textContent = 'Pemain ' + (i + 1);
    });
    jumlahPemain = cards.length;
}

function updateAddBtn() {
    var btn = document.getElementById('tambahPemainBtn');
    if (!btn) return;
    var maxed         = jumlahPemain >= MAX_PEMAIN;
    btn.disabled      = maxed;
    btn.style.opacity = maxed ? '0.3' : '1';
    btn.style.cursor  = maxed ? 'not-allowed' : 'pointer';
}

/* ── Upload bottom sheet ──────────────────────────────────── */
function showUploadChoice(idx) {
    /* Jika sudah ada preview → ketuk dropzone = ganti foto, buka sheet lagi */
    _SHEET.open(idx);
}

function closeSheet() {
    _SHEET.close();
}

/* ── File handling ────────────────────────────────────────── */
/* ── Konversi gambar → JPEG via Canvas (fix HEIC iPhone) ─── */
function convertToJpeg(file, callback) {
    var MAX_SIZE_KB  = 300;          // target ≤ 300KB untuk OCR
    var MAX_PIXEL    = 1600;         // max dimensi terpanjang
    var QUALITY_STEP = 0.10;         // step turun kualitas
    var MIN_QUALITY  = 0.35;         // batas bawah kualitas

    var reader = new FileReader();
    reader.onload = function (e) {
        var dataUrl = e.target.result;
        var img     = new Image();

        img.onload = function () {
            /* ── Scale down ── */
            var w = img.naturalWidth;
            var h = img.naturalHeight;
            if (w > MAX_PIXEL || h > MAX_PIXEL) {
                if (w > h) { h = Math.round(h * MAX_PIXEL / w); w = MAX_PIXEL; }
                else        { w = Math.round(w * MAX_PIXEL / h); h = MAX_PIXEL; }
            }

            var canvas = document.createElement('canvas');
            canvas.width  = w;
            canvas.height = h;
            var ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, w, h);

            /* ── Compress iteratif sampai ukuran OK ── */
            function tryCompress(quality) {
                canvas.toBlob(function (blob) {
                    if (!blob) { callback(file, dataUrl); return; }

                    var sizeKB = blob.size / 1024;

                    /* Kalau masih terlalu besar dan bisa diturunkan lagi */
                    if (sizeKB > MAX_SIZE_KB && quality - QUALITY_STEP >= MIN_QUALITY) {
                        tryCompress(parseFloat((quality - QUALITY_STEP).toFixed(2)));
                        return;
                    }

                    /* Kalau masih terlalu besar → scale down lagi 50% */
                    if (sizeKB > MAX_SIZE_KB) {
                        w = Math.round(w * 0.7);
                        h = Math.round(h * 0.7);
                        canvas.width  = w;
                        canvas.height = h;
                        ctx.drawImage(img, 0, 0, w, h);
                        tryCompress(0.55);
                        return;
                    }

                    var converted  = new File(
                        [blob],
                        file.name.replace(/\.[^.]+$/, '') + '.jpg',
                        { type: 'image/jpeg', lastModified: Date.now() }
                    );
                    var previewUrl = URL.createObjectURL(blob);
                    console.log('[KTP] Compressed: ' + Math.round(sizeKB) + 'KB, quality=' + quality + ', ' + w + 'x' + h + 'px');
                    callback(converted, previewUrl);

                }, 'image/jpeg', quality);
            }

            tryCompress(0.82);
        };

        img.onerror = function () {
            showToast('Format foto tidak didukung browser ini. Coba konversi ke JPG dulu.', 'warn');
            callback(null, null);
        };
        img.src = dataUrl;
    };
    reader.onerror = function () { showToast('Gagal membaca file foto.', 'error'); callback(null, null); };
    reader.readAsDataURL(file);
}

function fileSelect(input, idx) {
    if (input.files && input.files[0]) processFile(input.files[0], idx);
}

function drop(e, idx) {
    e.preventDefault();
    var dz = document.getElementById('ktpDropzone_' + idx);
    if (dz) dz.style.borderColor = 'rgba(249,115,22,.22)';
    var file = e.dataTransfer && e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) processFile(file, idx);
}

function processFile(file, idx) {
    if (file.size > 10 * 1024 * 1024) { showToast('File terlalu besar. Maks 10MB.', 'error'); return; }
    if (!slotState[idx]) slotState[idx] = {};

    /* Reset state dulu, tampilkan loading kecil di dropzone */
    slotState[idx].file        = null;
    slotState[idx].scanned     = false;
    slotState[idx].genderValid = !GENDER_RULE;

    /* Konversi ke JPEG (handle HEIC iPhone & kompres foto besar) */
    convertToJpeg(file, function (convertedFile, previewUrl) {
        if (!convertedFile) return; /* konversi gagal, sudah ada toast */

        slotState[idx].file = convertedFile;

        var imgEl = document.getElementById('ktpPreviewImg_' + idx);
        if (imgEl) imgEl.src = previewUrl;

        toggleEl('ktpPreview_' + idx, true);
        toggleEl('ktpDefault_' + idx, false);
        toggleEl('scanBtn_'    + idx, true);
        resetCardUI(idx);
    });
}

function resetSlot(e, idx) {
    e.stopPropagation();
    slotState[idx] = { file: null, scanned: false, genderValid: !GENDER_RULE };
    cardData[idx]  = {};

    /* Reset KETIGA input file */
    ['ktpCamera_','ktpFoto_','ktpFile_'].forEach(function(pfx){
        var el = document.getElementById(pfx + idx);
        if (el) el.value = '';
    });

    toggleEl('ktpPreview_' + idx, false);
    toggleEl('ktpDefault_' + idx, true);
    toggleEl('scanBtn_'    + idx, false);
    toggleEl('scanLoading_'+ idx, false);
    resetCardUI(idx);
}

function resetCardUI(idx) {
    var card    = document.getElementById('ktpDataCard_' + idx);
    var rows    = document.getElementById('ktpDataRows_' + idx);
    var badge   = document.getElementById('scan_badge_'  + idx);
    var ocrCard = document.getElementById('ocr_card_'    + idx);
    if (card)    card.className = 'ktp-data-card';
    if (rows)    rows.innerHTML = '';
    if (badge)   badge.style.display = 'none';
    if (ocrCard) ocrCard.classList.remove('scanned', 'gender-error', 'has-field-error');
}

/* ── SCAN OCR ─────────────────────────────────────────────── */
function scan(idx) {
    var fileToScan = slotState[idx] && slotState[idx].file;
    if (!fileToScan) {
        showToast('Upload foto KTP dulu sebelum scan.', 'warn');
        return;
    }

    toggleEl('scanBtn_'     + idx, false);
    toggleEl('scanLoading_' + idx, true);
    resetCardUI(idx);

    var fd   = new FormData();
    /* File sudah dikonversi ke JPEG oleh processFile — langsung pakai */
    fd.append('image', fileToScan, fileToScan.name || 'ktp.jpg');
    var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

    fetch('/ocr/ktp', {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body:    fd,
    })
    .then(function (resp) {
        toggleEl('scanLoading_' + idx, false);
        toggleEl('scanBtn_'     + idx, true);
        if (!resp.ok) {
            return resp.json().catch(function () { return {}; }).then(function (err) {
                showToast(err.message || ('HTTP ' + resp.status + ' — coba lagi.'), 'error');
            });
        }
        return resp.json().then(function (result) {
            if (!result.success) {
                showToast(result.message || 'Gagal membaca KTP. Coba foto ulang lebih jelas.', 'error');
                return;
            }
            var data       = result.data;
            var genderNorm = normalizeGender(data.jenis_kelamin || '');

            if (GENDER_RULE) {
                if (genderNorm === '') {
                    _onGenderFail(idx, data);
                    _GEM.showUnknown(idx, data.nama || '');
                    return;
                }
                if (genderNorm !== GENDER_RULE.required) {
                    _onGenderFail(idx, data);
                    _GEM.show(idx, data.nama || '', genderNorm);
                    return;
                }
                slotState[idx].genderValid = true;
            }

            renderCard(idx, data, genderNorm);
            slotState[idx].scanned = true;

            var badge   = document.getElementById('scan_badge_' + idx);
            var ocrCard = document.getElementById('ocr_card_'   + idx);
            if (badge)   badge.style.display = 'inline-flex';
            if (ocrCard) { ocrCard.classList.remove('gender-error'); ocrCard.classList.add('scanned'); }

            showToast('KTP Pemain ' + (idx + 1) + ' berhasil dibaca. Periksa data lalu edit jika perlu.', 'success');
        });
    })
    .catch(function (err) {
        toggleEl('scanLoading_' + idx, false);
        toggleEl('scanBtn_'     + idx, true);
        showToast('Tidak bisa konek ke OCR service.', 'error');
        console.error('OCR error:', err);
    });
}

function _onGenderFail(idx, data) {
    slotState[idx].scanned     = false;
    slotState[idx].genderValid = false;
    /* Reset ketiga input file */
    ['ktpCamera_','ktpFoto_','ktpFile_'].forEach(function(pfx){
        var el = document.getElementById(pfx + idx);
        if (el) el.value = '';
    });
    slotState[idx].file = null;
    toggleEl('ktpPreview_' + idx, false);
    toggleEl('ktpDefault_' + idx, true);
    toggleEl('scanBtn_'    + idx, false);
    var ocrCard = document.getElementById('ocr_card_' + idx);
    if (ocrCard) {
        ocrCard.classList.remove('scanned');
        ocrCard.classList.add('gender-error');
        setTimeout(function () { if (ocrCard) ocrCard.classList.remove('gender-error'); }, 600);
    }
    var hidGender = document.getElementById('khid_' + idx + '_jenis_kelamin');
    if (hidGender) hidGender.value = '';
    resetCardUI(idx);
}

/* ── Render KTP data card ─────────────────────────────────── */
function renderCard(idx, data, genderNorm) {
    cardData[idx] = {};
    var card = document.getElementById('ktpDataCard_' + idx);
    var rows = document.getElementById('ktpDataRows_' + idx);
    if (!card || !rows) return;
    rows.innerHTML = '';

    CARD_FIELDS.forEach(function (f) {
        var raw = '';
        if (f.k === 'tanggal_lahir') raw = (data.tanggal_lahir || data.tgl_lahir || '');
        else raw = (data[f.k] || '');
        var v = ('' + raw).trim();
        cardData[idx][f.k] = v;

        var valId = 'kval_' + idx + '_' + f.k;
        var inpId = 'kinp_' + idx + '_' + f.k;
        var hidId = 'khid_' + idx + '_' + f.k;

        var hidHtml = '<input type="hidden" id="' + hidId + '" name="' + f.n + '" value="' + esc(v) + '">';

        var valContent = v
            ? esc(v)
            : '<span style="color:rgba(255,255,255,.22);font-style:italic">— ketuk untuk isi</span>';

        var valSpan = '<span id="' + valId + '" class="ktp-value hl" title="Klik untuk edit"'
            + ' onclick="window._dewasa.inlineEdit(\'' + idx + '\',\'' + f.k + '\')">'
            + valContent + '</span>';

        var inpHtml = '<input id="' + inpId + '" type="text" class="ktp-inline-input" style="display:none"'
            + ' value="' + esc(v) + '" placeholder="' + esc(f.placeholder || '') + '"'
            + ' onkeydown="window._dewasa.inlineKey(event,\'' + idx + '\',\'' + f.k + '\')"'
            + ' onblur="window._dewasa.inlineSave(\'' + idx + '\',\'' + f.k + '\')">';

        rows.innerHTML +=
            '<div class="ktp-row">'
            + '<span class="ktp-label">' + f.l + ' <span style="color:#f97316">*</span></span>'
            + valSpan + inpHtml + hidHtml
            + '</div>';
    });

    /* Jenis Kelamin — read-only */
    var genderLabel, genderClass;
    if (genderNorm === 'L') {
        genderLabel = '♂ Laki-laki';
        genderClass = 'ktp-gender-value gender-l';
    } else if (genderNorm === 'P') {
        genderLabel = '♀ Perempuan';
        genderClass = 'ktp-gender-value gender-p';
    } else {
        genderLabel = '— tidak terbaca';
        genderClass = 'ktp-gender-value gender-unknown';
    }

    var hidGenderHtml = '<input type="hidden" id="khid_' + idx + '_jenis_kelamin"'
        + ' name="jenis_kelamin[' + idx + ']" value="' + esc(genderNorm) + '">';

    rows.innerHTML +=
        '<div class="ktp-row" style="margin-top:4px;">'
        + '<span class="ktp-label">Kelamin</span>'
        + '<span class="' + genderClass + '">' + genderLabel + '</span>'
        + '<span style="font-size:9px;color:rgba(255,255,255,.2);margin-left:4px;flex-shrink:0;">dari KTP</span>'
        + hidGenderHtml
        + '</div>';

    cardData[idx]['jenis_kelamin'] = genderNorm;

    /* Usia — read-only */
    var tglVal   = cardData[idx]['tanggal_lahir'] || '';
    var usia     = hitungUsia(tglVal);
    var usiaText = usia !== null ? usia + ' tahun (per 24 Ags 2026)' : '— isi tgl lahir dulu';
    var usiaClass= usia !== null ? 'usia-display has-value' : 'usia-display no-value';

    rows.innerHTML +=
        '<div class="ktp-row" style="margin-top:6px;padding-top:8px;border-top:1px solid rgba(249,115,22,.1);">'
        + '<span class="ktp-label" style="color:rgba(249,115,22,.5);">Usia</span>'
        + '<span id="usia_disp_' + idx + '" class="' + usiaClass + '" title="Dihitung otomatis dari Tanggal Lahir">'
        + usiaText
        + '</span>'
        + '<span style="font-size:9px;color:rgba(249,115,22,.35);margin-left:4px;flex-shrink:0;white-space:nowrap;">otomatis</span>'
        + '</div>';

    card.className = 'ktp-data-card show valid-card';
}

/* ── Inline edit ──────────────────────────────────────────── */
function inlineEdit(idx, fieldKey) {
    var valEl = document.getElementById('kval_' + idx + '_' + fieldKey);
    var inpEl = document.getElementById('kinp_' + idx + '_' + fieldKey);
    if (!valEl || !inpEl) return;
    valEl.style.display = 'none';
    inpEl.style.display = '';
    inpEl.focus();
    if (inpEl.select) inpEl.select();
}

function inlineSave(idx, fieldKey) {
    var valEl = document.getElementById('kval_' + idx + '_' + fieldKey);
    var inpEl = document.getElementById('kinp_' + idx + '_' + fieldKey);
    var hidEl = document.getElementById('khid_' + idx + '_' + fieldKey);
    if (!valEl || !inpEl) return;

    var newVal  = inpEl.value.trim();
    var origVal = (cardData[idx] && cardData[idx][fieldKey]) || '';
    var edited  = (newVal !== origVal);

    if (hidEl)         hidEl.value            = newVal;
    if (cardData[idx]) cardData[idx][fieldKey] = newVal;

    if (newVal) {
        valEl.innerHTML   = esc(newVal);
        valEl.style.color = edited ? '#fbbf24' : '';
        valEl.title       = edited ? 'Diedit manual — klik untuk ubah lagi' : 'Klik untuk edit';
    } else {
        valEl.innerHTML   = '<span style="color:rgba(255,255,255,.22);font-style:italic">— ketuk untuk isi</span>';
        valEl.style.color = '';
    }
    if (edited && inpEl.classList) inpEl.classList.add('was-edited');
    inpEl.style.display = 'none';
    valEl.style.display = '';

    if (fieldKey === 'tanggal_lahir') updateUsiaRow(idx, newVal);
}

function inlineKey(e, idx, fieldKey) {
    if (e.key === 'Enter')  { e.preventDefault(); inlineSave(idx, fieldKey); }
    if (e.key === 'Escape') {
        var valEl = document.getElementById('kval_' + idx + '_' + fieldKey);
        var inpEl = document.getElementById('kinp_' + idx + '_' + fieldKey);
        if (inpEl) inpEl.style.display = 'none';
        if (valEl) valEl.style.display  = '';
    }
}

/* ── Helpers ──────────────────────────────────────────────── */
function toggleEl(id, show) {
    var el = document.getElementById(id);
    if (!el) return;
    if (show) el.classList.remove('hidden');
    else      el.classList.add('hidden');
}

function esc(s) {
    return String(s)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* ── Toast ────────────────────────────────────────────────── */
var _toastTimer = null;
function showToast(msg, type) {
    type = type || 'success';
    var el = document.getElementById('_dewasaToast');
    if (!el) {
        el = document.createElement('div');
        el.id = '_dewasaToast';
        el.style.cssText =
            'position:fixed;top:88px;right:20px;z-index:99999;max-width:380px;'
            + 'padding:12px 16px;border-radius:13px;font-size:12px;line-height:1.5;'
            + 'font-weight:600;box-shadow:0 8px 36px rgba(0,0,0,.45);'
            + 'transition:opacity .3s,transform .3s;pointer-events:none;'
            + 'animation:toastIn .25s ease both;';
        document.body.appendChild(el);
    }
    var styles = {
        success: 'background:rgba(6,30,18,.97);border:1px solid rgba(16,185,129,.38);color:#34d399;',
        warn:    'background:rgba(30,22,4,.97);border:1px solid rgba(234,179,8,.38);color:#fbbf24;',
        error:   'background:rgba(30,6,6,.97);border:1px solid rgba(239,68,68,.38);color:#f87171;',
    };
    el.style.cssText += (styles[type] || styles.error) + 'opacity:1;transform:translateY(0);';
    el.textContent = msg;
    if (_toastTimer) clearTimeout(_toastTimer);
    _toastTimer = setTimeout(function () {
        el.style.opacity   = '0';
        el.style.transform = 'translateY(-8px)';
    }, 5000);
}

/* ── Client-side pre-validation ──────────────────────────── */
function clientValidate() {
    var cards   = document.querySelectorAll('.pemain-ocr-card');
    var missing = [];

    cards.forEach(function (card) {
        var idx = card.dataset.idx;

        if (GENDER_RULE && slotState[idx] && !slotState[idx].genderValid) {
            missing.push('Pemain ' + (parseInt(idx, 10) + 1) + ': KTP belum di-scan atau jenis kelamin tidak sesuai kategori');
            card.classList.add('gender-error');
            setTimeout(function () { card.classList.remove('gender-error'); }, 600);
            return;
        }

        CARD_FIELDS.forEach(function (f) {
            var hidEl = document.getElementById('khid_' + idx + '_' + f.k);
            if (hidEl && !hidEl.value.trim()) {
                missing.push('Pemain ' + (parseInt(idx, 10) + 1) + ': ' + f.l + ' wajib diisi');
                var rowEl = hidEl.closest && hidEl.closest('.ktp-row');
                if (rowEl) rowEl.style.background = 'rgba(239,68,68,.09)';
            }
        });
    });

    return missing;
}

/* ── Expose ───────────────────────────────────────────────── */
window._dewasa = {
    fileSelect, drop, reset: resetSlot,
    scan, hapus, tambah,
    inlineEdit, inlineSave, inlineKey,
    showUploadChoice, closeSheet,
    get slotState() { return slotState; },   // ← expose read-only getter
    get cardData()  { return cardData;  },
};

document.addEventListener('DOMContentLoaded', function () {
    initSlots();
    updateAddBtn();
});

})(); /* end _dewasa IIFE */
</script>

<script>
/* ================================================================
   AJAX FORM SUBMISSION
   - Tidak reload saat error
   - Data tetap ada, tinggal edit bagian yang salah
   - Tampilkan error per field
================================================================ */
(function () {
'use strict';

/* ── Helpers ──────────────────────────────────────────────── */
function setFieldError(fieldName, msg) {
    /* Coba cari error element berdasarkan nama field */
    var patterns = [
        'err_' + fieldName.replace(/[\[\]\.]/g, '_').replace(/__+/g,'_').replace(/_$/,''),
        'err_' + fieldName,
    ];
    var errEl = null;
    for (var i = 0; i < patterns.length; i++) {
        errEl = document.getElementById(patterns[i]);
        if (errEl) break;
    }
    if (!errEl) {
        /* Fallback: cari field input dan tempelkan error di bawahnya */
        var inp = document.querySelector('[name="' + fieldName + '"]');
        if (inp) {
            var existingErr = inp.parentNode.querySelector('.field-error-msg');
            if (!existingErr) {
                existingErr = document.createElement('p');
                existingErr.className = 'field-error-msg';
                inp.parentNode.appendChild(existingErr);
            }
            errEl = existingErr;
        }
    }
    if (!errEl) return;
    errEl.textContent = msg;
    errEl.classList.add('show');

    /* Tandai input dengan state error */
    var inp2 = document.getElementById('field_' + fieldName)
            || document.querySelector('[name="' + fieldName + '"]');
    if (inp2) inp2.classList.add('field-error');
}

function clearAllErrors() {
    /* Hapus semua error message */
    document.querySelectorAll('.field-error-msg').forEach(function (el) {
        el.textContent = '';
        el.classList.remove('show');
    });
    /* Hapus state error dari semua input */
    document.querySelectorAll('.input-field.field-error').forEach(function (el) {
        el.classList.remove('field-error');
    });
    /* Hapus highlight error dari ktp-row */
    document.querySelectorAll('.ktp-row[style*="rgba(239"]').forEach(function (el) {
        el.style.background = '';
    });
    /* Hapus has-field-error dari ocr cards */
    document.querySelectorAll('.pemain-ocr-card.has-field-error').forEach(function (el) {
        el.classList.remove('has-field-error');
    });
    /* Sembunyikan error banner */
    var banner = document.getElementById('ajaxErrorBanner');
    if (banner) banner.classList.remove('show');
}

function showErrorBanner(errors) {
    var banner = document.getElementById('ajaxErrorBanner');
    var list   = document.getElementById('ajaxErrorList');
    if (!banner || !list) return;

    list.innerHTML = '';
    errors.forEach(function (msg) {
        var li = document.createElement('li');
        li.textContent = msg;
        list.appendChild(li);
    });
    banner.classList.add('show');
    banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function setSubmitLoading(loading, msg) {
    var btn     = document.getElementById('submitBtn');
    var btnText = document.getElementById('submitBtnText');
    var spinner = document.getElementById('submitBtnSpinner');
    var overlay = document.getElementById('submitOverlay');
    var ovText  = document.getElementById('submitOverlayText');
    var progBar = document.getElementById('submitProgressInner');

    if (loading) {
        if (btn)     btn.disabled = true;
        if (btnText) btnText.textContent = 'Memproses...';
        if (spinner) spinner.classList.remove('hidden');
        if (overlay) overlay.classList.add('show');
        if (ovText)  ovText.textContent = msg || 'Mengirim data pendaftaran...';
        /* Reset & re-trigger animation progress bar */
        if (progBar) {
            progBar.style.animation = 'none';
            progBar.offsetHeight; /* reflow */
            progBar.style.animation = '';
        }
    } else {
        if (btn)     btn.disabled = false;
        if (btnText) btnText.innerHTML = 'DAFTAR &amp; BAYAR SEKARANG &rarr;';
        if (spinner) spinner.classList.add('hidden');
        if (overlay) overlay.classList.remove('show');
    }
}

/* ── Kumpulkan semua file KTP dari slot yang ada ──────────── */
function buildFormData() {
    var form = document.getElementById('regForm');
    var fd   = new FormData();

    // Ambil semua field text/select/hidden biasa
    var inputs = form.querySelectorAll('input:not([type="file"]), select, textarea');
    inputs.forEach(function (inp) {
        if (!inp.name) return;
        if (inp.type === 'checkbox' || inp.type === 'radio') {
            if (inp.checked) fd.append(inp.name, inp.value);
        } else {
            fd.append(inp.name, inp.value);
        }
    });

    // ← PERBAIKAN: akses via window._dewasa.slotState
    var _slotState = (window._dewasa && window._dewasa.slotState) || {};

    var cards = document.querySelectorAll('.pemain-ocr-card');
    cards.forEach(function (card) {
        var idx     = card.dataset.idx;
        var fileObj = _slotState[idx] && _slotState[idx].file;

        // Fallback dari input DOM
        if (!fileObj) {
            var sources = ['ktpCamera_', 'ktpFoto_', 'ktpFile_'];
            for (var si = 0; si < sources.length; si++) {
                var inp = document.getElementById(sources[si] + idx);
                if (inp && inp.files && inp.files[0]) {
                    fileObj = inp.files[0];
                    break;
                }
            }
        }

        if (fileObj) {
            fd.append('ktp_files[]', fileObj, fileObj.name || ('ktp-pemain-' + (parseInt(idx,10)+1) + '.jpg'));
        }
    });

    return fd;
}

/* ── Handle error response dari Laravel (422) ─────────────── */
function handleValidationErrors(errorsObj) {
    var allMessages = [];

    Object.keys(errorsObj).forEach(function (field) {
        var messages = errorsObj[field];
        if (!Array.isArray(messages)) messages = [messages];

        var firstMsg = messages[0];
        allMessages = allMessages.concat(messages);

        /* Map field name ke UI */
        /*
         * Laravel mengirim: nama, tim_pb, email, no_hp, provinsi, kota,
         *                   pemain, pemain.0, pemain.1, ...
         *                   ktp_files, ktp_files.0, ...
         *                   jenis_kelamin, usia_hitung, ...
         */
        var uiField = field;

        /* Field array: pemain.0 → err_pemain, ktp_files.0 → err_ktp_files */
        if (field.match(/^pemain\.\d+$/))    uiField = 'pemain';
        if (field.match(/^ktp_files\.\d+$/)) uiField = 'ktp_files';
        if (field.match(/^nik\.\d+$/))       uiField = 'pemain';
        if (field.match(/^tgl_lahir\.\d+$/)) uiField = 'pemain';
        if (field.match(/^jenis_kelamin/))   uiField = 'jenis_kelamin';
        if (field.match(/^usia_hitung/))     uiField = 'pemain';

        setFieldError(uiField, firstMsg);

        /* Tandai ocr card jika error terkait pemain */
        if (field.match(/^(pemain|nik|tgl_lahir|ktp_files|jenis_kelamin)\.\d+$/)) {
            var idxMatch = field.match(/\.(\d+)$/);
            if (idxMatch) {
                var cardEl = document.getElementById('ocr_card_' + idxMatch[1]);
                if (cardEl) cardEl.classList.add('has-field-error');
            }
        }
    });

    showErrorBanner(allMessages);
}

/* ── AJAX Submit ──────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('regForm');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        /* 1. Bersihkan error sebelumnya */
        clearAllErrors();

        /* 2. Client-side validation OCR */
        var missing = window._dewasa ? [] : [];
        try {
            /* Panggil validasi client-side dari namespace _dewasa */
           // Ganti blok try { var cards = ... } dengan ini:
            var cards        = document.querySelectorAll('.pemain-ocr-card');
            var clientErrors = [];
            var _slotState   = (window._dewasa && window._dewasa.slotState) || {};

            cards.forEach(function (card) {
                var idx = card.dataset.idx;

                // Cek file KTP
                var hasFile = (_slotState[idx] && _slotState[idx].file);
                if (!hasFile) {
                    // Fallback cek input DOM
                    ['ktpCamera_','ktpFoto_','ktpFile_'].forEach(function(pfx){
                        var inp = document.getElementById(pfx + idx);
                        if (inp && inp.files && inp.files[0]) hasFile = true;
                    });
                }
                if (!hasFile) {
                    clientErrors.push('Pemain ' + (parseInt(idx,10)+1) + ': File KTP wajib diupload');
                    card.classList.add('has-field-error');
                }

                // Cek field hidden KTP terisi
                [['nik','NIK'], ['nama','Nama'], ['tanggal_lahir','Tanggal Lahir']].forEach(function(pair){
                    var hidEl = document.getElementById('khid_' + idx + '_' + pair[0]);
                    if (hidEl && !hidEl.value.trim()) {
                        clientErrors.push('Pemain ' + (parseInt(idx,10)+1) + ': ' + pair[1] + ' wajib diisi (scan KTP atau isi manual)');
                        card.classList.add('has-field-error');
                    }
                });
            });

            if (clientErrors.length > 0) {
                showErrorBanner(clientErrors);
                var firstErr = document.querySelector('.pemain-ocr-card.has-field-error');
                if (firstErr) firstErr.scrollIntoView({ behavior:'smooth', block:'center' });
                return;
            }
        } catch(ex) { console.error('[validate]', ex); }

        /* 3. Build FormData */
        var fd = buildFormData();

        /* 4. Tampilkan loading */
        setSubmitLoading(true, 'Mengirim data pendaftaran...');

        /* 5. Kirim via AJAX */
        var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

        try {
            var response = await fetch("{{ route('registration.store') }}", {
                method:  'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept':       'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: fd,
            });

            var data = await response.json();

            if (response.status === 422) {
                /* Validasi gagal — tampilkan error, JANGAN redirect */
                setSubmitLoading(false);
                handleValidationErrors(data.errors || {});
                return;
            }

            if (!response.ok) {
                setSubmitLoading(false);
                var msg = (data && data.message) ? data.message : 'Terjadi kesalahan server. Coba lagi.';
                showErrorBanner([msg]);
                return;
            }

            /* Sukses — update teks overlay lalu redirect */
            var ovText = document.getElementById('submitOverlayText');
            if (ovText) ovText.textContent = 'Pendaftaran berhasil! Mengarahkan ke halaman pembayaran...';

            /* Redirect ke URL yang dikembalikan server */
            if (data.redirect) {
                window.location.href = data.redirect;
            } else if (data.payment_url) {
                window.location.href = data.payment_url;
            } else if (data.uuid) {
                window.location.href = '/registration/payment/' + (data.payment_token || data.uuid);
            } else {
                /* Fallback */
                window.location.href = '/';
            }

        } catch (err) {
            setSubmitLoading(false);
            console.error('[AJAX submit error]', err);
            showErrorBanner(['Koneksi gagal atau server tidak merespons. Periksa koneksi internet dan coba lagi.']);
        }
    });
});

})(); /* end AJAX IIFE */
</script>

{{-- ════════════════════════════════════════════════════════════════
     UPLOAD BOTTOM SHEET — 3 pilihan: Foto Kamera · Upload Foto · Upload File
════════════════════════════════════════════════════════════════ --}}
<div id="uploadSheetBackdrop" class="upload-sheet-backdrop" onclick="_SHEET.close()"></div>
<div id="uploadSheet" class="upload-sheet" role="dialog" aria-modal="true">
    <div class="upload-sheet-handle"></div>
    <p class="upload-sheet-title">Pilih Sumber KTP</p>

    <div class="upload-sheet-options">

        {{-- 1: Foto Kamera --}}
        <button type="button" class="upload-opt-btn opt-camera"
                onclick="_SHEET.pick('camera')" aria-label="Foto dengan kamera">
            <div class="upload-opt-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="#f97316" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
            </div>
            <span>Foto<br>Kamera</span>
        </button>

        {{-- 2: Upload Foto (galeri) --}}
        <button type="button" class="upload-opt-btn opt-foto"
                onclick="_SHEET.pick('foto')" aria-label="Upload dari galeri foto">
            <div class="upload-opt-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="#fb923c" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
            </div>
            <span>Upload<br>Foto</span>
        </button>

        {{-- 3: Upload File (file manager) --}}
        <button type="button" class="upload-opt-btn opt-file"
                onclick="_SHEET.pick('file')" aria-label="Upload dari file manager">
            <div class="upload-opt-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="#fdba74" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="12" y1="18" x2="12" y2="12"/>
                    <line x1="9" y1="15" x2="15" y2="15"/>
                </svg>
            </div>
            <span>Upload<br>File</span>
        </button>

    </div>

    <button type="button" class="upload-sheet-cancel" onclick="_SHEET.close()">Batal</button>
</div>

<script>
/* ================================================================
   _SHEET — Upload Bottom Sheet controller
================================================================ */
window._SHEET = (function () {
'use strict';

var _activeIdx   = null;
var _isAnimating = false;

function open(idx) {
    if (_isAnimating) return;
    _activeIdx = idx;
    var backdrop = document.getElementById('uploadSheetBackdrop');
    var sheet    = document.getElementById('uploadSheet');
    if (!backdrop || !sheet) return;
    backdrop.classList.remove('hiding');
    sheet.classList.remove('hiding');
    backdrop.classList.add('show');
    sheet.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function close() {
    if (_isAnimating) return;
    var backdrop = document.getElementById('uploadSheetBackdrop');
    var sheet    = document.getElementById('uploadSheet');
    if (!backdrop || !sheet) return;
    _isAnimating = true;
    backdrop.classList.add('hiding');
    sheet.classList.add('hiding');
    setTimeout(function () {
        backdrop.classList.remove('show', 'hiding');
        sheet.classList.remove('show', 'hiding');
        document.body.style.overflow = '';
        _isAnimating = false;
    }, 210);
}

function pick(type) {
    var idx = _activeIdx;
    close();
    /* Tunggu animasi tutup selesai baru trigger input */
    setTimeout(function () {
        var map = { camera: 'ktpCamera_', foto: 'ktpFoto_', file: 'ktpFile_' };
        var pfx = map[type];
        if (!pfx || idx === null) return;
        var inp = document.getElementById(pfx + idx);
        if (inp) inp.click();
    }, 230);
}

/* Swipe down ≥ 70px → tutup sheet */
var _ty0 = 0;
document.addEventListener('touchstart', function (e) {
    if (document.getElementById('uploadSheet').classList.contains('show'))
        _ty0 = e.touches[0].clientY;
}, { passive: true });
document.addEventListener('touchend', function (e) {
    if (!document.getElementById('uploadSheet').classList.contains('show')) return;
    if (e.changedTouches[0].clientY - _ty0 > 70) close();
}, { passive: true });

document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

return { open, close, pick };
})();
</script>

@endpush

@endsection