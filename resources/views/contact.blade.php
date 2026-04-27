@extends('layouts.app')

@section('title', 'Kontak - Bayan Open 2026')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════
   CONTACT PAGE — BAYAN OPEN 2026
   Dark fire theme · Matching live score design
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
    --success-bg: rgba(16,185,129,0.12);
    --r-xs:  8px;
    --r-sm:  12px;
    --r-md:  18px;
    --r-lg:  24px;
    --r-xl:  32px;
    --font-display: 'Montserrat', sans-serif;
    --font-body:    'Montserrat', sans-serif;
    --font-mono:    'Montserrat', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.ct { background: var(--paper); min-height: 100svh; font-family: var(--font-body); color: var(--ink); }

/* ════════════════════════════════════════
   VIDEO HERO
════════════════════════════════════════ */
.ct-hero {
    position: relative;
    height: clamp(280px, 42vw, 440px);
    overflow: hidden;
    display: flex; align-items: flex-end;
}
.ct-hero-video {
    position: absolute; inset: 0; z-index: 0;
    width: 100%; height: 100%; object-fit: cover;
    pointer-events: none;
}
.ct-hero-overlay {
    position: absolute; inset: 0; z-index: 1;
    background:
        linear-gradient(to bottom,
            rgba(13,9,6,0.45) 0%,
            rgba(13,9,6,0.30) 30%,
            rgba(13,9,6,0.82) 72%,
            rgba(13,9,6,0.98) 100%),
        radial-gradient(ellipse 80% 60% at 40% 40%, rgba(249,115,22,0.10) 0%, transparent 60%);
}
.ct-hero-grain {
    position: absolute; inset: 0; z-index: 2; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.045'/%3E%3C/svg%3E");
}
.ct-hero-content {
    position: relative; z-index: 3;
    width: 100%; max-width: 1120px;
    margin: 0 auto;
    padding: 0 28px 38px;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
}

.ct-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 5px 14px 5px 8px;
    border-radius: 99px;
    border: 1px solid rgba(249,115,22,0.3);
    background: rgba(249,115,22,0.09);
    backdrop-filter: blur(8px);
    margin-bottom: 14px;
}
.ct-eyebrow-icon { color: var(--fire); display:flex; align-items:center; }
.ct-eyebrow-text {
    font-family: var(--font-display);
    font-size: 9.5px; font-weight: 700;
    letter-spacing: .18em; text-transform: uppercase;
    color: var(--fire);
}
.ct-hero-title {
    font-family: var(--font-display);
    font-size: clamp(22px, 4vw, 42px); font-weight: 800;
    color: #fff; letter-spacing: -.03em; line-height: 1.08;
    margin-bottom: 8px;
}
.ct-hero-sub {
    font-size: 13.5px; color: var(--ash);
    line-height: 1.65; max-width: 480px;
}

/* ════════════════════════════════════════
   MAIN CONTENT
════════════════════════════════════════ */
.ct-main {
    max-width: 1120px; margin: 0 auto;
    padding: 48px 24px 80px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    align-items: start;
}

/* ════════════════════════════════════════
   SECTION HEADER
════════════════════════════════════════ */
.ct-section-label {
    display: flex; align-items: center; gap: 14px;
    margin-bottom: 20px;
}
.ct-section-label-text {
    font-family: var(--font-display);
    font-size: 11px; font-weight: 800;
    color: var(--ink); letter-spacing: .08em; text-transform: uppercase;
    display: flex; align-items: center; gap: 8px;
    white-space: nowrap;
}
.ct-section-fire { color: var(--fire); display:flex; align-items:center; }
.ct-section-line { flex:1; height:1px; background: linear-gradient(90deg, var(--ink-12), transparent); }

/* ════════════════════════════════════════
   INFO CARDS
════════════════════════════════════════ */
.ct-info-col { display: flex; flex-direction: column; gap: 14px; }

.ct-info-card {
    background: var(--white);
    border: 1px solid var(--ink-12);
    border-radius: var(--r-lg);
    padding: 22px 22px 22px 20px;
    display: flex; gap: 16px; align-items: flex-start;
    position: relative; overflow: hidden;
    transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s, border-color .3s;
}
.ct-info-card::before {
    content: '';
    position: absolute; left:0; top:0; bottom:0; width:3px;
    background: linear-gradient(to bottom, var(--fire), var(--fire-deep));
    opacity: .35; transition: opacity .25s;
}
.ct-info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 40px rgba(249,115,22,0.09), 0 2px 8px rgba(26,16,7,0.05);
    border-color: rgba(249,115,22,0.22);
}
.ct-info-card:hover::before { opacity: 1; }

