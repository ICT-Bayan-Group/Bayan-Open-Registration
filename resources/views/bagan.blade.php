<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bagan Pertandingan – Bayan Open 2026</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
/* ═══════════════════════════════════════════
   TOKENS
═══════════════════════════════════════════ */
:root {
  --fire      : #f97316;
  --fire-deep : #c2410c;
  --fire-light: #fff7ed;
  --fire-mid  : #fed7aa;
  --gold      : #fbbf24;
  --bg        : #f8f4f0;
  --primary   : #1e3a8a;
  --line      : rgba(149,170,200,0.9);
  --line-hi   : rgba(249,115,22,0.5);
  --text-dark : #1c0a00;
  --text-mid  : #6b3a1f;
  --text-light: #b97a52;
  --shadow-sm : 0 1px 4px rgba(0,0,0,0.07);
  --shadow-md : 0 4px 16px rgba(249,115,22,0.15);
  --shadow-lg : 0 8px 32px rgba(249,115,22,0.2);
  --font-hdr  : 'Syne', sans-serif;
  --font-body : 'DM Sans', sans-serif;

  /* ── WIN / LOSE COLORS ── */
  --win-bg    : #f0fdf4;   /* hijau muda */
  --win-text  : #15803d;   /* hijau tua */
  --win-score : #16a34a;   /* hijau skor */
  --win-club  : rgba(21,128,61,0.55);
  --win-bar   : #22c55e;   /* garis atas card completed */

  --lose-bg   : #f3f4f6;   /* abu muda */
  --lose-text : #9ca3af;   /* abu tua */
  --lose-score: #9ca3af;
  --lose-club : #c4c8ce;

  --card-w : 165px;
  --conn   :  60px;
  --col-w  : calc(var(--card-w) + var(--conn));
  --hc     : calc(var(--conn) / 2);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; overflow: hidden; }
body {
  font-family: var(--font-body);
  background: var(--bg);
  color: var(--text-dark);
  display: flex;
  flex-direction: column;
}

/* ═══════════════════════════════════════════
   HEADER
═══════════════════════════════════════════ */
.hdr {
  flex-shrink: 0;
  background: #fff;
  border-bottom: 2.5px solid var(--fire);
  box-shadow: 0 2px 14px rgba(249,115,22,0.12);
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 12px 20px 0;
  gap: 6px;
  z-index: 200;
}
.hdr-top {
  display: flex;
  align-items: center;
  gap: 14px;
  flex-wrap: wrap;
  justify-content: center;
}
.hdr-logo {
  height: 44px;
  width: auto;
  object-fit: contain;
}
.hdr-title-block { display: flex; flex-direction: column; gap: 3px; }
.hdr-title {
  font-family: var(--font-hdr);
  font-size: 15px;
  font-weight: 800;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--text-dark);
  line-height: 1.1;
}
.hdr-subtitle {
  font-size: 11px;
  color: var(--text-mid);
  letter-spacing: .04em;
}
.hdr-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 3px 12px;
  border-radius: 99px;
  border: 1.5px solid var(--fire);
  background: var(--fire-light);
  font-family: var(--font-hdr);
  font-size: 9px;
  font-weight: 700;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: var(--fire-deep);
}
.hdr-badge-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--fire);
  animation: blink 2s ease infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.25} }

.cat-nav {
  display: flex;
  gap: 2px;
  overflow-x: auto;
  scrollbar-width: none;
  width: 100%;
  padding: 0 20px;
}
.cat-nav::-webkit-scrollbar { display: none; }
.cat-btn {
  flex-shrink: 0;
  padding: 10px 20px;
  font-family: var(--font-hdr);
  font-size: 10px;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--text-light);
  background: transparent;
  border: none;
  border-bottom: 3px solid transparent;
  cursor: pointer;
  transition: all .2s;
  white-space: nowrap;
}
.cat-btn:hover  { color: var(--fire); }
.cat-btn.active { color: var(--fire-deep); border-bottom-color: var(--fire); }

