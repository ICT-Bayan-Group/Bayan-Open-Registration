@extends('layouts.app')

@section('title', 'Live Score - Bayan Open 2026')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════
   LIVE SCORE PAGE — BAYAN OPEN 2026
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

.ls { background: var(--paper); min-height: 100svh; font-family: var(--font-body); color: var(--ink); }

/* ════════════════════════════════════════
   VIDEO HERO
════════════════════════════════════════ */
.ls-hero {
    position: relative;
    height: clamp(280px, 42vw, 440px);
    overflow: hidden;
    display: flex; align-items: flex-end;
}
.ls-hero-video {
    position: absolute; inset: 0; z-index: 0;
    width: 100%; height: 100%; object-fit: cover;
    pointer-events: none;
}
.ls-hero-overlay {
    position: absolute; inset: 0; z-index: 1;
    background:
        linear-gradient(to bottom,
            rgba(13,9,6,0.45) 0%,
            rgba(13,9,6,0.30) 30%,
            rgba(13,9,6,0.82) 72%,
            rgba(13,9,6,0.98) 100%),
        radial-gradient(ellipse 80% 60% at 40% 40%, rgba(249,115,22,0.10) 0%, transparent 60%);
}
.ls-hero-grain {
    position: absolute; inset: 0; z-index: 2; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.045'/%3E%3C/svg%3E");
}
.ls-hero-content {
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

.ls-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 5px 14px 5px 8px;
    border-radius: 99px;
    border: 1px solid rgba(249,115,22,0.3);
    background: rgba(249,115,22,0.09);
    backdrop-filter: blur(8px);
    margin-bottom: 14px;
}
.ls-eyebrow-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--fire);
    box-shadow: 0 0 10px var(--fire);
    animation: lsblink 1.6s ease infinite;
}
@keyframes lsblink { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.35;transform:scale(.75)} }
.ls-eyebrow-text {
    font-family: var(--font-display);
    font-size: 9.5px; font-weight: 700;
    letter-spacing: .18em; text-transform: uppercase;
    color: var(--fire);
}

.ls-hero-title {
    font-family: var(--font-display);
    font-size: clamp(22px, 4vw, 42px); font-weight: 800;
    color: #fff; letter-spacing: -.03em; line-height: 1.08;
    margin-bottom: 8px;
}
.ls-hero-sub {
    font-size: 13.5px; color: var(--ash);
    line-height: 1.65; max-width: 420px;
}

.ls-stats {
    display: flex; gap: 8px; flex-shrink: 0; flex-wrap: wrap;
    align-self: flex-end;
}
.ls-stat {
    display: flex; flex-direction: column; align-items: center;
    padding: 12px 18px;
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: var(--r-md);
}
.ls-stat-val {
    font-family: var(--font-display);
    font-size: 22px; font-weight: 800;
    color: var(--fire); line-height: 1;
}
.ls-stat-lbl {
    font-size: 9px; color: var(--ash-2);
    text-transform: uppercase; letter-spacing: .08em;
    margin-top: 4px;
}

/* ════════════════════════════════════════
   FILTER STRIP (sticky — dark → light on scroll)
   Compact 2-row bar, mengikuti gaya filter di halaman Jadwal
════════════════════════════════════════ */
.ls-filter-strip-wrap {
    background: var(--night);
    border-bottom: 1px solid rgba(255,255,255,0.07);
    position: sticky;
    top: 64px; z-index: 40;
    transition: background 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
}
@media (min-width:640px)  { .ls-filter-strip-wrap { top: 80px; } }
@media (min-width:1024px) { .ls-filter-strip-wrap { top: 96px; } }

