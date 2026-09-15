@extends('layouts.app')

@section('title', 'Galeri Foto - Bayan Open 2026')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════
   GALERI FOTO — BAYAN OPEN 2026
   Dark fire theme · Face recognition gallery
   Konsisten dengan halaman Dokumen
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
    --danger:     #ef4444;
    --r-xs:  8px;
    --r-sm:  12px;
    --r-md:  18px;
    --r-lg:  24px;
    --r-xl:  32px;
    --font-display: 'Montserrat', sans-serif;
    --font-body:    'Montserrat', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.fg { background: var(--paper); min-height: 100svh; font-family: var(--font-body); color: var(--ink); }

/* ════════════════════════════════════════
   VIDEO HERO
════════════════════════════════════════ */
.fg-hero {
    position: relative;
    height: clamp(280px, 42vw, 440px);
    overflow: hidden;
    display: flex; align-items: flex-end;
}
.fg-hero-video {
    position: absolute; inset: 0; z-index: 0;
    width: 100%; height: 100%; object-fit: cover;
    pointer-events: none;
}
.fg-hero-overlay {
    position: absolute; inset: 0; z-index: 1;
    background:
        linear-gradient(to bottom,
            rgba(13,9,6,0.45) 0%,
            rgba(13,9,6,0.30) 30%,
            rgba(13,9,6,0.82) 72%,
            rgba(13,9,6,0.98) 100%),
        radial-gradient(ellipse 80% 60% at 40% 40%, rgba(249,115,22,0.10) 0%, transparent 60%);
}
.fg-hero-grain {
    position: absolute; inset: 0; z-index: 2; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.045'/%3E%3C/svg%3E");
}
.fg-hero-content {
    position: relative; z-index: 3;
    width: 100%; max-width: 1120px;
    margin: 0 auto;
    padding: 0 28px 38px;
}
.fg-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 5px 14px 5px 8px;
    border-radius: 99px;
    border: 1px solid rgba(249,115,22,0.3);
    background: rgba(249,115,22,0.09);
    backdrop-filter: blur(8px);
    margin-bottom: 14px;
}
.fg-eyebrow-icon { color: var(--fire); display:flex; align-items:center; }
.fg-eyebrow-text {
    font-family: var(--font-display);
    font-size: 9.5px; font-weight: 700;
    letter-spacing: .18em; text-transform: uppercase;
    color: var(--fire);
}
.fg-hero-title {
    font-family: var(--font-display);
    font-size: clamp(22px, 4vw, 42px); font-weight: 800;
    color: #fff; letter-spacing: -.03em; line-height: 1.08;
    margin-bottom: 8px;
}
.fg-hero-sub {
    font-size: 13.5px; color: var(--white);
    line-height: 1.65; max-width: 520px;
}

/* ════════════════════════════════════════
   MAIN
════════════════════════════════════════ */
.fg-main {
    max-width: 1120px; margin: 0 auto;
    padding: 48px 24px 80px;
    display: flex; flex-direction: column; gap: 40px;
}

.fg-section-label {
    display: flex; align-items: center; gap: 14px;
    margin-bottom: 20px;
}
.fg-section-label-text {
    font-family: var(--font-display);
    font-size: 11px; font-weight: 800;
    color: var(--ink); letter-spacing: .08em; text-transform: uppercase;
    display: flex; align-items: center; gap: 8px;
    white-space: nowrap;
}
.fg-section-fire { color: var(--fire); display:flex; align-items:center; }
.fg-section-line { flex:1; height:1px; background: linear-gradient(90deg, var(--ink-12), transparent); }

/* ════════════════════════════════════════
   INFO BANNER
════════════════════════════════════════ */
.fg-info-banner {
    background: var(--night);
    border-radius: var(--r-lg);
    padding: 20px 24px;
    display: flex; align-items: center; gap: 16px;
    border: 1px solid rgba(255,255,255,0.05);
    flex-wrap: wrap;
}
.fg-banner-icon {
    width: 40px; height: 40px; border-radius: 12px;
    background: var(--fire-soft);
    border: 1px solid rgba(249,115,22,0.2);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: var(--fire);
}
.fg-banner-content { flex: 1; min-width: 0; }
.fg-banner-title {
    font-family: var(--font-display); font-size: 11px; font-weight: 800;
    letter-spacing: .06em; text-transform: uppercase;
    color: rgba(255,255,255,0.85); margin-bottom: 4px;
}
.fg-banner-sub {
    font-size: 12px; color: rgb(255, 255, 255); line-height: 1.6; font-weight: 300;
}
.fg-banner-badge {
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
.fg-live-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--fire);
    animation: fgblink 2s ease infinite;
}
@keyframes fgblink { 0%,100%{opacity:1} 50%{opacity:.3} }

/* ════════════════════════════════════════
   FACE VERIFICATION ENTRY CARD
   (Kartu statis di halaman — kamera sungguhan cuma jalan di dalam modal
   fullscreen #bioModal supaya pengalamannya sama seperti versi React)
════════════════════════════════════════ */
.fg-register-card {
    background: var(--paper-2);
    border-radius: var(--r-xl);
    padding: 36px 32px;
    position: relative;
    overflow: hidden;
    border: 1px solid var(--ink-12);
}
.fg-register-inner {
    position: relative; z-index: 1;
    max-width: 620px; margin: 0 auto; text-align: center;
}
.fg-register-title {
    font-family: var(--font-display); font-weight: 800;
    font-size: clamp(20px, 3vw, 28px); color: var(--ink);
    letter-spacing: -.02em; margin-bottom: 10px;
}
.fg-register-sub {
    font-size: 13px; color: var(--ink-45); line-height: 1.7; font-weight: 500;
    margin-bottom: 28px;
}
.fg-preview-box {
    position: relative; width: 100%; max-width: 440px; margin: 0 auto 24px;
    border-radius: var(--r-lg); overflow: hidden;
    border: 1.5px solid rgba(249,115,22,0.2);
    background: var(--night-2); aspect-ratio: 4 / 3;
    display: flex; align-items: center; justify-content: center;
}
.fg-preview-box-inner {
    display: flex; flex-direction: column; align-items: center; gap: 10px;
    color: var(--ash-2);
}
.fg-preview-box-text {
    font-family: var(--font-display); font-size: 9.5px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
}

.fg-actions { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 18px; }
.fg-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 22px;
    border-radius: var(--r-xs);
    font-family: var(--font-display); font-size: 10px; font-weight: 800;
    letter-spacing: .1em; text-transform: uppercase;
    text-decoration: none; cursor: pointer; border: none;
    transition: transform .2s, box-shadow .2s, background .2s;
}
.fg-btn:hover { transform: translateY(-2px); }
.fg-btn-primary {
    background: linear-gradient(135deg, var(--fire), var(--fire-deep));
    color: #fff;
    box-shadow: 0 4px 16px rgba(249,115,22,0.35);
}
.fg-btn-primary:hover { box-shadow: 0 8px 24px rgba(249,115,22,0.5); }
.fg-btn[disabled] { opacity: .4; pointer-events: none; }