.ct-info-icon {
    width: 42px; height: 42px; border-radius: 12px;
    background: var(--fire-soft);
    border: 1px solid rgba(249,115,22,0.18);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: var(--fire);
}
.ct-info-content { flex: 1; min-width: 0; }
.ct-info-label {
    font-family: var(--font-display);
    font-size: 8.5px; font-weight: 800;
    letter-spacing: .14em; text-transform: uppercase;
    color: var(--ink-25); margin-bottom: 5px;
}
.ct-info-value {
    font-size: 13.5px; font-weight: 600; color: var(--ink);
    line-height: 1.5;
}
.ct-info-value a {
    color: var(--fire); text-decoration: none;
    transition: color .2s;
}
.ct-info-value a:hover { color: var(--fire-deep); text-decoration: underline; }
.ct-info-sub {
    font-size: 11.5px; color: var(--ink-45); margin-top: 3px;
    line-height: 1.55;
}

/* Venue cards (multi-location) */
.ct-venue-stack { display: flex; flex-direction: column; gap: 14px; }
.ct-venue-card {
    background: var(--white);
    border: 1px solid var(--ink-12);
    border-radius: var(--r-lg);
    overflow: hidden;
    position: relative;
    transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s, border-color .3s;
}
.ct-venue-card::before {
    content: '';
    position: absolute; left:0; top:0; bottom:0; width:3px;
    background: linear-gradient(to bottom, var(--fire), var(--fire-deep));
    opacity: .35; transition: opacity .25s;
}
.ct-venue-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 40px rgba(249,115,22,0.09), 0 2px 8px rgba(26,16,7,0.05);
    border-color: rgba(249,115,22,0.22);
}
.ct-venue-card:hover::before { opacity: 1; }

.ct-venue-head {
    padding: 14px 18px 10px 20px;
    display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px dashed var(--ink-12);
}
.ct-venue-name {
    font-size: 13px; font-weight: 700; color: var(--ink);
}
.ct-venue-num {
    width: 24px; height: 24px; border-radius: 8px;
    background: var(--night);
    font-family: var(--font-display); font-size: 10px; font-weight: 800;
    color: var(--fire);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.ct-venue-body { padding: 12px 20px 14px; }
.ct-venue-addr {
    font-size: 12px; color: var(--ink-45); line-height: 1.6;
}
.ct-venue-link {
    display: inline-flex; align-items: center; gap: 6px;
    margin-top: 8px;
    font-family: var(--font-display); font-size: 9px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    color: var(--fire); text-decoration: none;
    transition: color .2s, gap .2s;
}
.ct-venue-link:hover { color: var(--fire-deep); gap: 9px; }

/* ════════════════════════════════════════
   MAP COL
════════════════════════════════════════ */
.ct-map-col { display: flex; flex-direction: column; gap: 14px; }

.ct-map-card {
    background: var(--white);
    border: 1px solid var(--ink-12);
    border-radius: var(--r-lg);
    overflow: hidden;
    position: relative;
}
.ct-map-head {
    padding: 14px 18px 12px 20px;
    display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px dashed var(--ink-12);
    background: var(--white);
}
.ct-map-head-left { display: flex; align-items: center; gap: 10px; }
.ct-map-title {
    font-family: var(--font-display); font-size: 11px; font-weight: 800;
    letter-spacing: .06em; text-transform: uppercase; color: var(--ink);
}
.ct-map-sub { font-size: 11px; color: var(--ink-45); margin-top: 2px; }
.ct-live-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--success);
    box-shadow: 0 0 10px var(--success);
    animation: ctblink 2s ease infinite;
    flex-shrink: 0;
}
@keyframes ctblink { 0%,100%{opacity:1} 50%{opacity:.3} }