/* ── Scrolled: light mode ── */
.ls-filter-strip-wrap.scrolled {
    background: var(--white);
    border-bottom-color: var(--ink-12);
    box-shadow: 0 4px 20px rgba(26,16,7,0.08);
}
.ls-filter-strip-wrap.scrolled .ls-select {
    background-color: var(--paper);
    border-color: var(--ink-12);
    color: var(--ink-70);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='rgba(26,16,7,0.4)' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 9px center;
    background-size: 12px 12px;
}
.ls-filter-strip-wrap.scrolled .ls-select:focus,
.ls-filter-strip-wrap.scrolled .ls-select:hover {
    border-color: var(--fire);
    background-color: #fff;
}
.ls-filter-strip-wrap.scrolled .ls-select-icon { color: var(--fire-deep); }
.ls-filter-strip-wrap.scrolled .ls-count-badge {
    background: var(--paper);
    border-color: var(--ink-12);
}
.ls-filter-strip-wrap.scrolled .ls-count-lbl {
    color: var(--ink-45);
}
.ls-filter-strip-wrap.scrolled .ls-refresh-dot {
    box-shadow: 0 0 8px var(--fire);
}
.ls-filter-strip-wrap.scrolled .ls-refresh-text {
    color: var(--ink-25);
}

.ls-filter-strip {
    max-width: 1120px; margin: 0 auto;
    padding: 12px 24px;
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap;
}
.ls-filter-selects {
    display: flex; align-items: center; gap: 8px;
    flex: 1 1 auto; min-width: 0;
}

/* icon selects */
.ls-select-wrap { position: relative; flex: 1 1 0; min-width: 0; }
.ls-select-icon {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    color: var(--fire); pointer-events: none; display: flex; align-items: center;
    transition: color .35s ease;
}
.ls-select {
    width: 100%;
    appearance: none;
    padding: 8px 24px 8px 32px;
    background-color: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: var(--r-xs);
    color: rgba(255,255,255,0.8);
    font-family: var(--font-display); font-size: 10.5px; font-weight: 700;
    cursor: pointer; outline: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='rgba(255,255,255,0.4)' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 11px 11px;
    transition: border-color .2s, background-color .2s, color .35s ease;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.ls-select:focus, .ls-select:hover {
    border-color: rgba(249,115,22,0.5);
    background-color: rgba(249,115,22,0.07);
}
.ls-select option { background: #1a0a04; color: #fff; }

/* refresh info */
.ls-refresh-info {
    flex-shrink: 0;
    display: flex; align-items: center; gap: 6px;
}
.ls-refresh-dot {
    width: 6px; height: 6px; border-radius: 50%; background: var(--fire);
    box-shadow: 0 0 8px var(--fire); animation: lsblink 1.6s ease infinite;
}
.ls-refresh-text {
    font-family: var(--font-display);
    font-size: 8.5px; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase;
    color: rgba(255,255,255,0.3);
    transition: color 0.35s ease;
}
.ls-count-badge {
    flex-shrink: 0;
    display: flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: var(--r-xs);
    padding: 6px 11px;
    transition: background 0.35s ease, border-color 0.35s ease;
}
.ls-count-num {
    font-family: var(--font-display); font-size: 12px; font-weight: 800;
    background: var(--fire); color: #fff;
    padding: 1px 7px; border-radius: 6px;
}
.ls-count-lbl {
    font-family: var(--font-display); font-size: 9px; font-weight: 700;
    letter-spacing: .06em; text-transform: uppercase;
    color: rgba(255,255,255,0.5);
    transition: color 0.35s ease;
}


/* ════════════════════════════════════════
   MAIN CONTENT
════════════════════════════════════════ */
.ls-main { max-width: 1120px; margin: 0 auto; padding: 28px 24px 80px; }

/* ════════════════════════════════════════
   SCORE CARD
════════════════════════════════════════ */
.ls-card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
    gap: 14px;
}

.ls-card {
    background: var(--white);
    border: 1px solid var(--ink-12);
    border-radius: var(--r-lg);
    overflow: hidden;
    position: relative;
    transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s, border-color .3s;
}
.ls-card::before {
    content: '';
    position: absolute; left:0; top:0; bottom:0; width:3px;
    background: linear-gradient(to bottom, var(--fire), var(--fire-deep));
    opacity: .35; transition: opacity .25s;
}
.ls-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 48px rgba(249,115,22,0.1), 0 2px 8px rgba(26,16,7,0.06);
    border-color: rgba(249,115,22,0.22);
}
.ls-card:hover::before { opacity: 1; }