.fg-status {
    font-size: 12px; padding: 12px 16px; border-radius: var(--r-sm);
    line-height: 1.5; text-align: left;
}
.fg-status.success { background: rgb(16, 185, 129); border: 1px solid rgba(17, 211, 146, 0.3); color: #ffffff; font-weight: 500; }
.fg-status.error   { background: rgb(239, 68, 68); border: 1px solid rgb(239, 68, 68); color: #ffffff; font-weight: 500; }
.fg-status.info    { background: rgb(249, 116, 22); border: 1px solid rgb(249, 116, 22); color: #fffffe; font-weight: 500; }

/* ════════════════════════════════════════
   BIOMETRIC LIVENESS SCAN MODAL (fullscreen)
   Sama fungsinya dengan komponen React BiometricScanModal:
   CENTER → LEFT → RIGHT → UP/DOWN, ngobrol ke
   /api/user/biometric/{start,frame,complete,retry}
════════════════════════════════════════ */
.fg-bio-backdrop {
    position: fixed; inset: 0; z-index: 99999;
    background: rgba(8,11,20,0.78);
    backdrop-filter: blur(10px);
    display: none; align-items: center; justify-content: center;
}
.fg-bio-backdrop.active { display: flex; }

.fg-bio-frame {
    position: relative; background: var(--night);
    width: min(400px, 92vw); height: min(820px, 88vh);
    border-radius: 44px; border: 8px solid var(--night-2);
    box-shadow: 0 30px 80px -20px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.04);
    overflow: hidden;
}
.fg-bio-video {
    position: absolute; inset: 0; width: 100%; height: 100%;
    object-fit: cover; transform: scaleX(-1); background: #000;
}
.fg-bio-badge {
    position: absolute; top: 18px; left: 16px; z-index: 10;
    background: rgba(15,23,42,.55); color: #ffd8b0;
    font-size: 11px; font-weight: 700; letter-spacing: .02em;
    padding: 6px 10px; border-radius: 99px; backdrop-filter: blur(6px);
}
.fg-bio-close {
    position: absolute; top: 18px; right: 16px; z-index: 10;
    width: 34px; height: 34px; border-radius: 50%;
    background: rgba(15,23,42,.55); border: none; color: #fff;
    font-size: 18px; display: flex; align-items: center; justify-content: center;
    cursor: pointer; backdrop-filter: blur(6px);
}
.fg-bio-close:hover { background: rgba(15,23,42,.75); }
.fg-bio-dots {
    position: absolute; top: 62px; left: 0; right: 0; z-index: 10;
    display: flex; gap: 6px; justify-content: center;
}
.fg-bio-dot {
    width: 9px; height: 9px; border-radius: 50%;
    background: rgba(255,255,255,.28);
    transition: all .2s;
}
.fg-bio-dot.done { background: var(--success); }
.fg-bio-dot.current { background: var(--fire); transform: scale(1.4); }

.fg-bio-guide {
    position: absolute; inset: 0; z-index: 6;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    pointer-events: none; padding-bottom: 12%;
}
.fg-bio-oval {
    position: relative; width: 64%; aspect-ratio: 3/4; border-radius: 50%;
    border: 4px solid rgba(255,255,255,.5);
    box-shadow: 0 0 0 9999px rgba(5,7,13,.45);
    transition: border-color .25s ease, box-shadow .25s ease;
}
.fg-bio-oval.state-progress { border-color: var(--gold); }
.fg-bio-oval.state-near,
.fg-bio-oval.state-matched   { border-color: var(--success); box-shadow: 0 0 0 9999px rgba(5,7,13,.45), 0 0 26px 4px rgba(16,185,129,.55); }
.fg-bio-oval.state-error     { border-color: var(--danger); animation: fgbioshake .35s ease; }
.fg-bio-oval.flash           { box-shadow: 0 0 0 9999px rgba(16,185,129,.55), 0 0 40px 10px rgba(16,185,129,.8) !important; }
@keyframes fgbioshake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-6px)} 75%{transform:translateX(6px)} }

.fg-bio-arrow {
    position: absolute; width: 42px; height: 42px;
    filter: drop-shadow(0 1px 3px rgba(0,0,0,.5));
    animation: fgbiopulse 1.1s ease-in-out infinite;
    display: none;
}
.fg-bio-arrow.show { display: block; }
.fg-bio-arrow.up    { top: -56px; left: 50%; transform: translateX(-50%); }
.fg-bio-arrow.down  { bottom: -56px; left: 50%; transform: translateX(-50%) rotate(180deg); }
.fg-bio-arrow.left  { left: -56px; top: 50%; transform: translateY(-50%) rotate(-90deg); }
.fg-bio-arrow.right { right: -56px; top: 50%; transform: translateY(-50%) rotate(90deg); }
@keyframes fgbiopulse { 0%,100%{opacity:.55} 50%{opacity:1} }

.fg-bio-check {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .2s;
}
.fg-bio-check.show { opacity: 1; }

