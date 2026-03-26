@extends('layouts.app')

@section('title', 'Selamat Datang')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

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
    --font-display: 'Montserrat', sans-serif;
    --font-body:    'Montserrat', sans-serif;

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
   HERO — GLASS CARD VERSION
═══════════════════════════════════════ */
.hero {
    min-height: 100svh;
    background: var(--night);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
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
        linear-gradient(to bottom, rgba(13,9,6,0.60) 0%, rgba(13,9,6,0.35) 50%, rgba(13,9,6,0.82) 100%),
        radial-gradient(ellipse 80% 60% at 50% 40%, rgba(249,115,22,0.10) 0%, transparent 65%);
}

.hero-vignette {
    position: absolute; inset: 0; z-index: 2;
    background: radial-gradient(ellipse 120% 100% at 50% 50%, transparent 40%, rgba(13,9,6,0.55) 100%);
    pointer-events: none;
}

.hero-grain {
    position: absolute; inset: 0; z-index: 3;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.035'/%3E%3C/svg%3E");
    pointer-events: none;
}

/* Floating ambient orbs */
.hero-orb {
    position: absolute; border-radius: 50%; pointer-events: none; z-index: 4;
}
.hero-orb-1 {
    width: 420px; height: 420px;
    top: -80px; left: -120px;
    background: radial-gradient(circle, rgba(249,115,22,0.14) 0%, transparent 70%);
    animation: orb-drift-1 12s ease-in-out infinite alternate;
}
.hero-orb-2 {
    width: 320px; height: 320px;
    bottom: -60px; right: -80px;
    background: radial-gradient(circle, rgba(251,191,36,0.10) 0%, transparent 70%);
    animation: orb-drift-2 16s ease-in-out infinite alternate;
}
.hero-orb-3 {
    width: 200px; height: 200px;
    top: 30%; right: 10%;
    background: radial-gradient(circle, rgba(249,115,22,0.07) 0%, transparent 70%);
    animation: orb-drift-2 9s ease-in-out infinite alternate-reverse;
}
@keyframes orb-drift-1 {
    from { transform: translate(0, 0) scale(1); }
    to   { transform: translate(30px, -25px) scale(1.12); }
}
@keyframes orb-drift-2 {
    from { transform: translate(0, 0) scale(1); }
    to   { transform: translate(-20px, 20px) scale(1.08); }
}

/* ── Hero content & glass card ── */
.hero-content {
    position: relative; z-index: 5;
    display: flex; align-items: center; justify-content: center;
    width: 100%;
    padding: 140px 24px 120px;
}

.glass-card {
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(32px) saturate(1.5) brightness(1.05);
    -webkit-backdrop-filter: blur(32px) saturate(1.5) brightness(1.05);
    border: 1px solid rgba(255,255,255,0.26);
    border-radius: 36px;
    padding: 48px 52px 44px;
    max-width: 580px;
    width: 100%;
    text-align: center;
    box-shadow:
        0 0 0 1px rgba(255,255,255,0.07) inset,
        0 2px 0 rgba(255,255,255,0.12) inset,
        0 40px 100px rgba(0,0,0,0.40),
        0 8px 24px rgba(0,0,0,0.18);
    position: relative;
    overflow: hidden;
    animation: glass-in 0.9s cubic-bezier(0.22,1,0.36,1) 0.05s both;
}

/* Top shimmer line — the defining iPhone glass detail */
.glass-card::before {
    content: '';
    position: absolute;
    top: 0; left: 8%; right: 8%;
    height: 1px;
    background: linear-gradient(90deg,
        transparent,
        rgba(255,255,255,0.6) 30%,
        rgba(255,255,255,0.75) 50%,
        rgba(255,255,255,0.6) 70%,
        transparent
    );
    pointer-events: none;
}

/* Warm bottom glow */
.glass-card::after {
    content: '';
    position: absolute;
    bottom: -50px; left: 50%; transform: translateX(-50%);
    width: 320px; height: 130px;
    background: radial-gradient(ellipse, rgba(249,115,22,0.16) 0%, transparent 70%);
    pointer-events: none;
}

@keyframes glass-in {
    from { opacity: 0; transform: translateY(36px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* ── Eyebrow badge ── */
.eyebrow {
    display: inline-flex; align-items: center; gap: 9px;
    padding: 5px 16px 5px 7px;
    border-radius: 99px;
    background: rgba(255,255,255,0.10);
    border: 1px solid rgba(255,255,255,0.22);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    margin-bottom: 24px;
    animation: fade-up 0.7s cubic-bezier(0.22,1,0.36,1) 0.15s both;
}
.eyebrow-dot-wrap {
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(249,115,22,0.18);
    display: flex; align-items: center; justify-content: center;
}
.eyebrow-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--fire);
    box-shadow: 0 0 8px rgba(249,115,22,0.9);
    animation: blink-dot 2.4s ease infinite;
}
@keyframes blink-dot {
    0%,100% { opacity: 1; transform: scale(1); }
    50%      { opacity: 0.45; transform: scale(0.75); }
}
.eyebrow-text {
    font-family: var(--font-display);
    font-size: 10px; font-weight: 700;
    letter-spacing: 0.18em; text-transform: uppercase;
    color: rgba(255,255,255,0.92);
}

/* ── Logo ── */
.hero-logo {
    height: 110px; width: auto;
    display: block; margin: 0 auto 20px;
    filter:
        drop-shadow(0 0 32px rgba(249,115,22,0.35))
        drop-shadow(0 4px 16px rgba(0,0,0,0.55));
    animation: logo-in 0.85s cubic-bezier(0.22,1,0.36,1) 0.28s both;
}
@keyframes logo-in {
    from { opacity: 0; transform: scale(0.88) translateY(14px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

/* ── Tagline ── */
.hero-headline {
    font-family: var(--font-display);
    font-size: clamp(13px, 1.8vw, 15px);
    font-weight: 400;
    letter-spacing: 0.03em;
    color: rgba(255,255,255,0.68);
    line-height: 1.8;
    max-width: 360px;
    margin: 0 auto 30px;
    animation: fade-up 0.7s cubic-bezier(0.22,1,0.36,1) 0.42s both;
}

/* ── CTA Row ── */
.cta-row {
    display: flex; gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
    animation: fade-up 0.65s cubic-bezier(0.22,1,0.36,1) 0.54s both;
}

.btn-fire {
    display: inline-flex; align-items: center; gap: 9px;
    font-family: var(--font-display);
    font-size: 10.5px; font-weight: 700;
    letter-spacing: 0.12em; text-transform: uppercase;
    color: #fff; text-decoration: none;
    background: linear-gradient(135deg, var(--fire) 0%, var(--fire-deep) 100%);
    padding: 14px 30px;
    border-radius: 15px;
    border: none; cursor: pointer;
    box-shadow:
        0 0 0 1px rgba(249,115,22,0.4),
        0 8px 28px rgba(249,115,22,0.45),
        inset 0 1px 0 rgba(255,255,255,0.18);
    transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
    position: relative; overflow: hidden;
}
.btn-fire::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.10), transparent);
    pointer-events: none;
}
.btn-fire:hover {
    transform: translateY(-2px);
    box-shadow:
        0 0 0 1px rgba(249,115,22,0.5),
        0 16px 44px rgba(249,115,22,0.6),
        inset 0 1px 0 rgba(255,255,255,0.22);
}
.btn-fire:active { transform: translateY(0); }

.btn-glass-outline {
    display: inline-flex; align-items: center; gap: 8px;
    font-family: var(--font-display);
    font-size: 10.5px; font-weight: 600;
    letter-spacing: 0.10em; text-transform: uppercase;
    color: rgba(255,255,255,0.85); text-decoration: none;
    background: rgba(255,255,255,0.10);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 14px 26px;
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.22);
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.14);
}
.btn-glass-outline:hover {
    background: rgba(255,255,255,0.18);
    border-color: rgba(255,255,255,0.36);
    color: #fff;
    transform: translateY(-1px);
}
.btn-glass-outline:active { transform: translateY(0); }