.ls-card.selesai::before { background: linear-gradient(to bottom, var(--success), #059669); opacity:.6; }
.ls-card.selesai:hover   { border-color: rgba(16,185,129,0.3); box-shadow: 0 16px 48px rgba(16,185,129,0.08), 0 2px 8px rgba(26,16,7,0.05); }

/* time bar — menyatu langsung di atas card (bukan pill terpisah) */
.ls-card-time {
    background: linear-gradient(135deg, var(--night), var(--night-2));
    padding: 8px 16px;
    display: flex; align-items: center; gap: 7px;
}
.ls-card-time-dot { width:6px; height:6px; border-radius:50%; background:var(--fire); box-shadow:0 0 8px var(--fire); flex-shrink:0; animation: lsblink 1.6s ease infinite; }
.ls-card-time-text { font-family: var(--font-display); font-size: 12px; font-weight: 800; letter-spacing: .04em; color: #fff; }
.ls-card-time-wita { font-size: 9px; font-weight: 600; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: .06em; }

.ls-card-head {
    padding: 12px 16px 10px 20px;
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
    border-bottom: 1px dashed var(--ink-12);
}

.ls-kat-badge {
    padding: 3px 9px; border-radius: 99px;
    font-family: var(--font-display); font-size: 8px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
}
.kgp  { background:rgba(59,130,246,.1);  color:#2563eb; border:1px solid rgba(59,130,246,.18); }
.kgpi { background:rgba(244,63,94,.1);   color:#e11d48; border:1px solid rgba(244,63,94,.18); }
.kvp  { background:rgba(249,115,22,.1);  color:var(--fire-deep); border:1px solid rgba(249,115,22,.18); }
.kvpi { background:rgba(168,85,247,.1);  color:#7c3aed; border:1px solid rgba(168,85,247,.18); }
.kber { background:rgba(20,184,166,.1);  color:#0d9488; border:1px solid rgba(20,184,166,.18); }
.kdef { background:var(--ink-06); color:var(--ink-45); border:1px solid var(--ink-12); }

.ls-head-right { display: flex; align-items: center; gap: 6px; }
.ls-court-pill {
    display: flex; align-items: center; gap: 4px;
    background: var(--night); border-radius: 6px;
    padding: 3px 8px;
    font-family: var(--font-display); font-size: 9px; font-weight: 800;
    color: rgba(255,255,255,0.8); letter-spacing: .06em;
}
.ls-court-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--fire); box-shadow: 0 0 6px var(--fire); }
.ls-babak-tag {
    font-family: var(--font-display); font-size: 8px; font-weight: 800;
    letter-spacing: .1em; text-transform: uppercase;
    color: var(--ink-25); background: var(--ink-06);
    padding: 3px 8px; border-radius: 5px;
}

/* players vs score section — compact horizontal row (mobile-first) */
.ls-card-body { padding: 14px 16px 12px; }

.ls-match-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.ls-team { flex: 1 1 0; min-width: 0; }
.ls-team-right { text-align: right; }

.ls-player-name {
    font-size: 12.5px; font-weight: 600; color: var(--ink);
    line-height: 1.35; word-break: break-word;
}
.ls-player-pb {
    font-size: 9.5px; color: var(--ink-25); font-weight: 400;
    margin-top: 2px; display: block;
}

.ls-score-badge {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 64px;
    padding: 10px 12px;
    border-radius: var(--r-sm);
    background: transparent;
    font-family: var(--font-display);
}
.ls-score-num {
    display: block;
    width: 100%;
    text-align: center;
    font-weight: 800;
    font-size: 16px;
    letter-spacing: -.01em;
    color: var(--fire);
    line-height: 1.1;
    white-space: nowrap;
}
.ls-score-vs-label {
    display: block;
    width: 100%;
    text-align: center;
    font-size: 8px;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: rgb(0, 0, 0);
    line-height: 1;
    margin-top: 4px;
}
.ls-score-badge.pending {
    background: var(--ink-06);
    border: 1px solid var(--ink-12);
    color: var(--ink-25);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
    padding: 8px 10px;
}

/* set scores */
.ls-sets {
    margin-top: 10px; padding-top: 9px;
    border-top: 1px dashed var(--ink-12);
    display: flex; gap: 6px; flex-wrap: wrap; align-items: center;
}
.ls-set-label {
    font-family: var(--font-display); font-size: 8px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    color: var(--ink-25); margin-right: 2px;
}
.ls-set-chip {
    padding: 3px 9px; border-radius: 7px;
    font-family: var(--font-mono); font-size: 11px; font-weight: 800;
    background: var(--ink-06); color: var(--ink);
    border: 1px solid var(--ink-12);
}

/* card footer */
.ls-card-foot { padding: 10px 20px 14px; }
.ls-winner-banner {
    display: flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, var(--success-bg), rgba(16,185,129,0.06));
    border: 1px solid rgba(16,185,129,0.18);
    border-radius: var(--r-sm);
    padding: 8px 12px;
}
.ls-trophy { font-size: 14px; }
.ls-winner-label {
    font-family: var(--font-display); font-size: 8px; font-weight: 800;
    letter-spacing: .14em; text-transform: uppercase; color: var(--success);
    opacity: .7;
}
.ls-winner-name {
    font-size: 13px; font-weight: 700; color: var(--success); line-height: 1.3;
}

.ls-waiting {
    display: inline-flex; align-items: center; gap: 6px;
    font-family: var(--font-display); font-size: 9px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    color: var(--ink-25); padding: 4px 0;
}
.ls-wait-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--ink-25); animation: lsblink 1.6s ease infinite; }

/* ════════════════════════════════════════
   DATE SECTION LABEL
════════════════════════════════════════ */
.ls-date-section { margin-bottom: 32px; }
.ls-date-label {
    display: flex; align-items: center; gap: 14px; margin-bottom: 20px;
}
.ls-date-label-text {
    font-family: var(--font-display);
    font-size: clamp(11px,1.8vw,13px); font-weight: 800;
    color: var(--ink); letter-spacing: .04em; text-transform: uppercase;
    white-space: nowrap; display: flex; align-items: center; gap: 8px;
}
.ls-date-fire { color: var(--fire); display:flex; align-items:center; }
.ls-date-line { flex:1; height:1px; background: linear-gradient(90deg, var(--ink-12), transparent); }
.ls-date-count { font-size:11px; color:var(--ink-25); font-weight:600; white-space:nowrap; }

/* ════════════════════════════════════════
   SKELETON / EMPTY
════════════════════════════════════════ */
.skel { border-radius:6px; background:linear-gradient(90deg,var(--ink-06) 25%,var(--ink-12) 50%,var(--ink-06) 75%); background-size:200% 100%; animation:shimmer 1.4s infinite; }
@keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

.ls-skel-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(330px,1fr)); gap:14px; }
.ls-skel-card { background:var(--white); border:1px solid var(--ink-12); border-radius:var(--r-lg); padding:20px; }