.fg-bio-sheet {
    position: absolute; left: 0; right: 0; bottom: 0; z-index: 10;
    padding: 36px 24px 28px;
    text-align: center;
    background: linear-gradient(to top, rgba(2,4,10,.92) 20%, rgba(2,4,10,0));
}
.fg-bio-instruction { color: #fff; font-size: 18px; font-weight: 700; margin-bottom: 6px; }
.fg-bio-feedback { font-size: 13px; color: #cbd5e1; min-height: 18px; }
.fg-bio-feedback.err { color: #fca5a5; }

.fg-bio-status {
    position: absolute; inset: 0; z-index: 20; background: var(--night);
    display: none; flex-direction: column; align-items: center; justify-content: center;
    text-align: center; padding: 0 32px; gap: 10px;
}
.fg-bio-status.active { display: flex; }
.fg-bio-status-icon { font-size: 46px; }
.fg-bio-status-title { color: #fff; font-size: 17px; font-weight: 700; }
.fg-bio-status-sub { color: #94a3b8; font-size: 13.5px; max-width: 260px; }
.fg-bio-retry-btn {
    margin-top: 14px; padding: 10px 20px; border-radius: 99px; border: none;
    background: var(--fire); color: #fff; font-weight: 700; font-size: 13.5px; cursor: pointer;
}
.fg-bio-retry-btn:hover { background: var(--fire-deep); }

/* ════════════════════════════════════════
   DAY FILTER TABS
════════════════════════════════════════ */
.fg-day-tabs {
    display: flex; gap: 8px; flex-wrap: wrap;
}
.fg-day-tab {
    padding: 9px 18px;
    border-radius: 99px;
    border: 1.5px solid var(--ink-12);
    background: var(--white);
    font-family: var(--font-display); font-size: 10px; font-weight: 800;
    letter-spacing: .08em; text-transform: uppercase;
    color: var(--ink-45); cursor: pointer;
    transition: all .22s;
}
.fg-day-tab:hover { border-color: rgba(249,115,22,0.3); color: var(--fire); }
.fg-day-tab.active {
    background: linear-gradient(135deg, var(--fire), var(--fire-deep));
    color: #fff; border-color: transparent;
    box-shadow: 0 4px 16px rgba(249,115,22,0.3);
}

/* ════════════════════════════════════════
   RESULT HEADER
════════════════════════════════════════ */
.fg-result-head {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 14px; margin-bottom: 20px;
}
.fg-result-head-left {
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
}
.fg-result-count { font-size: 12.5px; color: var(--ink-45); }
.fg-result-count strong { color: var(--ink); font-weight: 700; }

.fg-managed-by {
    display: inline-flex; align-items: center; gap: 9px;
    padding: 11px 20px 11px 12px;
    border-radius: var(--r-xs);
    background: var(--white);
    border: 1.5px solid var(--ink-12);
    text-decoration: none; cursor: pointer; transition: border-color .2s;
}
.fg-managed-by:hover { border-color: rgba(249,115,22,0.4); }
.fg-managed-logo {
    height: 32px; width: auto; max-width: 70px; object-fit: contain; display: block; flex-shrink: 0;
}
.fg-managed-text {
    font-family: var(--font-display); font-size: 10px; font-weight: 700;
    letter-spacing: .1em; color: var(--ink-45); white-space: nowrap;
}

/* ════════════════════════════════════════
   PHOTO GRID
════════════════════════════════════════ */
.fg-photo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 18px;
}
.fg-photo-card {
    background: var(--paper-2);
    border: 0;
    border-radius: var(--r-sm);
    overflow: hidden;
    cursor: pointer;
    opacity: 0; transform: translateY(16px);
    animation: fgCardIn .5s ease-out forwards;
    transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s, border-color .3s;
}
@keyframes fgCardIn { to { opacity: 1; transform: translateY(0); } }
.fg-photo-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 44px rgba(26,16,7,0.14);
}
.fg-photo-img-wrap {
    position: relative; height: 200px; overflow: hidden; border-radius: inherit;
    background: var(--paper-2);
}
.fg-photo-img { width: 100%; height: 100%; object-fit: cover; display: block; }
.fg-photo-copyright {
    position: absolute; left: 12px; bottom: 10px;
    color: rgba(255,255,255,0.58);
    font-family: var(--font-display); font-size: 9px; font-weight: 500;
    letter-spacing: .03em; text-shadow: 0 1px 4px rgba(0,0,0,0.55);
    text-decoration: none;
    cursor: pointer;
    transition: color .2s;
}
.fg-photo-copyright:hover { color: #fff; }
.fg-photo-download {
    position: absolute; right: 10px; bottom: 7px;
    width: 30px; height: 30px; padding: 0;
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,0.82); background: rgba(13,9,6,0.42);
    border: 1px solid rgba(255,255,255,0.22); border-radius: 50%;
    cursor: pointer; transition: background .2s, color .2s;
}
.fg-photo-download:hover { color: #fff; background: rgba(249,115,22,0.85); }
.fg-photo-download.is-loading { pointer-events: none; opacity: .6; }
.fg-photo-day-badge {
    position: absolute; top: 10px; left: 10px;
    background: var(--night);
    color: var(--fire);
    font-family: var(--font-display); font-size: 8.5px; font-weight: 800;
    letter-spacing: .1em; text-transform: uppercase;
    padding: 5px 10px; border-radius: 99px;
    border: 1px solid rgba(249,115,22,0.3);
}
.fg-photo-body { display: none; }

/* ════════════════════════════════════════
   LOADING / EMPTY STATES
════════════════════════════════════════ */
.fg-loading, .fg-empty {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 14px; padding: 70px 20px; text-align: center;
}
.fg-spinner {
    width: 30px; height: 30px;
    border: 2.5px solid var(--ink-12);
    border-top-color: var(--fire);
    border-radius: 50%;
    animation: fgspin 0.7s linear infinite;
}
@keyframes fgspin { to { transform: rotate(360deg); } }
.fg-loading-text {
    font-family: var(--font-display); font-size: 10px; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase; color: var(--ink-25);
}
.fg-empty-icon { color: var(--ink-12); }
.fg-empty-title {
    font-family: var(--font-display); font-size: 14px; font-weight: 800;
    color: var(--ink); letter-spacing: -.01em;
}
.fg-empty-sub { font-size: 12.5px; color: var(--ink-45); max-width: 340px; line-height: 1.6; }

.fg-hidden { display: none !important; }

/* ════════════════════════════════════════
   MODAL FULLSCREEN PREVIEW (foto hasil galeri)
════════════════════════════════════════ */
.fg-modal-backdrop {
    position: fixed; inset: 0; z-index: 9998;
    background: #050505;
    display: none; align-items: center; justify-content: center;
    padding: 0;
    animation: fgfadein .2s ease;
}
.fg-modal-backdrop.active { display: flex; }
@keyframes fgfadein { from{opacity:0} to{opacity:1} }

.fg-modal {
    position: relative; width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
}
.fg-modal-head { position: absolute; inset: 0; z-index: 3; pointer-events: none; }
.fg-modal-close {
    position: absolute; top: 22px; right: 24px; width: 42px; height: 42px; border-radius: 50%;
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.24);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: #fff; pointer-events: auto;
    transition: background .2s, color .2s, border-color .2s;
    flex-shrink: 0;
}
.fg-modal-close:hover { background: rgba(255,255,255,0.24); border-color: #fff; }
.fg-modal-body { width: 100%; height: 100%; }
.fg-modal-img-wrap { position: relative; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.fg-modal-img { width: 100%; height: 100%; object-fit: contain; display: block; cursor: pointer; }
.fg-modal-quality-badge {
    position: absolute; left: 28px; bottom: 24px;
    color: rgba(255,255,255,0.58); background: transparent;
    font-family: var(--font-display); font-size: 11px; font-weight: 500;
    letter-spacing: .04em; padding: 0; border-radius: 0;
    text-decoration: none; cursor: pointer; transition: color .2s;
}
.fg-modal-quality-badge:hover { color: #fff; }
.fg-modal-download {
    position: absolute; right: 28px; bottom: 18px; z-index: 2;
    width: 38px; height: 38px; padding: 0; border: 0; background: transparent;
    color: rgba(255,255,255,0.72); cursor: pointer;
}
.fg-modal-download:hover { color: #fff; }
.fg-modal-download.is-loading { pointer-events: none; opacity: .5; }
.fg-modal-nav {
    position: absolute; top: 50%; z-index: 2; transform: translateY(-50%);
    width: 48px; height: 48px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1);
    color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center;
}
.fg-modal-nav:hover { background: rgba(255,255,255,0.22); }
.fg-modal-prev { left: 24px; }
.fg-modal-next { right: 24px; }

/* Toast notification */
.fg-toast {
    position: fixed; top: 18px; right: 18px; z-index: 10002;
    max-width: 340px;
    background: var(--night);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: var(--r-sm);
    padding: 13px 16px;
    display: flex; align-items: center; gap: 10px;
    box-shadow: 0 16px 40px rgba(13,9,6,0.4);
    animation: fgtoastin .3s cubic-bezier(.22,1,.36,1);
}
@keyframes fgtoastin { from{transform:translateX(60px);opacity:0} to{transform:translateX(0);opacity:1} }
@keyframes fgtoastout { from{transform:translateX(0);opacity:1} to{transform:translateX(60px);opacity:0} }
.fg-toast-icon { color: var(--fire); flex-shrink: 0; }
.fg-toast-text { font-size: 12px; color: #fff; line-height: 1.5; }

/* ════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════ */
@media (max-width: 768px) {
    .fg-hero { height: 260px; }
    .fg-hero-content { padding: 0 18px 24px; }
    .fg-hero-title { font-size: 22px; }
    .fg-main { padding: 28px 16px 60px; gap: 28px; }
    .fg-register-card { padding: 28px 18px; }
    .fg-photo-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; }
    .fg-photo-img-wrap { height: 150px; }
    .fg-modal-close { top: 14px; right: 14px; }
    .fg-modal-quality-badge { left: 16px; bottom: 16px; }
    .fg-modal-download { right: 16px; bottom: 10px; }
    .fg-modal-prev { left: 10px; }
    .fg-modal-next { right: 10px; }
}
</style>
@endpush

@section('content')
<div class="fg">

    {{-- ══ VIDEO HERO ══ --}}
    <div class="fg-hero">
        <image class="fg-hero-video"
            src="https://ik.imagekit.io/zaekg3ju7/AR__2415.JPG"
           preload="auto"></image>
        <div class="fg-hero-overlay"></div>
        <div class="fg-hero-grain"></div>

        <div class="fg-hero-content">
            <h1 class="fg-hero-title">Galeri Foto</h1>
            <p class="fg-hero-sub">Bayan Open 2026 &nbsp;·&nbsp; Balikpapan, Kalimantan Timur &nbsp;·&nbsp; 24–29 Agustus 2026</p>
        </div>
    </div>

    {{-- ══ MAIN ══ --}}
    <div class="fg-main">

        {{-- Face verification entry point --}}
        <div id="registerSection">
            <div class="fg-register-card">
                <div class="fg-register-inner">
                    <h2 class="fg-register-title">Cari Fotomu dengan Wajah</h2>
                    <p class="fg-register-sub">Ikuti instruksi arah kepala di popup lalu sistem akan mencari semua foto pertandingan yang memuat wajah Anda.</p>

                    <div class="fg-actions">
                        <button id="btnOpenBioScan" class="fg-btn fg-btn-primary" onclick="openBioScan()">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                            Nyalakan Kamera & Verifikasi
                        </button>
                    </div>

                    <div style="display:flex; justify-content:center; margin-bottom:18px;">
                        <a href="https://ambilfoto.id" target="_blank" rel="noopener" class="fg-managed-by">
                            <img class="fg-managed-logo"
                                src="https://res.cloudinary.com/dwyi4d3rq/image/upload/v1765171746/ambilfoto-logo_hvn8s2.png"
                                alt="AmbilFoto.id">
                            <span class="fg-managed-text">Managed by AmbilFoto.id</span>
                        </a>
                    </div>
                    <div id="statusMessage" class="fg-status fg-hidden"></div>
                </div>
            </div>
        </div>

        {{-- Gallery results --}}
        <div id="gallerySection" class="fg-hidden">

            <div class="fg-section-label">
                <div class="fg-section-label-text">
                    <span class="fg-section-fire">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <path d="M21 15l-5-5L5 21"/>
                        </svg>
                    </span>
                    Foto Anda
                </div>
                <div class="fg-section-line"></div>
            </div>

            <div class="fg-result-head">
                <div class="fg-result-head-left">
                    <div class="fg-result-count" id="photoCount">Ditemukan <strong>0</strong> foto</div>
                    <a href="https://ambilfoto.id" target="_blank" rel="noopener" class="fg-managed-by">
                        <img class="fg-managed-logo"
                            src="https://res.cloudinary.com/dwyi4d3rq/image/upload/v1765171746/ambilfoto-logo_hvn8s2.png"
                            alt="AmbilFoto.id">
                        <span class="fg-managed-text">Managed by AmbilFoto.id</span>
                    </a>
                </div>
                <button class="fg-btn" style="background:var(--night);color:#fff;border:1.5px solid rgba(249,115,22,0.3);" onclick="resetFaceData()">
                    Ulangi Pencarian
                </button>
            </div>

            <div class="fg-day-tabs fg-hidden" id="dayTabs"></div>

            <div class="fg-loading fg-hidden" id="loadingPhotos" style="margin-top:20px;">
                <div class="fg-spinner"></div>
                <div class="fg-loading-text">Mencari foto Anda…</div>
            </div>

            <div class="fg-photo-grid fg-hidden" id="photosGrid" style="margin-top:20px;"></div>

            <div class="fg-empty fg-hidden" id="emptyState">
                <div class="fg-empty-icon">
                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <path d="M21 15l-5-5L5 21"/>
                    </svg>
                </div>
                <div class="fg-empty-title">Belum Ada Foto Ditemukan</div>
                <div class="fg-empty-sub">Wajah Anda belum terdeteksi di hari ini. Coba pilih hari lain atau cek kembali nanti setelah panitia mengunggah lebih banyak foto.</div>
            </div>

        </div>

    </div>{{-- /.fg-main --}}

</div>{{-- /.fg --}}

{{-- ══ BIOMETRIC LIVENESS SCAN MODAL ══ --}}
<div class="fg-bio-backdrop" id="bioBackdrop">
    <div class="fg-bio-frame">
        <video id="bioVideo" class="fg-bio-video" autoplay playsinline muted></video>

        <div class="fg-bio-badge">Verifikasi Wajah</div>
        <button class="fg-bio-close" id="bioCloseBtn" aria-label="Tutup" onclick="cancelBioScan()">×</button>

        <div class="fg-bio-dots fg-hidden" id="bioDots"></div>

        <div class="fg-bio-guide" id="bioGuide">
            <div class="fg-bio-oval" id="bioOval">
                <div class="fg-bio-arrow up"    id="bioArrowUp">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 4 L12 20 M12 4 L6 10 M12 4 L18 10" stroke="#facc15" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="fg-bio-arrow down"  id="bioArrowDown">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 4 L12 20 M12 4 L6 10 M12 4 L18 10" stroke="#facc15" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="fg-bio-arrow left"  id="bioArrowLeft">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 4 L12 20 M12 4 L6 10 M12 4 L18 10" stroke="#facc15" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="fg-bio-arrow right" id="bioArrowRight">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 4 L12 20 M12 4 L6 10 M12 4 L18 10" stroke="#facc15" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="fg-bio-check" id="bioCheck">
                    <svg viewBox="0 0 24 24" fill="none" width="60" height="60">
                        <circle cx="12" cy="12" r="11" fill="#22c55e"/>
                        <path d="M7 12.5 L10.5 16 L17 8.5" stroke="white" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="fg-bio-sheet" id="bioSheet">
            <div class="fg-bio-instruction" id="bioInstruction">Menyiapkan kamera…</div>
            <div class="fg-bio-feedback" id="bioFeedback"></div>
        </div>

        <div class="fg-bio-status" id="bioStatus">
            <div class="fg-bio-status-icon" id="bioStatusIcon">⚠️</div>
            <div class="fg-bio-status-title" id="bioStatusTitle">Sesi bermasalah</div>
            <div class="fg-bio-status-sub" id="bioStatusSub"></div>
            <button class="fg-bio-retry-btn fg-hidden" id="bioRetryBtn" onclick="retryBioScan()">🔄 Coba Lagi</button>
        </div>
    </div>
</div>

{{-- ══ MODAL DETAIL FOTO ══ --}}
<div class="fg-modal-backdrop" id="photoModal" onclick="closeModalOutside(event)">
    <div class="fg-modal">
        <div class="fg-modal-head">
            <button class="fg-modal-close" onclick="closeModal()" aria-label="Tutup">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="fg-modal-body" id="modalBody"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function(){
    fetch('/api/track-visit', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ page: 'galeri-foto', label: 'Galeri Foto' })
    }).catch(()=>{});
})();
</script>
<script>
/* ══════════════════════════════════════════════
   KONFIGURASI
   ══════════════════════════════════════════════ */
const API_BASE_URL = 'https://gallery.bayanopen.com';
const BIOMETRIC_BASE = `${API_BASE_URL}/api/user/biometric`;
const PHOTOS_BY_USER_ENDPOINT = `${API_BASE_URL}/api/user/my_photos_by_id`;
const IMAGE_ENDPOINT = (filename) => `${API_BASE_URL}/api/preview/${filename}`;
const DOWNLOAD_ENDPOINT = (filename) => `${API_BASE_URL}/api/download/${filename}`;

const EVENT_SLUG = 'bayan-open-craft';
const STORAGE_KEY = `ambilfoto_user_id_${EVENT_SLUG}`;
const MIN_ANGLES = 4;
const BIO_CAPTURE_INTERVAL_MS = 600;
const NEAR_MATCH_THRESHOLD = 0.85;
const CHALLENGE_TRANSITION_MS = 450; // harus sama dengan delay setTimeout saat pindah challenge

/* Tanggal mulai turnamen — dipakai untuk memetakan tanggal foto ke label "Day N" */
const EVENT_START_DATE = '2026-08-24';
const EVENT_TOTAL_DAYS = 8;

const DIRECTION_META = {
    CENTER: { arrow: null,   label: 'Lihat lurus ke kamera' },
    LEFT:   { arrow: 'left',  label: 'Tolehkan kepala ke KIRI' },
    RIGHT:  { arrow: 'right', label: 'Tolehkan kepala ke KANAN' },
    UP:     { arrow: 'up',    label: 'Angkat dagu / lihat ke ATAS' },
    DOWN:   { arrow: 'down',  label: 'Tundukkan kepala ke BAWAH' },
};

/* ══════════════════════════════════════════════
   STATE — sesi scan biometrik
   ══════════════════════════════════════════════ */
let bioStream = null;
let bioSessionId = null;
let bioChallengeSequence = [];
let bioCurrentStep = 0;
let bioCurrentChallenge = null;
let bioCaptureTimer = null;
let bioFrameInFlight = false;
let bioClosed = false;
let bioFlashTimeout = null;

// Kunci capture loop selama window transisi antar-challenge (450ms). Tanpa ini,
// sebuah capture tick bisa jalan tepat saat server sudah maju ke challenge
// berikutnya tapi client belum, sehingga frame terkirim dengan challenge yang
// salah/basi → server balas 400 "Challenge tidak sesuai urutan".
let bioTransitioning = false;
let bioTransitionTimeout = null;

/* State galeri (identik dengan sebelumnya) */
let allPhotos = [];
let currentDay = 'all';
let visiblePhotos = [];
let currentPhotoIndex = 0;

/* Restore sesi sebelumnya */
window.addEventListener('DOMContentLoaded', () => {
    const storedUserId = localStorage.getItem(STORAGE_KEY);
    if (storedUserId) {
        document.getElementById('registerSection').classList.add('fg-hidden');
        loadPhotos(storedUserId);
    }
});

/* ══════════════════════════════════════════════
   HELPERS
   ══════════════════════════════════════════════ */
function toHttps(url) { return url.replace(/^http:/i, 'https:'); }

function computeDayLabel(dateStr) {
    if (!dateStr) return '';
    const start = new Date(EVENT_START_DATE + 'T00:00:00');
    const photoDate = new Date(dateStr);
    if (isNaN(photoDate.getTime())) return '';
    const diffDays = Math.floor((photoDate - start) / (1000 * 60 * 60 * 24));
    const dayNum = diffDays + 1;
    if (dayNum < 1 || dayNum > EVENT_TOTAL_DAYS) return '';
    return `Day ${dayNum}`;
}

function getPhotoImageUrl(photo) {
    return toHttps(photo.preview_url || IMAGE_ENDPOINT(photo.filename));
}
function getPhotoDownloadUrl(photo) {
    return photo.url || DOWNLOAD_ENDPOINT(photo.filename);
}

function getCameraErrorMessage(error) {
    const name = error && error.name;
    switch (name) {
        case 'NotAllowedError':
        case 'PermissionDeniedError':
            return 'Akses kamera ditolak. Izinkan akses kamera pada browser Anda (ikon gembok di address bar), lalu coba lagi.';
        case 'NotFoundError':
        case 'DevicesNotFoundError':
            return 'Kamera tidak ditemukan di perangkat ini. Pastikan perangkat Anda memiliki kamera yang aktif.';
        case 'NotReadableError':
        case 'TrackStartError':
            return 'Kamera sedang dipakai aplikasi lain. Tutup aplikasi/tab lain yang menggunakan kamera, lalu coba lagi.';
        case 'OverconstrainedError':
            return 'Kamera pada perangkat Anda tidak mendukung pengaturan yang dibutuhkan. Coba gunakan perangkat lain.';
        case 'SecurityError':
            return 'Akses kamera diblokir. Pastikan halaman ini dibuka melalui koneksi aman (https).';
        default:
            return 'Kamera tidak bisa diakses. Pastikan Anda sudah mengizinkan akses kamera pada browser, lalu coba lagi.';
    }
}

/** Deteksi error "challenge tidak sesuai urutan" dari API supaya cukup di-skip
 *  diam-diam (frame basi/telat), bukan langsung menganggap sesi gagal total. */
function isSequenceMismatchError(message) {
    if (!message) return false;
    const m = message.toLowerCase();
    return m.includes('tidak sesuai urutan') || m.includes('out of order') || m.includes('sequence');
}

function showStatus(message, type) {
    const el = document.getElementById('statusMessage');
    el.classList.remove('fg-hidden');
    el.className = 'fg-status ' + type;
    el.textContent = message;
}

/* ══════════════════════════════════════════════
   MODAL OPEN / CLOSE
   ══════════════════════════════════════════════ */
function openBioScan() {
    document.getElementById('statusMessage').classList.add('fg-hidden');
    resetBioUI();
    document.getElementById('bioBackdrop').classList.add('active');
    document.body.style.overflow = 'hidden';
    bioClosed = false;
    startBioSession();
}

function resetBioUI() {
    document.getElementById('bioStatus').classList.remove('active');
    document.getElementById('bioRetryBtn').classList.add('fg-hidden');
    document.getElementById('bioGuide').style.display = '';
    document.getElementById('bioSheet').style.display = '';
    document.getElementById('bioInstruction').textContent = 'Menyiapkan kamera…';
    document.getElementById('bioFeedback').textContent = '';
    document.getElementById('bioFeedback').classList.remove('err');
    document.getElementById('bioOval').className = 'fg-bio-oval';
    document.getElementById('bioCheck').classList.remove('show');
    hideAllArrows();
}

function cancelBioScan() {
    bioClosed = true;
    cleanupBioCamera();
    document.getElementById('bioBackdrop').classList.remove('active');
    document.body.style.overflow = '';
}

function cleanupBioCamera() {
    if (bioCaptureTimer) { clearInterval(bioCaptureTimer); bioCaptureTimer = null; }
    if (bioFlashTimeout) { clearTimeout(bioFlashTimeout); bioFlashTimeout = null; }
    if (bioTransitionTimeout) { clearTimeout(bioTransitionTimeout); bioTransitionTimeout = null; }
    bioTransitioning = false;
    if (bioStream) {
        bioStream.getTracks().forEach(t => t.stop());
        bioStream = null;
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && document.getElementById('bioBackdrop').classList.contains('active')) {
        cancelBioScan();
    }
});

/* ══════════════════════════════════════════════
   CHALLENGE UI
   ══════════════════════════════════════════════ */
function hideAllArrows() {
    ['Up', 'Down', 'Left', 'Right'].forEach(d => document.getElementById('bioArrow' + d).classList.remove('show'));
}

function applyChallenge(challenge) {
    const meta = DIRECTION_META[challenge] || DIRECTION_META.CENTER;
    bioCurrentChallenge = challenge;
    document.getElementById('bioInstruction').textContent = meta.label;
    document.getElementById('bioFeedback').textContent = '';
    document.getElementById('bioFeedback').classList.remove('err');
    document.getElementById('bioOval').className = 'fg-bio-oval';
    document.getElementById('bioCheck').classList.remove('show');
    hideAllArrows();
    if (meta.arrow) {
        const map = { up: 'Up', down: 'Down', left: 'Left', right: 'Right' };
        document.getElementById('bioArrow' + map[meta.arrow]).classList.add('show');
    }
}

function renderDots() {
    const wrap = document.getElementById('bioDots');
    wrap.classList.remove('fg-hidden');
    wrap.innerHTML = bioChallengeSequence.map((_, i) => {
        const cls = i < bioCurrentStep ? 'done' : (i === bioCurrentStep ? 'current' : '');
        return `<div class="fg-bio-dot ${cls}"></div>`;
    }).join('');
}

/* ══════════════════════════════════════════════
   SESSION LIFECYCLE
   ══════════════════════════════════════════════ */
async function startBioSession() {
    try {
        bioStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: 1280, height: 720 },
            audio: false
        });
        const video = document.getElementById('bioVideo');
        video.srcObject = bioStream;
        await video.play().catch(() => {});
    } catch (err) {
        showBioStatus('🚫', 'Kamera tidak bisa diakses', getCameraErrorMessage(err), false);
        return;
    }

    try {
        const resp = await fetch(`${BIOMETRIC_BASE}/start`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ event_slug: EVENT_SLUG, min_angles: MIN_ANGLES })
        });
        const data = await resp.json();
        if (!data.success) throw new Error(data.error || 'Gagal memulai sesi');

        bioSessionId = data.session_id;
        bioChallengeSequence = data.challenge;
        bioCurrentStep = 0;
        bioTransitioning = false;
        renderDots();
        applyChallenge(data.challenge[0]);
        startBioCaptureLoop();
    } catch (err) {
        showBioStatus('⚠️', 'Gagal memulai verifikasi', err && err.message, false);
    }
}

