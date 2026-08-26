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
   FACE REGISTRATION CARD
════════════════════════════════════════ */
.fg-register-card {
    background: transparent;
    border-radius: var(--r-xl);
    padding: 36px 32px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.05);
}
.fg-register-card::before {
    content: '';
    position: absolute; top: -40%; right: -10%;
    width: 420px; height: 420px;
    background: transparent; border-radius: 50%;
    pointer-events: none;
}
.fg-register-inner {
    position: relative; z-index: 1;
    max-width: 620px; margin: 0 auto; text-align: center;
}
.fg-register-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 5px 14px 5px 8px;
    border-radius: 99px;
    border: 1px solid rgba(249,115,22,0.3);
    background: rgba(249,115,22,0.09);
    font-family: var(--font-display); font-size: 9px; font-weight: 800;
    letter-spacing: .16em; text-transform: uppercase;
    color: var(--fire); margin-bottom: 16px;
}
.fg-register-title {
    font-family: var(--font-display); font-weight: 800;
    font-size: clamp(20px, 3vw, 28px); color: #f56a0d;
    letter-spacing: -.02em; margin-bottom: 10px;
}
.fg-register-sub {
    font-size: 13px; color: var(--ink-45); line-height: 1.7; font-weight: 500;
    margin-bottom: 28px;
}
.fg-scan-frame {
    position: absolute; inset: 14%; z-index: 4;
    pointer-events: none;
}
.fg-scan-corner {
    position: absolute; width: 26px; height: 26px;
    border: 3px solid var(--danger);
    transition: border-color .3s ease;
    opacity: .9;
}
.fg-scan-corner.tl { top: 0; left: 0; border-right: none; border-bottom: none; border-radius: 8px 0 0 0; }
.fg-scan-corner.tr { top: 0; right: 0; border-left: none; border-bottom: none; border-radius: 0 8px 0 0; }
.fg-scan-corner.bl { bottom: 0; left: 0; border-right: none; border-top: none; border-radius: 0 0 0 8px; }
.fg-scan-corner.br { bottom: 0; right: 0; border-left: none; border-top: none; border-radius: 0 0 8px 0; }
.fg-scan-frame.state-red .fg-scan-corner { border-color: var(--danger); animation: fgcornerpulse 1.4s ease infinite; }
.fg-scan-frame.state-yellow .fg-scan-corner { border-color: var(--gold); animation: fgcornerpulse .8s ease infinite; }
.fg-scan-frame.state-green .fg-scan-corner { border-color: #10b981; animation: none; }
@keyframes fgcornerpulse { 0%,100%{opacity:.5} 50%{opacity:1} }

.fg-traffic-light {
    position: absolute; top: 12px; right: 12px; z-index: 5;
    display: flex; flex-direction: column; gap: 6px;
    background: rgba(13,9,6,0.55);
    padding: 8px 6px; border-radius: 99px;
    backdrop-filter: blur(4px);
}
.fg-tl-dot {
    width: 9px; height: 9px; border-radius: 50%;
    background: rgba(255,255,255,0.15);
    transition: background .25s, box-shadow .25s;
}
.fg-tl-dot.fg-tl-red.on    { background: var(--danger); box-shadow: 0 0 10px 2px rgba(239,68,68,0.7); }
.fg-tl-dot.fg-tl-yellow.on { background: var(--gold);    box-shadow: 0 0 10px 2px rgba(251,191,36,0.7); }
.fg-tl-dot.fg-tl-green.on  { background: #10b981;        box-shadow: 0 0 10px 2px rgba(16,185,129,0.7); }

.fg-scan-status {
    position: absolute; left: 50%; bottom: 12px; transform: translateX(-50%); z-index: 5;
    display: flex; align-items: center; gap: 8px;
    padding: 7px 16px;
    border-radius: 99px;
    background: rgba(13,9,6,0.7);
    backdrop-filter: blur(4px);
    font-family: var(--font-display); font-size: 9.5px; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    color: #fff; white-space: nowrap;
    transition: background .25s;
}
.fg-scan-status-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--danger);
    animation: fgblink 1.2s ease infinite;
    transition: background .25s;
}
.fg-scan-status.state-red    .fg-scan-status-dot { background: var(--danger); }
.fg-scan-status.state-yellow .fg-scan-status-dot { background: var(--gold); }
.fg-scan-status.state-green  .fg-scan-status-dot { background: #10b981; animation: none; }

.fg-camera-frame { position: relative; width: 100%; max-width: 440px; margin: 0 auto 20px;
    border-radius: var(--r-lg); overflow: hidden;
    border: 1.5px solid rgba(249,115,22,0.25);
    background: var(--night-2); aspect-ratio: 4 / 3;
    transition: box-shadow .35s ease, border-color .35s ease; }
.fg-camera-frame.state-red    { border-color: rgba(239,68,68,0.7);  box-shadow: 0 0 0 3px rgba(239,68,68,0.18), 0 0 40px 6px rgba(239,68,68,0.35); }
.fg-camera-frame.state-yellow { border-color: rgba(251,191,36,0.7); box-shadow: 0 0 0 3px rgba(251,191,36,0.18), 0 0 40px 6px rgba(251,191,36,0.4); }
.fg-camera-frame.state-green  { border-color: rgba(16,185,129,0.8); box-shadow: 0 0 0 3px rgba(16,185,129,0.22), 0 0 50px 10px rgba(16,185,129,0.5); }

/* Full-frame color wash — cahaya menerpa wajah */
.fg-scan-tint {
    position: absolute; inset: 0; z-index: 2;
    pointer-events: none;
    mix-blend-mode: screen;
    opacity: 0;
    transition: opacity .3s ease, background .3s ease;
}
.fg-scan-tint.state-red    { opacity: .55; background: radial-gradient(circle at 50% 45%, rgba(239,68,68,0.85) 0%, rgba(239,68,68,0.15) 65%, transparent 100%); animation: fgtintpulse 1.3s ease infinite; }
.fg-scan-tint.state-yellow { opacity: .5;  background: radial-gradient(circle at 50% 45%, rgba(251,191,36,0.85) 0%, rgba(251,191,36,0.15) 65%, transparent 100%); animation: fgtintpulse .7s ease infinite; }
.fg-scan-tint.state-green  { opacity: .45; background: radial-gradient(circle at 50% 45%, rgba(16,185,129,0.85) 0%, rgba(16,185,129,0.15) 65%, transparent 100%); animation: none; }
@keyframes fgtintpulse { 0%,100%{opacity:.3} 50%{opacity:.62} }

/* Garis scan bergerak — efek laser biometrik */
.fg-scan-line {
    position: absolute; left: 0; right: 0; height: 2px; z-index: 3;
    pointer-events: none; opacity: 0;
}
.fg-scan-line.active {
    opacity: .9;
    animation: fgscanline 1.6s linear infinite;
}
.fg-scan-line.state-red    { background: linear-gradient(90deg, transparent, #ef4444, transparent); box-shadow: 0 0 12px 2px rgba(239,68,68,0.8); }
.fg-scan-line.state-yellow { background: linear-gradient(90deg, transparent, #fbbf24, transparent); box-shadow: 0 0 12px 2px rgba(251,191,36,0.8); }
.fg-scan-line.state-green  { opacity: 0; }
@keyframes fgscanline {
    0%   { top: 6%; }
    50%  { top: 92%; }
    100% { top: 6%; }
}
.fg-camera-video {
    width: 100%; height: 100%; object-fit: cover;
    transform: scaleX(-1);
    display: block;
}
.fg-camera-placeholder {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 10px; color: var(--ash-2);
}
.fg-camera-placeholder-text {
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
.fg-btn-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    box-shadow: 0 4px 16px rgba(16,185,129,0.35);
}
.fg-btn-ghost {
    background: rgba(255,255,255,0.06);
    border: 1.5px solid rgba(255,255,255,0.12);
    color: var(--ash);
}
.fg-btn-ghost:hover { border-color: rgba(249,115,22,0.4); color: var(--fire); }
.fg-btn[disabled] { opacity: .4; pointer-events: none; }

.fg-status {
    font-size: 12px; padding: 12px 16px; border-radius: var(--r-sm);
    line-height: 1.5; text-align: left;
}
.fg-status.success { background: rgb(16, 185, 129); border: 1px solid rgba(17, 211, 146, 0.3); color: #ffffff; font-weight: 500; }
.fg-status.error   { background: rgb(239, 68, 68); border: 1px solid rgb(239, 68, 68); color: #ffffff; font-weight: 500; }
.fg-status.info    { background: rgb(249, 116, 22); border: 1px solid rgb(249, 116, 22); color: #fffffe; font-weight: 500; }

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
.fg-result-count { font-size: 12.5px; color: var(--ink-45); }
.fg-result-count strong { color: var(--ink); font-weight: 700; }

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
    pointer-events: none;
}
.fg-photo-download {
    position: absolute; right: 10px; bottom: 7px;
    width: 30px; height: 30px; padding: 0;
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,0.82); background: rgba(13,9,6,0.42);
    border: 1px solid rgba(255,255,255,0.22); border-radius: 50%;
    cursor: pointer; transition: background .2s, color .2s;
}
.fg-photo-download:hover { color: #fff; background: rgba(249,115,22,0.85); }
.fg-photo-day-badge {
    position: absolute; top: 10px; left: 10px;
    background: var(--night);
    color: var(--fire);
    font-family: var(--font-display); font-size: 8.5px; font-weight: 800;
    letter-spacing: .1em; text-transform: uppercase;
    padding: 5px 10px; border-radius: 99px;
    border: 1px solid rgba(249,115,22,0.3);
}
.fg-photo-preview-badge {
    position: absolute; top: 10px; right: 10px;
    background: rgba(13,9,6,0.7);
    color: #fff; font-size: 8.5px; font-weight: 700;
    letter-spacing: .06em; text-transform: uppercase;
    padding: 4px 9px; border-radius: 6px;
}
.fg-photo-body { display: none; }
.fg-photo-meta-row {
    font-size: 11px; color: var(--ink-45); line-height: 1.7;
    display: flex; align-items: center; gap: 6px;
}

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
   MODAL FULLSCREEN PREVIEW
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
@keyframes fgslideup { from{transform:translateY(24px);opacity:0} to{transform:translateY(0);opacity:1} }

.fg-modal-head {
    position: absolute; inset: 0; z-index: 3; pointer-events: none;
}
.fg-modal-title {
    display: none;
}
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
    letter-spacing: .04em;
    padding: 0; border-radius: 0;
}
.fg-modal-download {
    position: absolute; right: 28px; bottom: 18px; z-index: 2;
    width: 38px; height: 38px; padding: 0; border: 0; background: transparent;
    color: rgba(255,255,255,0.72); cursor: pointer;
}
.fg-modal-download:hover { color: #fff; }
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
        <video class="fg-hero-video"
            src="https://ik.imagekit.io/ph84yodhs/2026082410523.mp4?tr=q-45,w-1280,f-auto"
            autoplay muted loop playsinline preload="auto"></video>
        <div class="fg-hero-overlay"></div>
        <div class="fg-hero-grain"></div>

        <div class="fg-hero-content">
            <h1 class="fg-hero-title">Galeri Foto</h1>
            <p class="fg-hero-sub">Bayan Open 2026 &nbsp;·&nbsp; Balikpapan, Kalimantan Timur &nbsp;·&nbsp; 24–29 Agustus 2026</p>
        </div>
    </div>

    {{-- ══ MAIN ══ --}}
    <div class="fg-main">

        {{-- Info banner 
        <div class="fg-info-banner">
            <div class="fg-banner-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4M12 16h.01"/>
                </svg>
            </div>
            <div class="fg-banner-content">
                <div class="fg-banner-title">Cara Kerja Galeri</div>
                <div class="fg-banner-sub">Ambil satu foto wajah, sistem AI akan mencocokkan dan menampilkan hanya foto pertandingan yang memuat wajah Anda. Data wajah disimpan di perangkat Anda sendiri.</div>
            </div>
            <div class="fg-banner-badge">
                <span class="fg-live-dot"></span>
                Face Recognition
            </div>
        </div>--}}

        {{-- Day filter — selalu tampil dari awal --}}
        <div>
            <div class="fg-section-label">
                <div class="fg-section-label-text">
                    <span class="fg-section-fire">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </span>
                    Pilih Hari
                </div>
                <div class="fg-section-line"></div>
            </div>
            <div class="fg-day-tabs" id="dayTabs">
                <button class="fg-day-tab active" data-day="all" onclick="filterByDay('all', this)">Semua Hari</button>
                <button class="fg-day-tab" data-day="Day 1" onclick="filterByDay('Day 1', this)">Day 1</button>
                <button class="fg-day-tab" data-day="Day 2" onclick="filterByDay('Day 2', this)">Day 2</button>
                <button class="fg-day-tab" data-day="Day 3" onclick="filterByDay('Day 3', this)">Day 3</button>
                <button class="fg-day-tab" data-day="Day 4" onclick="filterByDay('Day 4', this)">Day 4</button>
                <button class="fg-day-tab" data-day="Day 5" onclick="filterByDay('Day 5', this)">Day 5</button>
            </div>
            <p id="dayHint" class="fg-banner-sub" style="color:var(--ink-45);margin-top:10px;font-size:11.5px;">Pilih hari (opsional), lalu ambil foto wajah di bawah untuk mencari foto Anda.</p>
        </div>

        {{-- Face registration --}}
        <div id="registerSection">
            <div class="fg-register-card">
                <div class="fg-register-inner">
                    <h2 class="fg-register-title">Temukan Foto Anda</h2>
                    <p class="fg-register-sub">Nyalakan kamera, posisikan wajah di dalam bingkai, lalu ambil foto. Sistem akan mencari semua foto pertandingan yang memuat wajah Anda.</p>

                    <div class="fg-camera-frame" id="cameraFrame">
                        <div class="fg-camera-placeholder" id="cameraPlaceholder">
                            <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                            <span class="fg-camera-placeholder-text">Kamera belum aktif</span>
                        </div>
                        <video id="cameraVideo" class="fg-camera-video fg-hidden" autoplay playsinline muted></video>
                        <div class="fg-scan-tint fg-hidden" id="scanTint"></div>
                        <div class="fg-scan-line fg-hidden" id="scanLine"></div>
                        <div class="fg-scan-frame fg-hidden" id="scanFrame">
                            <span class="fg-scan-corner tl"></span>
                            <span class="fg-scan-corner tr"></span>
                            <span class="fg-scan-corner bl"></span>
                            <span class="fg-scan-corner br"></span>
                        </div>
                        <div class="fg-traffic-light fg-hidden" id="trafficLight">
                            <span class="fg-tl-dot fg-tl-red" id="tlRed"></span>
                            <span class="fg-tl-dot fg-tl-yellow" id="tlYellow"></span>
                            <span class="fg-tl-dot fg-tl-green" id="tlGreen"></span>
                        </div>
                        <div class="fg-scan-status fg-hidden" id="scanStatus">
                            <span class="fg-scan-status-dot"></span>
                            <span id="scanStatusText">Mencari wajah…</span>
                        </div>
                    </div>

                    <div class="fg-actions">
                        <button id="btnStartCamera" class="fg-btn fg-btn-primary" onclick="startCamera()">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                            Nyalakan Kamera
                        </button>
                        <button id="btnCapture" class="fg-btn fg-btn-primary fg-hidden" onclick="captureAndRegister()">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"/>
                            </svg>
                            Ambil Manual
                        </button>
                        <button id="btnStopCamera" class="fg-btn fg-btn-primary fg-hidden" onclick="stopCamera()">
                            Matikan Kamera
                        </button>
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
                <div class="fg-result-count" id="photoCount">Ditemukan <strong>0</strong> foto</div>
                <button class="fg-btn fg-btn-ghost" style="color:var(--ink-45);background:var(--white);border-color:var(--ink-12);" onclick="resetFaceData()">
                    Ulangi Pencarian
                </button>
            </div>

            <div class="fg-loading fg-hidden" id="loadingPhotos">
                <div class="fg-spinner"></div>
                <div class="fg-loading-text">Mencari foto Anda…</div>
            </div>

            <div class="fg-photo-grid fg-hidden" id="photosGrid"></div>

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

{{-- ══ MODAL DETAIL FOTO ══ --}}
<div class="fg-modal-backdrop" id="photoModal" onclick="closeModalOutside(event)">
    <div class="fg-modal">
        <div class="fg-modal-head">
            <div class="fg-modal-title">Detail Foto</div>
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
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
/* ══════════════════════════════════════════════
   KONFIGURASI — sesuaikan dengan API face recognition Anda
   ══════════════════════════════════════════════ */
const API_BASE_URL = 'https://gallery.bayanopen.com';
const REGISTER_ENDPOINT = `${API_BASE_URL}/api/user/register_face`;
const PHOTOS_ENDPOINT   = `${API_BASE_URL}/api/user/my_photos`;
const IMAGE_ENDPOINT = (filename) => `${API_BASE_URL}/api/preview/${filename}`;
const DOWNLOAD_ENDPOINT = (filename) => `${API_BASE_URL}/api/download/${filename}`;
const STORAGE_KEY = 'bayan_open_face_embedding';
const FACE_MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights';
const STABLE_FRAMES_NEEDED = 6;   // ~1.5 detik pada interval 250ms
const DETECT_INTERVAL_MS = 250;

/* Tanggal mulai turnamen — dipakai untuk memetakan tanggal foto ke label "Day N" */
const EVENT_START_DATE = '2026-08-24';
const EVENT_TOTAL_DAYS = 5;

let videoStream = null;
let faceEmbedding = null;
let allPhotos = [];
let currentDay = 'all';
let modelsLoaded = false;
let detectTimer = null;
let stableCount = 0;
let autoCaptureLock = false;
let visiblePhotos = [];
let currentPhotoIndex = 0;

/* Restore previous face session */
window.addEventListener('DOMContentLoaded', () => {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored) {
        try {
            faceEmbedding = JSON.parse(stored);
            document.getElementById('registerSection').classList.add('fg-hidden');
            loadPhotos();
        } catch (e) {
            localStorage.removeItem(STORAGE_KEY);
        }
    }
});

/* ══ CAMERA ══ */
async function startCamera() {
    try {
        videoStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: 640, height: 480 },
            audio: false
        });
        const video = document.getElementById('cameraVideo');
        video.srcObject = videoStream;
        video.classList.remove('fg-hidden');
        document.getElementById('cameraPlaceholder').classList.add('fg-hidden');
        document.getElementById('scanFrame').classList.remove('fg-hidden');
        document.getElementById('scanTint').classList.remove('fg-hidden');
        document.getElementById('scanLine').classList.remove('fg-hidden');
        document.getElementById('trafficLight').classList.remove('fg-hidden');
        document.getElementById('scanStatus').classList.remove('fg-hidden');

        document.getElementById('btnStartCamera').classList.add('fg-hidden');
        document.getElementById('btnCapture').classList.remove('fg-hidden');
        document.getElementById('btnStopCamera').classList.remove('fg-hidden');

        setScanState('red', 'Menyiapkan AI deteksi wajah…');
        showStatus('Kamera aktif. Posisikan wajah Anda di dalam bingkai.', 'info');

        await ensureModelsLoaded();

        if (modelsLoaded) {
            setScanState('red', 'Mencari wajah…');
            stableCount = 0;
            autoCaptureLock = false;
            video.addEventListener('loadeddata', startDetectionLoop, { once: true });
            if (video.readyState >= 2) startDetectionLoop();
        } else {
            setScanState('red', 'Deteksi otomatis tidak tersedia');
            showStatus('Deteksi otomatis gagal dimuat — gunakan tombol "Ambil Manual" di bawah.', 'error');
        }
    } catch (error) {
        showStatus('Tidak bisa mengakses kamera: ' + error.message, 'error');
    }
}

async function ensureModelsLoaded() {
    if (modelsLoaded) return;
    if (typeof faceapi === 'undefined') { modelsLoaded = false; return; }
    try {
        await faceapi.nets.tinyFaceDetector.loadFromUri(FACE_MODEL_URL);
        modelsLoaded = true;
    } catch (e) {
        console.error('Gagal memuat model AI:', e);
        modelsLoaded = false;
    }
}

function startDetectionLoop() {
    if (detectTimer) clearInterval(detectTimer);
    detectTimer = setInterval(runFaceDetection, DETECT_INTERVAL_MS);
}

async function runFaceDetection() {
    if (autoCaptureLock) return;
    const video = document.getElementById('cameraVideo');
    if (!video || video.readyState < 2) return;

    try {
        const result = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }));

        if (!result) {
            stableCount = 0;
            setScanState('red', 'Mencari wajah…');
            return;
        }

        // Cek wajah cukup besar & kira-kira di tengah bingkai
        const box = result.box;
        const vw = video.videoWidth, vh = video.videoHeight;
        const cx = box.x + box.width / 2, cy = box.y + box.height / 2;
        const centered = Math.abs(cx - vw / 2) < vw * 0.28 && Math.abs(cy - vh / 2) < vh * 0.28;
        const bigEnough = box.width > vw * 0.18;

        if (!centered || !bigEnough) {
            stableCount = Math.max(0, stableCount - 1);
            setScanState('yellow', 'Dekatkan &amp; tengahkan wajah Anda');
            return;
        }

        stableCount++;
        if (stableCount < STABLE_FRAMES_NEEDED) {
            setScanState('yellow', 'Tahan, jangan bergerak…');
        } else {
            setScanState('green', 'Terdeteksi! Mengambil foto…');
            autoCaptureLock = true;
            clearInterval(detectTimer);
            setTimeout(() => captureAndRegister(), 350);
        }
    } catch (e) {
        console.error('Deteksi wajah error:', e);
    }
}