.ls-empty { text-align:center; padding:80px 24px; }
.ls-empty-icon { width:60px; height:60px; border-radius:18px; background:var(--ink-06); display:flex; align-items:center; justify-content:center; margin:0 auto 18px; color:var(--ink-25); }
.ls-empty-title { font-family:var(--font-display); font-size:16px; font-weight:800; color:var(--ink); margin-bottom:6px; }
.ls-empty-sub { font-size:13px; color:var(--ink-45); }

/* ════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════ */
@media (max-width:640px) {
    .ls-hero { height:260px; }
    .ls-hero-content { padding:0 18px 24px; }
    .ls-stats { display:none; }
    .ls-hero-title { font-size:22px; }
    .ls-filter-strip { padding:10px 16px; gap:6px; }
    .ls-filter-selects {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 6px; width: 100%;
    }
    .ls-select { font-size:8.5px; padding:8px 18px 8px 24px; }
    .ls-select-icon { left:7px; }
    .ls-select-icon svg { width:11px; height:11px; }
    .ls-count-badge { display:none; }
    .ls-main { padding:20px 16px 60px; }
    .ls-card-grid { grid-template-columns:1fr; gap:10px; }
    .ls-refresh-info { display:none; }

    /* compact card header on phones */
    .ls-card-head { padding:11px 12px 8px 14px; gap:6px; flex-wrap: wrap; }
    .ls-head-right { gap:5px; flex-wrap: wrap; row-gap:5px; }
    .ls-kat-badge { font-size:7.5px; padding:3px 8px; }
    .ls-court-pill { font-size:8.5px; padding:3px 7px; }
    .ls-babak-tag { font-size:7.5px; padding:3px 7px; }
    .ls-card-time { padding:7px 14px; }
    .ls-card-time-text { font-size:11px; }

    /* tighter score row on phones */
    .ls-card-body { padding:12px 14px 10px; }
    .ls-match-row { gap:8px; }
    .ls-player-name { font-size:12px; }
    .ls-score-badge { min-width:48px; font-size:15px; padding:7px 5px; }
    .ls-set-chip { font-size:10px; padding:3px 8px; }

    .ls-card-foot { padding:8px 14px 12px; }
    .ls-winner-name { font-size:12px; }
}
</style>
@endpush