async function retryBioScan() {
    document.getElementById('bioStatus').classList.remove('active');
    document.getElementById('bioGuide').style.display = '';
    document.getElementById('bioSheet').style.display = '';
    document.getElementById('bioInstruction').textContent = 'Menyiapkan ulang…';

    try {
        if (bioSessionId) {
            fetch(`${BIOMETRIC_BASE}/retry`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ session_id: bioSessionId, scope: 'session' })
            }).catch(() => {});
        }
        const resp = await fetch(`${BIOMETRIC_BASE}/start`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ event_slug: EVENT_SLUG, min_angles: MIN_ANGLES })
        });
        const data = await resp.json();
        if (!data.success) {
            showBioStatus('⚠️', 'Gagal memulai ulang', data.error, true);
            return;
        }
        bioSessionId = data.session_id;
        bioChallengeSequence = data.challenge;
        bioCurrentStep = 0;
        bioTransitioning = false;
        renderDots();
        applyChallenge(data.challenge[0]);
        startBioCaptureLoop();
    } catch (err) {
        showBioStatus('⚠️', 'Gagal memulai ulang', err && err.message, true);
    }
}

function startBioCaptureLoop() {
    if (bioCaptureTimer) clearInterval(bioCaptureTimer);
    bioCaptureTimer = setInterval(captureAndSubmitBioFrame, BIO_CAPTURE_INTERVAL_MS);
}

