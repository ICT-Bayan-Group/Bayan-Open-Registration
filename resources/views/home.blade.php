@extends('layouts.app')

@section('title', 'Selamat Datang')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════
   TOKENS
═══════════════════════════════════════ */
:root {
    --fire:      #f97316;
    --fire-deep: #c2410c;
    --fire-glow: rgba(249,115,22,0.35);
    --gold:      #fbbf24;
    --night:     #0d0906;
    --night-2:   #140c07;
    --night-3:   #1e1209;
    --smoke:     rgba(255,255,255,0.06);
    --smoke-2:   rgba(255,255,255,0.03);
    --ash:       rgba(255,255,255,0.55);
    --ash-2:     rgba(255,255,255,0.25);
    --ash-3:     rgba(255,255,255,0.1);
    --paper:     #faf8f5;
    --paper-2:   #f2ede6;
    --ink:       #1a1007;
    --ink-60:    rgba(26,16,7,0.6);
    --ink-30:    rgba(26,16,7,0.3);
    --ink-12:    rgba(26,16,7,0.1);

    --r-sm: 12px;
    --r-md: 18px;
    --r-lg: 24px;
    --r-xl: 32px;
    --font-display: 'Syne', 'Unbounded', sans-serif;
    --font-body:    'DM Sans', sans-serif;

    /* Modal tokens */
    --orange:    #f97316;
    --orange-dk: #ea580c;
    --cream:     #f8f6f2;
    --cream-dk:  #f0ede8;
    --white:     #ffffff;
    --m-ink:     #1a1209;
    --m-ink-60:  rgba(26,18,9,0.6);
    --m-ink-35:  rgba(26,18,9,0.35);
    --m-ink-12:  rgba(26,18,9,0.12);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: var(--font-body);
    background: var(--paper);
    color: var(--ink);
    overflow-x: hidden;
}

/* ═══════════════════════════════════════
   HERO
═══════════════════════════════════════ */
.hero {
    min-height: 100svh;
    background: var(--night);
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
    margin-top: -96px;
}
.hero-video {
    position: absolute; inset: 0; z-index: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    pointer-events: none;
}
.hero-overlay {
    position: absolute; inset: 0; z-index: 1;
    background:
        linear-gradient(to bottom, rgba(13,9,6,0.60) 0%, rgba(13,9,6,0.40) 50%, rgba(13,9,6,0.80) 100%),
        radial-gradient(ellipse 80% 60% at 50% 40%, rgba(249,115,22,0.10) 0%, transparent 65%);
}
.hero-vignette {
    position: absolute; inset: 0; z-index: 2;
    background: radial-gradient(ellipse 120% 100% at 50% 50%, transparent 40%, rgba(13,9,6,0.6) 100%);
    pointer-events: none;
}
.hero-grain {
    position: absolute; inset: 0; z-index: 3;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.035'/%3E%3C/svg%3E");
    pointer-events: none;
}
.hero-radial { position: absolute; inset: 0; z-index: 4; pointer-events: none; will-change: transform; }
.hero-content {
    position: relative; z-index: 5;
    text-align: center;
    padding: 148px 24px 130px;
    max-width: 820px; margin: 0 auto;
}

/* Eyebrow */
.eyebrow {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 7px 20px 7px 10px;
    border-radius: 99px;
    border: 1px solid rgba(249,115,22,0.3);
    background: rgba(249,115,22,0.08);
    backdrop-filter: blur(8px);
    margin-bottom: 36px;
}
.eyebrow-dot {
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(249,115,22,0.15);
    display: flex; align-items: center; justify-content: center;
}
.eyebrow-dot::after {
    content: '';
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--fire);
    box-shadow: 0 0 10px var(--fire);
    animation: blink 2.4s ease infinite;
}
@keyframes blink {
    0%,100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.8); }
}
.eyebrow-text {
    font-family: var(--font-display);
    font-size: 10.5px; font-weight: 700;
    letter-spacing: 0.18em; text-transform: uppercase;
    color: var(--fire);
}

/* Logo */
.hero-logo {
    height: 170px; width: auto;
    object-fit: contain;
    filter: drop-shadow(0 0 40px rgba(249,115,22,0.3)) drop-shadow(0 8px 16px rgba(0,0,0,0.4));
    margin-bottom: 24px;
    display: block; margin-left: auto; margin-right: auto;
}

/* Headline */
.hero-headline {
    font-family: var(--font-display);
    font-size: clamp(13px, 2vw, 17px);
    font-weight: 400; letter-spacing: 0.05em;
    color: var(--ash);
    line-height: 1.8;
    max-width: 500px; margin: 0 auto 40px;
}

/* CTA Row */
.cta-row { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

.btn-fire {
    display: inline-flex; align-items: center; gap: 10px;
    font-family: var(--font-display);
    font-size: 11px; font-weight: 700;
    letter-spacing: 0.12em; text-transform: uppercase;
    color: #fff; text-decoration: none;
    background: linear-gradient(135deg, var(--fire) 0%, var(--fire-deep) 100%);
    padding: 16px 36px;
    border-radius: var(--r-md);
    box-shadow:
        0 0 0 1px rgba(249,115,22,0.4),
        0 8px 32px rgba(249,115,22,0.4),
        inset 0 1px 0 rgba(255,255,255,0.15);
    transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
    position: relative; overflow: hidden;
    border: none; cursor: pointer;
}
.btn-fire::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.08), transparent);
}
.btn-fire:hover {
    transform: translateY(-2px);
    box-shadow:
        0 0 0 1px rgba(249,115,22,0.5),
        0 16px 48px rgba(249,115,22,0.55),
        inset 0 1px 0 rgba(255,255,255,0.2);
}
.btn-fire:active { transform: translateY(0); }

.btn-ghost-dark {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: 13.5px; font-weight: 400;
    color: var(--ash); text-decoration: none;
    background: var(--smoke);
    padding: 16px 28px;
    border-radius: var(--r-md);
    border: 1px solid var(--ash-3);
    backdrop-filter: blur(8px);
    transition: all 0.25s ease;
}
.btn-ghost-dark:hover {
    background: rgba(255,255,255,0.09);
    border-color: rgba(249,115,22,0.3);
    color: #fff;
}