.ct-map-open-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px;
    background: var(--night);
    border-radius: 8px;
    font-family: var(--font-display); font-size: 9px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: background .2s, color .2s;
}
.ct-map-open-btn:hover { background: var(--fire); color: #fff; }
.ct-map-open-btn svg { flex-shrink: 0; }

.ct-map-iframe {
    width: 100%; height: 380px; border: 0; display: block;
    filter: grayscale(10%) contrast(1.05);
}

/* ════════════════════════════════════════
   CONTACT FORM CARD
════════════════════════════════════════ */
.ct-form-card {
    background: var(--white);
    border: 1px solid var(--ink-12);
    border-radius: var(--r-lg);
    overflow: hidden;
}
.ct-form-head {
    padding: 18px 22px 14px;
    border-bottom: 1px dashed var(--ink-12);
    display: flex; align-items: center; gap: 10px;
}
.ct-form-icon {
    width: 34px; height: 34px; border-radius: 10px;
    background: var(--night);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: var(--fire);
}
.ct-form-title {
    font-family: var(--font-display); font-size: 11px; font-weight: 800;
    letter-spacing: .06em; text-transform: uppercase; color: var(--ink);
}
.ct-form-sub { font-size: 11px; color: var(--ink-45); margin-top: 1px; }
.ct-form-body { padding: 20px 22px 22px; display: flex; flex-direction: column; gap: 14px; }

.ct-field { display: flex; flex-direction: column; gap: 6px; }
.ct-field label {
    font-family: var(--font-display);
    font-size: 8.5px; font-weight: 800;
    letter-spacing: .14em; text-transform: uppercase;
    color: var(--ink-45);
}
.ct-input {
    width: 100%; padding: 10px 14px;
    background: var(--paper);
    border: 1.5px solid var(--ink-12);
    border-radius: var(--r-xs);
    font-family: var(--font-body); font-size: 13px; font-weight: 500;
    color: var(--ink);
    transition: border-color .2s, box-shadow .2s, background .2s;
    outline: none;
    resize: none;
}
.ct-input::placeholder { color: var(--ink-25); }
.ct-input:focus {
    border-color: rgba(249,115,22,0.5);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(249,115,22,0.08);
}

.ct-submit-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 12px 20px;
    background: linear-gradient(135deg, var(--fire), var(--fire-deep));
    border: none; border-radius: var(--r-xs);
    font-family: var(--font-display); font-size: 10px; font-weight: 800;
    letter-spacing: .14em; text-transform: uppercase;
    color: #fff; cursor: pointer;
    box-shadow: 0 4px 18px rgba(249,115,22,0.35);
    transition: transform .2s, box-shadow .2s;
}
.ct-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(249,115,22,0.5);
}
.ct-submit-btn:active { transform: translateY(0); }

/* wa cta */
.ct-wa-card {
    display: flex; align-items: center; gap: 16px;
    background: var(--night);
    border-radius: var(--r-lg);
    padding: 18px 22px;
    border: 1px solid rgba(255,255,255,0.06);
}
.ct-wa-icon {
    width: 44px; height: 44px; border-radius: 14px;
    background: rgba(37,211,102,0.15);
    border: 1px solid rgba(37,211,102,0.25);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.ct-wa-content { flex: 1; min-width: 0; }
.ct-wa-label {
    font-family: var(--font-display); font-size: 8.5px; font-weight: 800;
    letter-spacing: .14em; text-transform: uppercase;
    color: rgba(255,255,255,0.3); margin-bottom: 4px;
}
.ct-wa-number {
    font-family: var(--font-display); font-size: 16px; font-weight: 800;
    color: #fff; letter-spacing: .02em;
}
.ct-wa-sub { font-size: 11px; color: rgba(255,255,255,0.35); margin-top: 2px; }
.ct-wa-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px;
    background: rgba(37,211,102,0.15);
    border: 1px solid rgba(37,211,102,0.3);
    border-radius: 10px;
    font-family: var(--font-display); font-size: 9px; font-weight: 800;
    letter-spacing: .1em; text-transform: uppercase;
    color: #25d366; text-decoration: none;
    flex-shrink: 0;
    transition: background .2s, border-color .2s;
}
.ct-wa-btn:hover {
    background: rgba(37,211,102,0.25);
    border-color: rgba(37,211,102,0.5);
}

/* social row */
.ct-social-row {
    display: flex; gap: 10px; flex-wrap: wrap;
}
.ct-social-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 14px;
    background: var(--white);
    border: 1px solid var(--ink-12);
    border-radius: var(--r-xs);
    font-family: var(--font-display); font-size: 9px; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    color: var(--ink-45); text-decoration: none;
    transition: border-color .2s, color .2s, transform .2s;
}
.ct-social-btn:hover {
    border-color: rgba(249,115,22,0.3);
    color: var(--fire);
    transform: translateY(-2px);
}