/* ── Glass divider ── */
.glass-divider {
    width: 100%;
    height: 1px;
    background: linear-gradient(90deg,
        transparent,
        rgba(255,255,255,0.14) 25%,
        rgba(255,255,255,0.18) 50%,
        rgba(255,255,255,0.14) 75%,
        transparent
    );
    margin: 26px 0;
    animation: fade-up 0.6s ease 0.66s both;
}

/* ── Stats strip inside glass ── */
.stats-row {
    display: flex; justify-content: center;
    animation: fade-up 0.6s ease 0.72s both;
}
.stat-cell {
    flex: 1; text-align: center;
    padding: 2px 8px;
    position: relative;
}
.stat-cell + .stat-cell::before {
    content: '';
    position: absolute; left: 0; top: 10%; bottom: 10%;
    width: 1px;
    background: rgba(255,255,255,0.14);
}
.stat-val {
    display: block;
    font-family: var(--font-display);
    font-size: 22px; font-weight: 800;
    color: var(--fire);
    line-height: 1;
    text-shadow: 0 0 14px rgba(249,115,22,0.4);
}
.stat-lbl {
    display: block;
    font-size: 9px; font-weight: 600;
    color: rgba(255,255,255,0.38);
    letter-spacing: 0.10em; text-transform: uppercase;
    margin-top: 5px;
}

/* ── Scroll cue ── */
.scroll-cue {
    position: absolute; bottom: 32px; left: 50%; transform: translateX(-50%);
    display: flex; flex-direction: column; align-items: center; gap: 7px;
    z-index: 5;
    animation: fade-in 0.5s ease 1.1s both;
}
.scroll-mouse {
    width: 20px; height: 30px;
    border: 1.5px solid rgba(255,255,255,0.2);
    border-radius: 10px;
    display: flex; justify-content: center; padding-top: 5px;
}
.scroll-wheel {
    width: 3px; height: 5px;
    background: var(--fire);
    border-radius: 2px;
    animation: wheel-anim 1.8s ease infinite;
}
@keyframes wheel-anim {
    0%   { opacity: 0; transform: translateY(0); }
    50%  { opacity: 1; }
    100% { opacity: 0; transform: translateY(7px); }
}
.scroll-label {
    font-family: var(--font-display);
    font-size: 8px; letter-spacing: 0.22em; text-transform: uppercase;
    color: rgba(255,255,255,0.28);
}

