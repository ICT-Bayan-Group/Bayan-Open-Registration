@extends('layouts.app')

@section('title', 'Dokumen - Bayan Open 2026')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════
   DOKUMEN PAGE — BAYAN OPEN 2026
   Dark fire theme · Matching contact page design
═══════════════════════════════════════════════════ */
:root {
    --fire:       #f97316;
    --fire-deep:  #c2410c;
    --fire-soft:  rgba(249,115,22,0.12);
    --gold:       #fbbf24;
    --night:      #0d0906;
    --night-2:    #140c07;
    --paper:      #faf8f5;
    --paper-2:    #f2ede6;
    --ink:        #1a1007;
    --ink-70:     rgba(26,16,7,0.70);
    --ink-45:     rgba(26,16,7,0.45);
    --ink-25:     rgba(26,16,7,0.25);
    --ink-12:     rgba(26,16,7,0.10);
    --ink-06:     rgba(26,16,7,0.05);
    --white:      #ffffff;
    --ash:        rgba(255,255,255,0.55);
    --ash-2:      rgba(255,255,255,0.22);
    --ash-3:      rgba(255,255,255,0.08);
    --success:    #10b981;
    --r-xs:  8px;
    --r-sm:  12px;
    --r-md:  18px;
    --r-lg:  24px;
    --r-xl:  32px;
    --font-display: 'Montserrat', sans-serif;
    --font-body:    'Montserrat', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.dk { background: var(--paper); min-height: 100svh; font-family: var(--font-body); color: var(--ink); }

/* ════════════════════════════════════════
   VIDEO HERO
════════════════════════════════════════ */
.dk-hero {
    position: relative;
    height: clamp(280px, 42vw, 440px);
    overflow: hidden;
    display: flex; align-items: flex-end;
}
.dk-hero-video {
    position: absolute; inset: 0; z-index: 0;
    width: 100%; height: 100%; object-fit: cover;
    pointer-events: none;
}
.dk-hero-overlay {
    position: absolute; inset: 0; z-index: 1;
    background:
        linear-gradient(to bottom,
            rgba(13,9,6,0.45) 0%,
            rgba(13,9,6,0.30) 30%,
            rgba(13,9,6,0.82) 72%,
            rgba(13,9,6,0.98) 100%),
        radial-gradient(ellipse 80% 60% at 40% 40%, rgba(249,115,22,0.10) 0%, transparent 60%);
}
.dk-hero-grain {
    position: absolute; inset: 0; z-index: 2; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.045'/%3E%3C/svg%3E");
}
.dk-hero-content {
    position: relative; z-index: 3;
    width: 100%; max-width: 1120px;
    margin: 0 auto;
    padding: 0 28px 38px;
}
.dk-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 5px 14px 5px 8px;
    border-radius: 99px;
    border: 1px solid rgba(249,115,22,0.3);
    background: rgba(249,115,22,0.09);
    backdrop-filter: blur(8px);
    margin-bottom: 14px;
}
.dk-eyebrow-icon { color: var(--fire); display:flex; align-items:center; }
.dk-eyebrow-text {
    font-family: var(--font-display);
    font-size: 9.5px; font-weight: 700;
    letter-spacing: .18em; text-transform: uppercase;
    color: var(--fire);
}
.dk-hero-title {
    font-family: var(--font-display);
    font-size: clamp(22px, 4vw, 42px); font-weight: 800;
    color: #fff; letter-spacing: -.03em; line-height: 1.08;
    margin-bottom: 8px;
}
.dk-hero-sub {
    font-size: 13.5px; color: var(--ash);
    line-height: 1.65; max-width: 520px;
}

/* ════════════════════════════════════════
   MAIN CONTENT
════════════════════════════════════════ */
.dk-main {
    max-width: 1120px; margin: 0 auto;
    padding: 48px 24px 80px;
    display: flex; flex-direction: column; gap: 40px;
}

/* ════════════════════════════════════════
   SECTION LABEL
════════════════════════════════════════ */
.dk-section-label {
    display: flex; align-items: center; gap: 14px;
    margin-bottom: 20px;
}
.dk-section-label-text {
    font-family: var(--font-display);
    font-size: 11px; font-weight: 800;
    color: var(--ink); letter-spacing: .08em; text-transform: uppercase;
    display: flex; align-items: center; gap: 8px;
    white-space: nowrap;
}
.dk-section-fire { color: var(--fire); display:flex; align-items:center; }
.dk-section-line { flex:1; height:1px; background: linear-gradient(90deg, var(--ink-12), transparent); }