/* ════════════════════════════════════════
   FULL-WIDTH MAP STRIP
════════════════════════════════════════ */
.ct-fullmap-wrap {
    max-width: 1120px; margin: 0 auto 64px;
    padding: 0 24px;
}
.ct-fullmap-inner {
    background: var(--white);
    border: 1px solid var(--ink-12);
    border-radius: var(--r-xl);
    overflow: hidden;
}
.ct-fullmap-head {
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    padding: 18px 24px;
    border-bottom: 1px dashed var(--ink-12);
    flex-wrap: wrap;
}
.ct-fullmap-title {
    font-family: var(--font-display); font-size: 12px; font-weight: 800;
    letter-spacing: .08em; text-transform: uppercase; color: var(--ink);
}
.ct-fullmap-sub { font-size: 12px; color: var(--ink-45); margin-top: 2px; }
.ct-fullmap-iframe { width: 100%; height: 460px; border: 0; display: block; }

/* ════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════ */
@media (max-width: 768px) {
    .ct-hero { height: 260px; }
    .ct-hero-content { padding: 0 18px 24px; }
    .ct-hero-title { font-size: 22px; }
    .ct-main {
        grid-template-columns: 1fr;
        padding: 28px 16px 60px;
        gap: 24px;
    }
    .ct-fullmap-wrap { padding: 0 16px; margin-bottom: 48px; }
    .ct-fullmap-iframe { height: 320px; }
    .ct-map-iframe { height: 280px; }
    .ct-wa-card { flex-wrap: wrap; }
}
</style>
@endpush