/* ═══════════════════════════════════════════
   2-D SCROLL VIEWPORT
═══════════════════════════════════════════ */
#scroll-viewport {
  flex: 1;
  overflow: scroll;
  scrollbar-width: thin;
  scrollbar-color: rgba(249,115,22,.4) rgba(249,115,22,.06);
}
#scroll-viewport::-webkit-scrollbar        { width: 7px; height: 7px; }
#scroll-viewport::-webkit-scrollbar-track  { background: rgba(249,115,22,.04); }
#scroll-viewport::-webkit-scrollbar-thumb  { background: rgba(249,115,22,.35); border-radius: 4px; }
#scroll-viewport::-webkit-scrollbar-corner { background: rgba(249,115,22,.04); }

#bracket-canvas {
  display: inline-flex;
  justify-content: center;
  align-items: stretch;
  padding: 36px 48px 72px;
  gap: 0;
  min-width: max-content;
  min-height: max-content;
}

.side {
  display: flex;
  align-items: stretch;
  gap: 0;
}
.side.left  { flex-direction: row; }
.side.right { flex-direction: row-reverse; }

/* ═══════════════════════════════════════════
   ROUND COLUMN
═══════════════════════════════════════════ */
.round {
  display: flex;
  flex-direction: column;
  width: var(--col-w);
  flex-shrink: 0;
}
.round-title {
  text-align: center;
  font-family: var(--font-hdr);
  font-size: 8.5px;
  font-weight: 800;
  color: var(--fire-deep);
  background: var(--fire-light);
  border: 1.5px solid var(--fire);
  border-radius: 4px;
  padding: 5px 8px;
  margin: 0 var(--hc) 14px;
  text-transform: uppercase;
  letter-spacing: .16em;
}

.match-pair {
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  flex: 1;
  position: relative;
  min-height: 140px;
}
.single-match {
  display: flex;
  flex-direction: column;
  justify-content: center;
  flex: 1;
  position: relative;
  min-height: 80px;
}

/* ── LEFT side connectors ── */
.side.left .match-pair::after {
  content: "";
  position: absolute;
  right: 0;
  top: 25%; bottom: 25%;
  width: var(--hc);
  border-right:  2px solid var(--line);
  border-top:    2px solid var(--line);
  border-bottom: 2px solid var(--line);
  border-radius: 0 5px 5px 0;
  z-index: 1;
  pointer-events: none;
}
.side.left .match-pair::before {
  content: "";
  position: absolute;
  right: calc(-1 * var(--hc));
  top: 50%;
  margin-top: -1px;
  width: var(--hc);
  height: 2px;
  background: var(--line);
  z-index: 1;
  pointer-events: none;
}
.side.left .single-match::after {
  content: "";
  position: absolute;
  right: calc(-1 * var(--conn));
  top: 50%;
  margin-top: -1px;
  width: var(--conn);
  height: 2px;
  background: var(--line);
  z-index: 1;
  pointer-events: none;
}

/* ── RIGHT side connectors ── */
.side.right .match-pair::after {
  content: "";
  position: absolute;
  left: 0;
  top: 25%; bottom: 25%;
  width: var(--hc);
  border-left:   2px solid var(--line);
  border-top:    2px solid var(--line);
  border-bottom: 2px solid var(--line);
  border-radius: 5px 0 0 5px;
  z-index: 1;
  pointer-events: none;
}
.side.right .match-pair::before {
  content: "";
  position: absolute;
  left: calc(-1 * var(--hc));
  top: 50%;
  margin-top: -1px;
  width: var(--hc);
  height: 2px;
  background: var(--line);
  z-index: 1;
  pointer-events: none;
}
.side.right .single-match::after {
  content: "";
  position: absolute;
  left: calc(-1 * var(--conn));
  top: 50%;
  margin-top: -1px;
  width: var(--conn);
  height: 2px;
  background: var(--line);
  z-index: 1;
  pointer-events: none;
}