/* ════════════════════════════════════════
   DOCUMENT CARDS GRID
════════════════════════════════════════ */
.dk-doc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.dk-doc-card {
    background: var(--white);
    border: 1px solid var(--ink-12);
    border-radius: var(--r-lg);
    overflow: hidden;
    position: relative;
    transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s, border-color .3s;
    display: flex; flex-direction: column;
}
.dk-doc-card::before {
    content: '';
    position: absolute; left:0; top:0; bottom:0; width:3px;
    background: linear-gradient(to bottom, var(--fire), var(--fire-deep));
    opacity: .35; transition: opacity .25s;
}
.dk-doc-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 44px rgba(249,115,22,0.10), 0 2px 8px rgba(26,16,7,0.06);
    border-color: rgba(249,115,22,0.25);
}
.dk-doc-card:hover::before { opacity: 1; }

/* Card header */
.dk-doc-head {
    padding: 18px 18px 14px 20px;
    border-bottom: 1px dashed var(--ink-12);
    display: flex; align-items: flex-start; gap: 14px;
}
.dk-doc-icon-wrap {
    width: 44px; height: 44px; border-radius: 12px;
    background: var(--night);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.dk-doc-icon-wrap svg { color: var(--fire); }
.dk-doc-meta { flex: 1; min-width: 0; }
.dk-doc-category {
    font-family: var(--font-display);
    font-size: 8.5px; font-weight: 800;
    letter-spacing: .14em; text-transform: uppercase;
    color: var(--fire); margin-bottom: 5px;
}
.dk-doc-title {
    font-family: var(--font-display);
    font-size: 12.5px; font-weight: 700;
    color: var(--ink); line-height: 1.4;
}
.dk-doc-desc {
    font-size: 11.5px; color: var(--ink-45);
    line-height: 1.6; margin-top: 3px;
}

/* PDF Preview */
.dk-doc-preview {
    position: relative;
    background: var(--paper-2);
    border-bottom: 1px dashed var(--ink-12);
    overflow: hidden;
    height: 360px;
    flex-shrink: 0;
}
.dk-doc-iframe {
    width: 100%; height: 100%; border: 0; display: block;
    transition: opacity .3s;
}
.dk-doc-preview-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(250,248,245,0.15) 0%, transparent 40%);
    pointer-events: none; z-index: 1;
}

/* Loading state */
.dk-doc-loading {
    position: absolute; inset: 0; z-index: 2;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 12px;
    background: var(--paper-2);
    transition: opacity .4s;
}
.dk-doc-loading.hidden { opacity: 0; pointer-events: none; }
.dk-spinner {
    width: 28px; height: 28px;
    border: 2.5px solid var(--ink-12);
    border-top-color: var(--fire);
    border-radius: 50%;
    animation: dkspin 0.7s linear infinite;
}
@keyframes dkspin { to { transform: rotate(360deg); } }
.dk-loading-text {
    font-family: var(--font-display);
    font-size: 9px; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase;
    color: var(--ink-25);
}

/* Card footer – action buttons */
.dk-doc-footer {
    padding: 12px 18px 14px 20px;
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap;
}
.dk-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 16px;
    border-radius: var(--r-xs);
    font-family: var(--font-display); font-size: 9px; font-weight: 800;
    letter-spacing: .12em; text-transform: uppercase;
    text-decoration: none; cursor: pointer; border: none;
    transition: transform .2s, box-shadow .2s, background .2s;
    flex-shrink: 0;
}
.dk-btn:hover { transform: translateY(-2px); }
.dk-btn-primary {
    background: linear-gradient(135deg, var(--fire), var(--fire-deep));
    color: #fff;
    box-shadow: 0 4px 16px rgba(249,115,22,0.3);
}
.dk-btn-primary:hover { box-shadow: 0 8px 24px rgba(249,115,22,0.45); }
.dk-btn-ghost {
    background: var(--paper);
    border: 1.5px solid var(--ink-12);
    color: var(--ink-45);
}
.dk-btn-ghost:hover { border-color: rgba(249,115,22,0.3); color: var(--fire); }