/* ══════════════════════════════════════════════
   FRAME CAPTURE + SUBMIT (fix race condition di sini)
   ══════════════════════════════════════════════ */
async function captureAndSubmitBioFrame() {
    if (
        bioFrameInFlight ||
        bioTransitioning ||     // ⬅️ FIX: skip total selama masa transisi challenge
        bioClosed ||
        !bioSessionId ||
        !bioCurrentChallenge
    ) return;

    const video = document.getElementById('bioVideo');
    if (!video || !video.videoWidth) return;

    bioFrameInFlight = true;
    try {
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        if (!ctx) return;
        ctx.drawImage(video, 0, 0);
        const imageData = canvas.toDataURL('image/jpeg', 0.85);

        const sentChallenge = bioCurrentChallenge; // snapshot, defensif

        const resp = await fetch(`${BIOMETRIC_BASE}/frame`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                session_id: bioSessionId,
                challenge: sentChallenge,
                frame: imageData
            })
        });
        const data = await resp.json();

        if (!data.success) {
            if (isSequenceMismatchError(data.error)) {
                console.warn('[BioScan] frame basi/telat, di-skip:', data.error);
                return;
            }
            if (bioCaptureTimer) { clearInterval(bioCaptureTimer); bioCaptureTimer = null; }
            showBioStatus('⚠️', 'Sesi bermasalah', data.error, true);
            return;
        }

        if (!data.matched) {
            const isErr = data.pose_progress === undefined;
            const progress = data.pose_progress || 0;
            const feedbackEl = document.getElementById('bioFeedback');
            feedbackEl.textContent = data.message || 'Lanjutkan gerakan…';
            feedbackEl.classList.toggle('err', isErr);

            const oval = document.getElementById('bioOval');
            oval.className = 'fg-bio-oval';
            if (isErr) oval.classList.add('state-error');
            else if (progress >= NEAR_MATCH_THRESHOLD) oval.classList.add('state-near');
            else if (progress > 0.05) oval.classList.add('state-progress');
            return;
        }

        // ── matched ──
        document.getElementById('bioFeedback').textContent = 'Bagus! Tertangkap wajahmu';
        document.getElementById('bioFeedback').classList.remove('err');
        const oval = document.getElementById('bioOval');
        oval.className = 'fg-bio-oval state-matched';
        document.getElementById('bioCheck').classList.add('show');
        hideAllArrows();
        if (data.progress) { bioCurrentStep = data.progress.current_step; renderDots(); }

        oval.classList.add('flash');
        if (bioFlashTimeout) clearTimeout(bioFlashTimeout);
        bioFlashTimeout = setTimeout(() => oval.classList.remove('flash'), 650);

        if (data.done) {
            bioTransitioning = true; // kunci — sesi mau selesai, tidak boleh ada frame lagi
            if (bioCaptureTimer) { clearInterval(bioCaptureTimer); bioCaptureTimer = null; }
            await completeBioSession();
        } else if (data.next_challenge) {
            // ⬅️ FIX: kunci capture loop sampai currentChallenge benar-benar
            // pindah, supaya tidak ada frame terkirim dengan challenge basi.
            bioTransitioning = true;
            if (bioTransitionTimeout) clearTimeout(bioTransitionTimeout);
            bioTransitionTimeout = setTimeout(() => {
                applyChallenge(data.next_challenge);
                bioTransitioning = false;
                bioTransitionTimeout = null;
            }, CHALLENGE_TRANSITION_MS);
        }
    } catch (err) {
        console.error('[BioScan] frame submit error:', err);
    } finally {
        bioFrameInFlight = false;
    }
}