/* ── Shared fade-up keyframe ── */
@keyframes fade-up {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fade-in {
    from { opacity: 0; }
    to   { opacity: 1; }
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
   KATEGORI SECTION — TAB SWITCHER
═══════════════════════════════════════ */
.kategori-section { background: var(--paper); }

.kat-tab-switcher {
    display: inline-flex;
    background: var(--ink-12);
    border-radius: 14px;
    padding: 5px;
    gap: 4px;
    margin-top: 32px;
    position: relative;
}
.kat-tab-btn {
    position: relative; z-index: 1;
    padding: 10px 22px;
    border-radius: 10px;
    border: none; cursor: pointer;
    font-family: var(--font-display);
    font-size: 11px; font-weight: 700;
    letter-spacing: 0.1em; text-transform: uppercase;
    transition: color 0.25s ease;
    background: transparent;
    color: var(--ink-30);
}
.kat-tab-btn.active {
    background: #fff;
    color: var(--ink);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
}
.kat-tab-btn.active.tab-sirnas {
    background: linear-gradient(135deg, #312e81, #4338ca);
    color: #fff;
    box-shadow: 0 4px 16px rgba(67,56,202,0.35);
}
.kat-tab-btn.active.tab-open {
    background: linear-gradient(135deg, var(--fire), var(--fire-deep));
    color: #fff;
    box-shadow: 0 4px 16px rgba(249,115,22,0.35);
}
.kat-tab-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 18px; height: 18px; border-radius: 99px;
    font-size: 9px; font-weight: 800;
    margin-left: 6px; vertical-align: middle;
}
.tab-open .kat-tab-badge { background: rgba(249,115,22,0.15); color: var(--fire-deep); }
.tab-open.active .kat-tab-badge { background: rgba(255,255,255,0.25); color: #fff; }
.tab-sirnas .kat-tab-badge { background: rgba(67,56,202,0.15); color: #4338ca; }
.tab-sirnas.active .kat-tab-badge { background: rgba(255,255,255,0.25); color: #fff; }

.kat-panel { display: none; }
.kat-panel.active { display: block; }

.kategori-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    margin-top: 28px;
}
.kat-card {
    position: relative; overflow: hidden;
    background: #fff;
    border: 1px solid var(--ink-12);
    border-radius: var(--r-xl);
    padding: 32px 28px 28px;
    text-decoration: none; display: block;
    transition: all 0.35s cubic-bezier(0.22,1,0.36,1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
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

/* ── SIRNAS PANEL ── */
.sirnas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 12px;
    margin-top: 28px;
}
.sirnas-card {
    display: flex; align-items: center; gap: 14px;
    background: #fff;
    border: 1px solid rgba(67,56,202,0.1);
    border-radius: 16px;
    padding: 16px 18px;
    text-decoration: none; cursor: pointer;
    transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
    position: relative; overflow: hidden;
    font-family: inherit; width: 100%; text-align: left;
}
.sirnas-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, #4338ca, #818cf8);
    opacity: 0; transition: opacity 0.25s;
}
.sirnas-card:hover {
    transform: translateY(-3px);
    border-color: rgba(67,56,202,0.3);
    box-shadow: 0 12px 32px rgba(67,56,202,0.12);
    background: linear-gradient(135deg,#fff,#eef2ff);
}
.sirnas-card:hover::before { opacity: 1; }
.sirnas-card-icon {
    width: 40px; height: 40px; border-radius: 12px; flex-shrink: 0;
    background: rgba(67,56,202,0.08);
    border: 1px solid rgba(67,56,202,0.15);
    display: flex; align-items: center; justify-content: center;
    transition: transform 0.2s;
}
.sirnas-card:hover .sirnas-card-icon { transform: scale(1.08) rotate(-3deg); }
.sirnas-card-body { flex: 1; min-width: 0; }
.sirnas-card-name { font-family: var(--font-display); font-size: 13px; font-weight: 700; color: var(--ink); line-height: 1.3; }
.sirnas-card-sub  { font-size: 10.5px; color: rgba(67,56,202,0.65); margin-top: 2px; }
.sirnas-card-arrow { opacity: 0.2; transition: opacity 0.2s, transform 0.2s; flex-shrink: 0; }
.sirnas-card:hover .sirnas-card-arrow { opacity: 0.6; transform: translateX(3px); }

.sirnas-note {
    display: flex; align-items: center; gap: 12px;
    background: rgba(67,56,202,0.05);
    border: 1px solid rgba(67,56,202,0.15);
    border-radius: 14px;
    padding: 14px 18px;
    margin-top: 20px;
}
.sirnas-note-icon {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    background: rgba(67,56,202,0.1);
    display: flex; align-items: center; justify-content: center;
}
.sirnas-note-text { font-size: 12px; color: rgba(67,56,202,0.8); line-height: 1.55; }
.sirnas-note-text strong { color: #3730a3; }

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
.mo-top-line-indigo {
    position: absolute; top: 0; left: 50%; transform: translateX(-50%);
    width: 90px; height: 3px; border-radius: 0 0 6px 6px;
    background: linear-gradient(90deg, transparent, #6366f1, transparent);
}

.mo-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 14px; border-radius: 99px;
    border: 1.5px solid rgba(249,115,22,0.25);
    background: rgba(249,115,22,0.06);
}
.mo-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--orange); animation: pulseDot 2s ease infinite; }
.mo-badge-text {
    font-family: var(--font-display); font-size: 10px; font-weight: 700;
    letter-spacing: 0.12em; text-transform: uppercase; color: var(--orange-dk);
}

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
.jalur-title { font-family: var(--font-display); font-weight: 800; font-size: 12px; color: var(--m-ink); letter-spacing: 0.04em; line-height: 1.4; margin: 0 0 4px; }
.jalur-sub-indigo { font-size: 11px; color: rgba(99,102,241,0.7); margin: 0 0 13px; }
.jalur-sub-orange { font-size: 11px; color: rgba(249,115,22,0.7); margin: 0 0 13px; }
.jalur-badge-pill { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 99px; }
.jc-indigo .jalur-badge-pill { background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); }
.jc-orange .jalur-badge-pill { background: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.2); }
.jalur-badge-pill-text { font-size: 10px; font-weight: 700; letter-spacing: 0.06em; font-family: var(--font-display); }
.jc-indigo .jalur-badge-pill-text { color: rgba(99,102,241,0.9); }
.jc-orange .jalur-badge-pill-text { color: rgba(249,115,22,0.9); }
.jc-ext { position: absolute; top: 10px; right: 10px; opacity: 0.2; transition: opacity 0.2s; }
.jalur-card:hover .jc-ext { opacity: 0.6; }

.mo-info-row { display: flex; align-items: center; gap: 10px; }
.mo-divider-line { flex: 1; height: 1px; background: var(--m-ink-12); }
.mo-divider-label { font-size: 9px; color: var(--m-ink-35); letter-spacing: 0.1em; text-transform: uppercase; font-family: var(--font-display); }
.mo-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.mo-info-box { padding: 10px 12px; border-radius: 10px; background: var(--cream); border: 1px solid var(--m-ink-12); }
.mo-info-title { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--m-ink-35); margin: 0 0 3px; font-family: var(--font-display); }
.mo-info-desc { font-size: 11px; color: var(--m-ink-60); margin: 0; line-height: 1.45; }

.mo2-back {
    width: 34px; height: 34px; flex-shrink: 0; border-radius: 99px;
    border: 1px solid var(--m-ink-12); background: var(--cream);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.2s;
}
.mo2-back:hover { border-color: rgba(249,115,22,0.35); background: rgba(249,115,22,0.06); }
.mo2-back-indigo:hover { border-color: rgba(99,102,241,0.35); background: rgba(99,102,241,0.06); }
.sub-card {
    display: flex; align-items: center; gap: 20px; width: 100%;
    font-family: inherit; border-radius: 20px; padding: 26px 28px;
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
.sub-card.sc-indigo:hover { border-color:rgba(99,102,241,0.4);  background:rgba(99,102,241,0.06);  box-shadow:0 4px 16px rgba(99,102,241,0.1); }
.sub-icon { width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: transform 0.2s; }
.sub-card:hover .sub-icon { transform: scale(1.08); }
.sub-name  { font-weight: 700; font-size: 17px; color: var(--m-ink); margin: 0 0 5px; }
.sub-desc  { font-size: 14px; color: var(--m-ink-60); margin: 0; line-height: 1.5; }
.sub-price { font-size: 15px; font-weight: 700; color: var(--orange-dk); flex-shrink: 0; margin-right: 6px; }
.sub-price-indigo { font-size: 15px; font-weight: 700; color: #4338ca; flex-shrink: 0; margin-right: 6px; }

.sirnas-modal-list { display: flex; flex-direction: column; gap: 14px; max-height: 72vh; overflow-y: auto; padding-right: 6px; }
.sirnas-modal-list::-webkit-scrollbar { width: 5px; }
.sirnas-modal-list::-webkit-scrollbar-track { background: transparent; }
.sirnas-modal-list::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.25); border-radius: 99px; }

.sirnas-group-label {
    font-size: 12px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase;
    color: rgba(99,102,241,0.5); padding: 6px 4px 2px;
    font-family: var(--font-display);
}

/* ═══════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════ */
@media (max-width: 640px) {
    .glass-card          { padding: 36px 28px 32px; border-radius: 28px; }
    .hero-logo           { height: 88px; }
    .hero-headline       { font-size: 13px; }
    .cta-row             { gap: 8px; }
    .btn-fire,
    .btn-glass-outline   { padding: 12px 22px; font-size: 10px; }
    .cta-banner          { padding: 44px 24px 40px; }
    .section             { padding: 64px 20px; }
    .steps-grid          { grid-template-columns: 1fr 1fr; }
    .jalur-grid          { grid-template-columns: 1fr; }
    .sirnas-grid         { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════
     HERO — GLASS CARD
══════════════════════════════════════════ --}}
<section class="hero">
    <video class="hero-video"
        src="https://res.cloudinary.com/djs5pi7ev/video/upload/q_50,w_1280/v1769500972/202601271004_aepgij.mp4"
        autoplay muted loop playsinline preload="auto"></video>
    <div class="hero-overlay"></div>
    <div class="hero-vignette"></div>
    <div class="hero-grain"></div>

    {{-- Ambient floating orbs --}}
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>

    <div class="hero-content">
        {{-- ── GLASS CARD ── --}}
        <div class="glass-card">

            {{-- Eyebrow badge --}}
            <div class="eyebrow" id="h-badge">
                <div class="eyebrow-dot-wrap">
                    <div class="eyebrow-dot"></div>
                </div>
                <span class="eyebrow-text">Pendaftaran Resmi Dibuka</span>
            </div>

            {{-- Logo --}}
            <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1773109896/LOGO_BO2026_pzbvxh.png"
                 alt="Bayan Open 2026" class="hero-logo" id="h-logo">

            {{-- Tagline --}}
            <p class="hero-headline" id="h-tag">
                Turnamen bulutangkis bergengsi dan Sirkuit Nasional.<br>
                Daftar sekarang dan buktikan kemampuanmu.
            </p>

            {{-- CTA Buttons --}}
            <div class="cta-row" id="h-cta">
                <button type="button" onclick="bukaModalDaftar()" class="btn-fire">
                    Daftar Sekarang
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
                <a href="#kategori" class="btn-glass-outline">
                    Lihat Kategori
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 9l-7 7-7-7"/></svg>
                </a>
            </div>

            {{-- Divider --}}
            <div class="glass-divider" id="h-div"></div>

            {{-- Stats strip --}}
            <div class="stats-row" id="h-stats">
                <div class="stat-cell">
                    <span class="stat-val">4</span>
                    <span class="stat-lbl">Kategori Open</span>
                </div>
                <div class="stat-cell">
                    <span class="stat-val">18</span>
                    <span class="stat-lbl">Sirkuit Nasional C</span>
                </div>
                <div class="stat-cell">
                    <span class="stat-val">2026</span>
                    <span class="stat-lbl">Edisi</span>
                </div>
            </div>

        </div>
        {{-- ── /GLASS CARD ── --}}
    </div>

    {{-- Scroll cue --}}
    <div class="scroll-cue" id="h-scroll">
        <div class="scroll-mouse"><div class="scroll-wheel"></div></div>
        <span class="scroll-label">Scroll</span>
    </div>
</section>

{{-- ══════════════════════════════════════════
     ABOUT BAYAN OPEN
══════════════════════════════════════════ --}}
<section class="section" style="background: var(--paper-2); padding-top: 88px; padding-bottom: 80px;">
    <div class="section-inner">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:72px; align-items:center;">

            {{-- Teks kiri --}}
            <div>
                <span class="sec-tag reveal">Tentang Turnamen</span>
                <h2 class="sec-title reveal">Mengenal<br><em style="font-style:normal;color:var(--fire);">Bayan Open</em></h2>
                <p class="sec-sub reveal" style="max-width:100%; margin-bottom:16px;">
                    Bayan Open adalah turnamen bulutangkis bergengsi yang diselenggarakan di Balikpapan, Kalimantan Timur — menjadi salah satu ajang paling ditunggu-tunggu oleh para pecinta olahraga tepuk bulu di wilayah Kalimantan dan sekitarnya.
                </p>
                <p style="font-size:13.5px; line-height:1.8; color:var(--ink-60);" class="reveal">
                    Pertama kali digelar pada 2023, Bayan Open hadir dengan misi mempertemukan atlet-atlet terbaik dari berbagai daerah dalam satu arena yang kompetitif, sportif, dan berkesan. Turnamen ini bukan sekadar pertandingan — ia adalah perayaan semangat juang, kebersamaan komunitas, dan cinta terhadap bulutangkis Indonesia.
                </p>
                <p style="font-size:13.5px; line-height:1.8; color:var(--ink-60); margin-top:12px;" class="reveal">
                    Pada edisi 2026 ini, Bayan Open hadir lebih besar dengan jalur <strong>Sirkuit Nasional C (PBSI)</strong> di samping kategori Open, menjadikannya platform resmi bagi atlet muda untuk meraih poin nasional.
                </p>

                {{-- Pills --}}
                <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:28px;" class="reveal">
                    @foreach([
                        ['Edisi 2026', '#f97316'],
                        ['Balikpapan, Kaltim', '#3b82f6'],
                        ['Sirkuit Nasional C', '#14b8a6'],
                        ['Open Kategori', '#e11d48'],
                    ] as $pill)
                    <span style="display:inline-flex;align-items:center;gap:7px;padding:7px 16px 7px 8px;background:#fff;border:1px solid var(--ink-12);border-radius:99px;font-size:11px;font-weight:700;color:var(--ink);">
                        <span style="width:8px;height:8px;border-radius:50%;background:{{ $pill[1] }};flex-shrink:0;"></span>
                        {{ $pill[0] }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- Stat cards kanan --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;" class="reveal">
                @foreach([
                    ['3+',   'Tahun Digelar',   'Konsisten hadir sejak 2023',         '#f97316', '#f97316'],
                    ['22',   'Total Kategori',   '4 Open + 18 Sirkuit Nasional C',     '#2563eb', '#3b82f6'],
                    ['500+', 'Peserta',          'Dari seluruh Kalimantan & Indonesia', '#e11d48', '#f43f5e'],
                    ['PBSI', 'Resmi Sirknas',    'Terdaftar di si.pbsi.id',            '#0d9488', '#14b8a6'],
                ] as $stat)
                <div style="background:#fff;border:1px solid var(--ink-12);border-radius:20px;padding:28px 22px;position:relative;overflow:hidden;transition:all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 20px 48px rgba(0,0,0,0.08)'"
                     onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <div style="position:absolute;top:0;left:0;right:0;height:3px;border-radius:20px 20px 0 0;background:linear-gradient(90deg,{{ $stat[4] }},{{ $stat[3] }});"></div>
                    <div style="font-size:36px;font-weight:800;line-height:1;color:{{ $stat[3] }};margin-bottom:4px;">{{ $stat[0] }}</div>
                    <div style="font-size:11px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:var(--ink-30);margin-bottom:6px;">{{ $stat[1] }}</div>
                    <div style="font-size:12px;color:var(--ink-60);line-height:1.5;">{{ $stat[2] }}</div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     KATEGORI
══════════════════════════════════════════ --}}
<section id="kategori" class="section kategori-section">
    <div class="section-inner">
        <div style="max-width:640px;">
            <span class="sec-tag reveal">Pilihan Kategori</span>
            <h2 class="sec-title reveal">Kategori Turnamen 2026</h2>
            <p class="sec-sub reveal">Pilih jalur dan kategori yang sesuai. Tersedia jalur Open dan Sirkuit Nasional C.</p>

            <div class="kat-tab-switcher reveal" role="tablist" aria-label="Pilih jalur turnamen">
                <button
                    type="button"
                    id="tab-open"
                    class="kat-tab-btn tab-open active"
                    role="tab"
                    aria-selected="true"
                    aria-controls="panel-open"
                    onclick="switchTab('open')">
                    Open
                    <span class="kat-tab-badge">4</span>
                </button>
                <button
                    type="button"
                    id="tab-sirnas"
                    class="kat-tab-btn tab-sirnas"
                    role="tab"
                    aria-selected="false"
                    aria-controls="panel-sirnas"
                    onclick="switchTab('sirnas')">
                    Sirkuit Nasional C
                    <span class="kat-tab-badge">18</span>
                </button>
            </div>
        </div>

        {{-- Panel: Open --}}
        <div id="panel-open" class="kat-panel active" role="tabpanel" aria-labelledby="tab-open">
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

        {{-- Panel: Sirkuit Nasional C --}}
        <div id="panel-sirnas" class="kat-panel" role="tabpanel" aria-labelledby="tab-sirnas">

            <div class="sirnas-note reveal">
                <div class="sirnas-note-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4338ca" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                    </svg>
                </div>
                <p class="sirnas-note-text">
                    Semua kategori Sirkuit Nasional C didaftarkan melalui <strong>sistem resmi PBSI</strong> di
                    <strong>si.pbsi.id</strong>. Klik kategori di bawah untuk langsung menuju halaman pendaftaran.
                </p>
            </div>

            @php
            $sirnas_groups = [
                'Usia Dini' => [
                    ['Tunggal Usia Dini Putra', 'Putra · Usia Dini'],
                    ['Tunggal Usia Dini Putri', 'Putri · Usia Dini'],
                ],
                'Anak-Anak' => [
                    ['Tunggal Anak-Anak Putra', 'Putra · Anak-Anak'],
                    ['Tunggal Anak-Anak Putri', 'Putri · Anak-Anak'],
                ],
                'Pemula' => [
                    ['Tunggal Pemula Putra', 'Putra · Kelas Pemula'],
                    ['Tunggal Pemula Putri', 'Putri · Kelas Pemula'],
                    ['Ganda Pemula Putra',   'Putra · Kelas Pemula'],
                    ['Ganda Pemula Putri',   'Putri · Kelas Pemula'],
                ],
                'Remaja' => [
                    ['Tunggal Remaja Putra',  'Putra · Kelas Remaja'],
                    ['Tunggal Remaja Putri',  'Putri · Kelas Remaja'],
                    ['Ganda Remaja Putra',    'Putra · Kelas Remaja'],
                    ['Ganda Remaja Putri',    'Putri · Kelas Remaja'],
                    ['Ganda Remaja Campuran', 'Campuran · Kelas Remaja'],
                ],
                'Taruna' => [
                    ['Tunggal Taruna Putra',  'Putra · Kelas Taruna'],
                    ['Tunggal Taruna Putri',  'Putri · Kelas Taruna'],
                    ['Ganda Taruna Putra',    'Putra · Kelas Taruna'],
                    ['Ganda Taruna Putri',    'Putri · Kelas Taruna'],
                    ['Ganda Taruna Campuran', 'Campuran · Kelas Taruna'],
                ],
            ];
            @endphp

            @foreach($sirnas_groups as $group => $items)
            <div style="margin-top: 24px;">
                <p class="sirnas-group-label reveal">{{ $group }}</p>
                <div class="sirnas-grid">
                    @foreach($items as $item)
                    <a href="https://si.pbsi.id/" target="_blank" rel="noopener noreferrer" class="sirnas-card reveal">
                        <div class="sirnas-card-icon">
                            @if(str_contains($item[0], 'Tunggal'))
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4338ca" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="7" r="4"/><path d="M6 21v-2a6 6 0 0112 0v2"/>
                            </svg>
                            @elseif(str_contains($item[0], 'Campuran'))
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4338ca" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="7" r="3"/><circle cx="16" cy="7" r="3"/>
                                <path d="M2 21v-1a6 6 0 016-6h2M22 21v-1a6 6 0 00-6-6h-2"/>
                            </svg>
                            @else
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4338ca" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                            </svg>
                            @endif
                        </div>
                        <div class="sirnas-card-body">
                            <p class="sirnas-card-name">{{ $item[0] }}</p>
                            <p class="sirnas-card-sub">{{ $item[1] }}</p>
                        </div>
                        <svg class="sirnas-card-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(0,0,0,0.5)" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     GUEST STAR — LIGHT VERSION