@section('content')
<div class="ls">

    {{-- ══ VIDEO HERO ══ --}}
    <div class="ls-hero">
        <video class="ls-hero-video"
            src="https://res.cloudinary.com/dzkvjy4ds/video/upload/v1787541050/2026082410523.mp4"
            autoplay muted loop playsinline preload="auto"></video>
        <div class="ls-hero-overlay"></div>
        <div class="ls-hero-grain"></div>

        <div class="ls-hero-content">
            <div>
                <div class="ls-eyebrow">
                    <span class="ls-eyebrow-dot"></span>
                    <span class="ls-eyebrow-text">Live</span>
                </div>
                <h1 class="ls-hero-title">Hasil Pertandingan</h1>
                <p class="ls-hero-sub">Bayan Open 2026 &nbsp;·&nbsp; DOME &amp; HEVINDO &nbsp;·&nbsp; Balikpapan</p>
            </div>
            <div class="ls-stats" id="heroStats" style="display:none;">
                <div class="ls-stat">
                    <span class="ls-stat-val" id="statSelesai">—</span>
                    <span class="ls-stat-lbl">Selesai</span>
                </div>
                <div class="ls-stat">
                    <span class="ls-stat-val" id="statTotal">—</span>
                    <span class="ls-stat-lbl">Total Match</span>
                </div>
                <div class="ls-stat">
                    <span class="ls-stat-val" id="statKat">—</span>
                    <span class="ls-stat-lbl">Kategori</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ FILTER STRIP ══ --}}
    <div class="ls-filter-strip-wrap" id="filterStripWrap">
        <div class="ls-filter-strip">
            <div class="ls-filter-selects">
                <div class="ls-select-wrap">
                    <span class="ls-select-icon">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                    </span>
                    <select id="filterTanggal" class="ls-select" onchange="applyFilter()">
                        <option value="ALL">Semua Tanggal</option>
                    </select>
                </div>
                <div class="ls-select-wrap">
                    <span class="ls-select-icon">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M20.59 13.41L11 3.83V3H3v8h.83L13.41 20.59a2 2 0 0 0 2.83 0l4.35-4.35a2 2 0 0 0 0-2.83z"/>
                            <circle cx="6.5" cy="6.5" r="1.5"/>
                        </svg>
                    </span>
                    <select id="filterKategori" class="ls-select" onchange="applyFilter()">
                        <option value="ALL">Semua Kategori</option>
                    </select>
                </div>
                <div class="ls-select-wrap">
                    <span class="ls-select-icon">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                    </span>
                    <select id="filterLapangan" class="ls-select" onchange="applyFilter()">
                        <option value="ALL">Semua Lapangan</option>
                    </select>
                </div>
            </div>

            <div class="ls-count-badge">
                <span class="ls-count-num" id="countNum">—</span>
                <span class="ls-count-lbl">Match</span>
            </div>

            <div class="ls-refresh-info">
                <span class="ls-refresh-dot"></span>
                <span class="ls-refresh-text" id="refreshLabel">Auto-refresh 30s</span>
            </div>
        </div>
    </div>

    {{-- ══ SCHEDULE BODY ══ --}}
    <div class="ls-main" id="lsMain">
        <div id="loadingSkeleton">
            <div style="height:18px;width:200px;border-radius:8px;margin-bottom:20px;" class="skel"></div>
            <div class="ls-skel-grid">
                @for($i=0;$i<6;$i++)
                <div class="ls-skel-card">
                    <div class="skel" style="height:11px;width:40%;margin-bottom:14px;"></div>
                    <div class="skel" style="height:14px;width:88%;margin-bottom:8px;"></div>
                    <div class="skel" style="height:10px;width:60%;margin-bottom:14px;"></div>
                    <div class="skel" style="height:28px;width:100%;border-radius:10px;"></div>
                </div>
                @endfor
            </div>
        </div>
        <div id="scoreContent" style="display:none;"></div>
    </div>