async function completeBioSession() {
    document.getElementById('bioInstruction').textContent = 'Memverifikasi…';
    document.getElementById('bioFeedback').textContent = '';
    try {
        const resp = await fetch(`${BIOMETRIC_BASE}/complete`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ session_id: bioSessionId })
        });
        const data = await resp.json();
        if (!data.success || !data.user_id) {
            showBioStatus('⚠️', 'Verifikasi gagal', data.error, true);
            return;
        }
        showBioStatus('✅', 'Wajah terverifikasi!', `Liveness score: ${((data.liveness_score || 0) * 100).toFixed(0)}%`, false);
        cleanupBioCamera();
        setTimeout(() => {
            if (bioClosed) return;
            bioClosed = true;
            localStorage.setItem(STORAGE_KEY, data.user_id);
            document.getElementById('bioBackdrop').classList.remove('active');
            document.body.style.overflow = '';
            document.getElementById('registerSection').classList.add('fg-hidden');
            showStatus(`Wajah berhasil dikenali (liveness ${((data.liveness_score || 0) * 100).toFixed(0)}%). Mencari foto Anda…`, 'success');
            loadPhotos(data.user_id);
        }, 900);
    } catch (err) {
        showBioStatus('⚠️', 'Gagal menyelesaikan verifikasi', err && err.message, true);
    }
}