@section('content')
<div class="ct">

    {{-- ══ VIDEO HERO ══ --}}
    <div class="ct-hero">
        <video class="ct-hero-video"
            src="https://res.cloudinary.com/djs5pi7ev/video/upload/q_50,w_1280/v1769502814/bayanopen-hero_iqhyip.mp4"
            autoplay muted loop playsinline preload="auto"></video>
        <div class="ct-hero-overlay"></div>
        <div class="ct-hero-grain"></div>

        <div class="ct-hero-content">
            <div>
                <div class="ct-eyebrow">
                    <span class="ct-eyebrow-icon">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.68A2 2 0 012 .82h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                        </svg>
                    </span>
                    <span class="ct-eyebrow-text">Hubungi Kami</span>
                </div>
                <h1 class="ct-hero-title">Kontak &amp; Lokasi</h1>
                <p class="ct-hero-sub">Bayan Open 2026 &nbsp;·&nbsp; Balikpapan, Kalimantan Timur &nbsp;·&nbsp; 24–29 Agustus 2026</p>
            </div>
        </div>
    </div>

    {{-- ══ MAIN GRID ══ --}}
    <div class="ct-main">

        {{-- ── LEFT: INFO & FORM ── --}}
        <div class="ct-info-col">

            {{-- Section label --}}
            <div class="ct-section-label">
                <div class="ct-section-label-text">
                    <span class="ct-section-fire">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.68A2 2 0 012 .82h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                        </svg>
                    </span>
                    Informasi Kontak
                </div>
                <div class="ct-section-line"></div>
            </div>

            {{-- WhatsApp CTA 
            <div class="ct-wa-card">
                <div class="ct-wa-icon">
                    <svg width="22" height="22" fill="#25d366" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </div>
                <div class="ct-wa-content">
                    <div class="ct-wa-label">WhatsApp Panitia</div>
                    <div class="ct-wa-number">+62 812-5381-6878</div>
                    <div class="ct-wa-sub">Aktif setiap hari · Respon cepat</div>
                </div>
                <a href="https://wa.me/6281253816878" target="_blank" rel="noopener" class="ct-wa-btn">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Chat Sekarang
                </a>
            </div>--}}

            {{-- Info cards --}}
            <div class="ct-info-card">
                <div class="ct-info-icon">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                </div>
                <div class="ct-info-content">
                    <div class="ct-info-label">Tanggal Turnamen</div>
                    <div class="ct-info-value">24 – 29 Agustus 2026</div>
                    <div class="ct-info-sub">Pertandingan berlangsung selama 6 hari penuh</div>
                </div>
            </div>

            <div class="ct-info-card">
                <div class="ct-info-icon">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <div class="ct-info-content">
                    <div class="ct-info-label">Kantor Penyelenggara</div>
                    <div class="ct-info-value">PT Bayan Resources Tbk</div>
                    <div class="ct-info-sub">Komplek Balikpapan Baru Blok D4 No. 9–10<br>Jl. MT Haryono, Balikpapan, Kalimantan Timur</div>
                     <a href="https://www.google.com/maps/place/PT.+BAYAN+RESOURCES/@-1.240484,116.871857,16z/data=!4m6!3m5!1s0x2df146697baa9f95:0x35f9167ed92933ca!8m2!3d-1.240484!4d116.8718565!16s%2Fg%2F11cmhzrmtf?hl=id&entry=ttu&g_ep=EgoyMDI2MDQyMi4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener" class="ct-venue-link">
                            Buka di Google Maps
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                </div>
            </div>

            <div class="ct-info-card">
                <div class="ct-info-icon">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <path d="M8 21h8M12 17v4"/>
                    </svg>
                </div>
                <div class="ct-info-content">
                    <div class="ct-info-label">Media Sosial</div>
                    <div class="ct-info-value" style="margin-bottom:10px;">Ikuti update terbaru turnamen</div>
                    <div class="ct-social-row">
                        <a href="https://www.instagram.com/bayan_open/" target="_blank" rel="noopener" class="ct-social-btn">
                            <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            Instagram
                        </a>
                        <a href="https://www.youtube.com/@BAYANOPEN" target="_blank" rel="noopener" class="ct-social-btn">
                            <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                            YouTube
                        </a>
                    </div>
                </div>
            </div>

            {{-- Section label: Venue --}}
            <div class="ct-section-label" style="margin-top:8px;">
                <div class="ct-section-label-text">
                    <span class="ct-section-fire">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </span>
                    Venue Pertandingan
                </div>
                <div class="ct-section-line"></div>
            </div>

            {{-- Venue cards --}}
            <div class="ct-venue-stack">
                @foreach([
                    ['num'=>'1','name'=>'BSCC Dome','addr'=>'Jl. Jend. Sudirman, Balikpapan Kota, Kalimantan Timur','maps'=>'https://maps.app.goo.gl/BayanDome'],
                    ['num'=>'2','name'=>'Hevindo Arena','addr'=>'Balikpapan, Kalimantan Timur','maps'=>'https://maps.app.goo.gl/BayanHevindo'],
                    ['num'=>'3','name'=>'GOR Bulutangkis BJBJ','addr'=>'Balikpapan, Kalimantan Timur','maps'=>'https://maps.app.goo.gl/BayanBJBJ'],
                ] as $venue)
                <div class="ct-venue-card">
                    <div class="ct-venue-head">
                        <div>
                            <div class="ct-venue-name">{{ $venue['name'] }}</div>
                        </div>
                        <div class="ct-venue-num">{{ $venue['num'] }}</div>
                    </div>
                    <div class="ct-venue-body">
                        <div class="ct-venue-addr">{{ $venue['addr'] }}</div>
                        <a href="{{ $venue['maps'] }}" target="_blank" rel="noopener" class="ct-venue-link">
                            Buka di Google Maps
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        {{-- ── RIGHT: MAP & FORM ── --}}
        <div class="ct-map-col">

         <div class="ct-section-label" style="margin-top:8px;">
                <div class="ct-section-label-text">
                    <span class="ct-section-fire">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                        </svg>
                    </span>
                    Kirim Pesan
                </div>
                <div class="ct-section-line"></div>
            </div>

            {{-- Map card --}}
          <div class="ct-form-card">
                <div class="ct-form-head">
                    <div class="ct-form-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="ct-form-title">Formulir Pertanyaan</div>
                        <div class="ct-form-sub">Tim kami akan merespons dalam 1×24 jam</div>
                    </div>
                </div>
                <div class="ct-form-body" id="contactForm">
                    <div class="ct-field">
                        <label>Nama Lengkap</label>
                        <input type="text" class="ct-input" placeholder="Masukkan nama lengkap Anda" id="cfName">
                    </div>
                    <div class="ct-field">
                        <label>Subjek</label>
                        <input type="text" class="ct-input" placeholder="Topik pertanyaan Anda" id="cfSubject">
                    </div>
                    <div class="ct-field">
                        <label>Pesan</label>
                        <textarea class="ct-input" rows="4" placeholder="Tuliskan pertanyaan atau kebutuhan Anda di sini…" id="cfMessage"></textarea>
                    </div>
                    <button type="button" class="ct-submit-btn" onclick="submitViaWA()">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.68A2 2 0 012 .82h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                        </svg>
                        Kirim via WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function submitViaWA() {
    const name    = document.getElementById('cfName').value.trim();
    const subject = document.getElementById('cfSubject').value.trim();
    const message = document.getElementById('cfMessage').value.trim();

    if (!name || !message) {
        alert('Mohon isi nama dan pesan terlebih dahulu.');
        return;
    }

    const text = `Halo Panitia Bayan Open 2026 🏸\n\n*Nama:* ${name}\n' : ''}${subject ? '*Subjek:* '+subject+'\n' : ''}\n*Pesan:*\n${message}`;
    const encoded = encodeURIComponent(text);
    window.open(`https://wa.me/6282133212777?text=${encoded}`, '_blank');
}
</script>
@endpush