</div>
@endsection

@push('scripts')

<script>
(function(){
    fetch('/api/track-visit', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ page: 'live-score', label: 'Live Score' })
    }).catch(()=>{});
})();
</script>

<script>
/* ═══════════════════════════════════════
   LIVE SCORE JS — Bayan Open 2026
═══════════════════════════════════════ */
let masterData = [];

const HARI          = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const BULAN_PANJANG = ['Januari','Februari','Maret','April','Mei','Juni',
                       'Juli','Agustus','September','Oktober','November','Desember'];

function parseDate(str) { const [y,m,d]=str.split('-').map(Number); return new Date(y,m-1,d); }
function fmtLong(s) { const d=parseDate(s); return `${HARI[d.getDay()]}, ${d.getDate()} ${BULAN_PANJANG[d.getMonth()]} ${d.getFullYear()}`; }

/* ── Set-win tally, e.g. "21-15 18-21 21-19" → { a:2, b:1 } ── */
function tallySets(skor) {
    if (!skor || skor === '-') return null;
    let a = 0, b = 0;
    skor.trim().split(/\s+/).forEach(set => {
        const [pa, pb] = set.split('-').map(Number);
        if (!isNaN(pa) && !isNaN(pb)) {
            if (pa > pb) a++;
            else if (pb > pa) b++;
        }
    });
    if (a === 0 && b === 0) return null;
    return { a, b };
}