/* Stats strip */
.stats-strip {
    display: flex; gap: 1px; justify-content: center;
    border: 1px solid var(--ash-3);
    border-radius: var(--r-lg);
    overflow: hidden;
    max-width: 380px;
    margin: 48px auto 0;
    background: var(--ash-3);
}
.stat-cell {
    flex: 1;
    padding: 20px 16px;
    text-align: center;
    background: rgba(255,255,255,0.03);
    backdrop-filter: blur(12px);
}
.stat-cell:hover { background: rgba(255,255,255,0.06); }
.stat-val {
    font-family: var(--font-display);
    font-size: 24px; font-weight: 800;
    color: var(--fire);
    display: block; line-height: 1;
}
.stat-lbl {
    font-size: 10px; color: var(--ash-2);
    letter-spacing: 0.08em; text-transform: uppercase;
    margin-top: 6px; display: block;
}

/* Scroll cue */
.scroll-cue {
    position: absolute; bottom: 36px; left: 50%; transform: translateX(-50%);
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    z-index: 5;
}
.scroll-cue-mouse {
    width: 22px; height: 34px;
    border: 1.5px solid var(--ash-3);
    border-radius: 11px;
    display: flex; justify-content: center; padding-top: 6px;
}
.scroll-cue-wheel {
    width: 3px; height: 6px;
    background: var(--fire);
    border-radius: 2px;
    animation: wheel-scroll 1.8s ease infinite;
}
@keyframes wheel-scroll {
    0% { opacity: 0; transform: translateY(0); }
    50% { opacity: 1; }
    100% { opacity: 0; transform: translateY(8px); }
}
.scroll-cue-label {
    font-family: var(--font-display);
    font-size: 8px; letter-spacing: 0.2em;
    text-transform: uppercase; color: var(--ash-2);
}