══════════════════════════════════════════ --}}
<section style="background:var(--paper);padding:88px 24px 96px;position:relative;overflow:hidden;">

    {{-- Decorative top border --}}
    <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,transparent 0%,#f97316 30%,#fbbf24 50%,#f97316 70%,transparent 100%);"></div>

    {{-- Soft warm bg glow --}}
    <div style="position:absolute;inset:0;pointer-events:none;background:radial-gradient(ellipse 70% 50% at 20% 60%,rgba(249,115,22,0.04) 0%,transparent 70%),radial-gradient(ellipse 60% 40% at 80% 30%,rgba(251,191,36,0.04) 0%,transparent 70%);"></div>

    <div class="section-inner" style="position:relative;z-index:1;">

        {{-- Header --}}
        <div style="text-align:center;margin-bottom:56px;">
            <span class="sec-tag reveal">Bintang Tamu Spesial</span>
            <h2 class="sec-title reveal">Guest Star <em style="font-style:normal;color:var(--fire);">Bayan Open</em> 2026</h2>
            <p class="sec-sub reveal" style="margin:14px auto 0;max-width:420px;">
                Dua legenda bulutangkis Indonesia hadir langsung di arena bersama para peserta dan penonton.
            </p>
        </div>

        {{-- Grid --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;max-width:860px;margin:0 auto;">

            @foreach([
                [
                    'name'     => 'Hendra Setiawan',
                    'nickname' => '"The Wall"',
                    'initial'  => 'HS',
                    'photo'    => 'https://redaktur.tvrinews.com/storage/images/1080x840/415c5c3b-9728-4574-b0e2-f6dde9bc2196.jpeg',
                    'bio'      => 'Salah satu ganda putra terbaik sepanjang masa. Dikenal lewat teknik net sempurna, gerakan elegan, dan mentalitas juara yang tak tertandingi di kancah internasional.',
                    'achv'     => [
                        'Juara Dunia BWF 2007 & 2013',
                        'Medali Emas Olimpiade 2004 & 2016',
                        'Ikon generasi emas badminton Indonesia',
                    ],
                    'role' => 'Ganda Putra · Legenda BWF',
                    'id'   => 'hs',
                ],
                [
                    'name'     => 'Marcus Fernaldi Gideon',
                    'nickname' => '"The Minion"',
                    'initial'  => 'MG',
                    'photo'    => 'https://jurnalbogor.com/wp-content/uploads/2024/03/Marcus-Fernaldi-Gideon.jpg',
                    'bio'      => 'Bagian dari duo legendaris "The Minions" bersama Kevin Sanjaya. Marcus membawa era baru dominasi ganda putra dengan kecepatan reflek luar biasa dan smash yang mematikan.',
                    'achv'     => [
                        'Juara Dunia BWF 2019',
                        'All England Champion 2017 & 2018',
                        'Peraih Medali Thomas Cup & Sudirman Cup',
                    ],
                    'role' => 'Ganda Putra · "The Minions"',
                    'id'   => 'mg',
                ],
            ] as $guest)

            <div class="reveal"
                 style="background:#fff;border:1px solid rgba(26,16,7,0.08);border-radius:28px;overflow:hidden;transition:all 0.35s cubic-bezier(0.22,1,0.36,1);box-shadow:0 2px 12px rgba(0,0,0,0.04);"
                 onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 28px 64px rgba(249,115,22,0.12),0 0 0 1.5px rgba(249,115,22,0.2)';this.style.borderColor='rgba(249,115,22,0.2)'"
                 onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,0.04)';this.style.borderColor='rgba(26,16,7,0.08)'">

                {{-- Photo strip --}}
                <div style="width:100%;height:220px;position:relative;overflow:hidden;background:var(--paper-2);">
                    <img src="{{ $guest['photo'] }}"
                         alt="{{ $guest['name'] }}"
                         id="photo-{{ $guest['id'] }}"
                         style="width:100%;height:100%;object-fit:cover;object-position:top center;display:block;transition:transform 0.5s ease;"
                         onmouseover="this.style.transform='scale(1.05)'"
                         onmouseout="this.style.transform='scale(1)'"
                         onerror="this.style.display='none';document.getElementById('ph-{{ $guest['id'] }}').style.display='flex'">
                    <div id="ph-{{ $guest['id'] }}"
                         style="display:none;width:100%;height:100%;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--paper-2) 0%,#ede8df 100%);font-size:56px;font-weight:800;color:rgba(249,115,22,0.15);letter-spacing:-0.04em;">
                        {{ $guest['initial'] }}
                    </div>
                    {{-- Gradient overlay --}}
                    <div style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 40%,rgba(255,247,237,0.6) 100%);pointer-events:none;"></div>
                    {{-- Badge
                    <div style="position:absolute;top:14px;left:14px;display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:rgba(255,255,255,0.92);border:1px solid rgba(249,115,22,0.2);border-radius:99px;font-size:9px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;color:#c2410c;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#f97316;animation:blink-dot 2.4s ease infinite;"></span>
                        Guest Star
                    </div> --}}
                </div>

                {{-- Body --}}
                <div style="padding:24px 28px 28px;">

                    <h3 style="font-size:20px;font-weight:800;letter-spacing:-0.02em;color:var(--ink);line-height:1.2;margin-bottom:3px;">{{ $guest['name'] }}</h3>
                    <p style="font-size:11px;font-weight:700;letter-spacing:0.10em;text-transform:uppercase;color:var(--fire);margin-bottom:12px;">{{ $guest['nickname'] }}</p>

                    {{-- Fire line divider --}}
                    <div style="height:1px;background:linear-gradient(90deg,rgba(249,115,22,0.15),rgba(249,115,22,0.05) 60%,transparent);margin:14px 0;"></div>

                    <p style="font-size:12.5px;line-height:1.75;color:var(--ink-60);margin-bottom:16px;">{{ $guest['bio'] }}</p>

                    {{-- Achievements --}}
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        @foreach($guest['achv'] as $a)
                        <div style="display:flex;align-items:flex-start;gap:9px;font-size:11.5px;color:var(--ink-60);line-height:1.45;">
                            <div style="width:20px;height:20px;border-radius:6px;flex-shrink:0;background:#fff7ed;border:1px solid rgba(249,115,22,0.15);display:flex;align-items:center;justify-content:center;margin-top:1px;">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </div>
                            {{ $a }}
                        </div>
                        @endforeach
                    </div>

                    {{-- Footer --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:18px;padding-top:16px;border-top:1px solid rgba(26,16,7,0.08);">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:24px;height:16px;border-radius:3px;overflow:hidden;border:1px solid rgba(26,16,7,0.08);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:13px;line-height:1;">🇮🇩</div>
                            <div>
                                <div style="font-size:12px;font-weight:700;color:var(--ink);">Indonesia</div>
                                <div style="font-size:10.5px;color:var(--ink-30);margin-top:1px;">{{ $guest['role'] }}</div>
                            </div>
                        </div>
                        <div style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:#fff7ed;border:1px solid rgba(249,115,22,0.15);border-radius:99px;font-size:10px;font-weight:700;letter-spacing:0.06em;color:#c2410c;">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#c2410c" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                            Ganda Putra
                        </div>
                    </div>

                </div>
            </div>
            @endforeach

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
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1767765503/Bayan-8672_iuuxhb.jpg',   'Ganda Veteran Putra'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1767765503/Bayan-1739_e0mi1r.jpg',   'Aksi Lapangan'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1767765501/Bayan-1715_rppm7m.jpg',   'Smash Keras'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1767765497/Bayan-8837_rpl0gl.jpg',   'Ganda Putri'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1767765495/PenyerahanMedali-575_yhyuds.jpg', 'Penyerahan Medali'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1773113997/Bayan-2268_tnmwt4.jpg',   'Guest Star'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1773113996/Bayan-36_mpnqui.jpg',     'Servis Perdana'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1773113996/Bayan-2324_itnoc5.jpg',   'Aksi Hendra Setiawan'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1773113995/Bayan-26_llektw.jpg',     'Taruna Putra'],
        ['https://res.cloudinary.com/djs5pi7ev/image/upload/w_600,q_60,f_webp/v1773113995/Bayan-1427_s2frld.jpg',   'Ganda Putra'],
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
            <p class="cta-banner-title">SIAP BERTANDING di<br><em>BAYAN OPEN 2026?</em></p>
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
                <h2 style="font-family:var(--font-display);font-size:24px;font-weight:800;color:var(--m-ink);margin:0 0 8px;letter-spacing:-0.02em;line-height:1.2;">Pilih Jalur Pendaftaran</h2>
                <p style="color:var(--m-ink-60);font-size:13px;margin:0;">Silakan pilih jalur turnamen yang ingin Anda ikuti</p>
            </div>

            <div class="jalur-grid" style="margin-bottom:22px;">
                <button type="button" onclick="bukaModalSirnas()" class="jalur-card jc-indigo" style="font-family:inherit;width:100%;">
                    <div class="jalur-shimmer"></div>
                    <div class="jalur-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(99,102,241,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <p class="jalur-title">SIRKUIT<br>NASIONAL C</p>
                    <p class="jalur-sub-indigo">Pilih kategori</p>
                    <div class="jalur-badge-pill">
                        <span class="mo-badge-dot" style="width:5px;height:5px;background:#6366f1;animation:none;"></span>
                        <span class="jalur-badge-pill-text">18 KATEGORI</span>
                    </div>
                </button>

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

            <button type="button" onclick="tutupModalDaftar()" style="width:100%;margin-top:16px;padding:10px;border:1px solid var(--m-ink-12);border-radius:10px;background:transparent;color:var(--m-ink-60);font-size:12px;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='var(--cream)'" onmouseout="this.style.background='transparent'">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL SIRNAS
══════════════════════════════════════════ --}}
<div id="modalSirnas" class="mo-overlay" style="display:none;">
    <div id="modalSirnasCard" class="mo-card" style="width:100%;max-width:780px;">
        <div class="mo-top-line-indigo"></div>
        <div style="padding:44px 44px 40px;">

            <div style="display:flex;align-items:center;gap:18px;margin-bottom:32px;">
                <button type="button" onclick="tutupModalSirnas()" class="mo2-back mo2-back-indigo" style="width:48px;height:48px;flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(26,18,9,0.6)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                </button>
                <div>
                    <h2 style="font-family:var(--font-display);font-size:26px;font-weight:800;color:var(--m-ink);margin:0 0 6px;letter-spacing:-0.02em;">Sirkuit Nasional C</h2>
                    <p style="color:var(--m-ink-60);font-size:15px;margin:0;">Pilih kategori — semua daftar via <span style="color:#4338ca;font-weight:700;">si.pbsi.id</span></p>
                </div>
            </div>

            <div class="sirnas-modal-list">
                @php
                $sicon_single = '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="rgba(99,102,241,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M6 21v-2a6 6 0 0112 0v2"/></svg>';
                $sicon_double = '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="rgba(99,102,241,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>';
                $sicon_mixed  = '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="rgba(99,102,241,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="7" r="3"/><circle cx="16" cy="7" r="3"/><path d="M2 21v-1a6 6 0 016-6h2M22 21v-1a6 6 0 00-6-6h-2"/></svg>';
                $ext_arrow = '<svg class="sc-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(26,18,9,0.6)" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>';
                $modal_groups = [
                    'Usia Dini' => [
                        ['Tunggal Usia Dini Putra','Usia Dini · Putra','single'],
                        ['Tunggal Usia Dini Putri','Usia Dini · Putri','single'],
                    ],
                    'Anak-Anak' => [
                        ['Tunggal Anak-Anak Putra','Anak-Anak · Putra','single'],
                        ['Tunggal Anak-Anak Putri','Anak-Anak · Putri','single'],
                    ],
                    'Pemula' => [
                        ['Tunggal Pemula Putra','Pemula · Putra','single'],
                        ['Tunggal Pemula Putri','Pemula · Putri','single'],
                        ['Ganda Pemula Putra',  'Pemula · Putra','double'],
                        ['Ganda Pemula Putri',  'Pemula · Putri','double'],
                    ],
                    'Remaja' => [
                        ['Tunggal Remaja Putra',  'Remaja · Putra',    'single'],
                        ['Tunggal Remaja Putri',  'Remaja · Putri',    'single'],
                        ['Ganda Remaja Putra',    'Remaja · Putra',    'double'],
                        ['Ganda Remaja Putri',    'Remaja · Putri',    'double'],
                        ['Ganda Remaja Campuran', 'Remaja · Campuran', 'mixed'],
                    ],
                    'Taruna' => [
                        ['Tunggal Taruna Putra',  'Taruna · Putra',    'single'],
                        ['Tunggal Taruna Putri',  'Taruna · Putri',    'single'],
                        ['Ganda Taruna Putra',    'Taruna · Putra',    'double'],
                        ['Ganda Taruna Putri',    'Taruna · Putri',    'double'],
                        ['Ganda Taruna Campuran', 'Taruna · Campuran', 'mixed'],
                    ],
                ];
                @endphp

                @foreach($modal_groups as $gname => $grows)
                <p class="sirnas-group-label" style="padding:{{ $loop->first ? '0' : '12px' }} 4px 6px;font-size:11px;">{{ $gname }}</p>
                @foreach($grows as $row)
                <a href="https://si.pbsi.id/" target="_blank" rel="noopener noreferrer" class="sub-card sc-indigo">
                    <div class="sub-icon" style="background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.2);">
                        {!! $row[2]==='single' ? $sicon_single : ($row[2]==='mixed' ? $sicon_mixed : $sicon_double) !!}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p class="sub-name">{{ $row[0] }}</p>
                        <p class="sub-desc">{{ $row[1] }}</p>
                    </div>
                    <span class="sub-price-indigo">si.pbsi.id</span>
                    {!! $ext_arrow !!}
                </a>
                @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL 2 — PILIH KATEGORI (OPEN)
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
                    <h2 style="font-family:var(--font-display);font-size:18px;font-weight:800;color:var(--m-ink);margin:0 0 3px;letter-spacing:-0.02em;">Pilih Kategori Open</h2>
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
// ── Route map ───────────────────────────────────────────────
const kategoriRoutes = {
    'ganda-dewasa-putra'  : '{{ route("registration.ganda-dewasa-putra") }}',
    'ganda-dewasa-putri'  : '{{ route("registration.ganda-dewasa-putri") }}',
    'ganda-veteran-putra' : '{{ route("registration.ganda-veteran-putra") }}',
    'beregu'              : '{{ route("registration.beregu") }}',
};

