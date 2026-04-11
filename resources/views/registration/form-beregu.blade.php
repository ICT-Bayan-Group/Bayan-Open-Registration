@extends('layouts.app')

@section('title', 'Pendaftaran Beregu — Bayan Open 2026')

@push('styles')
<style>
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes shimmerScan {
        0%   { background-position: -200% center; }
        100% { background-position:  200% center; }
    }
    @keyframes modalBackdropIn { from { opacity:0; } to { opacity:1; } }
    @keyframes spinLoader { to { transform: rotate(360deg); } }
    @keyframes progressBar { from { width:0%; } to { width:100%; } }
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
    @keyframes pulseOrange {
        0%, 100% { box-shadow: 0 0 0 0 rgba(249,115,22,.4); }
        50%       { box-shadow: 0 0 0 8px rgba(249,115,22,0); }
    }

    .form-section              { animation: fadeSlideUp .45s ease both; }
    .form-section:nth-child(1) { animation-delay: .06s; }
    .form-section:nth-child(2) { animation-delay: .12s; }
    .form-section:nth-child(3) { animation-delay: .18s; }
    .form-section:nth-child(4) { animation-delay: .24s; }
    .form-section:nth-child(5) { animation-delay: .30s; }

    /* ── Input field light theme ─────────────────────────────── */
    .input-field {
        background: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.15) !important;
        color: #2c2c2a !important;
        border-radius: 12px;
        transition: border-color .2s, box-shadow .2s;
    }
    .input-field:focus {
        outline: none;
        border-color: #f97316 !important;
        box-shadow: 0 0 0 3px rgba(249,115,22,.12) !important;
    }
    .input-field::placeholder { color: #b4b2a9 !important; }

    /* ── Select light theme ──────────────────────────────────── */
    select.input-field {
        color: #2c2c2a !important;
        background-color: #ffffff !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23888780' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 14px center !important;
        padding-right: 36px !important;
        cursor: pointer;
    }
    select.input-field option {
        background-color: #ffffff;
        color: #2c2c2a;
    }
    select.input-field:disabled {
        opacity: .45 !important;
        cursor: not-allowed;
        background-color: #f5f4f0 !important;
        color: #b4b2a9 !important;
    }
    select.input-field option:disabled { color: #b4b2a9; }

    /* ── Label style ─────────────────────────────────────────── */
    .field-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #888780;
        margin-bottom: 6px;
    }

    /* ── Grid anggota 2 kolom ────────────────────────────────── */
    #memberSlots {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    @media (max-width: 600px) { #memberSlots { grid-template-columns: 1fr; } }

    /* ── Member card compact ─────────────────────────────────── */
    .member-card {
        background: #ffffff;
        border-radius: 14px;
        border: 0.5px solid rgba(0,0,0,0.1);
        padding: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        transition: border-color .3s, background .3s, box-shadow .3s;
    }
    .member-card.scanned {
        border-color: rgba(16,185,129,.45);
        box-shadow: 0 0 0 1px rgba(16,185,129,.1) inset, 0 1px 4px rgba(0,0,0,.06);
    }
    .member-card.city-invalid {
        border-color: rgba(239,68,68,.4);
        box-shadow: 0 0 0 1px rgba(239,68,68,.06) inset, 0 1px 4px rgba(0,0,0,.06);
    }

    /* ── KTP Dropzone compact ────────────────────────────────── */
    .ktp-dropzone {
        border: 1.5px dashed rgba(249,115,22,.3);
        border-radius: 10px;
        background: rgba(249,115,22,.03);
        cursor: pointer;
        transition: border-color .2s, background .2s;
        min-height: 64px;
        display: flex; align-items: center; justify-content: center;
    }
    .ktp-dropzone:hover, .ktp-dropzone.drag-over {
        border-color: rgba(249,115,22,.6);
        background: rgba(249,115,22,.07);
    }

    /* ── KTP Data Card ───────────────────────────────────────── */
    .ktp-data-card {
        border-radius: 10px;
        background: #f8f7f4;
        border: 0.5px solid rgba(0,0,0,.08);
        padding: 10px 12px; margin-top: 10px; display: none;
    }
    .ktp-data-card.show       { display: block; animation: fadeSlideUp .3s ease both; }
    .ktp-data-card.valid-card { background: rgba(249,115,22,.03); border-color: rgba(249,115,22,.2); }

    /* ── KTP Row compact ─────────────────────────────────────── */
    .ktp-row {
        display: flex; align-items: center; gap: 6px;
        padding: 3px 0; border-bottom: 1px solid rgba(0,0,0,.05); min-height: 26px;
    }
    .ktp-row:last-child { border-bottom: none; padding-bottom: 0; }
    .ktp-label {
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: #888780;
        min-width: 58px; flex-shrink: 0;
    }
    .ktp-value {
        flex: 1; font-size: 11px; color: #444441; line-height: 1.3;
        word-break: break-word; padding: 2px 6px; border-radius: 5px;
        border: 1px solid transparent; cursor: pointer; min-width: 0;
        transition: background .15s, border-color .15s, color .15s;
        font-weight: 600;
    }
    .ktp-value:hover {
        background: rgba(249,115,22,.08); border-color: rgba(249,115,22,.28); color: #1a1a1a;
    }
    .ktp-value:hover::after { content: ' ✏'; font-size: 8px; opacity: .45; }

    /* ── Inline input ────────────────────────────────────────── */
    .ktp-inline-input {
        flex: 1; background: #fff;
        border: 1.5px solid rgba(249,115,22,.5); border-radius: 5px;
        color: #2c2c2a; font-size: 11px; font-weight: 600;
        padding: 2px 7px; outline: none; min-width: 0;
        transition: border-color .15s, box-shadow .15s;
    }
    .ktp-inline-input:focus { border-color: rgba(249,115,22,.9); box-shadow: 0 0 0 2px rgba(249,115,22,.14); }
    .ktp-inline-input.was-edited { border-color: rgba(234,179,8,.65); background: rgba(234,179,8,.05); }

    /* ── Usia display ────────────────────────────────────────── */
    .usia-display {
        flex: 1; font-size: 11px; font-weight: 700; padding: 2px 6px;
        border-radius: 5px; cursor: default; transition: color .25s, background .25s; min-width: 0;
    }
    .usia-display.has-value { color: #059669; background: rgba(16,185,129,.08); border: 1px solid rgba(16,185,129,.2); }
    .usia-display.no-value  { color: #b4b2a9; font-style: italic; font-weight: 400; background: transparent; border: 1px solid transparent; }

    /* ── City badge ──────────────────────────────────────────── */
    .city-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 8px; border-radius: 6px; font-size: 10px; font-weight: 700;
        margin-top: 8px; width: 100%; box-sizing: border-box;
    }
    .city-badge.valid   { background: rgba(16,185,129,.08);  border: 1px solid rgba(16,185,129,.25); color: #059669; }
    .city-badge.invalid { background: rgba(239,68,68,.08);   border: 1px solid rgba(239,68,68,.25);  color: #dc2626; }
    .city-badge.empty   { background: rgba(0,0,0,.04);       border: 1px solid rgba(0,0,0,.08);      color: #888780; }

    /* ── Scan loading bar ────────────────────────────────────── */
    .scan-loading-bar { height: 2px; border-radius: 99px; overflow: hidden; background: rgba(249,115,22,.1); margin-top: 6px; }
    .scan-loading-bar-inner {
        height: 100%; width: 40%;
        background: linear-gradient(90deg, transparent, #f97316, transparent);
        background-size: 200% 100%; animation: shimmerScan 1.2s ease infinite;
    }

    /* ── Scan badge ──────────────────────────────────────────── */
    .scan-badge {
        display: none; align-items: center; gap: 3px;
        padding: 1px 6px; border-radius: 99px; font-size: 9px; font-weight: 700;
        background: rgba(16,185,129,.1); border: 0.5px solid rgba(16,185,129,.3); color: #059669;
    }

    /* ── Counter bar ─────────────────────────────────────────── */
    .counter-bar { height: 5px; border-radius: 99px; background: rgba(0,0,0,.08); overflow: hidden; margin-top: 5px; }
    .counter-fill { height: 100%; border-radius: 99px; transition: width .4s ease, background .4s; }

    /* ── Preview compact ─────────────────────────────────────── */
    .preview-img-compact { max-height: 52px; max-width: 100%; border-radius: 6px; object-fit: contain; }
    .ktp-edit-hint { font-size: 8.5px; color: #b4b2a9; text-align: right; font-style: italic; margin-top: 2px; margin-bottom: 4px; }

    /* ── Scan semua button ───────────────────────────────────── */
    #scanAllBtn {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 10px 16px; border-radius: 10px; border: none; cursor: pointer;
        font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
        background: linear-gradient(135deg, #f97316, #c2410c);
        color: #ffffff;
        transition: opacity .2s, transform .1s;
        margin-bottom: 14px;
    }
    #scanAllBtn:hover  { opacity: .9; }
    #scanAllBtn:active { transform: scale(.98); }
    #scanAllBtn:disabled { opacity: .45; cursor: not-allowed; transform: none; }

    /* ── Submit button states ────────────────────────────────── */
    #submitBtn {
        transition: background .4s ease, box-shadow .4s ease, opacity .3s;
    }
    #submitBtn.btn-disabled {
        background: #d3d1c7 !important;
        color: #888780 !important;
        cursor: not-allowed;
        box-shadow: none !important;
    }
    #submitBtn.btn-ready {
        background: linear-gradient(135deg, #f97316, #c2410c) !important;
        color: #ffffff !important;
        cursor: pointer;
        animation: pulseOrange 2s ease infinite;
    }
    #submitBtn.btn-ready:hover { opacity: .92; }

    /* ── Upload Bottom Sheet ─────────────────────────────────── */
    .upload-sheet-backdrop {
        display: none; position: fixed; inset: 0; z-index: 88888;
        background: rgba(0,0,0,.45); backdrop-filter: blur(3px); -webkit-backdrop-filter: blur(3px);
    }
    .upload-sheet-backdrop.show   { display: block; animation: backdropIn .2s ease both; }
    .upload-sheet-backdrop.hiding { animation: backdropOut .2s ease both; }

    .upload-sheet {
        display: none; position: fixed; bottom: 0; left: 0; right: 0; z-index: 88889;
        background: #ffffff; border-top: 1.5px solid rgba(249,115,22,.22);
        border-radius: 22px 22px 0 0; padding: 0 0 calc(env(safe-area-inset-bottom,0px) + 16px);
        box-shadow: 0 -20px 60px rgba(0,0,0,.15); max-width: 540px; margin: 0 auto;
    }
    .upload-sheet.show   { display: block; animation: sheetIn .28s cubic-bezier(.34,1.3,.64,1) both; }
    .upload-sheet.hiding { animation: sheetOut .2s ease both; }

    .upload-sheet-handle { width: 40px; height: 4px; border-radius: 99px; background: rgba(0,0,0,.12); margin: 12px auto 18px; }
    .upload-sheet-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: #888780; text-align: center; margin-bottom: 16px; }
    .upload-sheet-options { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; padding: 0 16px; }

    .upload-opt-btn {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 8px; padding: 16px 8px; border-radius: 16px; border: 1.5px solid; cursor: pointer;
        font-size: 11px; font-weight: 700; line-height: 1.3; text-align: center;
        transition: background .15s, border-color .15s, transform .1s;
        -webkit-tap-highlight-color: transparent;
    }
    .upload-opt-btn:active { transform: scale(.95); }
    .upload-opt-icon { width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    .upload-opt-btn.opt-camera { color: #f97316; background: rgba(249,115,22,.08); border-color: rgba(249,115,22,.3); }
    .upload-opt-btn.opt-camera:hover  { background: rgba(249,115,22,.15); border-color: rgba(249,115,22,.55); }
    .upload-opt-btn.opt-camera .upload-opt-icon { background: rgba(249,115,22,.12); }

    .upload-opt-btn.opt-foto { color: #ea6d1a; background: rgba(249,115,22,.06); border-color: rgba(249,115,22,.22); }
    .upload-opt-btn.opt-foto:hover  { background: rgba(249,115,22,.12); border-color: rgba(249,115,22,.45); }
    .upload-opt-btn.opt-foto .upload-opt-icon { background: rgba(249,115,22,.1); }

    .upload-opt-btn.opt-file { color: #b45309; background: rgba(249,115,22,.04); border-color: rgba(249,115,22,.18); }
    .upload-opt-btn.opt-file:hover  { background: rgba(249,115,22,.1); border-color: rgba(249,115,22,.35); }
    .upload-opt-btn.opt-file .upload-opt-icon { background: rgba(249,115,22,.08); }

    .upload-sheet-cancel {
        display: block; width: calc(100% - 32px); margin: 14px 16px 0; padding: 13px;
        border-radius: 14px; border: 1px solid rgba(0,0,0,.1); background: #f5f4f0;
        color: #888780; font-size: 13px; font-weight: 700;
        text-align: center; cursor: pointer; transition: background .15s, color .15s;
    }
    .upload-sheet-cancel:hover { background: #e8e6e1; color: #444441; }

    /* ── Submit overlay ──────────────────────────────────────── */
    #submitOverlay {
        display: none; position: fixed; inset: 0; z-index: 99998;
        background: rgba(0,0,0,.55); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
        align-items: center; justify-content: center; flex-direction: column; gap: 20px;
    }
    #submitOverlay.show { display: flex; animation: modalBackdropIn .2s ease both; }
    .submit-spinner {
        width: 52px; height: 52px; border: 3px solid rgba(249,115,22,.2);
        border-top-color: #f97316; border-radius: 50%; animation: spinLoader .8s linear infinite;
    }
    .submit-progress-bar { width: 240px; height: 4px; background: rgba(255,255,255,.1); border-radius: 99px; overflow: hidden; }
    .submit-progress-inner { height: 100%; background: linear-gradient(90deg,#f97316,#fb923c); border-radius: 99px; animation: progressBar 3s ease forwards; }

    /* ── Ajax Error Banner ───────────────────────────────────── */
    #ajaxErrorBanner {
        display: none; border-radius: 16px; padding: 16px 20px; margin-bottom: 20px;
        background: rgba(239,68,68,.06); border: 1.5px solid rgba(239,68,68,.25);
        animation: fadeSlideUp .3s ease both;
    }
    #ajaxErrorBanner.show { display: block; }

    /* ── Field error ─────────────────────────────────────────── */
    .input-field.field-error { border-color: rgba(239,68,68,.6) !important; box-shadow: 0 0 0 2px rgba(239,68,68,.1) !important; }
    .field-error-msg { color: #dc2626; font-size: 11px; margin-top: 4px; display: none; }
    .field-error-msg.show { display: block; animation: fadeSlideUp .2s ease both; }

    /* ── Hint text ───────────────────────────────────────────── */
    .hint-text { color: #b4b2a9; font-size: 11px; margin-top: 4px; }
</style>
@endpush

@section('content')
<section class="min-h-screen py-20 px-4">
<div class="max-w-3xl mx-auto">

    {{-- ── HEADER ──────────────────────────────────────────────────── --}}
    <div class="text-center mb-10 form-section">
        <a href="{{ route('registration.index') }}"
           class="inline-flex items-center gap-2 text-white/30 text-xs hover:text-white/60 transition mb-6">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Ganti kategori
        </a>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-brand-500/40
                    bg-brand-500/10 text-brand-300 text-xs font-semibold uppercase tracking-widest mb-4">
            Pendaftaran Online · Bayan Open 2026
        </div>
        <h1 class="font-display text-3xl font-bold mb-3">Formulir Beregu</h1>
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-3"
             style="background:rgba(249,115,22,.1);border:1px solid rgba(249,115,22,.3);">
            <svg width="13" height="13" viewBox="0 0 20 20" fill="rgba(251,146,60,1)">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
            </svg>
            <span class="text-brand-400 text-xs font-bold uppercase tracking-widest">Beregu · 6–8 Anggota</span>
        </div>
        <p class="text-white/40 text-sm mt-2">
            Minimal <strong class="text-white/70">6</strong> dari
            <strong class="text-white/70">8</strong> anggota harus ber-KTP
            <strong class="text-brand-400">Kota Balikpapan</strong>
        </p>
    </div>

    {{-- ── AJAX ERROR BANNER ───────────────────────────────────────── --}}
    <div id="ajaxErrorBanner">
        <div class="flex items-start gap-3">
            <svg class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-red-500 text-sm font-bold mb-2">Terdapat kesalahan — perbaiki dan coba lagi:</p>
                <ul id="ajaxErrorList" class="text-red-400 text-sm space-y-1 list-disc list-inside"></ul>
            </div>
        </div>
    </div>

    <form id="regForm" novalidate>
    @csrf
    <input type="hidden" name="kategori" value="beregu">

    {{-- ═══════ SECTION 1 — DATA TIM & KONTAK ═══════ --}}
    <div class="card-glass rounded-2xl p-8 mb-6 form-section">
        <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs font-black text-white">1</span>
            Data Tim &amp; Kontak
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="field-label">Nama Tim / PB <span class="text-brand-400">*</span></label>
                <input type="text" name="tim_pb" id="field_tim_pb"
                    placeholder="Contoh: PB Garuda Sakti"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm" required>
                <p class="field-error-msg" id="err_tim_pb"></p>
            </div>
            <div class="md:col-span-2">
                <label class="field-label">Nama Ketua Tim / PIC <span class="text-brand-400">*</span></label>
                <input type="text" name="nama" id="field_nama"
                    placeholder="Nama lengkap ketua tim"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm" required>
                <p class="field-error-msg" id="err_nama"></p>
            </div>
            <div>
                <label class="field-label">Email <span class="text-brand-400">*</span></label>
                <input type="email" name="email" id="field_email"
                    placeholder="email@contoh.com"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm" required>
                <p class="hint-text">Link pembayaran dikirim setelah diverifikasi</p>
                <p class="field-error-msg" id="err_email"></p>
            </div>
            <div>
                <label class="field-label">Nomor WhatsApp <span class="text-brand-400">*</span></label>
                <input type="text" name="no_hp" id="field_no_hp"
                    placeholder="Contoh: 08123456789"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm" required>
                <p class="field-error-msg" id="err_no_hp"></p>
            </div>
            <div>
                <label class="field-label">Provinsi <span class="text-brand-400">*</span></label>
                <div class="relative">
                    <select id="selectProvinsi" name="provinsi"
                        onchange="WILAYAH.onProvinsiChange(this)"
                        class="input-field w-full px-4 py-3 rounded-xl text-sm appearance-none" required>
                        <option value="" style="color:#b4b2a9;">-- Pilih Provinsi --</option>
                    </select>
                    <div id="loadingProvinsi" class="hidden absolute right-10 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg class="animate-spin w-4 h-4" style="color:#f97316;" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </div>
                </div>
                <p class="field-error-msg" id="err_provinsi"></p>
            </div>
            <div>
                <label class="field-label">Kota / Kabupaten <span class="text-brand-400">*</span></label>
                <div class="relative">
                    <select id="selectKota" name="kota" disabled
                        class="input-field w-full px-4 py-3 rounded-xl text-sm appearance-none" required>
                        <option value="">-- Pilih Provinsi dulu --</option>
                    </select>
                    <div id="loadingKota" class="hidden absolute right-10 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg class="animate-spin w-4 h-4" style="color:#f97316;" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </div>
                </div>
                <p class="field-error-msg" id="err_kota"></p>
            </div>
        </div>
    </div>

    {{-- ═══════ SECTION 2 — DATA PELATIH ═══════ --}}
    <div class="card-glass rounded-2xl p-8 mb-6 form-section">
        <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs font-black text-white">2</span>
            Data Pelatih
            <span class="text-white/40 text-xs font-normal normal-case ml-1">(opsional)</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="field-label">Nama Pelatih</label>
                <input type="text" name="nama_pelatih" placeholder="Nama lengkap pelatih" class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
            <div>
                <label class="field-label">No. HP Pelatih</label>
                <input type="text" name="no_hp_pelatih" placeholder="Contoh: 08123456789" class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
        </div>
    </div>

    {{-- ═══════ SECTION 3 — ANGGOTA TIM ═══════ --}}
    <div class="card-glass rounded-2xl p-6 mb-6 form-section">

        <h2 class="font-display text-sm font-bold mb-1 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0"
                  style="background:rgba(249,115,22,.9);color:#fff;">3</span>
            Data Anggota Tim
        </h2>
        <p class="text-xs mb-1 ml-8" style="color:#444441;">
            Upload KTP lalu klik <strong style="color:#f97316;">SCAN</strong> — data terisi otomatis &amp; bisa diedit.
        </p>
        <p class="text-xs mb-5 ml-8" style="color:#b4b2a9;">
            Minimal <strong style="color:#888780;">6 anggota</strong> harus ber-KTP Kota Balikpapan.
        </p>

        {{-- Counter KTP Valid --}}
        <div class="mb-4 p-4 rounded-xl" style="background:#ffffff;border:0.5px solid rgba(0,0,0,.10);box-shadow:0 1px 4px rgba(0,0,0,.06);">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-semibold uppercase tracking-wide" style="color:#888780;">KTP Balikpapan Valid</span>
                <span id="counterText" class="text-xs font-bold" style="color:#444441;">0 / 8 anggota KTP valid</span>
            </div>
            <div style="background:rgba(0,0,0,.08);border-radius:99px;height:5px;overflow:hidden;">
                <div id="counterFill" style="width:0%;background:#f97316;height:100%;border-radius:99px;transition:width .4s ease,background .4s;"></div>
            </div>
            <p id="counterNote" class="text-xs mt-1.5" style="color:#b4b2a9;">Upload &amp; scan KTP untuk melihat progress</p>
        </div>

        {{-- Scan Semua Button --}}
        <button type="button" id="scanAllBtn" onclick="BEREGU.scanAll()" disabled>
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
            </svg>
            <span id="scanAllText">SCAN SEMUA KTP</span>
        </button>

        <div id="memberSlots"></div>

        <p class="field-error-msg mt-3" id="err_pemain"></p>
    </div>

    {{-- ═══════ SECTION 4 — RINGKASAN BIAYA ═══════ --}}
    <div class="card-glass rounded-2xl p-6 mb-6 form-section">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-white/40 text-xs mb-1">Kategori</p>
                <p class="font-display text-white font-bold text-sm">Beregu</p>
                <p class="text-white/28 text-xs mt-0.5">6–8 anggota · Syarat KTP Balikpapan</p>
            </div>
            <div class="text-right">
                <p class="text-white/40 text-xs mb-1">Total Pembayaran</p>
                <p class="font-display text-brand-400 font-bold text-2xl">Rp 1.000.000</p>
            </div>
        </div>
    </div>

    <div class="card-glass rounded-2xl p-6 mb-6 form-section">
        <div class="flex items-start gap-3">
            <svg class="w-4 h-4 text-brand-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-brand-400 text-xs font-bold mb-1">Proses Verifikasi Admin</p>
                <p class="text-white/40 font-semibold text-xs leading-relaxed">
                    Pendaftaran beregu akan diverifikasi admin terlebih dahulu. Setelah disetujui, link pembayaran dikirim ke email.
                </p>
            </div>
        </div>
    </div>

    {{-- Submit Button — abu-abu dulu, jadi oranye kalau sudah 6 KTP valid --}}
    <div class="form-section mb-2">
        <button type="submit" id="submitBtn" disabled
            class="btn-disabled w-full py-4 rounded-xl font-display text-sm font-bold tracking-wide
                   flex items-center justify-center gap-3">
            <span id="submitBtnText">Scan minimal 6 KTP Balikpapan dulu</span>
            <svg id="submitBtnSpinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
        </button>
        <p id="submitHint" class="text-center text-xs mt-2" style="color:#b4b2a9;">
            Butuh <span id="submitNeedCount">6</span> KTP Balikpapan valid lagi
        </p>
    </div>

    <p class="text-black font-semibold text-xs text-center mt-2 mb-6 form-section">
        Dengan mendaftar, Anda menyetujui syarat &amp; ketentuan Bayan Open 2026
    </p>
    </form>
</div>
</section>

{{-- ── SUBMIT OVERLAY ──────────────────────────────────────────── --}}
<div id="submitOverlay">
    <div class="submit-spinner"></div>
    <p id="submitOverlayText" class="text-white/60 text-sm font-semibold">Mengirim data pendaftaran...</p>
    <div class="submit-progress-bar"><div class="submit-progress-inner"></div></div>
</div>

{{-- ── UPLOAD BOTTOM SHEET ─────────────────────────────────────── --}}
<div id="uploadSheetBackdrop" class="upload-sheet-backdrop" onclick="_SHEET.close()"></div>
<div id="uploadSheet" class="upload-sheet" role="dialog" aria-modal="true">
    <div class="upload-sheet-handle"></div>
    <p class="upload-sheet-title">Pilih Sumber KTP</p>
    <div class="upload-sheet-options">
        <button type="button" class="upload-opt-btn opt-camera" onclick="_SHEET.pick('camera')">
            <div class="upload-opt-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="#f97316" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
            </div>
            <span>Foto<br>Kamera</span>
        </button>
        <button type="button" class="upload-opt-btn opt-foto" onclick="_SHEET.pick('foto')">
            <div class="upload-opt-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="#ea6d1a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
            </div>
            <span>Upload<br>Foto</span>
        </button>
        <button type="button" class="upload-opt-btn opt-file" onclick="_SHEET.pick('file')">
            <div class="upload-opt-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="#b45309" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
                </svg>
            </div>
            <span>Upload<br>File</span>
        </button>
    </div>
    <button type="button" class="upload-sheet-cancel" onclick="_SHEET.close()">Batal</button>
</div>

@push('scripts')
<script>
/* ================================================================ WILAYAH ================================================================ */
(function () {
'use strict';
async function loadProvinsi() {
    var sel = document.getElementById('selectProvinsi');
    var spin = document.getElementById('loadingProvinsi');
    if (!sel) return;
    spin && spin.classList.remove('hidden');
    try {
        var data = await (await fetch('/wilayah/provinces')).json();
        data.forEach(function (p) {
            var opt = new Option(p.name||p.nama, p.name||p.nama);
            opt.dataset.code = p.id;
            sel.appendChild(opt);
        });
    } catch (e) {
        sel.innerHTML = '<option value="">Gagal memuat — refresh</option>';
    } finally {
        spin && spin.classList.add('hidden');
    }
}
async function onProvinsiChange(sel) {
    var opt = sel.options[sel.selectedIndex], code = opt ? (opt.dataset.code||'') : '';
    var ks = document.getElementById('selectKota');
    if (ks) {
        ks.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        ks.disabled = true;
    }
    if (code) await loadKota(code);
}
async function loadKota(provId, selectedName) {
    var sel = document.getElementById('selectKota'), spin = document.getElementById('loadingKota');
    if (!sel) return;
    sel.disabled = true;
    sel.innerHTML = '<option value="">Memuat...</option>';
    spin && spin.classList.remove('hidden');
    try {
        var data = await (await fetch('/wilayah/regencies/' + encodeURIComponent(provId))).json();
        sel.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        data.forEach(function (k) {
            var l = k.name||k.nama;
            var opt = new Option(l,l);
            if (selectedName && l.toUpperCase()===selectedName.toUpperCase()) opt.selected=true;
            sel.appendChild(opt);
        });
        sel.disabled = false;
    } catch (e) {
        sel.innerHTML = '<option value="">Gagal memuat</option>';
        sel.disabled = false;
    } finally {
        spin && spin.classList.add('hidden');
    }
}
window.WILAYAH = { onProvinsiChange: onProvinsiChange };
document.addEventListener('DOMContentLoaded', loadProvinsi);
})();
</script>

<script>
/* ================================================================ _SHEET ================================================================ */
window._SHEET = (function () {
'use strict';
var _activeId = null, _isAnimating = false;
function open(id) {
    if (_isAnimating) return;
    _activeId = id;
    var bd = document.getElementById('uploadSheetBackdrop'), sh = document.getElementById('uploadSheet');
    if (!bd||!sh) return;
    bd.classList.remove('hiding'); sh.classList.remove('hiding');
    bd.classList.add('show'); sh.classList.add('show');
    document.body.style.overflow = 'hidden';
}
function close() {
    if (_isAnimating) return;
    var bd = document.getElementById('uploadSheetBackdrop'), sh = document.getElementById('uploadSheet');
    if (!bd||!sh) return;
    _isAnimating = true;
    bd.classList.add('hiding'); sh.classList.add('hiding');
    setTimeout(function () {
        bd.classList.remove('show','hiding');
        sh.classList.remove('show','hiding');
        document.body.style.overflow='';
        _isAnimating=false;
    }, 210);
}
function pick(type) {
    var id = _activeId; close();
    setTimeout(function () {
        var map = { camera:'fiCam_', foto:'fiFoto_', file:'fiFile_' };
        var el = document.getElementById((map[type]||'fiFoto_') + id);
        if (el) el.click();
    }, 230);
}
var _ty0 = 0;
document.addEventListener('touchstart', function (e) {
    if (document.getElementById('uploadSheet').classList.contains('show')) _ty0=e.touches[0].clientY;
}, {passive:true});
document.addEventListener('touchend', function (e) {
    if (!document.getElementById('uploadSheet').classList.contains('show')) return;
    if (e.changedTouches[0].clientY-_ty0>70) close();
}, {passive:true});
document.addEventListener('keydown', function (e) { if (e.key==='Escape') close(); });
return { open:open, close:close, pick:pick };
})();
</script>

<script>
/* ================================================================ BEREGU ================================================================ */
(function () {
'use strict';

var MIN_MEMBERS   = 6;
var MAX_MEMBERS   = 8;
var MIN_KTP_VALID = 6;

var members  = [];
var nextId   = 0;
var cardData = {};
var memberFiles = {};

var TOURNAMENT_DATE = new Date(2026, 7, 24);
var VALID_CITY      = ['BALIKPAPAN'];

var CARD_FIELDS = [
    { l:'NIK',     k:'nik',           n:'nik[]',       placeholder:'16 digit NIK' },
    { l:'Nama',    k:'nama',          n:'pemain[]',    placeholder:'Nama sesuai KTP' },
    { l:'Tgl Lhr', k:'tanggal_lahir', n:'tgl_lahir[]', placeholder:'DD-MM-YYYY' },
];

/* ── Hitung usia ─────────────────────────────────────────────── */
function hitungUsia(str) {
    if (!str||!str.trim()) return null;
    str = str.trim();
    var m1 = str.match(/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{4})$/);
    var m2 = str.match(/^(\d{4})[-\/\.](\d{1,2})[-\/\.](\d{1,2})$/);
    var tgl = m1 ? new Date(+m1[3],+m1[2]-1,+m1[1]) : m2 ? new Date(+m2[1],+m2[2]-1,+m2[3]) : new Date(str);
    if (!tgl||isNaN(tgl.getTime())) return null;
    var usia = TOURNAMENT_DATE.getFullYear() - tgl.getFullYear();
    var bm   = TOURNAMENT_DATE.getMonth() - tgl.getMonth();
    if (bm<0||(bm===0&&TOURNAMENT_DATE.getDate()<tgl.getDate())) usia--;
    if (usia<0||usia>120) return null;
    return usia;
}

function updateUsiaRow(id, tglValue) {
    var usia = hitungUsia(tglValue);
    var el   = document.getElementById('usia_disp_' + id);
    if (!el) return;
    if (usia !== null) { el.className = 'usia-display has-value'; el.textContent = usia + ' th (Ags 2026)'; }
    else               { el.className = 'usia-display no-value';  el.textContent = '—'; }
}

function isCityValid(city) {
    if (!city) return false;
    city = city.toUpperCase().trim();
    return VALID_CITY.some(function (k) { return city.indexOf(k) !== -1; });
}

/* ── Konversi HEIC/besar → JPEG ─────────────────────────────── */
function convertToJpeg(file, callback) {
    var MAX_SIZE_KB  = 300;
    var MAX_PIXEL    = 1600;
    var QUALITY_STEP = 0.10;
    var MIN_QUALITY  = 0.35;

    var reader = new FileReader();
    reader.onload = function (e) {
        var dataUrl = e.target.result;
        var img     = new Image();
        img.onload = function () {
            var w = img.naturalWidth, h = img.naturalHeight;
            if (w > MAX_PIXEL || h > MAX_PIXEL) {
                if (w > h) { h = Math.round(h * MAX_PIXEL / w); w = MAX_PIXEL; }
                else        { w = Math.round(w * MAX_PIXEL / h); h = MAX_PIXEL; }
            }
            var canvas = document.createElement('canvas');
            canvas.width = w; canvas.height = h;
            var ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, w, h);
            function tryCompress(quality) {
                canvas.toBlob(function (blob) {
                    if (!blob) { callback(file, dataUrl); return; }
                    var sizeKB = blob.size / 1024;
                    if (sizeKB > MAX_SIZE_KB && quality - QUALITY_STEP >= MIN_QUALITY) {
                        tryCompress(parseFloat((quality - QUALITY_STEP).toFixed(2)));
                        return;
                    }
                    if (sizeKB > MAX_SIZE_KB) {
                        w = Math.round(w * 0.7); h = Math.round(h * 0.7);
                        canvas.width = w; canvas.height = h;
                        ctx.drawImage(img, 0, 0, w, h);
                        tryCompress(0.55);
                        return;
                    }
                    var converted = new File([blob], file.name.replace(/\.[^.]+$/, '') + '.jpg', { type: 'image/jpeg', lastModified: Date.now() });
                    var previewUrl = URL.createObjectURL(blob);
                    callback(converted, previewUrl);
                }, 'image/jpeg', quality);
            }
            tryCompress(0.82);
        };
        img.onerror = function () { showToast('Format foto tidak didukung. Coba konversi ke JPG.', 'warn'); callback(null, null); };
        img.src = dataUrl;
    };
    reader.onerror = function () { showToast('Gagal membaca file foto.', 'error'); callback(null, null); };
    reader.readAsDataURL(file);
}

/* ── Init — langsung 8 card ──────────────────────────────────── */
function init() {
    for (var i = 0; i < MAX_MEMBERS; i++) addMember();
    updateCounter();
}

/* ── Tambah member ───────────────────────────────────────────── */
function addMember() {
    if (members.length >= MAX_MEMBERS) return;
    var id = nextId++;
    members.push({ id:id, scanned:false, cityValid:null });
    cardData[id] = {};
    memberFiles[id] = null;
    renderSlot(id);
    updateCounter();
}

/* ── Hapus member ────────────────────────────────────────────── */
function removeMember(id) {
    var card = document.getElementById('mc_' + id);
    if (card) card.remove();
    members = members.filter(function (m) { return m.id !== id; });
    delete cardData[id];
    delete memberFiles[id];
    updateCounter();
    renumber();
}

function renumber() {
    document.querySelectorAll('.member-card').forEach(function (c, i) {
        var n = c.querySelector('.member-num'), l = c.querySelector('.member-lbl');
        if (n) n.textContent = i+1;
        if (l) l.textContent = 'Anggota ' + (i+1);
    });
}

/* ── Render slot ─────────────────────────────────────────────── */
function renderSlot(id) {
    var idx = members.length - 1;
    var deletable = members.length > MIN_MEMBERS;
    var container = document.getElementById('memberSlots');

    var delBtn = deletable
        ? '<button type="button" onclick="BEREGU.remove(' + id + ')"'
          + ' class="w-6 h-6 rounded-md flex items-center justify-center transition flex-shrink-0"'
          + ' style="background:rgba(239,68,68,.10);border:0.5px solid rgba(239,68,68,.2);" title="Hapus">'
          + '<svg width="10" height="10" fill="none" stroke="#ef4444" viewBox="0 0 24 24" stroke-width="2.5">'
          + '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>'
        : '';

    var html =
        '<div id="mc_' + id + '" class="member-card" data-id="' + id + '">'

        + '<div class="flex items-center justify-between mb-3">'
        +   '<div class="flex items-center gap-2">'
        +     '<div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0"'
        +          ' style="background:rgba(249,115,22,.10);border:1px solid rgba(249,115,22,.25);">'
        +       '<span class="member-num font-black" style="font-size:9px;color:#f97316;">' + (idx+1) + '</span>'
        +     '</div>'
        +     '<span class="member-lbl font-bold" style="font-size:11px;color:#444441;">Anggota ' + (idx+1) + '</span>'
        +     '<span id="scan_badge_' + id + '" class="scan-badge" style="display:none;">'
        +       '<svg width="8" height="8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">'
        +         '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Ter-scan'
        +     '</span>'
        +   '</div>'
        +   delBtn
        + '</div>'

        /* Dropzone */
        + '<div id="dz_' + id + '" class="ktp-dropzone"'
        +     ' onclick="BEREGU.showSheet(' + id + ')"'
        +     ' ondragover="event.preventDefault();this.classList.add(\'drag-over\')"'
        +     ' ondragleave="this.classList.remove(\'drag-over\')"'
        +     ' ondrop="BEREGU.onDrop(event,' + id + ')">'

        /* Preview */
        +   '<div id="prev_' + id + '" class="hidden w-full flex items-center gap-2 px-3 py-2">'
        +     '<div class="relative flex-shrink-0">'
        +       '<img id="prevImg_' + id + '" src="" alt="" style="width:48px;height:36px;border-radius:6px;object-fit:cover;border:0.5px solid rgba(0,0,0,.1);">'
        +       '<button type="button" onclick="BEREGU.resetFile(event,' + id + ')"'
        +               ' class="absolute -top-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center"'
        +               ' style="background:#ef4444;">'
        +         '<svg width="7" height="7" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="3">'
        +           '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>'
        +       '</button>'
        +     '</div>'
        +     '<p style="font-size:10px;color:#b4b2a9;">Ketuk ganti</p>'
        +   '</div>'

        /* Placeholder */
        +   '<div id="dzDefault_' + id + '" class="flex flex-col items-center py-2 gap-1">'
        +     '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(249,115,22,.45)" stroke-width="1.5">'
        +       '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h6"/>'
        +     '</svg>'
        +     '<p style="font-size:10px;color:#b4b2a9;">Ketuk upload KTP</p>'
        +   '</div>'
        + '</div>'

        /* 3 hidden file inputs */
        + '<input type="file" id="fiCam_' + id + '"  accept="image/*" capture="environment" class="hidden" onchange="BEREGU.onFileSelect(this,' + id + ')">'
        + '<input type="file" id="fiFoto_' + id + '" accept="image/*" class="hidden" name="ktp_files[]" onchange="BEREGU.onFileSelect(this,' + id + ')">'
        + '<input type="file" id="fiFile_' + id + '" accept="image/*,.heic,.heif" class="hidden" onchange="BEREGU.onFileSelect(this,' + id + ')">'

        /* Scan button per card */
        + '<button type="button" id="scanBtn_' + id + '" onclick="BEREGU.scan(' + id + ')"'
        +         ' class="hidden mt-2 w-full flex items-center justify-center gap-1.5"'
        +         ' style="background:linear-gradient(135deg,#f97316,#c2410c);color:#fff;padding:6px 10px;font-size:10px;font-weight:800;border-radius:9px;border:none;cursor:pointer;letter-spacing:.06em;">'
        +   '<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">'
        +     '<path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>'
        +   '</svg>SCAN KTP'
        + '</button>'

        /* Loading */
        + '<div id="scanLoading_' + id + '" class="hidden mt-2 text-center" style="padding:4px 0;">'
        +   '<p style="font-size:10px;color:#f97316;font-weight:600;">Membaca KTP...</p>'
        +   '<div class="scan-loading-bar"><div class="scan-loading-bar-inner"></div></div>'
        + '</div>'

        /* KTP Data Card */
        + '<div id="ktpDataCard_' + id + '" class="ktp-data-card">'
        +   '<p class="ktp-edit-hint">✏ Klik untuk edit</p>'
        +   '<div id="ktpDataRows_' + id + '"></div>'
        + '</div>'

        + '</div>';

    container.insertAdjacentHTML('beforeend', html);
}

/* ── Scan Semua ──────────────────────────────────────────────── */
function scanAll() {
    /* Kumpulkan ID member yang punya file tapi belum di-scan */
    var toScan = members.filter(function (m) {
        return memberFiles[m.id] && !m.scanned;
    }).map(function (m) { return m.id; });

    if (toScan.length === 0) {
        toast('Semua KTP yang sudah diupload telah di-scan.', 'warn');
        return;
    }

    var btn = document.getElementById('scanAllBtn');
    var txt = document.getElementById('scanAllText');
    if (btn) { btn.disabled = true; }
    if (txt) txt.textContent = 'Scanning ' + toScan.length + ' KTP...';

    /* Scan berurutan pakai rekursi async */
    var idx = 0;
    function scanNext() {
        if (idx >= toScan.length) {
            if (txt) txt.textContent = 'SCAN SEMUA KTP';
            updateScanAllBtn();
            return;
        }
        var id = toScan[idx++];
        if (txt) txt.textContent = 'Scanning ' + idx + ' / ' + toScan.length + '...';
        scanWithCallback(id, function () {
            setTimeout(scanNext, 400);
        });
    }
    scanNext();
}

/* Scan dengan callback setelah selesai */
function scanWithCallback(id, cb) {
    var fileToScan = memberFiles[id];
    if (!fileToScan) { if (cb) cb(); return; }

    hide('scanBtn_' + id); show('scanLoading_' + id);
    resetCardUI(id);

    var fd   = new FormData();
    fd.append('image', fileToScan, fileToScan.name || 'ktp.jpg');
    var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

    fetch('/ocr/ktp', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: fd,
    })
    .then(function (r) {
        hide('scanLoading_' + id); show('scanBtn_' + id);
        return r.json().then(function (j) { return { ok: r.ok, j: j }; });
    })
    .then(function (res) {
        if (res.ok && res.j.success) {
            renderKtpCard(id, res.j.data);
            var m = getMember(id);
            if (m) { m.scanned = true; m.cityValid = res.j.data.city_valid; }
            var badge = document.getElementById('scan_badge_' + id);
            if (badge) badge.style.display = 'inline-flex';
            var mc = document.getElementById('mc_' + id);
            if (mc) {
                mc.classList.toggle('scanned', !!res.j.data.city_valid);
                mc.classList.toggle('city-invalid', !res.j.data.city_valid);
            }
            updateCounter();
        }
        if (cb) cb();
    })
    .catch(function () {
        hide('scanLoading_' + id); show('scanBtn_' + id);
        if (cb) cb();
    });
}

/* ── File handling ───────────────────────────────────────────── */
function showSheet(id) { _SHEET.open(id); }

function onFileSelect(input, id) {
    if (input.files && input.files[0]) processFile(input.files[0], id);
}

function onDrop(e, id) {
    e.preventDefault();
    document.getElementById('dz_' + id).classList.remove('drag-over');
    var file = e.dataTransfer && e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) processFile(file, id);
}

function processFile(file, id) {
    if (file.size > 10*1024*1024) { toast('File terlalu besar. Maks 10MB.', 'error'); return; }
    convertToJpeg(file, function (converted, previewUrl) {
        if (!converted) return;
        memberFiles[id] = converted;
        var m = getMember(id);
        if (m) { m.scanned = false; m.cityValid = null; }
        document.getElementById('prevImg_' + id).src = previewUrl;
        show('prev_' + id); hide('dzDefault_' + id); show('scanBtn_' + id);
        resetCardUI(id);
        updateScanAllBtn();
    });
}

function resetFile(e, id) {
    e.stopPropagation();
    memberFiles[id] = null;
    var m = getMember(id);
    if (m) { m.scanned = false; m.cityValid = null; }
    cardData[id] = {};
    ['fiCam_','fiFoto_','fiFile_'].forEach(function(p){ var el=document.getElementById(p+id); if(el) el.value=''; });
    hide('prev_'+id); show('dzDefault_'+id); hide('scanBtn_'+id); hide('scanLoading_'+id);
    resetCardUI(id);
    updateCounter();
    updateScanAllBtn();
}

function resetCardUI(id) {
    var card  = document.getElementById('ktpDataCard_' + id);
    var rows  = document.getElementById('ktpDataRows_' + id);
    var badge = document.getElementById('scan_badge_'  + id);
    var mc    = document.getElementById('mc_'          + id);
    if (card)  { card.className = 'ktp-data-card'; }
    if (rows)  rows.innerHTML = '';
    if (badge) badge.style.display = 'none';
    if (mc)    mc.classList.remove('scanned', 'city-invalid');
}

/* ── SCAN ────────────────────────────────────────────────────── */
function scan(id) {
    scanWithCallback(id, function () {
        var m = getMember(id);
        var mi = getIndexById(id);
        if (m) {
            toast(m.cityValid
                ? '✓ Anggota ' + (mi+1) + ' — KTP Balikpapan valid'
                : '⚠ Anggota ' + (mi+1) + ' — Kota bukan Balikpapan',
                m.cityValid ? 'success' : 'warn');
        }
    });
}

/* ── Render KTP Data Card ────────────────────────────────────── */
function renderKtpCard(id, data) {
    cardData[id] = {};
    var card = document.getElementById('ktpDataCard_' + id);
    var rows = document.getElementById('ktpDataRows_' + id);
    if (!card || !rows) return;
    rows.innerHTML = '';

    CARD_FIELDS.forEach(function (f) {
        var raw = f.k === 'tanggal_lahir' ? (data.tanggal_lahir||data.tgl_lahir||'') : (data[f.k]||'');
        var v   = ('' + raw).trim();
        cardData[id][f.k] = v;
        var valId = 'kval_'+id+'_'+f.k, inpId = 'kinp_'+id+'_'+f.k, hidId = 'khid_'+id+'_'+f.k;
        var valContent = v ? esc(v) : '<span style="color:#b4b2a9;font-style:italic">—</span>';
        rows.innerHTML +=
            '<div class="ktp-row">'
            + '<span class="ktp-label">' + f.l + ' <span style="color:#f97316">*</span></span>'
            + '<span id="' + valId + '" class="ktp-value" title="Klik edit" onclick="BEREGU.inlineEdit(' + id + ',\'' + f.k + '\')">' + valContent + '</span>'
            + '<input id="' + inpId + '" type="text" class="ktp-inline-input" style="display:none"'
            +        ' value="' + esc(v) + '" placeholder="' + esc(f.placeholder||'') + '"'
            +        ' onkeydown="BEREGU.inlineKey(event,' + id + ',\'' + f.k + '\')"'
            +        ' onblur="BEREGU.inlineSave(' + id + ',\'' + f.k + '\')">'
            + '<input type="hidden" id="' + hidId + '" name="' + f.n + '" value="' + esc(v) + '">'
            + '</div>';
    });

    rows.innerHTML += '<input type="hidden" id="khid_' + id + '_kota" name="kota_ktp[]" value="' + esc((data.kota||'').trim()) + '">';

    var tglVal = cardData[id]['tanggal_lahir']||'';
    var usia   = hitungUsia(tglVal);
    rows.innerHTML +=
        '<div class="ktp-row" style="margin-top:4px;padding-top:6px;border-top:1px solid rgba(249,115,22,.1);">'
        + '<span class="ktp-label" style="color:rgba(249,115,22,.6);">Usia</span>'
        + '<span id="usia_disp_' + id + '" class="' + (usia!==null?'usia-display has-value':'usia-display no-value') + '">'
        + (usia!==null ? usia+' th (Ags 2026)' : '—') + '</span>'
        + '<span style="font-size:8px;color:#b4b2a9;flex-shrink:0;">auto</span>'
        + '</div>';

    var kotaRaw = data.kota||'';
    rows.innerHTML += data.city_valid
        ? '<div class="city-badge valid">✓ KTP Balikpapan — Memenuhi Syarat</div>'
        : (kotaRaw
            ? '<div class="city-badge invalid">✗ "' + esc(kotaRaw) + '" — Bukan Balikpapan</div>'
            : '<div class="city-badge empty">— Kota belum terbaca</div>');

    card.className = 'ktp-data-card show valid-card';
}

/* ── Inline edit ─────────────────────────────────────────────── */
function inlineEdit(id, key) {
    var v = document.getElementById('kval_'+id+'_'+key);
    var i = document.getElementById('kinp_'+id+'_'+key);
    if (!v||!i) return;
    v.style.display='none'; i.style.display=''; i.focus(); if(i.select) i.select();
}
function inlineSave(id, key) {
    var v = document.getElementById('kval_'+id+'_'+key);
    var i = document.getElementById('kinp_'+id+'_'+key);
    var h = document.getElementById('khid_'+id+'_'+key);
    if (!v||!i) return;
    var nv = i.value.trim(), ov = (cardData[id]&&cardData[id][key])||'', edited = nv!==ov;
    if (h) h.value = nv;
    if (cardData[id]) cardData[id][key] = nv;
    v.innerHTML   = nv ? esc(nv) : '<span style="color:#b4b2a9;font-style:italic">—</span>';
    v.style.color = edited ? '#d97706' : '';
    v.title       = edited ? 'Diedit manual' : 'Klik untuk edit';
    if (edited && i.classList) i.classList.add('was-edited');
    i.style.display='none'; v.style.display='';
    if (key==='tanggal_lahir') updateUsiaRow(id, nv);
}
function inlineKey(e, id, key) {
    if (e.key==='Enter')  { e.preventDefault(); inlineSave(id, key); }
    if (e.key==='Escape') {
        var i=document.getElementById('kinp_'+id+'_'+key);
        var v=document.getElementById('kval_'+id+'_'+key);
        if(i)i.style.display='none'; if(v)v.style.display='';
    }
}

/* ── Counter + Submit Button State ──────────────────────────── */
function updateCounter() {
    var total      = members.length;
    var scanned    = members.filter(function (m) { return m.scanned; }).length;
    var validCount = members.filter(function (m) { return m.cityValid === true; }).length;
    var pct        = Math.round((validCount / MIN_KTP_VALID) * 100);
    if (pct > 100) pct = 100;

    var textEl = document.getElementById('counterText');
    var fillEl = document.getElementById('counterFill');
    var noteEl = document.getElementById('counterNote');
    if (textEl) textEl.textContent = validCount + ' / ' + total + ' anggota KTP valid';
    if (fillEl) {
        fillEl.style.width = pct + '%';
        fillEl.style.background = validCount >= MIN_KTP_VALID ? '#10b981' : '#f97316';
    }
    if (noteEl) {
        if (!scanned) {
            noteEl.textContent = 'Upload & scan KTP untuk melihat progress';
            noteEl.style.color = '#b4b2a9';
        } else if (validCount < MIN_KTP_VALID) {
            noteEl.textContent = 'Butuh ' + (MIN_KTP_VALID - validCount) + ' KTP Balikpapan lagi';
            noteEl.style.color = '#f97316';
        } else {
            noteEl.textContent = '✓ Syarat minimal terpenuhi — siap dikirim';
            noteEl.style.color = '#059669';
        }
    }

    /* Update submit button */
    var btn  = document.getElementById('submitBtn');
    var txt  = document.getElementById('submitBtnText');
    var hint = document.getElementById('submitHint');
    var need = document.getElementById('submitNeedCount');

    if (validCount >= MIN_KTP_VALID) {
        if (btn) { btn.disabled = false; btn.className = btn.className.replace('btn-disabled','').trim(); btn.classList.add('btn-ready'); }
        if (txt) txt.textContent = 'KIRIM PENDAFTARAN →';
        if (hint) hint.style.display = 'none';
    } else {
        if (btn) { btn.disabled = true; btn.classList.remove('btn-ready'); btn.classList.add('btn-disabled'); }
        var kurang = MIN_KTP_VALID - validCount;
        if (txt) txt.textContent = 'Scan minimal ' + MIN_KTP_VALID + ' KTP Balikpapan dulu';
        if (hint) hint.style.display = '';
        if (need) need.textContent = kurang;
    }

    updateScanAllBtn();
}

function updateScanAllBtn() {
    /* Aktif kalau ada file yang belum di-scan */
    var hasPending = members.some(function (m) {
        return memberFiles[m.id] && !m.scanned;
    });
    var btn = document.getElementById('scanAllBtn');
    var txt = document.getElementById('scanAllText');
    if (!btn) return;
    btn.disabled = !hasPending;
    if (txt && !btn.disabled) txt.textContent = 'SCAN SEMUA KTP';
}

/* ── Helpers ─────────────────────────────────────────────────── */
function getMember(id)    { return members.find(function (m) { return m.id === id; }); }
function getIndexById(id) { return members.findIndex(function (m) { return m.id === id; }); }
function show(elId) { var el=document.getElementById(elId); if(el) el.classList.remove('hidden'); }
function hide(elId) { var el=document.getElementById(elId); if(el) el.classList.add('hidden'); }
function esc(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

/* ── Toast ───────────────────────────────────────────────────── */
var _tt = null;
function showToast(msg, type) { toast(msg, type); }
function toast(msg, type) {
    var el = document.getElementById('_beregToast');
    if (!el) {
        el = document.createElement('div');
        el.id = '_beregToast';
        el.style.cssText = 'position:fixed;top:88px;right:20px;z-index:99999;max-width:360px;padding:10px 14px;border-radius:11px;font-size:11px;line-height:1.5;font-weight:600;box-shadow:0 8px 36px rgba(0,0,0,.15);transition:opacity .3s,transform .3s;pointer-events:none;';
        document.body.appendChild(el);
    }
    var styles = {
        success:'background:#f0fdf4;border:1px solid rgba(16,185,129,.3);color:#059669;',
        warn:'background:#fffbeb;border:1px solid rgba(234,179,8,.3);color:#d97706;',
        error:'background:#fef2f2;border:1px solid rgba(239,68,68,.3);color:#dc2626;'
    };
    el.style.cssText += (styles[type]||styles.error) + 'opacity:1;transform:translateY(0);';
    el.textContent = msg;
    if (_tt) clearTimeout(_tt);
    _tt = setTimeout(function () { el.style.opacity='0'; el.style.transform='translateY(-8px)'; }, 5000);
}

/* ── AJAX Error helpers ──────────────────────────────────────── */
function clearAllErrors() {
    document.querySelectorAll('.field-error-msg').forEach(function (e) { e.textContent=''; e.classList.remove('show'); });
    document.querySelectorAll('.input-field.field-error').forEach(function (e) { e.classList.remove('field-error'); });
    var banner = document.getElementById('ajaxErrorBanner');
    if (banner) banner.classList.remove('show');
}

function showErrorBanner(errors) {
    var banner = document.getElementById('ajaxErrorBanner'), list = document.getElementById('ajaxErrorList');
    if (!banner||!list) return;
    list.innerHTML = '';
    errors.forEach(function (m) { var li=document.createElement('li'); li.textContent=m; list.appendChild(li); });
    banner.classList.add('show');
    banner.scrollIntoView({behavior:'smooth',block:'start'});
}

function setSubmitLoading(loading) {
    var btn=document.getElementById('submitBtn'), txt=document.getElementById('submitBtnText');
    var sp=document.getElementById('submitBtnSpinner'), ov=document.getElementById('submitOverlay');
    if (loading) {
        if(btn) btn.disabled=true; if(txt) txt.textContent='Memproses...';
        if(sp) sp.classList.remove('hidden'); if(ov) ov.classList.add('show');
    } else {
        if(btn) btn.disabled=false; if(txt) txt.innerHTML='KIRIM PENDAFTARAN →';
        if(sp) sp.classList.add('hidden'); if(ov) ov.classList.remove('show');
    }
}

/* ── Expose ──────────────────────────────────────────────────── */
window.BEREGU = {
    addMember: addMember, remove: removeMember,
    onFileSelect: onFileSelect, onDrop: onDrop, resetFile: resetFile,
    scan: scan, scanAll: scanAll, showSheet: showSheet,
    inlineEdit: inlineEdit, inlineSave: inlineSave, inlineKey: inlineKey,
};

/* ── Bootstrap ───────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    init();
    var form = document.getElementById('regForm');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearAllErrors();

        var validKtp = members.filter(function (m) { return m.cityValid === true; }).length;
        if (validKtp < MIN_KTP_VALID) {
            showErrorBanner(['Minimal ' + MIN_KTP_VALID + ' KTP Balikpapan diperlukan. Saat ini: ' + validKtp + '.']);
            return;
        }

        var clientErrors = [];
        members.forEach(function (m, i) {
            CARD_FIELDS.forEach(function (f) {
                var h = document.getElementById('khid_'+m.id+'_'+f.k);
                if (h && !h.value.trim()) {
                    clientErrors.push('Anggota ' + (i+1) + ': ' + f.l + ' wajib diisi');
                    var mc = document.getElementById('mc_'+m.id);
                    if (mc) mc.classList.add('city-invalid');
                }
            });
        });
        if (clientErrors.length > 0) { showErrorBanner(clientErrors); return; }

        var fd = new FormData();
        form.querySelectorAll('input:not([type="file"]), select, textarea').forEach(function (inp) {
            if (!inp.name) return;
            fd.append(inp.name, inp.value);
        });
        members.forEach(function (m, i) {
            var f = memberFiles[m.id];
            if (!f) {
                ['fiCam_','fiFoto_','fiFile_'].some(function (p) {
                    var el = document.getElementById(p + m.id);
                    if (el && el.files && el.files[0]) { f = el.files[0]; return true; }
                });
            }
            if (f) fd.append('ktp_files[]', f, f.name || ('ktp-anggota-'+(i+1)+'.jpg'));
        });

        setSubmitLoading(true);
        var csrf = (document.querySelector('meta[name="csrf-token"]')||{}).content||'';

        try {
            var response = await fetch("{{ route('registration.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN':csrf, 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' },
                body: fd,
            });
            var data = await response.json();

            if (response.status === 422) {
                setSubmitLoading(false);
                var allMessages = [];
                Object.keys(data.errors||{}).forEach(function (field) {
                    var msgs = data.errors[field];
                    if (!Array.isArray(msgs)) msgs = [msgs];
                    allMessages = allMessages.concat(msgs);
                    var errEl = document.getElementById('err_' + field.replace(/\.\d+$/,''));
                    if (errEl) { errEl.textContent=msgs[0]; errEl.classList.add('show'); }
                    var inp = document.getElementById('field_' + field.replace(/\.\d+$/,''));
                    if (inp) inp.classList.add('field-error');
                });
                showErrorBanner(allMessages);
                return;
            }

            if (!response.ok) {
                setSubmitLoading(false);
                showErrorBanner([(data&&data.message)||'Terjadi kesalahan server. Coba lagi.']);
                return;
            }

            var ovText = document.getElementById('submitOverlayText');
            if (ovText) ovText.textContent = 'Pendaftaran berhasil! Menunggu verifikasi admin...';
            window.location.href = data.redirect || '/';

        } catch (err) {
            setSubmitLoading(false);
            showErrorBanner(['Koneksi gagal. Periksa internet dan coba lagi.']);
        }
    });
});

})();
</script>
@endpush

@endsection