function setScanState(state, label) {
    const cameraFrame = document.getElementById('cameraFrame');
    const scanFrame = document.getElementById('scanFrame');
    const tint = document.getElementById('scanTint');
    const line = document.getElementById('scanLine');
    const statusEl = document.getElementById('scanStatus');
    const statusText = document.getElementById('scanStatusText');
    if (!scanFrame || !statusEl) return;

    const states = ['state-red', 'state-yellow', 'state-green'];
    [cameraFrame, scanFrame, tint, line, statusEl].forEach(el => el && el.classList.remove(...states));
    [cameraFrame, scanFrame, tint, line, statusEl].forEach(el => el && el.classList.add('state-' + state));

    if (line) line.classList.toggle('active', state !== 'green');
    if (statusText) statusText.innerHTML = label;

    ['Red', 'Yellow', 'Green'].forEach(c => {
        const dot = document.getElementById('tl' + c);
        if (dot) dot.classList.toggle('on', c.toLowerCase() === state);
    });
}

function stopCamera() {
    if (detectTimer) { clearInterval(detectTimer); detectTimer = null; }
    stableCount = 0;
    autoCaptureLock = false;

    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;
    }
    const video = document.getElementById('cameraVideo');
    video.srcObject = null;
    video.classList.add('fg-hidden');
    document.getElementById('cameraPlaceholder').classList.remove('fg-hidden');
    document.getElementById('scanFrame').classList.add('fg-hidden');
    document.getElementById('scanTint').classList.add('fg-hidden');
    document.getElementById('scanLine').classList.add('fg-hidden');
    document.getElementById('trafficLight').classList.add('fg-hidden');
    document.getElementById('scanStatus').classList.add('fg-hidden');

    document.getElementById('btnStartCamera').classList.remove('fg-hidden');
    document.getElementById('btnCapture').classList.add('fg-hidden');
    document.getElementById('btnStopCamera').classList.add('fg-hidden');
}

