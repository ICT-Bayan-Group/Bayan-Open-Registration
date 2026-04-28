@extends('layouts.app')

@section('title', 'Pendaftaran Ganda Veteran Putra — Bayan Open 2026')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════════
   ANIMATIONS
══════════════════════════════════════════════════════════════ */
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
@keyframes shake {
    0%,100% { transform: translateX(0); }
    20%     { transform: translateX(-6px); }
    40%     { transform: translateX(6px); }
    60%     { transform: translateX(-4px); }
    80%     { transform: translateX(4px); }
}
@keyframes backdropIn  { from { opacity:0; } to { opacity:1; } }
@keyframes backdropOut { from { opacity:1; } to { opacity:0; } }

.form-section                 { animation: fadeSlideUp .45s ease both; }
.form-section:nth-child(1)    { animation-delay: .06s; }
.form-section:nth-child(2)    { animation-delay: .12s; }
.form-section:nth-child(3)    { animation-delay: .18s; }
.form-section:nth-child(4)    { animation-delay: .24s; }
.form-section:nth-child(5)    { animation-delay: .30s; }

/* ── Age badge ─────────────────────────────────────────────── */
.age-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 99px;
    font-size: 11px; font-weight: 700; border: 1px solid;
    transition: background .25s, border-color .25s, color .25s;
}
.age-badge.pending { background: rgba(255,255,255,.04); border-color: rgba(255,255,255,.1);  color: rgba(255,255,255,.28); }
.age-badge.valid   { background: rgba(16,185,129,.1);  border-color: rgba(16,185,129,.38);  color: #34d399; }
.age-badge.invalid { background: rgba(239,68,68,.1);   border-color: rgba(239,68,68,.38);   color: #f87171; }

/* ── OCR Card ──────────────────────────────────────────────── */
.pemain-ocr-card {
    border-radius: 18px;
    border: 1.5px solid rgba(234,179,8,.18);
    background: rgba(20,16,4,.78);
    padding: 22px;
    transition: border-color .3s, background .3s, box-shadow .3s;
}
.pemain-ocr-card.scanned {
    border-color: rgba(16,185,129,.42);
    background: rgba(4,20,12,.78);
    box-shadow: 0 0 0 1px rgba(16,185,129,.09) inset;
}
.pemain-ocr-card.invalid-age {
    border-color: rgba(239,68,68,.42);
    background: rgba(20,4,4,.78);
}
/* FIX: has-field-error state untuk highlight card yang belum lengkap */
.pemain-ocr-card.has-field-error {
    border-color: rgba(239,68,68,.5) !important;
    box-shadow: 0 0 0 2px rgba(239,68,68,.1) !important;
    animation: shake .4s ease;
}

/* ── KTP Data Card ─────────────────────────────────────────── */
.ktp-data-card {
    border-radius: 13px;
    background: rgba(255,255,255,.025);
    border: 1px solid rgba(255,255,255,.07);
    padding: 14px 16px; margin-top: 14px; display: none;
}
.ktp-data-card.show         { display: block; animation: fadeSlideUp .3s ease both; }
.ktp-data-card.valid-card   { background: rgba(16,185,129,.04); border-color: rgba(16,185,129,.2); }
.ktp-data-card.invalid-card { background: rgba(239,68,68,.04);  border-color: rgba(239,68,68,.2); }

/* ── KTP Row ───────────────────────────────────────────────── */
.ktp-row {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 5px 0; border-bottom: 1px solid rgba(255,255,255,.04); min-height: 30px;
}
.ktp-row:last-child { border-bottom: none; padding-bottom: 0; }
.ktp-label {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .06em; color: rgba(255,255,255,.28);
    min-width: 82px; flex-shrink: 0; padding-top: 1px;
}
.ktp-value           { font-size: 12px; color: rgba(255,255,255,.72); line-height: 1.4; word-break: break-word; }
.ktp-value.highlight { color: #fff; font-weight: 600; }

.ktp-locked-note {
    display: flex; align-items: center; gap: 6px;
    margin-top: 10px; padding-top: 10px;
    border-top: 1px solid rgba(255,255,255,.05);
    font-size: 10px; color: rgba(255,255,255,.18); font-style: italic;
}

/* ── Scan loading bar ──────────────────────────────────────── */
.scan-loading-bar {
    height: 3px; border-radius: 99px; overflow: hidden;
    background: rgba(234,179,8,.1); margin-top: 10px;
}
.scan-loading-bar-inner {
    height: 100%; width: 40%;
    background: linear-gradient(90deg, transparent, #eab308, transparent);
    background-size: 200% 100%;
    animation: shimmerScan 1.2s ease infinite;
}

/* ── Veteran summary ───────────────────────────────────────── */
.veteran-summary {
    border-radius: 14px; padding: 14px 16px; margin-top: 20px; display: none;
}
.veteran-summary.show { display: flex; align-items: flex-start; gap: 12px; animation: fadeSlideUp .3s ease both; }
.veteran-summary.ok   { background: rgba(16,185,129,.07); border: 1px solid rgba(16,185,129,.28); }
.veteran-summary.bad  { background: rgba(239,68,68,.07);  border: 1px solid rgba(239,68,68,.28); }

.total-usia-box {
    border-radius: 10px; padding: 10px 14px;
    margin-top: 14px; display: none; border: 1px solid;
}
.total-usia-box.show { display: block; animation: fadeSlideUp .25s ease both; }
.total-usia-box.ok   { background: rgba(16,185,129,.05); border-color: rgba(16,185,129,.22); }
.total-usia-box.bad  { background: rgba(239,68,68,.05);  border-color: rgba(239,68,68,.22); }

.submit-warning {
    display: none; border-radius: 12px; padding: 12px 16px; margin-top: 12px;
    font-size: 12px; font-weight: 600; color: #fbbf24;
    background: rgba(234,179,8,.07); border: 1px solid rgba(234,179,8,.2);
}

/* ── Upload Bottom Sheet ───────────────────────────────────── */
.upload-sheet-backdrop {
    display: none; position: fixed; inset: 0; z-index: 88888;
    background: rgba(0,0,0,.55);
    backdrop-filter: blur(3px); -webkit-backdrop-filter: blur(3px);
}
.upload-sheet-backdrop.show   { display: block; animation: backdropIn .2s ease both; }
.upload-sheet-backdrop.hiding { animation: backdropOut .2s ease both; }

.upload-sheet {
    display: none; position: fixed; bottom: 0; left: 0; right: 0; z-index: 88889;
    background: rgba(18,9,2,.98);
    border-top: 1.5px solid rgba(234,179,8,.25);
    border-radius: 22px 22px 0 0;
    padding: 0 0 calc(env(safe-area-inset-bottom, 0px) + 16px);
    box-shadow: 0 -20px 60px rgba(0,0,0,.6);
    max-width: 540px; margin: 0 auto;
}
.upload-sheet.show   { display: block; animation: sheetIn .28s cubic-bezier(.34,1.3,.64,1) both; }
.upload-sheet.hiding { animation: sheetOut .2s ease both; }

.upload-sheet-handle {
    width: 40px; height: 4px; border-radius: 99px;
    background: rgba(255,255,255,.18); margin: 12px auto 18px;
}
.upload-sheet-title {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .1em; color: rgba(255,255,255,.3);
    text-align: center; margin-bottom: 16px;
}
.upload-sheet-options {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 10px; padding: 0 16px;
}
.upload-opt-btn {
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; gap: 8px; padding: 16px 8px;
    border-radius: 16px; border: 1.5px solid; cursor: pointer;
    font-size: 11px; font-weight: 700; line-height: 1.3; text-align: center;
    transition: background .15s, border-color .15s, transform .1s;
    -webkit-tap-highlight-color: transparent;
}
.upload-opt-btn:active { transform: scale(.95); }
.upload-opt-icon {
    width: 44px; height: 44px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.upload-opt-btn.opt-camera {
    color: #eab308; background: rgba(234,179,8,.10); border-color: rgba(234,179,8,.35);
}
.upload-opt-btn.opt-camera:hover  { background: rgba(234,179,8,.18); border-color: rgba(234,179,8,.6); }
.upload-opt-btn.opt-camera .upload-opt-icon { background: rgba(234,179,8,.15); }
.upload-opt-btn.opt-foto {
    color: #facc15; background: rgba(234,179,8,.07); border-color: rgba(234,179,8,.28);
}
.upload-opt-btn.opt-foto:hover  { background: rgba(234,179,8,.14); border-color: rgba(234,179,8,.5); }
.upload-opt-btn.opt-foto .upload-opt-icon { background: rgba(234,179,8,.12); }
.upload-opt-btn.opt-file {
    color: #fde68a; background: rgba(234,179,8,.05); border-color: rgba(234,179,8,.20);
}
.upload-opt-btn.opt-file:hover  { background: rgba(234,179,8,.12); border-color: rgba(234,179,8,.4); }
.upload-opt-btn.opt-file .upload-opt-icon { background: rgba(234,179,8,.10); }

.upload-sheet-cancel {
    display: block; width: calc(100% - 32px); margin: 14px 16px 0;
    padding: 13px; border-radius: 14px;
    border: 1px solid rgba(255,255,255,.1); background: rgba(255,255,255,.04);
    color: rgba(255,255,255,.45); font-size: 13px; font-weight: 700;
    text-align: center; cursor: pointer; transition: background .15s, color .15s;
}
.upload-sheet-cancel:hover { background: rgba(255,255,255,.08); color: rgba(255,255,255,.7); }

/* ── Submit overlay ────────────────────────────────────────── */
#submitOverlay {
    display: none; position: fixed; inset: 0; z-index: 99998;
    background: rgba(0,0,0,.65); backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    align-items: center; justify-content: center;
    flex-direction: column; gap: 20px;
}
#submitOverlay.show { display: flex; animation: modalBackdropIn .2s ease both; }
.submit-spinner {
    width: 52px; height: 52px;
    border: 3px solid rgba(234,179,8,.2);
    border-top-color: #eab308;
    border-radius: 50%;
    animation: spinLoader .8s linear infinite;
}
.submit-progress-bar {
    width: 240px; height: 4px; background: rgba(255,255,255,.1);
    border-radius: 99px; overflow: hidden;
}
.submit-progress-inner {
    height: 100%;
    background: linear-gradient(90deg, #eab308, #facc15);
    border-radius: 99px;
    animation: progressBar 3s ease forwards;
}

/* ── Ajax Error Banner ─────────────────────────────────────── */
#ajaxErrorBanner {
    display: none; border-radius: 16px; padding: 16px 20px; margin-bottom: 20px;
    background: rgba(239,68,68,.08); border: 1.5px solid rgba(239,68,68,.3);
    animation: fadeSlideUp .3s ease both;
}
#ajaxErrorBanner.show { display: block; }

/* ── Select dark ───────────────────────────────────────────── */
select.input-field {
    color: rgba(255,255,255,.85) !important;
    background-color: #0d1117 !important;
    cursor: pointer;
}
select.input-field option          { background-color: #0d1117; color: rgba(255,255,255,.85); }
select.input-field option:disabled { color: rgba(255,255,255,.3); }
select.input-field:disabled        { opacity: .4 !important; cursor: not-allowed; }

/* ── Field error ───────────────────────────────────────────── */
.input-field.field-error {
    border-color: rgba(239,68,68,.6) !important;
    box-shadow: 0 0 0 2px rgba(239,68,68,.1);
}
.field-error-msg {
    color: #f87171; font-size: 11px; margin-top: 4px; display: none;
}
.field-error-msg.show { display: block; animation: fadeSlideUp .2s ease both; }

/* ── Regulasi box ──────────────────────────────────────────── */
.regulasi-box {
    border-radius: 12px; padding: 12px 16px;
    background: rgba(234,179,8,.05);
    border: 1px solid rgba(234,179,8,.14);
    margin-top: 12px;
}

/* ── Toast ─────────────────────────────────────────────────── */
@keyframes toastIn {
    from { opacity: 0; transform: translateY(-12px) scale(.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
</style>
@endpush

@section('content')
<section class="min-h-screen py-20 px-6">
<div class="max-w-2xl mx-auto">

    {{-- ── HEADER ─────────────────────────────────────────────── --}}
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
             style="background:rgba(234,179,8,.1);border:1px solid rgba(234,179,8,.3);">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(250,204,21,1)" stroke-width="2">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.86L12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            <span class="text-yellow-400 text-xs font-bold uppercase tracking-widest">Ganda Veteran Putra</span>
        </div>

        <div class="regulasi-box max-w-md mx-auto text-left mt-3">
            <p class="text-yellow-400/85 text-xs font-bold mb-2 flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Regulasi Usia Veteran
            </p>
            <ul class="text-white/40 text-xs space-y-1 leading-relaxed list-none">
                <li>&#9654; Setiap pemain <strong class="text-white/60">wajib berusia &ge; 45 tahun</strong></li>
                <li>&#9654; <strong class="text-white/60">Total usia</strong> kedua pemain <strong class="text-white/60">wajib &ge; 95 tahun</strong></li>
                <li>&#9654; Verifikasi otomatis via scan KTP</li>
            </ul>
        </div>

        <p class="text-white/38 text-sm mt-4">Isi semua data dengan benar dan lengkap</p>
    </div>

    {{-- ── AJAX ERROR BANNER ──────────────────────────────────── --}}
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

    <form id="regForm" novalidate>
    @csrf
    <input type="hidden" name="kategori" value="ganda-veteran-putra">

    {{-- ════════════════════ SECTION 1 — DATA TIM ════════════════ --}}
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

    {{-- ══════════════════ SECTION 2 — DATA PELATIH ══════════════ --}}
    <div class="card-glass rounded-2xl p-8 mb-6 form-section">
        <h2 class="font-display text-sm font-bold mb-6 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-xs font-black">2</span>
            Data Pelatih
            <span class="text-white/28 text-xs font-normal normal-case ml-1">(opsional)</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">Nama Pelatih</label>
                <input type="text" name="nama_pelatih"
                    placeholder="Nama lengkap pelatih"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-white/60 text-xs font-semibold uppercase tracking-wide mb-2">No. HP Pelatih</label>
                <input type="text" name="no_hp_pelatih"
                    placeholder="Contoh: 08123456789"
                    class="input-field w-full px-4 py-3 rounded-xl text-sm">
            </div>
        </div>
    </div>

    {{-- ══════════ SECTION 3 — SCAN KTP & VERIFIKASI USIA ═════════ --}}
    <div class="rounded-2xl p-8 mb-6 form-section"
         style="background:rgba(234,179,8,.035);border:1.5px solid rgba(234,179,8,.16);">

        <h2 class="font-display text-sm font-bold mb-1 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black"
                  style="background:rgba(234,179,8,.9);color:#000;">3</span>
            Scan KTP &amp; Verifikasi Usia
        </h2>
        <p class="text-white/30 text-xs mb-1 ml-9">
            Upload foto KTP lalu klik <strong class="text-yellow-400/75">SCAN KTP</strong> — verifikasi usia otomatis.
        </p>
        <p class="text-white/18 text-xs mb-7 ml-9">
            Data KTP dikunci setelah scan. Jika ada kesalahan, reset dan scan ulang.
        </p>

        @foreach([0,1] as $idx)
        <div id="ocr_card_{{ $idx }}" class="pemain-ocr-card {{ $idx === 1 ? 'mt-5' : '' }}">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                         style="background:rgba(234,179,8,.14);border:1px solid rgba(234,179,8,.3);">
                        <span class="text-yellow-400 text-xs font-black">{{ $idx + 1 }}</span>
                    </div>
                    <span class="text-white/80 text-sm font-bold">Pemain {{ $idx + 1 }}</span>
                </div>
                <div id="age_badge_{{ $idx }}" class="age-badge pending">Belum scan</div>
            </div>

            <div class="mb-4">
                <label class="block text-white/45 text-xs font-semibold uppercase tracking-wide mb-2">
                    Foto KTP <span class="text-brand-400">*</span>
                    <span class="text-white/22 font-normal normal-case">
                        &mdash; JPG · PNG · HEIC · WebP &middot; Maks 10MB
                    </span>
                </label>

                {{-- Dropzone --}}
                <div id="ktpDropzone_{{ $idx }}"
                    onclick="VET.showSheet({{ $idx }})"
                    class="border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all"
                    style="border-color:rgba(234,179,8,.22);background:rgba(234,179,8,.018);"
                    ondragover="event.preventDefault();this.style.borderColor='rgba(234,179,8,.6)'"
                    ondragleave="this.style.borderColor='rgba(234,179,8,.22)'"
                    ondrop="VET.drop(event,{{ $idx }})">

                    {{-- Preview --}}
                    <div id="ktpPreview_{{ $idx }}" class="hidden">
                        <div class="relative inline-block mb-2">
                            <img id="ktpPreviewImg_{{ $idx }}" src="" alt=""
                                 class="max-h-32 mx-auto rounded-lg object-contain"
                                 style="box-shadow:0 4px 20px rgba(0,0,0,.55);">
                            <button type="button" onclick="VET.resetSlot(event,{{ $idx }})"
                                    class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 flex items-center justify-center transition">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <p class="text-white/28 text-xs">Ketuk untuk ganti foto</p>
                    </div>

                    {{-- Default placeholder --}}
                    <div id="ktpDefault_{{ $idx }}" class="flex flex-col items-center py-3">
                        <div class="w-11 h-11 rounded-xl bg-yellow-500/10 flex items-center justify-center mb-3">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                 stroke="rgba(250,204,21,.55)" stroke-width="1.5">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <path d="M7 9h10M7 13h6"/>
                            </svg>
                        </div>
                        <p class="text-white/50 text-sm font-medium">Ketuk untuk upload KTP</p>
                        <p class="text-white/22 text-xs mt-0.5">Kamera · Galeri · File Manager &middot; Maks 10MB</p>
                    </div>
                </div>

                {{-- 3 hidden file inputs --}}
                <input type="file" id="ktpCamera_{{ $idx }}"
                       accept="image/*" capture="environment" class="hidden"
                       onchange="VET.fileSelect(this,{{ $idx }})">
                <input type="file" id="ktpFoto_{{ $idx }}"
                       accept="image/*" class="hidden" name="ktp_files[]"
                       onchange="VET.fileSelect(this,{{ $idx }})">
                <input type="file" id="ktpFile_{{ $idx }}"
                       accept="image/*,.heic,.heif" class="hidden"
                       onchange="VET.fileSelect(this,{{ $idx }})">

                {{-- Scan button --}}
                <button type="button" id="scanBtn_{{ $idx }}" onclick="VET.scan({{ $idx }})"
                    class="hidden mt-3 w-full py-2.5 rounded-xl font-display text-xs font-bold text-white tracking-wider flex items-center justify-center gap-2"
                    style="background:linear-gradient(135deg,#eab308,#b45309);box-shadow:0 4px 16px rgba(234,179,8,.22);">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                    </svg>
                    SCAN KTP &amp; VERIFIKASI USIA
                </button>

                {{-- Scan loading --}}
                <div id="scanLoading_{{ $idx }}" class="hidden mt-3 text-center py-2">
                    <p class="text-yellow-400 text-xs font-semibold mb-1">Membaca KTP dengan AI...</p>
                    <div class="scan-loading-bar"><div class="scan-loading-bar-inner"></div></div>
                </div>
            </div>

            {{-- KTP data card (read-only setelah scan) --}}
            <div id="ktpDataCard_{{ $idx }}" class="ktp-data-card">
                <p class="text-xs font-bold text-white/35 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                        <path d="M7 9h10M7 13h6"/>
                    </svg>
                    Data KTP Terbaca
                </p>
                <div id="ktpDataRows_{{ $idx }}"></div>
            </div>

            <p id="tgl_info_{{ $idx }}" class="hidden text-xs mt-2 font-medium"></p>

            {{-- Hidden fields — selalu ada di DOM sejak render Blade --}}
            <input type="hidden" name="pemain[]"      id="pemain_{{ $idx }}"      value="">
            <input type="hidden" name="nik[]"         id="nik_{{ $idx }}"         value="">
            <input type="hidden" name="tgl_lahir[]"   id="tgl_lahir_{{ $idx }}"   value="">
            <input type="hidden" name="usia_valid[]"  id="usia_valid_{{ $idx }}"  value="0">
            <input type="hidden" name="usia_hitung[]" id="usia_hitung_{{ $idx }}" value="">
        </div>
        @endforeach

        <div id="totalUsiaBox" class="total-usia-box">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/45">Total Usia 2 Pemain</span>
                <span id="totalUsiaValue" class="text-sm font-bold text-white/40">—</span>
            </div>
        </div>

        <div id="veteranSummary" class="veteran-summary">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                 id="summaryIcon"></div>
            <div>
                <p id="summaryTitle"  class="text-xs font-bold"></p>
                <p id="summaryDetail" class="text-xs mt-0.5 opacity-60"></p>
            </div>
        </div>

        <p class="field-error-msg" id="err_usia_hitung"></p>
        <p class="field-error-msg" id="err_usia_valid"></p>
    </div>

    {{-- ════════════════════ SECTION 4 — BIAYA ═══════════════════ --}}
    <div class="card-glass rounded-2xl p-6 mb-6 form-section">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-white/40 text-xs mb-1">Kategori</p>
                <p class="font-display text-white font-bold text-sm">Ganda Veteran Putra</p>
            </div>
            <div class="text-right">
                <p class="text-white/40 text-xs mb-1">Total Pembayaran</p>
                <p class="font-display text-brand-400 font-bold text-2xl">Rp 400.000</p>
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

    <div id="submitWarning" class="submit-warning">
        &#9888; Harap scan KTP kedua pemain terlebih dahulu untuk verifikasi usia.
    </div>

    <p class="text-white/25 text-xs text-center mt-4">
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
                     stroke="#eab308" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
            </div>
            <span>Foto<br>Kamera</span>
        </button>
        <button type="button" class="upload-opt-btn opt-foto" onclick="_SHEET.pick('foto')">
            <div class="upload-opt-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="#facc15" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
                     stroke="#fde68a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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

@push('scripts')
<script>
/* ================================================================
   WILAYAH CASCADE
================================================================ */
(function () {
'use strict';
async function loadProvinsi() {
    var sel  = document.getElementById('selectProvinsi');
    var spin = document.getElementById('loadingProvinsi');
    if (!sel) return;
    spin && spin.classList.remove('hidden');
    try {
        var data = await (await fetch('/wilayah/provinces')).json();
        data.forEach(function (p) {
            var opt = new Option(p.name || p.nama, p.name || p.nama);
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
    var opt  = sel.options[sel.selectedIndex];
    var code = opt ? (opt.dataset.code || '') : '';
    var kotaSel = document.getElementById('selectKota');
    if (kotaSel) {
        kotaSel.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        kotaSel.disabled  = true;
        kotaSel.classList.add('opacity-40');
    }
    if (code) await loadKota(code);
}
async function loadKota(provId) {
    var sel  = document.getElementById('selectKota');
    var spin = document.getElementById('loadingKota');
    if (!sel) return;
    sel.disabled = true; sel.classList.add('opacity-40');
    sel.innerHTML = '<option value="">Memuat...</option>';
    spin && spin.classList.remove('hidden');
    try {
        var data = await (await fetch('/wilayah/regencies/' + encodeURIComponent(provId))).json();
        sel.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
        data.forEach(function (k) {
            var label = k.name || k.nama;
            sel.appendChild(new Option(label, label));
        });
        sel.disabled = false; sel.classList.remove('opacity-40');
    } catch (e) {
        sel.innerHTML = '<option value="">Gagal memuat</option>';
        sel.disabled  = false; sel.classList.remove('opacity-40');
    } finally {
        spin && spin.classList.add('hidden');
    }
}
window.WILAYAH = { onProvinsiChange: onProvinsiChange };
document.addEventListener('DOMContentLoaded', loadProvinsi);
})();
</script>

<script>
/* ================================================================
   _SHEET — Upload Bottom Sheet
================================================================ */
window._SHEET = (function () {
'use strict';
var _activeIdx = null, _isAnimating = false;

function open(idx) {
    if (_isAnimating) return;
    _activeIdx = idx;
    var bd = document.getElementById('uploadSheetBackdrop');
    var sh = document.getElementById('uploadSheet');
    if (!bd || !sh) return;
    bd.classList.remove('hiding'); sh.classList.remove('hiding');
    bd.classList.add('show');     sh.classList.add('show');
    document.body.style.overflow = 'hidden';
}
function close() {
    if (_isAnimating) return;
    var bd = document.getElementById('uploadSheetBackdrop');
    var sh = document.getElementById('uploadSheet');
    if (!bd || !sh) return;
    _isAnimating = true;
    bd.classList.add('hiding'); sh.classList.add('hiding');
    setTimeout(function () {
        bd.classList.remove('show','hiding');
        sh.classList.remove('show','hiding');
        document.body.style.overflow = '';
        _isAnimating = false;
    }, 210);
}
function pick(type) {
    var idx = _activeIdx; close();
    setTimeout(function () {
        var map = { camera:'ktpCamera_', foto:'ktpFoto_', file:'ktpFile_' };
        var el  = document.getElementById((map[type] || 'ktpFoto_') + idx);
        if (el) el.click();
    }, 230);
}

var _ty0 = 0;
document.addEventListener('touchstart', function (e) {
    if (document.getElementById('uploadSheet').classList.contains('show'))
        _ty0 = e.touches[0].clientY;
}, { passive:true });
document.addEventListener('touchend', function (e) {
    if (!document.getElementById('uploadSheet').classList.contains('show')) return;
    if (e.changedTouches[0].clientY - _ty0 > 70) close();
}, { passive:true });
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

return { open:open, close:close, pick:pick };
})();
</script>

<script>
/* ================================================================
   VET — Veteran OCR + AJAX Submit
   FIX LIST:
   1. ktpFiles object di-expose via window.VET.ktpFiles (getter)
      → buildFormData tidak lagi crash "slotState is not defined"
   2. scanStatus di-expose via window.VET.scanStatus
   3. Client validation tampilkan error banner + highlight card merah
   4. Redirect ke pending-payment (bukan langsung ke Midtrans)
   5. Validasi form dasar (nama, email, dll) sebelum cek KTP
================================================================ */
(function () {
'use strict';

var MIN_AGE_EACH  = 45;
var MIN_AGE_TOTAL = 95;

/* ── State — expose via VET getter ────────────────────────── */
var ktpFiles   = { 0: null, 1: null };   /* file hasil konversi JPEG */
var scanStatus = [false, false];          /* sudah scan + valid tgl lahir */
var usiaArr    = [null, null];

/* ── Konversi HEIC/besar → JPEG via Canvas ─────────────────── */
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

/* ── Hitung usia dari string tanggal ───────────────────────── */
function hitungUsia(str) {
    if (!str) return null;
    var tgl = null;
    var m1  = str.match(/^(\d{1,2})[-\/\.](\d{1,2})[-\/\.](\d{4})$/);
    var m2  = str.match(/^(\d{4})[-\/\.](\d{1,2})[-\/\.](\d{1,2})$/);
    if (m1)      tgl = new Date(+m1[3], +m1[2]-1, +m1[1]);
    else if (m2) tgl = new Date(+m2[1], +m2[2]-1, +m2[3]);
    else         tgl = new Date(str);
    if (!tgl || isNaN(tgl.getTime())) return null;
    var usia = new Date().getFullYear() - tgl.getFullYear();
    if (usia < 0 || usia > 120) return null;
    return { usia: usia, tgl: tgl };
}

/* ── File select / drop ─────────────────────────────────────── */
function fileSelect(input, idx) {
    if (input.files && input.files[0]) processFile(input.files[0], idx);
}

function drop(e, idx) {
    e.preventDefault();
    var dz = document.getElementById('ktpDropzone_' + idx);
    if (dz) dz.style.borderColor = 'rgba(234,179,8,.22)';
    var file = e.dataTransfer && e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) processFile(file, idx);
}

function processFile(file, idx) {
    if (file.size > 10 * 1024 * 1024) {
        showToast('File terlalu besar. Maks 10MB.', 'error');
        return;
    }
    convertToJpeg(file, function (converted, previewUrl) {
        if (!converted) return;
        ktpFiles[idx]   = converted;
        scanStatus[idx] = false;
        usiaArr[idx]    = null;

        var imgEl = document.getElementById('ktpPreviewImg_' + idx);
        if (imgEl) imgEl.src = previewUrl;

        toggleEl('ktpPreview_'  + idx, true);
        toggleEl('ktpDefault_'  + idx, false);
        toggleEl('scanBtn_'     + idx, true);
        resetCardUI(idx);

        /* Hapus highlight error jika sebelumnya ada */
        var card = document.getElementById('ocr_card_' + idx);
        if (card) card.classList.remove('has-field-error');
    });
}

function resetSlot(e, idx) {
    e.stopPropagation();
    ktpFiles[idx]   = null;
    scanStatus[idx] = false;
    usiaArr[idx]    = null;

    ['ktpCamera_','ktpFoto_','ktpFile_'].forEach(function (p) {
        var el = document.getElementById(p + idx);
        if (el) el.value = '';
    });

    toggleEl('ktpPreview_'  + idx, false);
    toggleEl('ktpDefault_'  + idx, true);
    toggleEl('scanBtn_'     + idx, false);
    toggleEl('scanLoading_' + idx, false);

    setHid('pemain_'     + idx, '');
    setHid('nik_'        + idx, '');
    setHid('tgl_lahir_'  + idx, '');
    setHid('usia_valid_' + idx, '0');
    setHid('usia_hitung_'+ idx, '');

    resetCardUI(idx);
    updateAgeBadge(idx, null);
    clearSummary();
}

function resetCardUI(idx) {
    var card    = document.getElementById('ktpDataCard_' + idx);
    var rows    = document.getElementById('ktpDataRows_' + idx);
    var ocrCard = document.getElementById('ocr_card_'    + idx);
    var infoEl  = document.getElementById('tgl_info_'   + idx);
    if (card)    card.className = 'ktp-data-card';
    if (rows)    rows.innerHTML = '';
    if (ocrCard) ocrCard.classList.remove('scanned','invalid-age');
    if (infoEl)  { infoEl.textContent = ''; infoEl.classList.add('hidden'); }
}

/* ── SCAN OCR ─────────────────────────────────────────────── */
function scan(idx) {
    var fileToScan = ktpFiles[idx];
    if (!fileToScan) {
        showToast('Upload foto KTP dulu sebelum scan.', 'warn');
        return;
    }

    toggleEl('scanBtn_'     + idx, false);
    toggleEl('scanLoading_' + idx, true);
    resetCardUI(idx);

    var fd   = new FormData();
    fd.append('image', fileToScan, fileToScan.name || 'ktp.jpg');
    var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

    fetch('/ocr/ktp', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: fd,
    })
    .then(function (resp) {
        toggleEl('scanLoading_' + idx, false);
        toggleEl('scanBtn_'     + idx, true);
        return resp.json().then(function (j) { return { ok: resp.ok, j: j }; });
    })
    .then(function (res) {
        if (!res.ok || !res.j.success) {
            showToast(res.j.message || 'Gagal membaca KTP. Foto lebih jelas.', 'error');
            return;
        }
        var data     = res.j.data;
        var tglLahir = ((data.tanggal_lahir || data.tgl_lahir || '') + '').trim();

        if (!tglLahir) {
            showToast('KTP terbaca tapi tgl lahir tidak terdeteksi. Foto lebih jelas.', 'warn');
            return;
        }

        setHid('pemain_'   + idx, data.nama  || '');
        setHid('nik_'      + idx, data.nik   || '');
        setHid('tgl_lahir_'+ idx, tglLahir);
        updateAgeBadge(idx, tglLahir);

        var usia  = parseInt(document.getElementById('usia_hitung_' + idx).value, 10) || 0;
        var valid = document.getElementById('usia_valid_' + idx).value === '1';

        scanStatus[idx] = true;
        toggleEl('scanBtn_' + idx, false);
        renderCard(idx, data, usia, valid);

        /* Hapus highlight error setelah scan berhasil */
        var card = document.getElementById('ocr_card_' + idx);
        if (card) card.classList.remove('has-field-error');

        if (valid) {
            showToast('✅ Pemain ' + (idx+1) + ' — ' + (data.nama||'') + ' · ' + usia + ' thn · Memenuhi syarat!', 'success');
        } else {
            showToast('⚠ Pemain ' + (idx+1) + ' — ' + usia + ' thn · TIDAK memenuhi syarat (min. 45 thn).', 'warn');
        }
    })
    .catch(function () {
        toggleEl('scanLoading_' + idx, false);
        toggleEl('scanBtn_'     + idx, true);
        showToast('Tidak bisa konek ke OCR service.', 'error');
    });
}

/* ── Age badge + summary ────────────────────────────────────── */
function updateAgeBadge(idx, tglStr) {
    var badge   = document.getElementById('age_badge_'   + idx);
    var ocrCard = document.getElementById('ocr_card_'    + idx);
    var hidV    = document.getElementById('usia_valid_'  + idx);
    var hidAge  = document.getElementById('usia_hitung_' + idx);
    var infoEl  = document.getElementById('tgl_info_'   + idx);

    if (!tglStr) {
        setBadge(badge, 'pending', 'Belum scan');
        if (hidV)   hidV.value   = '0';
        if (hidAge) hidAge.value = '';
        usiaArr[idx] = null;
        return;
    }
    var result = hitungUsia(tglStr);
    if (!result) {
        setBadge(badge, 'invalid', 'Tgl lahir tidak valid');
        if (hidV) hidV.value = '0';
        usiaArr[idx] = null;
        return;
    }

    var usia  = result.usia;
    var valid = usia >= MIN_AGE_EACH;
    var fmt   = result.tgl.toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' });

    if (hidV)   hidV.value   = valid ? '1' : '0';
    if (hidAge) hidAge.value = usia;
    usiaArr[idx] = usia;

    if (valid) {
        setBadge(badge, 'valid', '✓ ' + usia + ' thn — Memenuhi syarat');
        if (ocrCard) { ocrCard.classList.add('scanned'); ocrCard.classList.remove('invalid-age'); }
    } else {
        setBadge(badge, 'invalid', '✗ ' + usia + ' thn — Min. 45 tahun');
        if (ocrCard) { ocrCard.classList.add('invalid-age'); ocrCard.classList.remove('scanned'); }
    }

    if (infoEl) {
        infoEl.textContent = 'Lahir: ' + fmt + ' · ' + usia + ' tahun';
        infoEl.className   = 'text-xs mt-2 font-medium ' + (valid ? 'text-emerald-400' : 'text-red-400');
        infoEl.classList.remove('hidden');
    }
    updateSummary();
}

function setBadge(el, state, text) {
    if (!el) return;
    el.className   = 'age-badge ' + state;
    el.textContent = text;
}

function updateSummary() {
    if (!scanStatus[0] || !scanStatus[1]) return;
    var v0 = document.getElementById('usia_valid_0').value === '1';
    var v1 = document.getElementById('usia_valid_1').value === '1';
    var u0 = usiaArr[0] || 0, u1 = usiaArr[1] || 0;
    var total = u0 + u1, totalOk = total >= MIN_AGE_TOTAL;

    var tBox = document.getElementById('totalUsiaBox');
    var tVal  = document.getElementById('totalUsiaValue');
    if (tBox && tVal) {
        tBox.className = 'total-usia-box show ' + (totalOk ? 'ok' : 'bad');
        tVal.innerHTML = total + ' tahun '
            + (totalOk
                ? '<span style="color:#34d399">✓ memenuhi</span>'
                : '<span style="color:#f87171">✗ kurang ' + (MIN_AGE_TOTAL - total) + ' thn</span>');
    }

    var summary = document.getElementById('veteranSummary');
    var icon    = document.getElementById('summaryIcon');
    var title   = document.getElementById('summaryTitle');
    var detail  = document.getElementById('summaryDetail');
    var warning = document.getElementById('submitWarning');
    var allOk   = v0 && v1 && totalOk;

    if (allOk) {
        summary.className = 'veteran-summary show ok';
        if (icon) icon.innerHTML =
            '<svg width="16" height="16" viewBox="0 0 20 20" fill="#34d399">'
            + '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
            + '</svg>';
        if (title)  { title.className  = 'text-xs font-bold text-emerald-400'; title.textContent  = 'Kedua pemain memenuhi syarat veteran!'; }
        if (detail) { detail.className = 'text-xs mt-0.5 text-emerald-400/55'; detail.textContent = 'Usia individual ≥ 45 thn · Total usia ' + total + ' thn ≥ 95 thn'; }
        if (warning) warning.style.display = 'none';
    } else {
        summary.className = 'veteran-summary show bad';
        if (icon) icon.innerHTML =
            '<svg width="16" height="16" viewBox="0 0 20 20" fill="#f87171">'
            + '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>'
            + '</svg>';
        if (title) { title.className = 'text-xs font-bold text-red-400'; title.textContent = 'Syarat usia belum terpenuhi'; }
        var msg = !v0 && !v1 ? 'Kedua pemain tidak memenuhi syarat usia (min. 45 tahun).'
                : !v0   ? 'Pemain 1 tidak memenuhi syarat usia (min. 45 tahun).'
                : !v1   ? 'Pemain 2 tidak memenuhi syarat usia (min. 45 tahun).'
                : 'Total usia ' + total + ' tahun, kurang ' + (MIN_AGE_TOTAL - total) + ' tahun dari minimum.';
        if (detail) { detail.className = 'text-xs mt-0.5 text-red-400/55'; detail.textContent = msg; }
        if (warning) { warning.style.display = 'block'; warning.textContent = '⚠ ' + msg; }
    }
}

function clearSummary() {
    var summary = document.getElementById('veteranSummary');
    var warning = document.getElementById('submitWarning');
    var tBox    = document.getElementById('totalUsiaBox');
    if (summary) summary.className     = 'veteran-summary';
    if (warning) warning.style.display = 'none';
    if (tBox)    tBox.className        = 'total-usia-box';
}

/* ── Render KTP card (read-only) ────────────────────────────── */
function renderCard(idx, data, usia, valid) {
    var card = document.getElementById('ktpDataCard_' + idx);
    var rows = document.getElementById('ktpDataRows_' + idx);
    if (!card || !rows) return;
    rows.innerHTML = '';

    var tglNorm = (data.tanggal_lahir || data.tgl_lahir || '').trim();
    var fields  = [
        { label:'NIK',        key:'nik',          hl:true  },
        { label:'Nama',       key:'nama',          hl:true  },
        { label:'Tgl Lahir',  key:'__tgl_lahir__', hl:true  },
        { label:'Usia',       key:'__usia__',      hl:false },
        { label:'Jenis Kel.', key:'jenis_kelamin', hl:false },
    ];

    fields.forEach(function (f) {
        var valHtml = '';
        if (f.key === '__usia__') {
            var warna = valid ? '#34d399' : '#f87171';
            valHtml = '<span style="color:' + warna + ';font-weight:700;font-size:12px;">'
                + (valid ? '✓' : '✗') + ' ' + usia + ' tahun — '
                + (valid ? 'Memenuhi syarat' : 'Tidak memenuhi syarat') + '</span>';
        } else if (f.key === '__tgl_lahir__') {
            if (!tglNorm) return;
            valHtml = '<span class="ktp-value highlight">' + esc(tglNorm) + '</span>';
        } else if (f.key === 'jenis_kelamin') {
            var v = ((data[f.key] || '') + '').trim();
            if (!v) return;
            var label = (v === 'L') ? 'LAKI-LAKI' : (v === 'P') ? 'PEREMPUAN' : v;
            valHtml = '<span class="ktp-value' + (f.hl ? ' highlight' : '') + '">' + esc(label) + '</span>';
        } else {
            var v = ((data[f.key] || '') + '').trim();
            if (!v) return;
            valHtml = '<span class="ktp-value' + (f.hl ? ' highlight' : '') + '">' + esc(v) + '</span>';
        }
        rows.innerHTML += '<div class="ktp-row"><span class="ktp-label">' + f.label + '</span>' + valHtml + '</div>';
    });

    rows.innerHTML +=
        '<div class="ktp-locked-note">'
        + '<svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">'
        + '<path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>'
        + '</svg>Data dikunci. Reset &amp; scan ulang jika ada kesalahan.</div>';

    card.className = 'ktp-data-card show ' + (valid ? 'valid-card' : 'invalid-card');
}

/* ── Helpers ─────────────────────────────────────────────────── */
function toggleEl(id, show) {
    var el = document.getElementById(id);
    if (!el) return;
    if (show) el.classList.remove('hidden');
    else      el.classList.add('hidden');
}
function setHid(id, val) {
    var el = document.getElementById(id);
    if (el) el.value = val;
}
function esc(s) {
    return String(s)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* ── Toast ───────────────────────────────────────────────────── */
var _tt = null;
function showToast(msg, type) {
    var el = document.getElementById('_vetToast');
    if (!el) {
        el = document.createElement('div');
        el.id = '_vetToast';
        document.body.appendChild(el);
    }
    var styles = {
        success: 'background:rgba(6,30,18,.97);border:1px solid rgba(16,185,129,.38);color:#34d399;',
        warn:    'background:rgba(30,22,4,.97);border:1px solid rgba(234,179,8,.38);color:#fbbf24;',
        error:   'background:rgba(30,6,6,.97);border:1px solid rgba(239,68,68,.38);color:#f87171;',
    };
    el.setAttribute('style',
        'position:fixed;top:88px;right:20px;z-index:99999;max-width:380px;'
        + 'padding:12px 16px;border-radius:13px;font-size:12px;line-height:1.5;'
        + 'font-weight:600;box-shadow:0 8px 36px rgba(0,0,0,.45);'
        + 'pointer-events:none;opacity:1;transform:translateY(0);'
        + 'transition:opacity .3s,transform .3s;animation:toastIn .25s ease both;'
        + (styles[type] || styles.error)
    );
    el.textContent = msg;
    if (_tt) clearTimeout(_tt);
    _tt = setTimeout(function () {
        el.style.opacity   = '0';
        el.style.transform = 'translateY(-8px)';
    }, 5500);
}

/* ── AJAX Submit helpers ─────────────────────────────────────── */
function clearAllErrors() {
    document.querySelectorAll('.field-error-msg').forEach(function (e) {
        e.textContent = ''; e.classList.remove('show');
    });
    document.querySelectorAll('.input-field.field-error').forEach(function (e) {
        e.classList.remove('field-error');
    });
    document.querySelectorAll('.pemain-ocr-card.has-field-error').forEach(function (e) {
        e.classList.remove('has-field-error');
    });
    var banner = document.getElementById('ajaxErrorBanner');
    if (banner) banner.classList.remove('show');
}

function showErrorBanner(errors) {
    var banner = document.getElementById('ajaxErrorBanner');
    var list   = document.getElementById('ajaxErrorList');
    if (!banner || !list) return;
    list.innerHTML = '';
    errors.forEach(function (m) {
        var li = document.createElement('li');
        li.textContent = m;
        list.appendChild(li);
    });
    banner.classList.add('show');
    banner.scrollIntoView({ behavior:'smooth', block:'start' });
}

function setFieldError(field, msg) {
    /* Cari err_* element */
    var key = field.replace(/[\[\]\.]/g,'_').replace(/__+/g,'_').replace(/_$/,'');
    var el  = document.getElementById('err_' + key) || document.getElementById('err_' + field);
    if (el) { el.textContent = msg; el.classList.add('show'); }
    var inp = document.getElementById('field_' + field);
    if (inp) inp.classList.add('field-error');
}

function setSubmitLoading(loading, msg) {
    var btn     = document.getElementById('submitBtn');
    var btnText = document.getElementById('submitBtnText');
    var spinner = document.getElementById('submitBtnSpinner');
    var overlay = document.getElementById('submitOverlay');
    var ovText  = document.getElementById('submitOverlayText');
    if (loading) {
        if (btn)     btn.disabled = true;
        if (btnText) btnText.textContent = 'Memproses...';
        if (spinner) spinner.classList.remove('hidden');
        if (overlay) overlay.classList.add('show');
        if (ovText)  ovText.textContent = msg || 'Mengirim data pendaftaran...';
    } else {
        if (btn)     btn.disabled = false;
        if (btnText) btnText.innerHTML = 'DAFTAR &amp; BAYAR SEKARANG &rarr;';
        if (spinner) spinner.classList.add('hidden');
        if (overlay) overlay.classList.remove('show');
    }
}

/* ── FIX: Expose ktpFiles & scanStatus via getter ──────────── */
window.VET = {
    fileSelect: fileSelect,
    drop:       drop,
    resetSlot:  resetSlot,
    scan:       scan,
    showSheet:  function (idx) { _SHEET.open(idx); },
    get ktpFiles()   { return ktpFiles;   },
    get scanStatus() { return scanStatus; },
};

/* ── DOMContentLoaded — AJAX submit ─────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('regForm');
    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        /* 1. Bersihkan semua error */
        clearAllErrors();

        var clientErrors = [];

        /* 2. FIX: Validasi field form dasar (nama, email, dll) */
        var fieldChecks = [
            { id: 'field_nama',    name: 'nama',     label: 'Nama Ketua Tim' },
            { id: 'field_tim_pb',  name: 'tim_pb',   label: 'Nama Tim / PB' },
            { id: 'field_email',   name: 'email',    label: 'Email' },
            { id: 'field_no_hp',   name: 'no_hp',    label: 'Nomor HP' },
        ];
        fieldChecks.forEach(function (f) {
            var el = document.getElementById(f.id);
            if (el && !el.value.trim()) {
                clientErrors.push(f.label + ' wajib diisi');
                el.classList.add('field-error');
                var errEl = document.getElementById('err_' + f.name);
                if (errEl) { errEl.textContent = f.label + ' wajib diisi'; errEl.classList.add('show'); }
            }
        });
        var selProv = document.getElementById('selectProvinsi');
        var selKota = document.getElementById('selectKota');
        if (selProv && !selProv.value) {
            clientErrors.push('Provinsi wajib dipilih');
            selProv.classList.add('field-error');
        }
        if (selKota && !selKota.value) {
            clientErrors.push('Kota / Kabupaten wajib dipilih');
            selKota.classList.add('field-error');
        }

        /* 3. FIX: Validasi KTP kedua pemain dengan UI feedback */
        [0, 1].forEach(function (idx) {
            var noNum = idx + 1;
            var card  = document.getElementById('ocr_card_' + idx);

            /* Cek file sudah diupload */
            var hasFile = (ktpFiles[idx] !== null && ktpFiles[idx] !== undefined);
            if (!hasFile) {
                /* Fallback: cek input DOM */
                ['ktpCamera_','ktpFoto_','ktpFile_'].forEach(function (pfx) {
                    var inp = document.getElementById(pfx + idx);
                    if (inp && inp.files && inp.files[0]) hasFile = true;
                });
            }
            if (!hasFile) {
                clientErrors.push('Pemain ' + noNum + ': File KTP wajib diupload');
                if (card) card.classList.add('has-field-error');
                return;
            }

            /* Cek sudah scan */
            if (!scanStatus[idx]) {
                clientErrors.push('Pemain ' + noNum + ': KTP belum di-scan — klik tombol SCAN KTP');
                if (card) card.classList.add('has-field-error');
                return;
            }

            /* Cek usia valid */
            var hidV = document.getElementById('usia_valid_' + idx);
            if (!hidV || hidV.value !== '1') {
                var usia = document.getElementById('usia_hitung_' + idx);
                var usiaVal = usia ? usia.value : '?';
                clientErrors.push('Pemain ' + noNum + ': Usia ' + usiaVal + ' tahun tidak memenuhi syarat (min. 45 tahun)');
                if (card) card.classList.add('has-field-error');
            }
        });

        /* 4. Validasi total usia (hanya jika kedua sudah scan) */
        if (scanStatus[0] && scanStatus[1]) {
            var v0    = document.getElementById('usia_valid_0').value === '1';
            var v1    = document.getElementById('usia_valid_1').value === '1';
            var u0    = parseInt(document.getElementById('usia_hitung_0').value, 10) || 0;
            var u1    = parseInt(document.getElementById('usia_hitung_1').value, 10) || 0;
            var total = u0 + u1;
            if (v0 && v1 && total < MIN_AGE_TOTAL) {
                clientErrors.push(
                    'Total usia kedua pemain ' + total + ' tahun — minimum 95 tahun '
                    + '(kurang ' + (MIN_AGE_TOTAL - total) + ' tahun)'
                );
            }
        }

        /* 5. Tampilkan error banner jika ada */
        if (clientErrors.length > 0) {
            showErrorBanner(clientErrors);
            /* Scroll ke card pertama yang error */
            var firstErrCard = document.querySelector('.pemain-ocr-card.has-field-error');
            if (firstErrCard) {
                firstErrCard.scrollIntoView({ behavior:'smooth', block:'center' });
            } else {
                var banner = document.getElementById('ajaxErrorBanner');
                if (banner) banner.scrollIntoView({ behavior:'smooth', block:'start' });
            }
            return;
        }

        /* 6. Build FormData */
        var fd = new FormData();
        form.querySelectorAll('input:not([type="file"]), select, textarea').forEach(function (inp) {
            if (!inp.name) return;
            fd.append(inp.name, inp.value);
        });

        /* FIX: tambahkan file dari ktpFiles object (bukan dari input DOM) */
        [0, 1].forEach(function (idx) {
            var f = ktpFiles[idx];
            if (f) fd.append('ktp_files[]', f, f.name || ('ktp-pemain-' + (idx+1) + '.jpg'));
        });

        /* 7. Loading */
        setSubmitLoading(true, 'Mengirim data pendaftaran...');

        var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

        try {
            var response = await fetch("{{ route('registration.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN':     csrf,
                    'Accept':           'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: fd,
            });

            var data = await response.json();

            if (response.status === 422) {
                setSubmitLoading(false);
                var allMessages = [];
                Object.keys(data.errors || {}).forEach(function (field) {
                    var msgs = Array.isArray(data.errors[field])
                        ? data.errors[field]
                        : [data.errors[field]];
                    allMessages = allMessages.concat(msgs);
                    setFieldError(field.replace(/\.\d+$/, ''), msgs[0]);

                    /* Highlight ocr card jika error terkait pemain */
                    var m = field.match(/\.\d+$/);
                    if (m && /^(pemain|nik|tgl_lahir|ktp_files|usia_hitung|usia_valid)/.test(field)) {
                        var cardIdx = field.match(/\.(\d+)$/);
                        if (cardIdx) {
                            var cardEl = document.getElementById('ocr_card_' + cardIdx[1]);
                            if (cardEl) cardEl.classList.add('has-field-error');
                        }
                    }
                });
                showErrorBanner(allMessages);
                return;
            }

            if (!response.ok) {
                setSubmitLoading(false);
                showErrorBanner([(data && data.message) || 'Terjadi kesalahan server. Coba lagi.']);
                return;
            }

            /* Sukses — FIX: redirect ke pending-payment, bukan langsung Midtrans */
            var ovText = document.getElementById('submitOverlayText');
            if (ovText) ovText.textContent = 'Pendaftaran berhasil! Mengarahkan ke halaman konfirmasi...';

            if (data.redirect) {
                window.location.href = data.redirect;
            } else if (data.uuid) {
                window.location.href = '/daftar/pending-payment/' + data.uuid;
            } else {
                window.location.href = '/';
            }

        } catch (err) {
            setSubmitLoading(false);
            console.error('[VET submit error]', err);
            showErrorBanner(['Koneksi gagal. Periksa internet dan coba lagi.']);
        }
    });
});

})(); /* end VET IIFE */
</script>
@endpush

@endsection