/* Fullscreen toggle */
.dk-expand-btn {
    margin-left: auto;
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 12px;
    background: var(--paper);
    border: 1.5px solid var(--ink-12);
    border-radius: var(--r-xs);
    font-family: var(--font-display); font-size: 8.5px; font-weight: 700;
    letter-spacing: .10em; text-transform: uppercase;
    color: var(--ink-45); cursor: pointer;
    transition: border-color .2s, color .2s, transform .2s;
    flex-shrink: 0;
}
.dk-expand-btn:hover { border-color: rgba(249,115,22,0.3); color: var(--fire); transform: translateY(-2px); }

/* ════════════════════════════════════════
   MODAL FULLSCREEN PREVIEW
════════════════════════════════════════ */
.dk-modal-backdrop {
    position: fixed; inset: 0; z-index: 9998;
    background: rgba(13,9,6,0.88);
    backdrop-filter: blur(6px);
    display: none; align-items: center; justify-content: center;
    padding: 20px;
    animation: dkfadein .2s ease;
}
.dk-modal-backdrop.active { display: flex; }
@keyframes dkfadein { from{opacity:0} to{opacity:1} }

.dk-modal {
    background: var(--white);
    border: 1px solid var(--ink-12);
    border-radius: var(--r-xl);
    overflow: hidden;
    width: 100%; max-width: 960px;
    height: 90vh;
    display: flex; flex-direction: column;
    box-shadow: 0 32px 80px rgba(13,9,6,0.5);
    animation: dkslideup .25s cubic-bezier(.22,1,.36,1);
}
@keyframes dkslideup { from{transform:translateY(24px);opacity:0} to{transform:translateY(0);opacity:1} }

.dk-modal-head {
    padding: 14px 18px;
    border-bottom: 1px dashed var(--ink-12);
    display: flex; align-items: center; gap: 12px;
    background: var(--white);
    flex-shrink: 0;
}
.dk-modal-title {
    font-family: var(--font-display); font-size: 11px; font-weight: 800;
    letter-spacing: .06em; text-transform: uppercase; color: var(--ink);
    flex: 1; min-width: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.dk-modal-close {
    width: 30px; height: 30px; border-radius: 8px;
    background: var(--paper); border: 1.5px solid var(--ink-12);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--ink-45);
    transition: background .2s, color .2s, border-color .2s;
    flex-shrink: 0;
}
.dk-modal-close:hover { background: var(--fire); border-color: var(--fire); color: #fff; }

.dk-modal-iframe {
    flex: 1; width: 100%; border: 0; display: block;
}

/* ════════════════════════════════════════
   INFO BANNER
════════════════════════════════════════ */
.dk-info-banner {
    background: var(--night);
    border-radius: var(--r-lg);
    padding: 20px 24px;
    display: flex; align-items: center; gap: 16px;
    border: 1px solid rgba(255,255,255,0.05);
    flex-wrap: wrap;
}
.dk-banner-icon {
    width: 40px; height: 40px; border-radius: 12px;
    background: var(--fire-soft);
    border: 1px solid rgba(249,115,22,0.2);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: var(--fire);
}
.dk-banner-content { flex: 1; min-width: 0; }
.dk-banner-title {
    font-family: var(--font-display); font-size: 11px; font-weight: 800;
    letter-spacing: .06em; text-transform: uppercase;
    color: rgba(255,255,255,0.85); margin-bottom: 4px;
}
.dk-banner-sub {
    font-size: 12px; color: rgba(255,255,255,0.38); line-height: 1.6;
}
.dk-banner-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px;
    background: var(--fire-soft);
    border: 1px solid rgba(249,115,22,0.25);
    border-radius: 99px;
    font-family: var(--font-display); font-size: 9px; font-weight: 800;
    letter-spacing: .12em; text-transform: uppercase;
    color: var(--fire);
    flex-shrink: 0;
}
.dk-live-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--fire);
    animation: dkblink 2s ease infinite;
}
@keyframes dkblink { 0%,100%{opacity:1} 50%{opacity:.3} }

/* ════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════ */
@media (max-width: 768px) {
    .dk-hero { height: 260px; }
    .dk-hero-content { padding: 0 18px 24px; }
    .dk-hero-title { font-size: 22px; }
    .dk-main { padding: 28px 16px 60px; gap: 28px; }
    .dk-doc-grid { grid-template-columns: 1fr; }
    .dk-doc-preview { height: 280px; }
    .dk-modal { height: 95vh; border-radius: var(--r-lg); }
    .dk-info-banner { gap: 12px; }
}
</style>
@endpush