// ── Tab Switcher ────────────────────────────────────────────
function switchTab(tab) {
    document.querySelectorAll('.kat-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.kat-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + tab).classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
}

// ── Modal helpers ───────────────────────────────────────────
function animCard(card, inOut) {
    card.style.animation = 'none';
    void card.offsetWidth;
    card.style.animation = inOut === 'in'
        ? 'slideUpCard 0.4s cubic-bezier(0.22,1,0.36,1) 0.04s both'
        : 'slideDownCard 0.2s ease forwards';
}

function bukaModalDaftar() {
    document.body.style.overflow = 'hidden';
    showModal1();
}

function showModal1() {
    const m1 = document.getElementById('modal1');
    const m1c = document.getElementById('modal1Card');
    m1.style.display = 'flex';
    m1.classList.add('anim-in');
    animCard(m1c, 'in');
    setTimeout(() => m1.classList.remove('anim-in'), 400);
    m1.onclick = (e) => { if (e.target === m1) tutupModalDaftar(); };
}

function showModal2() {
    const m2 = document.getElementById('modal2');
    const m2c = document.getElementById('modal2Card');
    m2.style.display = 'flex';
    m2.classList.add('anim-in');
    animCard(m2c, 'in');
    setTimeout(() => m2.classList.remove('anim-in'), 400);
    m2.onclick = (e) => { if (e.target === m2) tutupModalDaftar(); };
}

function showModalSirnas() {
    const ms = document.getElementById('modalSirnas');
    const msc = document.getElementById('modalSirnasCard');
    ms.style.display = 'flex';
    ms.classList.add('anim-in');
    animCard(msc, 'in');
    setTimeout(() => ms.classList.remove('anim-in'), 400);
    ms.onclick = (e) => { if (e.target === ms) tutupModalDaftar(); };
}

function tutupModalDaftar() {
    ['modal1','modal2','modalSirnas'].forEach(id => {
        const mo = document.getElementById(id);
        if (mo && mo.style.display !== 'none') {
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
    const m1 = document.getElementById('modal1');
    const m1c = document.getElementById('modal1Card');
    m1.classList.add('anim-out');
    animCard(m1c, 'out');
    setTimeout(() => { m1.style.display = 'none'; m1.classList.remove('anim-out'); showModal2(); }, 200);
}

function tutupModal2() {
    const m2 = document.getElementById('modal2');
    const m2c = document.getElementById('modal2Card');
    m2.classList.add('anim-out');
    animCard(m2c, 'out');
    setTimeout(() => { m2.style.display = 'none'; m2.classList.remove('anim-out'); showModal1(); }, 200);
}

function bukaModalSirnas() {
    const m1 = document.getElementById('modal1');
    const m1c = document.getElementById('modal1Card');
    m1.classList.add('anim-out');
    animCard(m1c, 'out');
    setTimeout(() => { m1.style.display = 'none'; m1.classList.remove('anim-out'); showModalSirnas(); }, 200);
}

function tutupModalSirnas() {
    const ms = document.getElementById('modalSirnas');
    const msc = document.getElementById('modalSirnasCard');
    ms.classList.add('anim-out');
    animCard(msc, 'out');
    setTimeout(() => { ms.style.display = 'none'; ms.classList.remove('anim-out'); showModal1(); }, 200);
}

function pilihKategori(k) {
    const m2 = document.getElementById('modal2');
    const m2c = document.getElementById('modal2Card');
    m2.classList.add('anim-out');
    animCard(m2c, 'out');
    setTimeout(() => { window.location.href = kategoriRoutes[k]; }, 220);
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') tutupModalDaftar();
});

// ── GSAP + Marquee ──────────────────────────────────────────
var t1 = document.getElementById('track1');
var t2 = document.getElementById('track2');
if (t1) t1.classList.add('is-marquee-left');
if (t2) t2.classList.add('is-marquee-right');

/* CSS fallback marquee (before GSAP loads) */
var styleEl = document.createElement('style');
styleEl.textContent = `
    @keyframes marquee-left  { from{transform:translateX(0)} to{transform:translateX(-50%)} }
    @keyframes marquee-right { from{transform:translateX(-50%)} to{transform:translateX(0)} }
    .is-marquee-left  { animation: marquee-left  38s linear infinite; }
    .is-marquee-right { animation: marquee-right 46s linear infinite; }
    .gallery-track:hover { animation-play-state: paused; }
`;
document.head.appendChild(styleEl);

function initGSAP() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        setTimeout(initGSAP, 100); return;
    }
    gsap.registerPlugin(ScrollTrigger);

    // Scroll reveal
    document.querySelectorAll('.reveal').forEach(el => {
        var rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight) return;
        gsap.set(el, { opacity: 0, y: 28 });
        ScrollTrigger.create({
            trigger: el, start: 'top 88%', once: true,
            onEnter: () => gsap.to(el, { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' })
        });
    });

    // GSAP marquee (override CSS)
    if (t1 && t2) {
        t1.classList.remove('is-marquee-left');
        t2.classList.remove('is-marquee-right');
        t1.style.transform = ''; t2.style.transform = '';

        var tw1 = gsap.fromTo(t1, { x: 0 }, { x: -t1.scrollWidth/2, duration: 38, ease: 'none', repeat: -1 });
        var tw2 = gsap.fromTo(t2, { x: -t2.scrollWidth/2 }, { x: 0, duration: 46, ease: 'none', repeat: -1 });

        var paused = false;
        [t1, t2].forEach(track => {
            track.addEventListener('mouseenter', () => { if (!paused) { tw1.pause(); tw2.pause(); paused = true; } });
            track.addEventListener('mouseleave', () => { if (paused)  { tw1.resume(); tw2.resume(); paused = false; } });
        });
    }

    // Parallax mouse on hero orbs
    var orbs = document.querySelectorAll('.hero-orb');
    document.addEventListener('mousemove', e => {
        var mx = (e.clientX / window.innerWidth  - 0.5);
        var my = (e.clientY / window.innerHeight - 0.5);
        orbs.forEach((orb, i) => {
            var factor = (i + 1) * 18;
            gsap.to(orb, { x: mx * factor, y: my * factor, duration: 2, ease: 'power2.out' });
        });
    });
}

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initGSAP);
else initGSAP();
</script>
@endpush