async function captureAndRegister() {
    if (detectTimer) { clearInterval(detectTimer); detectTimer = null; }
    autoCaptureLock = true;

    const video = document.getElementById('cameraVideo');
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.scale(-1, 1);
    ctx.drawImage(video, -canvas.width, 0);

    const imageData = canvas.toDataURL('image/jpeg', 0.85);
    showStatus('Memproses wajah Anda…', 'info');
    document.getElementById('btnCapture').setAttribute('disabled', 'true');

    try {
        const response = await fetch(REGISTER_ENDPOINT, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ image: imageData })
        });
        const data = await response.json();

        if (data.success) {
            faceEmbedding = data.embedding;
            localStorage.setItem(STORAGE_KEY, JSON.stringify(faceEmbedding));
            showStatus('Wajah berhasil dikenali. Mencari foto Anda…', 'success');
            stopCamera();
            setTimeout(() => {
                document.getElementById('registerSection').classList.add('fg-hidden');
                loadPhotos();
            }, 900);
        } else {
            showStatus('Gagal mengenali wajah: ' + (data.error || 'coba lagi dengan pencahayaan lebih baik.'), 'error');
            setScanState('red', 'Gagal, coba lagi…');
            stableCount = 0;
            autoCaptureLock = false;
            if (modelsLoaded && videoStream) startDetectionLoop();
        }
    } catch (error) {
        showStatus('Koneksi gagal: ' + error.message, 'error');
        stableCount = 0;
        autoCaptureLock = false;
        if (modelsLoaded && videoStream) startDetectionLoop();
    } finally {
        document.getElementById('btnCapture').removeAttribute('disabled');
    }
}