@section('content')
<div class="dk">

    {{-- ══ VIDEO HERO ══ --}}
    <div class="dk-hero">
        <video class="dk-hero-video"
            src="https://res.cloudinary.com/djs5pi7ev/video/upload/q_50,w_1280/v1769502814/bayanopen-hero_iqhyip.mp4"
            autoplay muted loop playsinline preload="auto"></video>
        <div class="dk-hero-overlay"></div>
        <div class="dk-hero-grain"></div>

        <div class="dk-hero-content">
            <div class="dk-eyebrow">
                <span class="dk-eyebrow-icon">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </span>
                <span class="dk-eyebrow-text">Unduhan Resmi</span>
            </div>
            <h1 class="dk-hero-title">Dokumen Kejuaraan</h1>
            <p class="dk-hero-sub">Bayan Open 2026 &nbsp;·&nbsp; Balikpapan, Kalimantan Timur &nbsp;·&nbsp; 24–29 Agustus 2026</p>
        </div>
    </div>

    {{-- ══ MAIN ══ --}}
    <div class="dk-main">

        {{-- Info banner --}}
        <div class="dk-info-banner">
            <div class="dk-banner-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4M12 16h.01"/>
                </svg>
            </div>
            <div class="dk-banner-content">
                <div class="dk-banner-title">Dokumen Resmi Panitia</div>
                <div class="dk-banner-sub">Baca ketentuan kejuaraan dengan seksama sebelum mendaftar. Klik pratinjau untuk membaca langsung, atau unduh dokumen ke perangkat Anda.</div>
            </div>
            <div class="dk-banner-badge">
                <span class="dk-live-dot"></span>
                2 Dokumen
            </div>
        </div>

        {{-- Section label --}}
        <div>
            <div class="dk-section-label">
                <div class="dk-section-label-text">
                    <span class="dk-section-fire">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </span>
                    Ketentuan Kejuaraan
                </div>
                <div class="dk-section-line"></div>
            </div>

            <div class="dk-doc-grid">

                {{-- ── DOCUMENT 1: Beregu Se-Kota Balikpapan ── --}}
                <div class="dk-doc-card">
                    <div class="dk-doc-head">
                        <div class="dk-doc-icon-wrap">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                        </div>
                        <div class="dk-doc-meta">
                            <div class="dk-doc-category">Ketentuan Kejuaraan</div>
                            <div class="dk-doc-title">Beregu Se-Kota Balikpapan</div>
                            <div class="dk-doc-desc">Aturan dan ketentuan lengkap kategori beregu tingkat kota Balikpapan</div>
                        </div>
                    </div>

                    {{-- PDF Preview via Google Drive embed --}}
                    <div class="dk-doc-preview" id="preview-wrap-1">
                        <div class="dk-doc-loading" id="loading-1">
                            <div class="dk-spinner"></div>
                            <div class="dk-loading-text">Memuat dokumen…</div>
                        </div>
                        <iframe
                            class="dk-doc-iframe"
                            id="iframe-1"
                            src="https://drive.google.com/file/d/154jY1ezg_dZ-XRnYvgoaWZRm_8XayzAa/preview"
                            allow="autoplay"
                            onload="iframeLoaded('loading-1')"
                            title="Ketentuan Beregu Se-Kota Balikpapan">
                        </iframe>
                        <div class="dk-doc-preview-overlay"></div>
                    </div>

                    <div class="dk-doc-footer">
                        <a href="https://drive.google.com/uc?export=download&id=154jY1ezg_dZ-XRnYvgoaWZRm_8XayzAa"
                           class="dk-btn dk-btn-primary"
                           download>
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Unduh PDF
                        </a>
                        <button class="dk-expand-btn" onclick="openModal('modal-1', 'Beregu Se-Kota Balikpapan', 'https://drive.google.com/file/d/154jY1ezg_dZ-XRnYvgoaWZRm_8XayzAa/preview')">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/>
                            </svg>
                            Layar Penuh
                        </button>
                    </div>
                </div>

                {{-- ── DOCUMENT 2: Dewasa dan Veteran ── --}}
                <div class="dk-doc-card">
                    <div class="dk-doc-head">
                        <div class="dk-doc-icon-wrap">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                        </div>
                        <div class="dk-doc-meta">
                            <div class="dk-doc-category">Ketentuan Kejuaraan</div>
                            <div class="dk-doc-title">Dewasa dan Veteran</div>
                            <div class="dk-doc-desc">Aturan dan ketentuan lengkap kategori dewasa dan veteran Bayan Open 2026</div>
                        </div>
                    </div>

                    {{-- PDF Preview via Google Drive embed --}}
                    <div class="dk-doc-preview" id="preview-wrap-2">
                        <div class="dk-doc-loading" id="loading-2">
                            <div class="dk-spinner"></div>
                            <div class="dk-loading-text">Memuat dokumen…</div>
                        </div>
                        <iframe
                            class="dk-doc-iframe"
                            id="iframe-2"
                            src="https://drive.google.com/file/d/1C2NmuBWD6mZGoJR0nOvs96yuYI0eY234/preview"
                            allow="autoplay"
                            onload="iframeLoaded('loading-2')"
                            title="Ketentuan Dewasa dan Veteran">
                        </iframe>
                        <div class="dk-doc-preview-overlay"></div>
                    </div>

                    <div class="dk-doc-footer">
                        <a href="https://drive.google.com/uc?export=download&id=1C2NmuBWD6mZGoJR0nOvs96yuYI0eY234"
                           class="dk-btn dk-btn-primary"
                           download>
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Unduh PDF
                        </a>
                        <button class="dk-expand-btn" onclick="openModal('modal-2', 'Dewasa dan Veteran', 'https://drive.google.com/file/d/1C2NmuBWD6mZGoJR0nOvs96yuYI0eY234/preview')">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/>
                            </svg>
                            Layar Penuh
                        </button>
                    </div>
                </div>

            </div>{{-- /.dk-doc-grid --}}
        </div>

    </div>{{-- /.dk-main --}}