function showBioStatus(icon, title, sub, withRetry) {
    document.getElementById('bioGuide').style.display = 'none';
    document.getElementById('bioSheet').style.display = 'none';
    document.getElementById('bioStatusIcon').textContent = icon;
    document.getElementById('bioStatusTitle').textContent = title;
    document.getElementById('bioStatusSub').textContent = sub || '';
    document.getElementById('bioRetryBtn').classList.toggle('fg-hidden', !withRetry);
    document.getElementById('bioStatus').classList.add('active');
}

/* ══════════════════════════════════════════════
   LOAD & FILTER PHOTOS
   ══════════════════════════════════════════════ */
async function loadPhotos(userId) {
    document.getElementById('gallerySection').classList.remove('fg-hidden');
    document.getElementById('loadingPhotos').classList.remove('fg-hidden');
    document.getElementById('photosGrid').classList.add('fg-hidden');
    document.getElementById('emptyState').classList.add('fg-hidden');

    try {
        const response = await fetch(PHOTOS_BY_USER_ENDPOINT, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_id: userId, event_slug: EVENT_SLUG })
        });
        const data = await response.json();

        document.getElementById('loadingPhotos').classList.add('fg-hidden');

        if (data.success && data.photos && data.photos.length > 0) {
            allPhotos = data.photos.map(p => ({
                ...p,
                metadata: { ...(p.metadata || {}), day: computeDayLabel(p.metadata && p.metadata.date) }
            }));
            renderDayTabs();
            const filtered = currentDay === 'all' ? allPhotos : allPhotos.filter(p => p.metadata && p.metadata.day === currentDay);
            renderPhotos(filtered);
        } else {
            allPhotos = [];
            document.getElementById('dayTabs').classList.add('fg-hidden');
            setEmptyState('Belum Ada Foto Ditemukan', 'Wajah Anda belum terdeteksi. Coba lagi nanti setelah panitia mengunggah lebih banyak foto.');
            document.getElementById('emptyState').classList.remove('fg-hidden');
            document.getElementById('photoCount').innerHTML = 'Ditemukan <strong>0</strong> foto';
        }
    } catch (error) {
        console.error('Gagal memuat foto:', error);
        document.getElementById('loadingPhotos').classList.add('fg-hidden');
        setEmptyState('Gagal Memuat Foto', 'Sepertinya ada gangguan koneksi ke server. Silakan periksa koneksi internet Anda dan coba lagi.');
        document.getElementById('emptyState').classList.remove('fg-hidden');
    }
}