function showStatus(message, type) {
    const el = document.getElementById('statusMessage');
    el.classList.remove('fg-hidden');
    el.className = 'fg-status ' + type;
    el.textContent = message;
}

/* ══ HELPERS — cocok dengan struktur respons API aktual ══
   Respons /api/user/my_photos: { success, photos: [{ distance, filename, photo_id, url,
   metadata: { date, event_name, location, photographer } }] }
   Catatan: metadata TIDAK punya field "day" bawaan dari API, jadi kita hitung sendiri
   dari metadata.date terhadap tanggal mulai turnamen (EVENT_START_DATE).
   Field "url" sudah berupa URL lengkap ke file foto, jadi dipakai langsung
   (fallback ke IMAGE_ENDPOINT/DOWNLOAD_ENDPOINT kalau url tidak ada). */

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
    const url = photo.preview_url || IMAGE_ENDPOINT(photo.filename);   // pakai preview dulu
    return url.replace(/^http:/i, 'https:');
}

function getPhotoDownloadUrl(photo) {
    return photo.url || DOWNLOAD_ENDPOINT(photo.filename);   // tetap full-res, ini sudah benar
}

/* ══ LOAD & FILTER PHOTOS ══ */
async function loadPhotos() {
    document.getElementById('gallerySection').classList.remove('fg-hidden');
    document.getElementById('loadingPhotos').classList.remove('fg-hidden');
    document.getElementById('photosGrid').classList.add('fg-hidden');
    document.getElementById('emptyState').classList.add('fg-hidden');

    try {
        const response = await fetch(PHOTOS_ENDPOINT, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ embedding: faceEmbedding })
        });
        const data = await response.json();

        document.getElementById('loadingPhotos').classList.add('fg-hidden');

        if (data.success && data.photos && data.photos.length > 0) {
            // Data sudah terurut dari yang paling mirip (distance terkecil) — urutan dipertahankan.
            // Tambahkan label "day" hasil hitungan karena API tidak mengirimkannya.
            allPhotos = data.photos.map(p => ({
                ...p,
                metadata: {
                    ...(p.metadata || {}),
                    day: computeDayLabel(p.metadata && p.metadata.date)
                }
            }));
            const filtered = currentDay === 'all'
                ? allPhotos
                : allPhotos.filter(p => p.metadata && p.metadata.day === currentDay);
            renderPhotos(filtered);
        } else {
            allPhotos = [];
            document.getElementById('emptyState').classList.remove('fg-hidden');
            document.getElementById('photoCount').innerHTML = 'Ditemukan <strong>0</strong> foto';
        }
    } catch (error) {
        document.getElementById('loadingPhotos').classList.add('fg-hidden');
        document.getElementById('emptyState').classList.remove('fg-hidden');
        console.error('Gagal memuat foto:', error);
    }
}