/* ═══════════════════════════════════════════
   MATCH CARD
═══════════════════════════════════════════ */
.match-card {
  background: #fff;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  font-size: 11px;
  position: relative;
  width: var(--card-w);
  margin: 5px var(--hc);
  z-index: 2;
  box-shadow: var(--shadow-sm);
  overflow: visible;
  transition: border-color .18s, box-shadow .18s;
}
.match-card:hover {
  border-color: var(--fire);
  box-shadow: var(--shadow-md);
}
.match-card.completed {
  background: #fff;
  border-color: rgba(34,197,94,.4);
}
/* top bar hijau untuk completed */
.match-card.completed::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: var(--win-bar);
  border-radius: 6px 6px 0 0;
}

/* ── MATCH ID PILL ── */
.match-id {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: #111;
  color: #fff;
  font-family: var(--font-hdr);
  font-size: 8px;
  font-weight: 700;
  padding: 2px 6px;
  border-radius: 3px;
  z-index: 20;
  pointer-events: none;
  white-space: nowrap;
  box-shadow: 0 1px 4px rgba(0,0,0,.3);
}
.side.left  .match-id { right: calc(-1 * var(--hc) - 14px); }
.side.right .match-id { left:  calc(-1 * var(--hc) - 14px); }

/* ── PLAYER ROWS ── */
.player {
  padding: 7px 10px;
  font-size: 10.5px;
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  border-bottom: 1px solid #f1f5f9;
  color: #1e293b;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 4px;
  min-height: 38px;
  transition: background .15s;
}
.player:last-child  { border-bottom: none; }
.player.bye         { color: #94a3b8; font-style: italic; font-weight: 400; font-size: 10px; }
.player.tbd         { color: #94a3b8; font-weight: 400; font-size: 10px; }

/* ── PEMENANG: HIJAU ── */
.player.winner {
  color: var(--win-text);
  font-weight: 700;
  background: var(--win-bg);
}

/* ── KALAH: ABU-ABU ── */
.player.loser {
  color: var(--lose-text);
  font-weight: 400;
  background: var(--lose-bg);
  text-decoration: none;
  opacity: 0.75;
}

.p-info  { flex: 1; display: flex; flex-direction: column; gap: 1px; overflow: hidden; min-width: 0; }
.p-name  { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 10.5px; font-weight: 600; line-height: 1.3; }
.p-club  { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 8.5px; font-weight: 400; color: #94a3b8; line-height: 1.2; }

.player.winner .p-name { font-weight: 700; }
.player.winner .p-club { color: var(--win-club); }

.player.loser .p-name  { font-weight: 400; }
.player.loser .p-club  { color: var(--lose-club); }

.player.bye .p-club, .player.tbd .p-club { display: none; }

.p-score { font-family: var(--font-hdr); font-size: 11px; font-weight: 800; color: rgba(0,0,0,.2); flex-shrink: 0; min-width: 14px; text-align: right; align-self: center; }
.p-score.win  { color: var(--win-score); }
.p-score.lose { color: var(--lose-score); }

/* ═══════════════════════════════════════════
   FINAL CENTER AREA
═══════════════════════════════════════════ */
.final-area {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 230px;
  flex-shrink: 0;
  padding: 0 16px;
  position: relative;
  z-index: 5;
}
.trophy-icon {
  font-size: 38px;
  color: var(--gold);
  margin-bottom: 8px;
  filter: drop-shadow(0 0 10px rgba(251,191,36,.5));
}
.final-round-lbl {
  font-family: var(--font-hdr);
  font-size: 8px;
  font-weight: 800;
  letter-spacing: .22em;
  text-transform: uppercase;
  color: var(--fire-deep);
  margin-bottom: 12px;
}
.final-box {
  border: 2px solid var(--fire);
  background: #fff;
  padding: 20px 18px 18px;
  border-radius: 12px;
  text-align: center;
  width: 100%;
  position: relative;
  box-shadow: var(--shadow-lg);
}
.final-box::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, var(--gold), var(--fire), var(--gold));
  border-radius: 12px 12px 0 0;
}
.final-id {
  position: absolute;
  top: -11px; left: 50%;
  transform: translateX(-50%);
  background: var(--fire);
  color: #fff;
  font-family: var(--font-hdr);
  font-size: 8px;
  font-weight: 700;
  padding: 2px 10px;
  border-radius: 4px;
  letter-spacing: .1em;
  white-space: nowrap;
  box-shadow: 0 2px 8px rgba(249,115,22,.4);
}
.final-player {
  font-family: var(--font-hdr);
  font-size: 13px;
  font-weight: 700;
  color: var(--text-dark);
  padding: 6px 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.final-vs {
  font-size: 10px;
  color: #94a3b8;
  padding: 5px 0;
  font-weight: 600;
  letter-spacing: .1em;
}
.final-left-conn  { position: absolute; left:  calc(-1 * var(--hc)); top: 50%; margin-top: -1px; width: var(--hc); height: 2px; background: var(--line); pointer-events: none; }
.final-right-conn { position: absolute; right: calc(-1 * var(--hc)); top: 50%; margin-top: -1px; width: var(--hc); height: 2px; background: var(--line); pointer-events: none; }

/* ═══════════════════════════════════════════
   LOADING / EMPTY STATES
═══════════════════════════════════════════ */
.state-box {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  min-height: 60vh; gap: 16px; color: var(--text-light); padding: 40px;
}
.spinner {
  width: 32px; height: 32px;
  border: 2px solid rgba(249,115,22,.15);
  border-top-color: var(--fire);
  border-radius: 50%;
  animation: spin .8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.state-lbl { font-family: var(--font-hdr); font-size: 11px; letter-spacing: .12em; text-transform: uppercase; }

/* ═══════════════════════════════════════════
   FOOTER LEGEND
═══════════════════════════════════════════ */
.legend {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
  padding: 9px 24px;
  border-top: 1.5px solid var(--fire-mid);
  background: rgba(255,255,255,.97);
  z-index: 198;
  box-shadow: 0 -2px 10px rgba(249,115,22,.07);
}
.leg-item { display: flex; align-items: center; gap: 6px; font-size: 10px; color: var(--text-mid); }
.leg-dot  { width: 8px; height: 8px; border-radius: 2px; flex-shrink: 0; }

/* ═══════════════════════════════════════════
   SCROLL HINT TOAST
═══════════════════════════════════════════ */
.scroll-hint {
  position: fixed;
  bottom: 54px; right: 18px;
  z-index: 300;
  background: var(--fire);
  color: #fff;
  font-family: var(--font-hdr);
  font-size: 9px; font-weight: 700;
  letter-spacing: .08em;
  padding: 7px 14px;
  border-radius: 20px;
  box-shadow: 0 3px 12px rgba(249,115,22,.4);
  pointer-events: none;
  display: flex; align-items: center; gap: 6px;
  transition: opacity .5s;
}
.scroll-hint.gone { opacity: 0; }

@media (max-width: 600px) {
  :root { --card-w: 140px; --conn: 44px; }
  .hdr-title  { font-size: 12px; }
  .hdr-logo   { height: 34px; }
}

/* ═══════════════════════════════════════════
   BACK BUTTON
═══════════════════════════════════════════ */
.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 14px 5px 10px;
  background: var(--fire-light);
  border: 1.5px solid var(--fire);
  border-radius: 99px;
  color: var(--fire-deep);
  font-family: var(--font-hdr);
  font-size: 10px;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  text-decoration: none;
  cursor: pointer;
  transition: background .18s, box-shadow .18s, transform .1s;
  flex-shrink: 0;
}
.btn-back:hover {
  background: var(--fire-mid);
  box-shadow: 0 2px 10px rgba(249,115,22,.25);
  transform: translateX(-2px);
}
.btn-back:active { transform: translateX(0); }
.btn-back svg { flex-shrink: 0; }

/* ═══════════════════════════════════════════
   ZOOM WRAPPER
═══════════════════════════════════════════ */
#zoom-wrapper {
  transform-origin: top left;
  display: inline-flex;
  transition: transform .12s ease;
}
#bracket-canvas, #bracket-canvas * {
  pointer-events: none !important;
  cursor: default !important;
  user-select: none !important;
  -webkit-user-select: none !important;
}