</div>{{-- /.dk --}}

{{-- ══ MODALS ══ --}}
<div class="dk-modal-backdrop" id="modal-1" onclick="closeModalOutside(event, 'modal-1')">
    <div class="dk-modal">
        <div class="dk-modal-head">
            <div class="dk-doc-icon-wrap" style="width:30px;height:30px;border-radius:8px;flex-shrink:0;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
            <div class="dk-modal-title" id="modal-1-title">Dokumen</div>
            <a id="modal-1-download"
               href="https://drive.google.com/uc?export=download&id=154jY1ezg_dZ-XRnYvgoaWZRm_8XayzAa"
               class="dk-btn dk-btn-primary" style="padding:6px 12px;font-size:8px;" download>
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Unduh
            </a>
            <button class="dk-modal-close" onclick="closeModal('modal-1')" aria-label="Tutup">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <iframe class="dk-modal-iframe" id="modal-1-iframe"
            src="https://drive.google.com/file/d/154jY1ezg_dZ-XRnYvgoaWZRm_8XayzAa/preview"
            allow="autoplay"
            title="Preview Dokumen">
        </iframe>
    </div>
</div>

<div class="dk-modal-backdrop" id="modal-2" onclick="closeModalOutside(event, 'modal-2')">
    <div class="dk-modal">
        <div class="dk-modal-head">
            <div class="dk-doc-icon-wrap" style="width:30px;height:30px;border-radius:8px;flex-shrink:0;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
            <div class="dk-modal-title" id="modal-2-title">Dokumen</div>
            <a id="modal-2-download"
               href="https://drive.google.com/uc?export=download&id=1C2NmuBWD6mZGoJR0nOvs96yuYI0eY234"
               class="dk-btn dk-btn-primary" style="padding:6px 12px;font-size:8px;" download>
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Unduh
            </a>
            <button class="dk-modal-close" onclick="closeModal('modal-2')" aria-label="Tutup">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <iframe class="dk-modal-iframe" id="modal-2-iframe"
            src="https://drive.google.com/file/d/1C2NmuBWD6mZGoJR0nOvs96yuYI0eY234/preview"
            allow="autoplay"
            title="Preview Dokumen">
        </iframe>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* Hide spinner once iframe loads */
function iframeLoaded(loadingId) {
    const el = document.getElementById(loadingId);
    if (el) el.classList.add('hidden');
}

/* Open modal */
function openModal(modalId, title) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    document.getElementById(modalId + '-title').textContent = title;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

/* Close modal */
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

/* Close when clicking backdrop */
function closeModalOutside(event, modalId) {
    if (event.target === event.currentTarget) closeModal(modalId);
}

/* Close on Escape key */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.dk-modal-backdrop.active').forEach(function(m) {
            m.classList.remove('active');
        });
        document.body.style.overflow = '';
    }
});
</script>
@endpush