/* ═══════════════════════════════════════
   SECTION COMMONS
═══════════════════════════════════════ */
.section { padding: 80px 24px; }
.section-inner { max-width: 1120px; margin: 0 auto; }
.sec-tag {
    font-family: var(--font-display);
    font-size: 10px; font-weight: 700;
    letter-spacing: 0.22em; text-transform: uppercase;
    color: var(--fire); margin-bottom: 12px; display: block;
}
.sec-title {
    font-family: var(--font-display);
    font-size: clamp(26px, 4.5vw, 42px); font-weight: 800;
    color: var(--ink); letter-spacing: -0.03em; line-height: 1.1;
}
.sec-title.light { color: #fff; }
.sec-sub {
    font-size: 15px; color: var(--ink-60);
    line-height: 1.75; max-width: 480px; margin-top: 14px;
}

/* ═══════════════════════════════════════
   KATEGORI GRID
═══════════════════════════════════════ */
.kategori-section { background: var(--paper); }
.kategori-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    margin-top: 52px;
}
.kat-card {
    position: relative; overflow: hidden;
    background: #fff;
    border: 1px solid var(--ink-12);
    border-radius: var(--r-xl);
    padding: 32px 28px 28px;
    text-decoration: none; display: block;
    transition: all 0.35s cubic-bezier(0.22,1,0.36,1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 0 0 0 rgba(249,115,22,0);
    cursor: pointer; border: none; text-align: left; font-family: inherit; width: 100%;
}
.kat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 24px 56px rgba(0,0,0,0.1), 0 0 0 2px rgba(249,115,22,0.15);
}
.kat-card::after {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    border-radius: var(--r-xl) var(--r-xl) 0 0;
    opacity: 0; transition: opacity 0.3s;
}
.kat-card:hover::after { opacity: 1; }
.kat-card.c-blue::after  { background: linear-gradient(90deg,#3b82f6,#818cf8); }
.kat-card.c-rose::after  { background: linear-gradient(90deg,#f43f5e,#ec4899); }
.kat-card.c-amber::after { background: linear-gradient(90deg,#f97316,#fbbf24); }
.kat-card.c-teal::after  { background: linear-gradient(90deg,#14b8a6,#06b6d4); }
.kat-card.c-blue:hover  { background: linear-gradient(135deg,#fff,#eff6ff); }
.kat-card.c-rose:hover  { background: linear-gradient(135deg,#fff,#fff1f2); }
.kat-card.c-amber:hover { background: linear-gradient(135deg,#fff,#fff7ed); }
.kat-card.c-teal:hover  { background: linear-gradient(135deg,#fff,#f0fdfa); }
.kat-icon {
    width: 48px; height: 48px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 22px;
    transition: transform 0.25s cubic-bezier(0.22,1,0.36,1);
}
.kat-card:hover .kat-icon { transform: scale(1.1) rotate(-4deg); }
.c-blue  .kat-icon { background: rgba(59,130,246,0.09);  border: 1px solid rgba(59,130,246,0.18); }
.c-rose  .kat-icon { background: rgba(244,63,94,0.09);   border: 1px solid rgba(244,63,94,0.18); }
.c-amber .kat-icon { background: rgba(249,115,22,0.09);  border: 1px solid rgba(249,115,22,0.18); }
.c-teal  .kat-icon { background: rgba(20,184,166,0.09);  border: 1px solid rgba(20,184,166,0.18); }
.kat-name { font-family: var(--font-display); font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 6px; }
.kat-desc { font-size: 12.5px; color: var(--ink-60); line-height: 1.65; margin-bottom: 24px; }
.kat-footer { display: flex; align-items: flex-end; justify-content: space-between; }
.kat-price { font-family: var(--font-display); font-size: 22px; font-weight: 800; line-height: 1; }
.c-blue  .kat-price { color: #2563eb; }
.c-rose  .kat-price { color: #e11d48; }
.c-amber .kat-price { color: var(--fire-deep); }
.c-teal  .kat-price { color: #0d9488; }
.kat-per { font-size: 11px; color: var(--ink-30); margin-top: 4px; }
.kat-cta {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transform: translateX(-4px);
    transition: all 0.25s ease;
}
.kat-card:hover .kat-cta { opacity: 1; transform: translateX(0); }
.c-blue  .kat-cta { background: rgba(59,130,246,0.1); }
.c-rose  .kat-cta { background: rgba(244,63,94,0.1); }
.c-amber .kat-cta { background: rgba(249,115,22,0.1); }
.c-teal  .kat-cta { background: rgba(20,184,166,0.1); }

/* ═══════════════════════════════════════
   GALLERY MARQUEE
═══════════════════════════════════════ */
.gallery-section { padding: 80px 0 100px; overflow: hidden; position: relative; background: var(--night); }
.gallery-bg-video { position: absolute; inset: 0; z-index: 0; width: 100%; height: 100%; object-fit: cover; pointer-events: none; }
.gallery-video-overlay {
    position: absolute; inset: 0; z-index: 1;
    background:
        linear-gradient(to bottom, rgba(13,9,6,0.80) 0%, rgba(13,9,6,0.65) 50%, rgba(13,9,6,0.85) 100%),
        radial-gradient(ellipse 70% 60% at 50% 50%, rgba(249,115,22,0.07) 0%, transparent 90%);
}
.gallery-section .grain {
    position: absolute; inset: 0; z-index: 2; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.05'/%3E%3C/svg%3E");
    opacity: 0.5;
}
.gallery-header { text-align: center; padding: 0 24px; margin-bottom: 52px; position: relative; z-index: 4; }
.gallery-track-wrap { display: flex; flex-direction: column; gap: 18px; position: relative; z-index: 4; }
.gallery-track-wrap::before, .gallery-track-wrap::after {
    content: '';
    position: absolute; top: 0; bottom: 0; width: 180px; z-index: 5; pointer-events: none;
}
.gallery-track-wrap::before { left: 0; background: linear-gradient(90deg, rgba(13,9,6,0.92), transparent); }
.gallery-track-wrap::after  { right: 0; background: linear-gradient(-90deg, rgba(13,9,6,0.92), transparent); }
.gallery-track { display: flex; gap: 16px; width: max-content; will-change: transform; }
.gallery-item {
    flex-shrink: 0; width: 260px; height: 175px; border-radius: var(--r-lg); overflow: hidden;
    position: relative; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 24px rgba(0,0,0,0.4);
}
.gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
.gallery-item:hover img { transform: scale(1.07); }
.gallery-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(249,115,22,0.75) 0%, transparent 50%);
    opacity: 0; transition: opacity 0.3s ease;
    display: flex; align-items: flex-end; padding: 14px;
}
.gallery-item:hover .gallery-overlay { opacity: 1; }
.gallery-overlay span { font-family: var(--font-display); font-size: 9.5px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #fff; }

/* ═══════════════════════════════════════
   CARA DAFTAR
═══════════════════════════════════════ */
.steps-section { background: var(--paper-2); }
.steps-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 52px; }
.step-card {
    background: #fff; border: 1px solid var(--ink-12); border-radius: var(--r-xl);
    padding: 32px 24px 28px; position: relative; overflow: hidden;
    transition: all 0.32s cubic-bezier(0.22,1,0.36,1); box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}
.step-card:hover { transform: translateY(-5px); box-shadow: 0 20px 48px rgba(249,115,22,0.12); border-color: rgba(249,115,22,0.2); }
.step-num {
    font-family: var(--font-display); font-size: 56px; font-weight: 800;
    color: rgba(249,115,22,0.07); line-height: 1; position: absolute; top: 16px; right: 20px;
    user-select: none; pointer-events: none; transition: color 0.3s;
}
.step-card:hover .step-num { color: rgba(249,115,22,0.12); }
.step-badge {
    width: 40px; height: 40px; border-radius: 12px;
    background: linear-gradient(135deg, var(--fire), var(--fire-deep));
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-display); font-size: 15px; font-weight: 800; color: #fff;
    margin-bottom: 20px; box-shadow: 0 4px 16px rgba(249,115,22,0.35);
}
.step-title { font-family: var(--font-display); font-size: 15px; font-weight: 700; color: var(--ink); margin-bottom: 8px; }
.step-desc  { font-size: 13px; color: var(--ink-60); line-height: 1.65; }
@media(min-width:768px) {
    .steps-grid { position: relative; }
    .steps-grid::before {
        content: ''; position: absolute; top: 56px; left: calc(25% - 2px); right: calc(25% - 2px);
        height: 1px; background: repeating-linear-gradient(90deg, rgba(249,115,22,0.3) 0, rgba(249,115,22,0.3) 6px, transparent 6px, transparent 16px);
        pointer-events: none; z-index: 0;
    }
}

/* ═══════════════════════════════════════
   CTA BANNER
═══════════════════════════════════════ */
.cta-banner {
    background: var(--night-2); border-radius: 28px; padding: 64px 48px 56px;
    text-align: center; position: relative; overflow: hidden; border: 1px solid rgba(249,115,22,0.15);
}
.cta-banner::before {
    content: ''; position: absolute; top: -80px; right: -80px;
    width: 360px; height: 360px; border-radius: 50%;
    background: radial-gradient(circle, rgba(249,115,22,0.2) 0%, transparent 65%); pointer-events: none;
}
.cta-banner::after {
    content: ''; position: absolute; bottom: -60px; left: -60px;
    width: 260px; height: 260px; border-radius: 50%;
    background: radial-gradient(circle, rgba(251,191,36,0.1) 0%, transparent 65%); pointer-events: none;
}
.cta-fire-line {
    width: 80px; height: 2px;
    background: linear-gradient(90deg, transparent, var(--fire), transparent);
    margin: 0 auto 28px; position: relative; z-index: 1;
}
.cta-banner-title {
    font-family: var(--font-display);
    font-size: clamp(24px, 4.5vw, 40px); font-weight: 800;
    color: #fff; letter-spacing: -0.03em; line-height: 1.15; margin-bottom: 14px; position: relative; z-index: 1;
}
.cta-banner-title em { font-style: normal; color: var(--fire); }
.cta-banner-sub { font-size: 15px; color: rgba(255,255,255,0.4); margin-bottom: 36px; position: relative; z-index: 1; }

/* ═══════════════════════════════════════
   HERO ANIMATIONS
═══════════════════════════════════════ */
@keyframes fadeUp  { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeIn  { from { opacity: 0; } to { opacity: 1; } }
@keyframes scaleUp { from { opacity: 0; transform: scale(0.94) translateY(16px); } to { opacity: 1; transform: scale(1) translateY(0); } }
#h-badge  { animation: fadeUp   0.7s cubic-bezier(0.22,1,0.36,1) 0.1s both; }
#h-logo   { animation: scaleUp  0.8s cubic-bezier(0.22,1,0.36,1) 0.3s both; }
#h-tag    { animation: fadeUp   0.7s cubic-bezier(0.22,1,0.36,1) 0.5s both; }
#h-cta    { animation: fadeUp   0.65s cubic-bezier(0.22,1,0.36,1) 0.65s both; }
#h-stats  { animation: fadeUp   0.6s cubic-bezier(0.22,1,0.36,1) 0.8s both; }
#h-scroll { animation: fadeIn   0.5s ease 1.1s both; }

/* Gallery marquee */
@keyframes marquee-left  { from { transform: translateX(0); } to { transform: translateX(-50%); } }
@keyframes marquee-right { from { transform: translateX(-50%); } to { transform: translateX(0); } }
.is-marquee-left  { animation: marquee-left  38s linear infinite; }
.is-marquee-right { animation: marquee-right 46s linear infinite; }
.gallery-track:hover { animation-play-state: paused; }

.reveal, .reveal-left, .reveal-right { will-change: transform, opacity; }

/* ═══════════════════════════════════════
   MODAL STYLES
═══════════════════════════════════════ */
@keyframes fadeInOverlay  { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeOutOverlay { from { opacity: 1; } to { opacity: 0; } }
@keyframes slideUpCard    { from { opacity: 0; transform: translateY(28px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
@keyframes slideDownCard  { from { opacity: 1; transform: translateY(0) scale(1); } to { opacity: 0; transform: translateY(16px) scale(0.97); } }
@keyframes pulseDot       { 0%,100% { box-shadow: 0 0 0 0 rgba(249,115,22,0.5); } 50% { box-shadow: 0 0 0 4px rgba(249,115,22,0); } }

.mo-overlay {
    position: fixed; inset: 0; z-index: 9999;
    display: flex; align-items: center; justify-content: center; padding: 16px;
    background: rgba(0,0,0,0.55);
}
.mo-overlay.anim-in  { animation: fadeInOverlay  0.3s ease forwards; }
.mo-overlay.anim-out { animation: fadeOutOverlay 0.2s ease forwards; pointer-events: none; }
.mo-card-in          { animation: slideUpCard 0.4s cubic-bezier(0.22,1,0.36,1) 0.04s both; }

.mo-card {
    background: var(--white); border: 1px solid var(--m-ink-12);
    border-radius: 24px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.12), 0 4px 16px rgba(0,0,0,0.06);
    position: relative; overflow: hidden;
}
.mo-top-line {
    position: absolute; top: 0; left: 50%; transform: translateX(-50%);
    width: 90px; height: 3px; border-radius: 0 0 6px 6px;
    background: linear-gradient(90deg, transparent, var(--orange), transparent);
}

/* Badge */
.mo-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 14px; border-radius: 99px;
    border: 1.5px solid rgba(249,115,22,0.25);
    background: rgba(249,115,22,0.06);
}
.mo-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--orange); animation: pulseDot 2s ease infinite; }
.mo-badge-text {
    font-family: 'Unbounded', 'Syne', sans-serif; font-size: 10px; font-weight: 700;
    letter-spacing: 0.12em; text-transform: uppercase; color: var(--orange-dk);
}

/* Jalur Cards (Modal 1) */
.jalur-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.jalur-card {
    display: block; text-decoration: none; border-radius: 16px; padding: 26px 16px;
    text-align: center; position: relative; overflow: hidden;
    transition: all 0.25s cubic-bezier(0.22,1,0.36,1); cursor: pointer;
}
.jalur-card.jc-indigo { border: 1.5px solid rgba(99,102,241,0.2); background: rgba(99,102,241,0.04); }
.jalur-card.jc-orange { border: 1.5px solid rgba(249,115,22,0.2); background: rgba(249,115,22,0.04); font-family: inherit; width: 100%; }
.jalur-card.jc-indigo:hover { border-color: rgba(99,102,241,0.5); background: rgba(99,102,241,0.08); box-shadow: 0 8px 28px rgba(99,102,241,0.12); transform: translateY(-2px); }
.jalur-card.jc-orange:hover { border-color: rgba(249,115,22,0.45); background: rgba(249,115,22,0.08); box-shadow: 0 8px 28px rgba(249,115,22,0.15); transform: translateY(-2px); }
.jalur-shimmer { position: absolute; top: 0; left: 0; right: 0; height: 1px; opacity: 0; transition: opacity 0.3s; }
.jc-indigo .jalur-shimmer { background: linear-gradient(90deg,transparent,rgba(99,102,241,0.5),transparent); }
.jc-orange .jalur-shimmer { background: linear-gradient(90deg,transparent,rgba(249,115,22,0.6),transparent); }
.jalur-card:hover .jalur-shimmer { opacity: 1; }
.jalur-icon { width: 52px; height: 52px; border-radius: 14px; margin: 0 auto 14px; display: flex; align-items: center; justify-content: center; transition: transform 0.25s; }
.jalur-card:hover .jalur-icon { transform: scale(1.08); }
.jc-indigo .jalur-icon { background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); }
.jc-orange .jalur-icon { background: rgba(249,115,22,0.1);  border: 1px solid rgba(249,115,22,0.2); }
.jalur-title { font-family: 'Unbounded','Syne',sans-serif; font-weight: 800; font-size: 12px; color: var(--m-ink); letter-spacing: 0.04em; line-height: 1.4; margin: 0 0 4px; }
.jalur-sub-indigo { font-size: 11px; color: rgba(99,102,241,0.7); margin: 0 0 13px; }
.jalur-sub-orange { font-size: 11px; color: rgba(249,115,22,0.7); margin: 0 0 13px; }
.jalur-badge-pill { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 99px; }
.jc-indigo .jalur-badge-pill { background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); }
.jc-orange .jalur-badge-pill { background: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.2); }
.jalur-badge-pill-text { font-size: 10px; font-weight: 700; letter-spacing: 0.06em; font-family: 'Unbounded','Syne',sans-serif; }
.jc-indigo .jalur-badge-pill-text { color: rgba(99,102,241,0.9); }
.jc-orange .jalur-badge-pill-text { color: rgba(249,115,22,0.9); }
.jc-ext { position: absolute; top: 10px; right: 10px; opacity: 0.2; transition: opacity 0.2s; }
.jalur-card:hover .jc-ext { opacity: 0.6; }

/* Info row */
.mo-info-row { display: flex; align-items: center; gap: 10px; }
.mo-divider-line { flex: 1; height: 1px; background: var(--m-ink-12); }
.mo-divider-label { font-size: 9px; color: var(--m-ink-35); letter-spacing: 0.1em; text-transform: uppercase; font-family: 'Unbounded','Syne',sans-serif; }
.mo-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.mo-info-box { padding: 10px 12px; border-radius: 10px; background: var(--cream); border: 1px solid var(--m-ink-12); }
.mo-info-title { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--m-ink-35); margin: 0 0 3px; font-family: 'Unbounded','Syne',sans-serif; }
.mo-info-desc { font-size: 11px; color: var(--m-ink-60); margin: 0; line-height: 1.45; }

/* Sub Cards (Modal 2) */
.mo2-back {
    width: 34px; height: 34px; flex-shrink: 0; border-radius: 99px;
    border: 1px solid var(--m-ink-12); background: var(--cream);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.2s;
}
.mo2-back:hover { border-color: rgba(249,115,22,0.35); background: rgba(249,115,22,0.06); }
.sub-card {
    display: flex; align-items: center; gap: 14px; width: 100%;
    font-family: inherit; border-radius: 14px; padding: 14px 16px;
    border: 1.5px solid var(--m-ink-12); background: var(--cream);
    text-align: left; cursor: pointer; position: relative; overflow: hidden;
    transition: all 0.2s cubic-bezier(0.22,1,0.36,1);
}
.sub-card:hover { transform: translateY(-1px); }
.sub-card .sc-arrow { margin-left: auto; flex-shrink: 0; opacity: 0.2; transition: opacity 0.2s, transform 0.2s; }
.sub-card:hover .sc-arrow { opacity: 0.65; transform: translateX(3px); }
.sub-card.sc-blue:hover   { border-color:rgba(59,130,246,0.4);  background:rgba(59,130,246,0.06);  box-shadow:0 4px 16px rgba(59,130,246,0.1); }
.sub-card.sc-pink:hover   { border-color:rgba(236,72,153,0.4);  background:rgba(236,72,153,0.06);  box-shadow:0 4px 16px rgba(236,72,153,0.1); }
.sub-card.sc-yellow:hover { border-color:rgba(234,179,8,0.4);   background:rgba(234,179,8,0.06);   box-shadow:0 4px 16px rgba(234,179,8,0.1); }
.sub-card.sc-green:hover  { border-color:rgba(16,185,129,0.4);  background:rgba(16,185,129,0.06);  box-shadow:0 4px 16px rgba(16,185,129,0.1); }
.sub-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: transform 0.2s; }
.sub-card:hover .sub-icon { transform: scale(1.08); }
.sub-name  { font-weight: 700; font-size: 13px; color: var(--m-ink); margin: 0 0 2px; }
.sub-desc  { font-size: 11px; color: var(--m-ink-60); margin: 0; }
.sub-price { font-size: 12px; font-weight: 700; color: var(--orange-dk); flex-shrink: 0; margin-right: 6px; }

/* ═══════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════ */
@media (max-width: 640px) {
    .hero-content  { padding: 130px 20px 120px; }
    .hero-logo     { height: 120px; }
    .stats-strip   { max-width: 320px; }
    .stat-val      { font-size: 20px; }
    .cta-banner    { padding: 44px 24px 40px; }
    .section       { padding: 64px 20px; }
    .steps-grid    { grid-template-columns: 1fr 1fr; }
    .jalur-grid    { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ --}}
<section class="hero">
    <video class="hero-video"
        src="https://res.cloudinary.com/djs5pi7ev/video/upload/v1769500972/202601271004_aepgij.mp4"
        autoplay muted loop playsinline preload="auto"></video>
    <div class="hero-overlay"></div>
    <div class="hero-vignette"></div>
    <div class="hero-grain"></div>
    <div class="hero-radial"></div>

    <div class="hero-content">
        <div class="eyebrow" id="h-badge">
            <div class="eyebrow-dot"></div>
            <span class="eyebrow-text">Pendaftaran Resmi Dibuka</span>
        </div>

        <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1773109896/LOGO_BO2026_pzbvxh.png"
             alt="Bayan Open 2026" class="hero-logo" id="h-logo">

        <p class="hero-headline" id="h-tag">
            Turnamen bulutangkis bergengsi di Kalimantan Timur.<br>
            Daftar sekarang dan buktikan kemampuanmu.
        </p>

        <div class="cta-row" id="h-cta">
            {{-- Tombol ini langsung buka modal, bukan redirect --}}
            <button type="button" onclick="bukaModalDaftar()" class="btn-fire">
                Daftar Sekarang
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
            <a href="#kategori" class="btn-ghost-dark">
                Lihat Kategori
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 9l-7 7-7-7"/></svg>
            </a>
        </div>
    </div>

    <div class="scroll-cue" id="h-scroll">
        <div class="scroll-cue-mouse"><div class="scroll-cue-wheel"></div></div>
        <span class="scroll-cue-label">Scroll</span>
    </div>
</section>

{{-- ══════════════════════════════════════════
     KATEGORI
══════════════════════════════════════════ --}}
<section id="kategori" class="section kategori-section">
    <div class="section-inner">
        <div style="max-width:560px;">
            <span class="sec-tag reveal">Pilihan Kategori</span>
            <h2 class="sec-title reveal">Kategori Turnamen 2026</h2>
            <p class="sec-sub reveal">Pilih kategori yang sesuai dan buktikan kemampuanmu bersama pasangan terbaik.</p>
        </div>

        <div class="kategori-grid">
            <a href="{{ route('registration.ganda-dewasa-putra') }}" class="kat-card c-blue reveal">
                <div class="kat-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>
                <p class="kat-name">Ganda Dewasa Putra</p>
                <p class="kat-desc">Upload KTP di akhir pendaftaran. Terbuka untuk semua usia dewasa.</p>
                <div class="kat-footer">
                    <div class="kat-price-wrap">
                        <p class="kat-price">Rp 150.000</p>
                        <p class="kat-per">per pasangan</p>
                    </div>
                    <div class="kat-cta"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
                </div>
            </a>

            <a href="{{ route('registration.ganda-dewasa-putri') }}" class="kat-card c-rose reveal">
                <div class="kat-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>
                <p class="kat-name">Ganda Dewasa Putri</p>
                <p class="kat-desc">Upload KTP di akhir pendaftaran. Terbuka untuk semua usia dewasa.</p>
                <div class="kat-footer">
                    <div class="kat-price-wrap">
                        <p class="kat-price">Rp 150.000</p>
                        <p class="kat-per">per pasangan</p>
                    </div>
                    <div class="kat-cta"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f43f5e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
                </div>
            </a>

            <a href="{{ route('registration.ganda-veteran-putra') }}" class="kat-card c-amber reveal">
                <div class="kat-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <p class="kat-name">Ganda Veteran Putra</p>
                <p class="kat-desc">Scan KTP wajib. Minimal usia 45 tahun.</p>
                <div class="kat-footer">
                    <div class="kat-price-wrap">
                        <p class="kat-price">Rp 150.000</p>
                        <p class="kat-per">per pasangan</p>
                    </div>
                    <div class="kat-cta"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
                </div>
            </a>

            <a href="{{ route('registration.beregu') }}" class="kat-card c-teal reveal">
                <div class="kat-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </div>
                <p class="kat-name">Beregu</p>
                <p class="kat-desc">Upload KTP, minimum 6 pemain per regu. Cocok untuk tim komunitas.</p>
                <div class="kat-footer">
                    <div class="kat-price-wrap">
                        <p class="kat-price">Rp 200.000</p>
                        <p class="kat-per">per regu</p>
                    </div>
                    <div class="kat-cta"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#14b8a6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     GALERI
══════════════════════════════════════════ --}}
<section class="gallery-section">
    <video class="gallery-bg-video"
        src="https://res.cloudinary.com/djs5pi7ev/video/upload/q_50,w_1280/v1769502814/bayanopen-hero_iqhyip.mp4"
        autoplay muted loop playsinline preload="none"></video>
    <div class="gallery-video-overlay"></div>
    <div class="grain" aria-hidden="true"></div>

    <div class="gallery-header reveal">
        <span class="sec-tag" style="color:var(--fire);">Momen Bayan Open</span>
        <h2 class="sec-title light">Galeri Turnamen</h2>
        <p class="sec-sub" style="color:rgba(255,255,255,0.4); margin:14px auto 0; max-width:400px;">
            Rekaman semangat &amp; keseruan dari tahun-tahun sebelumnya.
        </p>
    </div>

    @php
    $photos = [
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1767765503/Bayan-8672_iuuxhb.jpg',   'Pertandingan Sengit'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1767765503/Bayan-1739_e0mi1r.jpg',   'Aksi Lapangan'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1767765501/Bayan-1715_rppm7m.jpg',   'Smash Keras'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1767765497/Bayan-8837_rpl0gl.jpg',   'Final Seru'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1767765495/PenyerahanMedali-575_yhyuds.jpg', 'Penyerahan Medali'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1773113997/Bayan-2268_tnmwt4.jpg',   'Momen Juara'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1773113996/Bayan-36_mpnqui.jpg',     'Servis Perdana'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1773113996/Bayan-2324_itnoc5.jpg',   'Ganda Putra'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1773113995/Bayan-26_llektw.jpg',     'Warming Up'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1773113995/Bayan-1427_s2frld.jpg',   'Veteran Bertanding'],
    ];
    $doubled  = array_merge($photos, $photos);
    $doubled2 = array_merge(array_reverse($photos), array_reverse($photos));
    @endphp

    <div class="gallery-track-wrap">
        <div class="gallery-track" id="track1">
            @foreach($doubled as $photo)
            <div class="gallery-item">
                <img src="{{ $photo[0] }}" alt="{{ $photo[1] }}" loading="lazy" decoding="async">
                <div class="gallery-overlay"><span>{{ $photo[1] }}</span></div>
            </div>
            @endforeach
        </div>
        <div class="gallery-track" id="track2">
            @foreach($doubled2 as $photo)
            <div class="gallery-item">
                <img src="{{ $photo[0] }}" alt="{{ $photo[1] }}" loading="lazy" decoding="async">
                <div class="gallery-overlay"><span>{{ $photo[1] }}</span></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     CARA DAFTAR
══════════════════════════════════════════ --}}
<section class="section steps-section" style="padding-bottom: 40px; padding-top: 72px;">
    <div class="section-inner">
        <div style="max-width:440px; margin:0 auto; text-align:center;">
            <span class="sec-tag reveal">Cara Daftar</span>
            <h2 class="sec-title reveal">4 Langkah Mudah</h2>
        </div>
        <div class="steps-grid">
            @foreach([
                ['1','Pilih Kategori','Pilih jalur dan kategori turnamen yang ingin diikuti.'],
                ['2','Isi Data','Isi data tim, kontak, dan upload scan KTP pemain.'],
                ['3','Bayar','Lakukan pembayaran via transfer, QRIS, atau metode tersedia.'],
                ['4','Bertanding!','Konfirmasi diterima — sampai jumpa di lapangan!'],
            ] as $s)
            <div class="step-card reveal">
                <div class="step-num">{{ $s[0] }}</div>
                <div class="step-badge">{{ $s[0] }}</div>
                <h4 class="step-title">{{ $s[1] }}</h4>
                <p class="step-desc">{{ $s[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     CTA BANNER
══════════════════════════════════════════ --}}
<section style="padding: 0 24px; background: var(--paper-2); margin-bottom: -1px;">
    <div style="max-width: 1120px; margin: 0 auto; padding-bottom: 48px;">
        <div class="cta-banner reveal">
            <div class="cta-fire-line"></div>
            <p class="cta-banner-title">Siap Bertanding di<br><em>Bayan Open 2026?</em></p>
            <p class="cta-banner-sub">Tempat terbatas — jangan sampai ketinggalan!</p>
            <button type="button" onclick="bukaModalDaftar()" class="btn-fire" style="font-size:11px;">
                Daftar Sekarang
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>
</section>

@endsection

{{-- ══════════════════════════════════════════
     MODAL 1 — PILIH JALUR
══════════════════════════════════════════ --}}
@push('modals')
<div id="modal1" class="mo-overlay" style="display:none;">
    <div id="modal1Card" class="mo-card" style="width:100%;max-width:560px;">
        <div class="mo-top-line"></div>
        <div style="padding:36px 32px 30px;">

            <div style="display:flex;justify-content:center;margin-bottom:22px;">
                <div class="mo-badge">
                    <span class="mo-badge-dot"></span>
                    <span class="mo-badge-text">Bayan Open 2026</span>
                </div>
            </div>

            <div style="text-align:center;margin-bottom:26px;">
                <h2 style="font-family:'Syne',sans-serif;font-size:24px;font-weight:800;color:var(--m-ink);margin:0 0 8px;letter-spacing:-0.02em;line-height:1.2;">Pilih Jalur Pendaftaran</h2>
                <p style="color:var(--m-ink-60);font-size:13px;margin:0;">Silakan pilih jalur turnamen yang ingin Anda ikuti</p>
            </div>

            <div class="jalur-grid" style="margin-bottom:22px;">
                <a href="https://si.pbsi.id/" target="_blank" rel="noopener noreferrer" class="jalur-card jc-indigo">
                    <div class="jalur-shimmer"></div>
                    <div class="jalur-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(99,102,241,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <p class="jalur-title">SIRKUIT<br>NASIONAL C</p>
                    <p class="jalur-sub-indigo">via si.pbsi.com</p>
                    <div class="jc-ext">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="rgba(0,0,0,0.5)" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                    </div>
                </a>

                <button type="button" onclick="bukaModal2()" class="jalur-card jc-orange">
                    <div class="jalur-shimmer"></div>
                    <div class="jalur-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(249,115,22,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>
                        </svg>
                    </div>
                    <p class="jalur-title">OPEN</p>
                    <p class="jalur-sub-orange">Daftar langsung di sini</p>
                    <div class="jalur-badge-pill">
                        <span class="mo-badge-dot" style="width:5px;height:5px;"></span>
                        <span class="jalur-badge-pill-text">DAFTAR SEKARANG</span>
                    </div>
                    <div class="jc-ext" style="bottom:11px;right:11px;top:auto;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(0,0,0,0.4)" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </button>
            </div>

            <div class="mo-info-row" style="margin-bottom:12px;">
                <div class="mo-divider-line"></div>
                <span class="mo-divider-label">Info</span>
                <div class="mo-divider-line"></div>
            </div>
            <div class="mo-info-grid">
                <div class="mo-info-box">
                    <p class="mo-info-title">Sirkuit Nasional C</p>
                    <p class="mo-info-desc">Didaftarkan melalui sistem resmi PBSI</p>
                </div>
                <div class="mo-info-box">
                    <p class="mo-info-title">Open</p>
                    <p class="mo-info-desc">Pendaftaran mandiri, pilih kategori sesuai kelas</p>
                </div>
            </div>

            {{-- Tombol tutup --}}
            <button type="button" onclick="tutupModalDaftar()" style="width:100%;margin-top:16px;padding:10px;border:1px solid var(--m-ink-12);border-radius:10px;background:transparent;color:var(--m-ink-60);font-size:12px;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='var(--cream)'" onmouseout="this.style.background='transparent'">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL 2 — PILIH KATEGORI
══════════════════════════════════════════ --}}
<div id="modal2" class="mo-overlay" style="display:none;">
    <div id="modal2Card" class="mo-card" style="width:100%;max-width:520px;">
        <div class="mo-top-line"></div>
        <div style="padding:30px 26px 26px;">

            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                <button type="button" onclick="tutupModal2()" class="mo2-back">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(26,18,9,0.6)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                </button>
                <div>
                    <h2 style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:var(--m-ink);margin:0 0 3px;letter-spacing:-0.02em;">Pilih Kategori Open</h2>
                    <p style="color:var(--m-ink-60);font-size:12px;margin:0;">Pilih kelas pertandingan yang akan Anda ikuti</p>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:8px;">
                <button type="button" class="sub-card sc-blue" onclick="pilihKategori('ganda-dewasa-putra')">
                    <div class="sub-icon" style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(59,130,246,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p class="sub-name">Ganda Dewasa Putra</p>
                        <p class="sub-desc">Upload KTP di akhir pendaftaran</p>
                    </div>
                    <span class="sub-price">Rp 150.000</span>
                    <svg class="sc-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(26,18,9,0.6)" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>

                <button type="button" class="sub-card sc-pink" onclick="pilihKategori('ganda-dewasa-putri')">
                    <div class="sub-icon" style="background:rgba(236,72,153,0.1);border:1px solid rgba(236,72,153,0.2);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(236,72,153,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p class="sub-name">Ganda Dewasa Putri</p>
                        <p class="sub-desc">Upload KTP di akhir pendaftaran</p>
                    </div>
                    <span class="sub-price">Rp 150.000</span>
                    <svg class="sc-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(26,18,9,0.6)" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>

                <button type="button" class="sub-card sc-yellow" onclick="pilihKategori('ganda-veteran-putra')">
                    <div class="sub-icon" style="background:rgba(234,179,8,0.1);border:1px solid rgba(234,179,8,0.2);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(234,179,8,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p class="sub-name">Ganda Veteran Putra</p>
                        <p class="sub-desc">Scan KTP · Minimal umur 45 Tahun</p>
                    </div>
                    <span class="sub-price">Rp 150.000</span>
                    <svg class="sc-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(26,18,9,0.6)" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>

                <button type="button" class="sub-card sc-green" onclick="pilihKategori('beregu')">
                    <div class="sub-icon" style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.2);">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="rgba(16,185,129,1)"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p class="sub-name">Beregu</p>
                        <p class="sub-desc">Upload KTP · min. 6 pemain per regu</p>
                    </div>
                    <span class="sub-price">Rp 200.000</span>
                    <svg class="sc-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(26,18,9,0.6)" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

<script>
// ── Route map ─────────────────────────────────────────────
const kategoriRoutes = {
    'ganda-dewasa-putra'  : '{{ route("registration.ganda-dewasa-putra") }}',
    'ganda-dewasa-putri'  : '{{ route("registration.ganda-dewasa-putri") }}',
    'ganda-veteran-putra' : '{{ route("registration.ganda-veteran-putra") }}',
    'beregu'              : '{{ route("registration.beregu") }}',
};

// ── Modal helpers ──────────────────────────────────────────
function animCard(card, inOut) {
    card.style.animation = 'none';
    void card.offsetWidth;
    card.style.animation = inOut === 'in'
        ? 'slideUpCard 0.4s cubic-bezier(0.22,1,0.36,1) 0.04s both'
        : 'slideDownCard 0.2s ease forwards';
}

/**
 * bukaModalDaftar(kategori?)
 * - Tanpa argumen → tampilkan Modal 1 (pilih jalur)
 * - Dengan argumen kategori → langsung tampilkan Modal 2
 */
function bukaModalDaftar(kategori) {
    document.body.style.overflow = 'hidden';
    if (kategori) {
        // Langsung ke modal 2 dengan kategori ter-highlight (opsional)
        showModal2();
    } else {
        showModal1();
    }
}

function showModal1() {
    const m1  = document.getElementById('modal1');
    const m1c = document.getElementById('modal1Card');
    m1.style.display = 'flex';
    m1.classList.add('anim-in');
    animCard(m1c, 'in');
    setTimeout(() => m1.classList.remove('anim-in'), 400);

    // Klik luar untuk tutup
    m1.onclick = (e) => { if (e.target === m1) tutupModalDaftar(); };
}

function showModal2() {
    const m2  = document.getElementById('modal2');
    const m2c = document.getElementById('modal2Card');
    m2.style.display = 'flex';
    m2.classList.add('anim-in');
    animCard(m2c, 'in');
    setTimeout(() => m2.classList.remove('anim-in'), 400);

    // Klik luar untuk tutup
    m2.onclick = (e) => { if (e.target === m2) tutupModalDaftar(); };
}

function tutupModalDaftar() {
    const m1 = document.getElementById('modal1');
    const m2 = document.getElementById('modal2');
    [m1, m2].forEach(mo => {
        if (mo.style.display !== 'none') {
            const card = mo.querySelector('.mo-card');
            mo.classList.add('anim-out');
            animCard(card, 'out');
            setTimeout(() => {
                mo.style.display = 'none';
                mo.classList.remove('anim-out');
            }, 220);
        }
    });
    document.body.style.overflow = '';
}

function bukaModal2() {
    const m1  = document.getElementById('modal1');
    const m1c = document.getElementById('modal1Card');
    const m2  = document.getElementById('modal2');
    const m2c = document.getElementById('modal2Card');

    m1.classList.add('anim-out');
    animCard(m1c, 'out');
    setTimeout(() => {
        m1.style.display = 'none';
        m1.classList.remove('anim-out');
        showModal2();
    }, 200);
}

function tutupModal2() {
    const m1 = document.getElementById('modal1');
    const m2 = document.getElementById('modal2');
    const m2c = document.getElementById('modal2Card');

    m2.classList.add('anim-out');
    animCard(m2c, 'out');
    setTimeout(() => {
        m2.style.display = 'none';
        m2.classList.remove('anim-out');
        showModal1();
    }, 200);
}

function pilihKategori(k) {
    const m2  = document.getElementById('modal2');
    const m2c = document.getElementById('modal2Card');
    m2.classList.add('anim-out');
    animCard(m2c, 'out');
    setTimeout(() => { window.location.href = kategoriRoutes[k]; }, 220);
}

// Escape key closes modal
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') tutupModalDaftar();
});

// ── GSAP + Marquee ─────────────────────────────────────────
var t1 = document.getElementById('track1');
var t2 = document.getElementById('track2');
if (t1) t1.classList.add('is-marquee-left');
if (t2) t2.classList.add('is-marquee-right');

function initGSAP() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        setTimeout(initGSAP, 100); return;
    }
    gsap.registerPlugin(ScrollTrigger);

    // Scroll reveals
    document.querySelectorAll('.reveal').forEach(el => {
        var rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight) return;
        gsap.set(el, { opacity: 0, y: 28 });
        gsap.to(el, {
            scrollTrigger: { trigger: el, start: 'top 88%', once: true,
                onEnter: () => gsap.to(el, { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' }) },
            duration: 0.001
        });
    });

    // Upgrade marquee to GSAP
    if (t1 && t2) {
        t1.classList.remove('is-marquee-left');
        t2.classList.remove('is-marquee-right');
        t1.style.transform = ''; t2.style.transform = '';

        var tw1 = gsap.fromTo(t1, { x: 0 }, { x: -t1.scrollWidth/2, duration: 38, ease: 'none', repeat: -1 });
        var tw2 = gsap.fromTo(t2, { x: -t2.scrollWidth/2 }, { x: 0, duration: 46, ease: 'none', repeat: -1 });

        var paused = false;
        [t1, t2].forEach(track => {
            track.addEventListener('mouseenter', () => { if (!paused) { tw1.pause(); tw2.pause(); paused = true; } });
            track.addEventListener('mouseleave', () => { if (paused) { tw1.resume(); tw2.resume(); paused = false; } });
        });
    }

    // Mouse parallax hero
    var heroRadial = document.querySelector('.hero-radial');
    if (heroRadial) {
        document.addEventListener('mousemove', e => {
            gsap.to(heroRadial, { x: (e.clientX/window.innerWidth-.5)*30, y: (e.clientY/window.innerHeight-.5)*20, duration: 1.5, ease: 'power2.out' });
        });
    }
}

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initGSAP);
else initGSAP();
</script>
@endpush