function filterByDay(day, btn) {
    currentDay = day;
    document.querySelectorAll('.fg-day-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');

    const hint = document.getElementById('dayHint');
    if (hint) {
        hint.textContent = day === 'all'
            ? 'Pilih hari (opsional), lalu ambil foto wajah di bawah untuk mencari foto Anda.'
            : `Filter aktif: ${day}. Ambil foto wajah di bawah untuk mencari foto Anda pada hari ini.`;
    }

    // Kalau foto sudah dimuat (wajah sudah discan), langsung filter hasilnya
    if (allPhotos.length > 0) {
        const filtered = day === 'all'
            ? allPhotos
            : allPhotos.filter(p => p.metadata && p.metadata.day === day);
        renderPhotos(filtered);
    }
    // Kalau belum discan, pilihan hari ini akan otomatis dipakai saat loadPhotos() jalan
}

function renderPhotos(photos) {
    const grid = document.getElementById('photosGrid');
    const empty = document.getElementById('emptyState');
    visiblePhotos = photos;

    document.getElementById('photoCount').innerHTML = `Ditemukan <strong>${photos.length}</strong> foto`;

    if (photos.length === 0) {
        grid.classList.add('fg-hidden');
        empty.classList.remove('fg-hidden');
        return;
    }

    empty.classList.add('fg-hidden');
    grid.classList.remove('fg-hidden');

    grid.innerHTML = photos.map((photo, i) => {
        const imgUrl = getPhotoImageUrl(photo);

        return `
            <div class="fg-photo-card" style="animation-delay:${Math.min(i * 0.05, 0.6)}s"
                 onclick="showPhotoDetail(${i})" role="button" tabindex="0"
                 onkeydown="if (event.key === 'Enter' || event.key === ' ') showPhotoDetail(${i})">
                <div class="fg-photo-img-wrap">
                    <img class="fg-photo-img" src="${imgUrl}" alt="${photo.filename}" loading="lazy">
                    <span class="fg-photo-copyright">© AmbilFoto.id</span>
                    <button class="fg-photo-download" aria-label="Download foto" title="Download foto"
                            onclick="event.stopPropagation(); downloadPhoto('${getPhotoDownloadUrl(photo)}', '${photo.filename}')">
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
            <span class="fg-modal-quality-badge">© AmbilFoto.id</span>
            <button class="fg-modal-download" aria-label="Download foto" title="Download foto"
                    onclick="event.stopPropagation(); downloadPhoto('${getPhotoDownloadUrl(photo)}', '${photo.filename}')">
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

/* ══ DOWNLOAD ══ */
function downloadPhoto(url, filename) {
    const a = document.createElement('a');
    a.href = url.replace(/^http:/i, 'https:');
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    showToast('Unduhan dimulai — kualitas HD penuh.');
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
    faceEmbedding = null;
    allPhotos = [];
    currentDay = 'all';
    document.querySelectorAll('.fg-day-tab').forEach(t => t.classList.remove('active'));
    document.querySelector('.fg-day-tab[data-day="all"]').classList.add('active');
    document.getElementById('dayHint').textContent = 'Pilih hari (opsional), lalu ambil foto wajah di bawah untuk mencari foto Anda.';
    document.getElementById('gallerySection').classList.add('fg-hidden');
    document.getElementById('registerSection').classList.remove('fg-hidden');
    document.getElementById('statusMessage').classList.add('fg-hidden');
    window.scrollTo({ top: document.getElementById('registerSection').offsetTop - 100, behavior: 'smooth' });
}
</script>
@endpush