/* ═══════════════════════════════════════════
   ZOOM CONTROLS
═══════════════════════════════════════════ */
.zoom-controls {
  position: fixed;
  bottom: 52px;
  left: 16px;
  z-index: 400;
  display: flex;
  align-items: center;
  gap: 2px;
  background: #fff;
  border: 1.5px solid var(--fire-mid);
  border-radius: 24px;
  padding: 4px 6px;
  box-shadow: 0 3px 14px rgba(249,115,22,0.18);
  user-select: none;
}
.zoom-btn {
  width: 30px; height: 30px;
  border: none;
  background: transparent;
  border-radius: 50%;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: var(--fire-deep);
  font-size: 18px;
  font-weight: 700;
  transition: background .15s;
  font-family: var(--font-hdr);
  line-height: 1;
  flex-shrink: 0;
}
.zoom-btn:hover  { background: var(--fire-light); }
.zoom-btn:active { background: var(--fire-mid); }
.zoom-btn:disabled { color: #ccc; cursor: default; }
.zoom-btn:disabled:hover { background: transparent; }
.zoom-sep {
  width: 1px; height: 18px;
  background: var(--fire-mid);
  flex-shrink: 0;
  margin: 0 2px;
}
.zoom-pct {
  font-family: var(--font-hdr);
  font-size: 10px; font-weight: 700;
  color: var(--fire-deep);
  min-width: 38px; text-align: center;
  letter-spacing: .06em;
  cursor: pointer;
  padding: 3px 4px;
  border-radius: 4px;
  transition: background .15s;
}
.zoom-pct:hover { background: var(--fire-light); }
@media (max-width: 600px) {
  .zoom-controls { bottom: 52px; left: 10px; }
}
</style>
</head>
<body>

<!-- ══════════ HEADER ══════════ -->
<header class="hdr">
  <div class="hdr-top">
    <a href="/" class="btn-back" title="Kembali ke Beranda">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 12H5M12 5l-7 7 7 7"/>
      </svg>
      Kembali
    </a>
    <img class="hdr-logo"
      src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1773109896/LOGO_BO2026_pzbvxh.png"
      alt="Bayan Open 2026"
      onerror="this.style.display='none'">
    <div class="hdr-title-block">
      <div class="hdr-title">Bagan Pertandingan</div>
      <div class="hdr-subtitle">Bayan Open 2026 — Balikpapan</div>
    </div>
    <div class="hdr-badge"><span class="hdr-badge-dot"></span>Live</div>
  </div>
  <nav class="cat-nav" id="catNav"></nav>
</header>

<!-- ══════════ 2-D SCROLL VIEWPORT ══════════ -->
<div id="scroll-viewport">
  <div id="zoom-wrapper">
    <div id="bracket-canvas">
      <div class="state-box" id="stateBox">
        <div class="spinner"></div>
        <div class="state-lbl">Memuat bagan…</div>
      </div>
    </div>
  </div>
</div>

<!-- ══════════ ZOOM CONTROLS ══════════ -->
<div class="zoom-controls" id="zoomControls">
  <button class="zoom-btn" id="zoomOut" title="Zoom out">−</button>
  <div class="zoom-sep"></div>
  <span class="zoom-pct" id="zoomPct" title="Klik untuk reset">100%</span>
  <div class="zoom-sep"></div>
  <button class="zoom-btn" id="zoomIn" title="Zoom in">+</button>
</div>

<!-- ══════════ FOOTER ══════════ -->
<footer class="legend">
  <div class="leg-item"><div class="leg-dot" style="background:#22c55e"></div>Menang</div>
  <div class="leg-item"><div class="leg-dot" style="background:#d1d5db;border:1.5px solid #9ca3af"></div>Kalah</div>
  <div class="leg-item"><div class="leg-dot" style="background:#fff;border:1.5px solid #cbd5e1"></div>Menunggu</div>
  <div class="leg-item" style="margin-left:auto;font-size:9px;color:var(--text-light)">
    <i class="fas fa-sitemap" style="margin-right:4px;color:var(--fire)"></i>result.bayanopen.com
  </div>
</footer>

<!-- ══════════ SCROLL HINT ══════════ -->
<div class="scroll-hint" id="sh">
  <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
    <path d="M5 9l7-7 7 7M5 15l7 7 7-7"/>
  </svg>
  Geser &amp; Ctrl+scroll untuk zoom
</div>

<script>
/* ═══════════════════════════════════════════
   CONFIG
═══════════════════════════════════════════ */
const API = 'https://result.bayanopen.com/get-matches';
const ROUND_ORDER = ['R256','R128','R64','R32','R16','R8','PEREMPAT FINAL','SEMI FINAL','FINAL'];

let allMatches = [];

/* ═══════════════════════
   ZOOM
═══════════════════════ */
const ZOOM_MIN  = 0.25;
const ZOOM_MAX  = 2.0;
const ZOOM_STEP = 0.15;
let   currentZoom = 1.0;

function applyZoom(z, pivotX, pivotY) {
  const vp      = document.getElementById('scroll-viewport');
  const wrapper = document.getElementById('zoom-wrapper');
  const clamped = Math.min(ZOOM_MAX, Math.max(ZOOM_MIN, z));
  const ratio   = clamped / currentZoom;
  const px = (pivotX !== undefined) ? pivotX : vp.scrollLeft + vp.clientWidth  / 2;
  const py = (pivotY !== undefined) ? pivotY : vp.scrollTop  + vp.clientHeight / 2;
  currentZoom = clamped;
  wrapper.style.transform = `scale(${clamped})`;
  vp.scrollLeft = px * ratio - (px - vp.scrollLeft);
  vp.scrollTop  = py * ratio - (py - vp.scrollTop);
  document.getElementById('zoomPct').textContent = Math.round(clamped * 100) + '%';
  document.getElementById('zoomOut').disabled = clamped <= ZOOM_MIN;
  document.getElementById('zoomIn').disabled  = clamped >= ZOOM_MAX;
}

function initZoom() {
  const vp = document.getElementById('scroll-viewport');
  document.getElementById('zoomIn').onclick   = () => applyZoom(currentZoom + ZOOM_STEP);
  document.getElementById('zoomOut').onclick  = () => applyZoom(currentZoom - ZOOM_STEP);
  document.getElementById('zoomPct').onclick  = () => applyZoom(1.0);

  vp.addEventListener('wheel', e => {
    if(!e.ctrlKey && !e.metaKey) return;
    e.preventDefault();
    const rect   = vp.getBoundingClientRect();
    const pivotX = e.clientX - rect.left + vp.scrollLeft;
    const pivotY = e.clientY - rect.top  + vp.scrollTop;
    applyZoom(currentZoom + (e.deltaY > 0 ? -ZOOM_STEP : ZOOM_STEP), pivotX, pivotY);
  }, { passive: false });

  let lastDist = null, ppx = 0, ppy = 0;
  vp.addEventListener('touchstart', e => {
    if(e.touches.length === 2) {
      lastDist = Math.hypot(e.touches[0].clientX - e.touches[1].clientX,
                            e.touches[0].clientY - e.touches[1].clientY);
      const rect = vp.getBoundingClientRect();
      ppx = (e.touches[0].clientX + e.touches[1].clientX) / 2 - rect.left + vp.scrollLeft;
      ppy = (e.touches[0].clientY + e.touches[1].clientY) / 2 - rect.top  + vp.scrollTop;
    }
  }, { passive: true });
  vp.addEventListener('touchmove', e => {
    if(e.touches.length !== 2 || lastDist === null) return;
    e.preventDefault();
    const d = Math.hypot(e.touches[0].clientX - e.touches[1].clientX,
                         e.touches[0].clientY - e.touches[1].clientY);
    applyZoom(currentZoom * (d / lastDist), ppx, ppy);
    lastDist = d;
  }, { passive: false });
  vp.addEventListener('touchend', () => { lastDist = null; }, { passive: true });

  document.addEventListener('keydown', e => {
    if(e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
    if(e.key === '+' || e.key === '=') { e.preventDefault(); applyZoom(currentZoom + ZOOM_STEP); }
    if(e.key === '-')                  { e.preventDefault(); applyZoom(currentZoom - ZOOM_STEP); }
    if(e.key === '0')                  { e.preventDefault(); applyZoom(1.0); }
  });
}

/* ═══════════════════════════════════════════
   FETCH
═══════════════════════════════════════════ */
async function loadData() {
  try {
    const res = await fetch(API);
    allMatches = await res.json();
    boot();
  } catch(e) {
    document.getElementById('stateBox').innerHTML =
      '<div class="state-lbl" style="color:var(--fire-deep)">Gagal memuat data</div>' +
      '<div style="font-size:11px;color:var(--text-light);margin-top:6px">Periksa koneksi atau URL API</div>' +
      '<button onclick="loadData()" style="margin-top:14px;padding:9px 22px;background:var(--fire);border:none;border-radius:6px;color:#fff;font-family:\'Syne\',sans-serif;font-size:10px;letter-spacing:.1em;text-transform:uppercase;cursor:pointer">Coba Lagi</button>';
  }
}

/* ═══════════════════════════════════════════
   BOOT
═══════════════════════════════════════════ */
function boot() {
  const cats = [...new Set(allMatches.map(m => m.kategori).filter(Boolean))];
  const nav  = document.getElementById('catNav');
  nav.innerHTML = '';

  cats.forEach((cat, i) => {
    const btn = document.createElement('button');
    btn.className  = 'cat-btn' + (i === 0 ? ' active' : '');
    btn.textContent = cat;
    btn.onclick = () => {
      nav.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      render(cat);
    };
    nav.appendChild(btn);
  });

  if(cats.length) render(cats[0]);

  initZoom();

  const vp = document.getElementById('scroll-viewport');
  const sh = document.getElementById('sh');
  vp.addEventListener('scroll', () => sh.classList.add('gone'), { once: true });
  setTimeout(() => sh.classList.add('gone'), 5000);
}

/* ═══════════════════════════════════════════
   RENDER BRACKET
═══════════════════════════════════════════ */
function render(cat) {
  const canvas = document.getElementById('bracket-canvas');
  canvas.innerHTML = '';

  const data = allMatches.filter(m => m.kategori === cat);
  if(!data.length) {
    canvas.innerHTML = '<div class="state-box"><div class="state-lbl">Tidak ada data</div></div>';
    return;
  }

  const leftData   = data.filter(m => m.side === 'LEFT');
  const rightData  = data.filter(m => m.side === 'RIGHT');
  const finalMatch = data.find(m => m.babak === 'FINAL' || m.side === 'FINAL-CENTER');

  const leftSide = document.createElement('div');
  leftSide.className = 'side left';

  const leftRounds = getRoundsPresent(leftData);
  leftRounds.forEach(rnd => {
    const matches = leftData.filter(m => m.babak === rnd);
    leftSide.appendChild(createRound(matches, rnd, 'left'));
  });

  const finalEl = finalMatch ? buildFinalArea(finalMatch) : buildEmptyFinal();

  const rightSide = document.createElement('div');
  rightSide.className = 'side right';

  const rightRounds = getRoundsPresent(rightData);
  rightRounds.forEach(rnd => {
    const matches = rightData.filter(m => m.babak === rnd);
    rightSide.appendChild(createRound(matches, rnd, 'right'));
  });

  canvas.appendChild(leftSide);
  canvas.appendChild(finalEl);
  canvas.appendChild(rightSide);
}

function getRoundsPresent(matches) {
  const seen = new Set(matches.map(m => m.babak));
  return ROUND_ORDER.filter(r => seen.has(r) && r !== 'FINAL');
}

/* ═══════════════════════════════════════════
   CREATE ROUND COLUMN
═══════════════════════════════════════════ */
function createRound(matches, name, side) {
  const col = document.createElement('div');
  col.className = 'round';

  const title = document.createElement('div');
  title.className  = 'round-title';
  title.textContent = name;
  col.appendChild(title);

  if(matches.length === 1) {
    const wrapper = document.createElement('div');
    wrapper.className = 'single-match';
    wrapper.appendChild(buildMatchCard(matches[0], side));
    col.appendChild(wrapper);
  } else {
    for(let i = 0; i < matches.length; i += 2) {
      const wrapper = document.createElement('div');
      wrapper.className = 'match-pair';
      wrapper.appendChild(buildMatchCard(matches[i], side));
      if(matches[i+1]) wrapper.appendChild(buildMatchCard(matches[i+1], side));
      col.appendChild(wrapper);
    }
  }
  return col;
}

/* ═══════════════════════════════════════════
   BUILD MATCH CARD
═══════════════════════════════════════════ */
function buildMatchCard(m, side) {
  const card = document.createElement('div');
  card.className = 'match-card' + (m.status === 'COMPLETED' ? ' completed' : '');

  const pill = document.createElement('div');
  pill.className  = 'match-id';
  pill.textContent = m.id;
  card.appendChild(pill);

  card.appendChild(buildPlayer(m, 1));
  card.appendChild(buildPlayer(m, 2));

  return card;
}

function parseName(raw) {
  if(!raw) return { name: '—', club: '' };
  const m = raw.match(/^(.+?)\s*\((.+?)\)\s*$/);
  if(m) return { name: m[1].trim(), club: m[2].trim() };
  return { name: raw.trim(), club: '' };
}

function buildPlayer(m, slot) {
  const raw   = slot === 1 ? m.p1   : m.p2;
  const score = slot === 1 ? m.skor_p1 : m.skor_p2;
  const other = slot === 1 ? m.skor_p2 : m.skor_p1;
  const done  = m.status === 'COMPLETED';
  const win   = done && Number(score) > Number(other);
  const lose  = done && !win && raw && raw !== 'BYE' && raw !== 'TBD';
  const bye   = raw === 'BYE';
  const tbd   = !raw || raw === 'TBD';

  const { name, club } = tbd ? { name: 'Menunggu', club: '' }
                       : bye ? { name: 'BYE', club: '' }
                       : parseName(raw);

  const div = document.createElement('div');
  div.className = 'player' +
    (bye  ? ' bye'    : '') +
    (tbd  ? ' tbd'    : '') +
    (win  ? ' winner' : '') +
    (lose ? ' loser'  : '');

  const info = document.createElement('div');
  info.className = 'p-info';

  const nm = document.createElement('span');
  nm.className   = 'p-name';
  nm.textContent = name;
  info.appendChild(nm);

  if(club) {
    const cl = document.createElement('span');
    cl.className   = 'p-club';
    cl.textContent = club;
    info.appendChild(cl);
  }

  div.appendChild(info);

  if(!bye && !tbd && done) {
    const sc = document.createElement('span');
    sc.className   = 'p-score' + (win ? ' win' : lose ? ' lose' : '');
    sc.textContent = score ?? '';
    div.appendChild(sc);
  }
  return div;
}

/* ═══════════════════════════════════════════
   BUILD FINAL AREA
═══════════════════════════════════════════ */
function buildFinalArea(m) {
  const area = document.createElement('div');
  area.className = 'final-area';

  area.innerHTML = `
    <div class="trophy-icon"><i class="fas fa-trophy"></i></div>
    <div class="final-round-lbl">Grand Final</div>
    <div class="final-box">
      <div class="final-id">Match ${m.id}</div>
      <div class="final-player">${m.p1 && m.p1 !== 'TBD' ? parseName(m.p1).name : 'Menunggu'}</div>
      <div class="final-club" style="font-size:9px;color:#94a3b8;margin:-4px 0 2px;text-align:center;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${m.p1 && m.p1 !== 'TBD' ? parseName(m.p1).club : ''}</div>
      <div class="final-vs">VS</div>
      <div class="final-player">${m.p2 && m.p2 !== 'TBD' ? parseName(m.p2).name : 'Menunggu'}</div>
      <div class="final-club" style="font-size:9px;color:#94a3b8;margin-top:-4px;text-align:center;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${m.p2 && m.p2 !== 'TBD' ? parseName(m.p2).club : ''}</div>
      <div class="final-left-conn"></div>
      <div class="final-right-conn"></div>
    </div>
  `;
  return area;
}

function buildEmptyFinal() {
  const d = document.createElement('div');
  d.className = 'final-area';
  d.style.width = '40px';
  return d;
}

/* ─── START ─── */
loadData();
</script>
</body>
</html>