/* ── SCROLL TRANSITION ────────────────────── */
(function () {
    const strip = document.getElementById('filterStripWrap');
    if (!strip) return;

    function onScroll() {
        const heroHeight = document.querySelector('.ls-hero')?.offsetHeight ?? 400;
        if (window.scrollY > heroHeight - 120) {
            strip.classList.add('scrolled');
        } else {
            strip.classList.remove('scrolled');
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();

/* ── INIT ─────────────────────────────────── */
async function init() {
    await fetchData(true);
    setInterval(() => fetchData(false), 30000);
}

async function fetchData(isFirst = false) {
    try {
        const res  = await fetch('https://result.bayanopen.com/api/get-full-schedule');
        masterData = await res.json();

        if (isFirst) {
            populateFilters();
            document.getElementById('loadingSkeleton').style.display = 'none';
            document.getElementById('scoreContent').style.display = '';
        }

        updateStats();
        renderScore();

    } catch(err) {
        console.error('Gagal fetch:', err);
        if (isFirst) {
            document.getElementById('loadingSkeleton').innerHTML = `
                <div class="ls-empty">
                    <div class="ls-empty-icon">
                        <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                        </svg>
                    </div>
                    <p class="ls-empty-title">Gagal memuat data</p>
                    <p class="ls-empty-sub">Periksa koneksi internet dan coba lagi.</p>
                </div>`;
        }
    }
}

/* ── FILTER POPULATION ────────────────────── */
function populateFilters() {
    const selTgl = document.getElementById('filterTanggal');
    const selKat = document.getElementById('filterKategori');
    const selLap = document.getElementById('filterLapangan');

    const unikTgl = [...new Set(masterData.map(m => m.tanggal).filter(Boolean))].sort();
    const unikKat = [...new Set(masterData.map(m => m.kategori).filter(Boolean))].sort();
    const unikLap = [...new Set(masterData.map(m => m.court).filter(Boolean))].sort();

    unikTgl.forEach(t => selTgl.add(new Option(fmtLong(t), t)));
    unikKat.forEach(k => selKat.add(new Option(k, k)));
    unikLap.forEach(l => selLap.add(new Option('Lapangan ' + l, l)));
}

function applyFilter() { renderScore(); }

/* ── STATS ────────────────────────────────── */
function updateStats() {
    const total   = masterData.length;
    const selesai = masterData.filter(m => (m.status_label||'').toUpperCase() === 'SELESAI').length;
    const kats    = new Set(masterData.map(m => m.kategori).filter(Boolean)).size;

    document.getElementById('statTotal').textContent   = total;
    document.getElementById('statSelesai').textContent = selesai;
    document.getElementById('statKat').textContent     = kats;
    document.getElementById('heroStats').style.display = 'flex';

    const now = new Date();
    document.getElementById('refreshLabel').textContent =
        `Diperbarui ${now.getHours().toString().padStart(2,'0')}:${now.getMinutes().toString().padStart(2,'0')}`;
}

/* ── RENDER ───────────────────────────────── */
function renderScore() {
    const valTgl = document.getElementById('filterTanggal').value;
    const valKat = document.getElementById('filterKategori').value;
    const valLap = document.getElementById('filterLapangan').value;

    const filtered = masterData.filter(m => {
        const mTgl = (valTgl === 'ALL' || m.tanggal === valTgl);
        const mKat = (valKat === 'ALL' || m.kategori === valKat);
        const mLap = (valLap === 'ALL' || String(m.court) === valLap);
        return mTgl && mKat && mLap;
    });

    document.getElementById('countNum').textContent = filtered.length;

    const content = document.getElementById('scoreContent');

    if (filtered.length === 0) {
        content.innerHTML = `
            <div class="ls-empty">
                <div class="ls-empty-icon">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                </div>
                <p class="ls-empty-title">Tidak ada data</p>
                <p class="ls-empty-sub">Coba ubah filter untuk menampilkan hasil.</p>
            </div>`;
        return;
    }

    // Group by tanggal saja — jam sekarang menyatu di dalam tiap card
    const byDate = {};
    filtered.forEach(m => {
        if (!byDate[m.tanggal]) byDate[m.tanggal] = [];
        byDate[m.tanggal].push(m);
    });

    let html = '';
    Object.keys(byDate).sort().forEach(date => {
        const matches = byDate[date].slice().sort((a, b) => (a.jam || '').localeCompare(b.jam || ''));
        html += `<div class="ls-date-section">
            <div class="ls-date-label">
                <div class="ls-date-label-text">
                    <span class="ls-date-fire">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                    </span>
                    ${fmtLong(date)}
                </div>
                <div class="ls-date-line"></div>
                <span class="ls-date-count">${matches.length} pertandingan</span>
            </div>
            <div class="ls-card-grid">
                ${matches.map(m => buildCard(m)).join('')}
            </div>
        </div>`;
    });

    content.innerHTML = html;
}

/* ── BUILD CARD ───────────────────────────── */
function buildCard(m) {
    const isSelesai = (m.status_label||'').toUpperCase() === 'SELESAI';

    const kat = (m.kategori||'').toLowerCase();
    let kc = 'kdef';
    if      (kat.includes('ganda putra') && !kat.includes('veteran')) kc='kgp';
    else if (kat.includes('ganda putri') && !kat.includes('veteran')) kc='kgpi';
    else if (kat.includes('veteran putra')) kc='kvp';
    else if (kat.includes('veteran putri')) kc='kvpi';
    else if (kat.includes('beregu'))        kc='kber';

    const partai  = m.partai || '—';
    const vsSplit = partai.split(' vs ');
    const p1Raw   = vsSplit[0] || '—';
    const p2Raw   = vsSplit[1] || '—';

    const formatPlayer = (txt) => txt.replace(/\s*\(([^)]+)\)/g,
        '<span class="ls-player-pb">($1)</span>');

    const skor    = (m.skor || '').toString().trim();
    const winner  = m.winner || '';
    const hasSkor = skor && skor !== '-';
    const skorDisplay = hasSkor ? skor.replace(/\s*-\s*/g, ' - ') : '';

    const scoreBadgeHtml = hasSkor
        ? `<div class="ls-score-badge">
                <div class="ls-score-num">${skorDisplay}</div>
                <div class="ls-score-vs-label">vs</div>
        </div>`
        : `<div class="ls-score-badge pending">VS</div>`;

    const courtLabel = m.venue ? `${m.venue} · Lap ${m.court}` : `Lap ${m.court}`;
    const jamText     = (m.jam || '').substring(0,5);

    return `
    <div class="ls-card${isSelesai ? ' selesai' : ''}">
        <div class="ls-card-time">
            <span class="ls-card-time-dot"></span>
            <span class="ls-card-time-text">${jamText}</span>
            <span class="ls-card-time-wita">WITA</span>
        </div>
        <div class="ls-card-head">
            <span class="ls-kat-badge ${kc}">${m.kategori || '—'}</span>
            <div class="ls-head-right">
                <div class="ls-court-pill">
                    <span class="ls-court-dot"></span>
                    ${courtLabel}
                </div>
                <span class="ls-babak-tag">${m.babak || '—'}</span>
            </div>
        </div>

        <div class="ls-card-body">
            <div class="ls-match-row">
                <div class="ls-team ls-team-left">
                    <div class="ls-player-name">${formatPlayer(p1Raw)}</div>
                </div>
            ${scoreBadgeHtml}
                <div class="ls-team ls-team-right">
                    <div class="ls-player-name">${formatPlayer(p2Raw)}</div>
                </div>
            </div>
        </div>

        <div class="ls-card-foot">
            ${isSelesai
                ? `<div class="ls-winner-banner">
                        <span class="ls-trophy">🏆</span>
                        <div>
                            <div class="ls-winner-label">Pemenang</div>
                            <div class="ls-winner-name">${winner || '—'}</div>
                        </div>
                   </div>`
                : `<div class="ls-waiting">
                        <span class="ls-wait-dot"></span>
                        Menunggu / Berlangsung
                   </div>`
            }
        </div>
    </div>`;
}

/* ── BOOT ─────────────────────────────────── */
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
else init();
</script>
@endpush