function renderDayTabs() {
    const wrap = document.getElementById('dayTabs');
    if (allPhotos.length === 0) { wrap.classList.add('fg-hidden'); return; }
    wrap.classList.remove('fg-hidden');
    const days = ['all', ...Array.from({ length: EVENT_TOTAL_DAYS }, (_, i) => `Day ${i + 1}`)];
    wrap.innerHTML = days.map(d => `
        <button class="fg-day-tab ${currentDay === d ? 'active' : ''}" onclick="filterByDay('${d}', this)">
            ${d === 'all' ? 'Semua Hari' : d}
        </button>
    `).join('');
}

function filterByDay(day, btn) {
    currentDay = day;
    document.querySelectorAll('.fg-day-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    const filtered = day === 'all' ? allPhotos : allPhotos.filter(p => p.metadata && p.metadata.day === day);
    renderPhotos(filtered);
}

function setEmptyState(title, sub) {
    const emptyEl = document.getElementById('emptyState');
    const titleEl = emptyEl.querySelector('.fg-empty-title');
    const subEl = emptyEl.querySelector('.fg-empty-sub');
    if (titleEl) titleEl.textContent = title;
    if (subEl) subEl.textContent = sub;
}

function renderPhotos(photos) {
    const grid = document.getElementById('photosGrid');
    const empty = document.getElementById('emptyState');
    visiblePhotos = photos;

    document.getElementById('photoCount').innerHTML = `Ditemukan <strong>${photos.length}</strong> foto`;

    if (photos.length === 0) {
        grid.classList.add('fg-hidden');
        setEmptyState('Belum Ada Foto Ditemukan', 'Wajah Anda belum terdeteksi pada hari yang dipilih. Coba pilih hari lain atau cek kembali nanti.');
        empty.classList.remove('fg-hidden');
        return;
    }

    empty.classList.add('fg-hidden');
    grid.classList.remove('fg-hidden');

    grid.innerHTML = photos.map((photo, i) => {
        const imgUrl = getPhotoImageUrl(photo);
        const dayBadge = photo.metadata && photo.metadata.day
            ? `<div class="fg-photo-day-badge">${photo.metadata.day}</div>` : '';
        return `
            <div class="fg-photo-card" style="animation-delay:${Math.min(i * 0.05, 0.6)}s"
                 onclick="showPhotoDetail(${i})" role="button" tabindex="0"
                 onkeydown="if (event.key === 'Enter' || event.key === ' ') showPhotoDetail(${i})">
                <div class="fg-photo-img-wrap">
                    <img class="fg-photo-img" src="${imgUrl}" alt="${photo.filename}" loading="lazy"
                         onerror="this.closest('.fg-photo-card').style.display='none'">
                    ${dayBadge}
                    <a href="https://ambilfoto.id" target="_blank" rel="noopener" class="fg-photo-copyright" onclick="event.stopPropagation()">© AmbilFoto.id</a>
                    <button class="fg-photo-download" aria-label="Unduh foto" title="Unduh foto"
                            onclick="event.stopPropagation(); downloadPhoto('${getPhotoDownloadUrl(photo)}', '${photo.filename}', this)">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 3v12m0 0 4-4m-4 4-4-4M5 21h14"/>
                        </svg>
                    </button>
                </div>
            </div>
        `;
    }).join('');
}

/* ══ FULLSCREEN PHOTO PREVIEW ══ */
function showPhotoDetail(index) {
    if (!visiblePhotos[index]) return;
    currentPhotoIndex = index;
    const photo = visiblePhotos[index];
    const modal = document.getElementById('modalBody');
    const imgUrl = getPhotoImageUrl(photo);

    modal.innerHTML = `
        <div class="fg-modal-img-wrap" onclick="closeModal()">
            <img class="fg-modal-img" src="${imgUrl}" alt="Foto pertandingan">
             <a href="https://ambilfoto.id" target="_blank" rel="noopener" class="fg-modal-quality-badge" onclick="event.stopPropagation()">© AmbilFoto.id</a>
            <button class="fg-modal-download" aria-label="Unduh foto" title="Unduh foto"
                    onclick="event.stopPropagation(); downloadPhoto('${getPhotoDownloadUrl(photo)}', '${photo.filename}', this)">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M12 3v12m0 0 4-4m-4 4-4-4M5 21h14"/>
                </svg>
            </button>
            <button class="fg-modal-nav fg-modal-prev" aria-label="Foto sebelumnya" title="Foto sebelumnya"
                    onclick="event.stopPropagation(); navigatePhoto(-1)">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <button class="fg-modal-nav fg-modal-next" aria-label="Foto berikutnya" title="Foto berikutnya"
                    onclick="event.stopPropagation(); navigatePhoto(1)">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
            </button>
        </div>
    `;

    document.getElementById('photoModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function navigatePhoto(direction) {
    if (!visiblePhotos.length) return;
    const nextIndex = (currentPhotoIndex + direction + visiblePhotos.length) % visiblePhotos.length;
    showPhotoDetail(nextIndex);
}

function closeModal() {
    document.getElementById('photoModal').classList.remove('active');
    document.body.style.overflow = '';
}

function closeModalOutside(event) {
    if (event.target === event.currentTarget) closeModal();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

/* ══ DOWNLOAD ══
   Diunduh langsung (fetch → blob → object URL). Server di API_BASE_URL wajib
   mengirim header CORS untuk endpoint download; kalau tidak, otomatis
   fallback ke buka tab baru. */
async function downloadPhoto(url, filename, btnEl) {
    const secureUrl = toHttps(url);

    if (btnEl) btnEl.classList.add('is-loading');
    showToast('Mempersiapkan unduhan…');

    try {
        const response = await fetch(secureUrl, { mode: 'cors' });
        if (!response.ok) throw new Error('Respons server tidak OK (' + response.status + ')');

        const blob = await response.blob();
        const blobUrl = URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = blobUrl;
        a.download = filename || 'foto.jpg';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        setTimeout(() => URL.revokeObjectURL(blobUrl), 4000);
        showToast('Unduhan dimulai — kualitas HD penuh.');
    } catch (error) {
        console.error('Download error:', error);
        showToast('Tidak bisa mengunduh langsung, membuka foto di tab baru.');
        window.open(secureUrl, '_blank');
    } finally {
        if (btnEl) btnEl.classList.remove('is-loading');
    }
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fg-toast';
    toast.innerHTML = `
        <span class="fg-toast-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </span>
        <span class="fg-toast-text">${message}</span>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.animation = 'fgtoastout .3s ease-in forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/* ══ RESET ══ */
function resetFaceData() {
    if (!confirm('Ulangi pencarian wajah? Data wajah yang tersimpan akan dihapus dari perangkat ini.')) return;
    localStorage.removeItem(STORAGE_KEY);
    allPhotos = [];
    currentDay = 'all';
    document.getElementById('gallerySection').classList.add('fg-hidden');
    document.getElementById('registerSection').classList.remove('fg-hidden');
    document.getElementById('statusMessage').classList.add('fg-hidden');
    window.scrollTo({ top: document.getElementById('registerSection').offsetTop - 100, behavior: 'smooth' });
}
</script>
@endpush