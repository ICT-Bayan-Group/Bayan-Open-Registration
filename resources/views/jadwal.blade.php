@extends('layouts.app')

@section('title', 'Jadwal Pertandingan')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════
   JADWAL PAGE — BAYAN OPEN 2026
   Modern redesign: video hero + date tabs + card grid
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
    --r-xs:  8px;
    --r-sm:  12px;
    --r-md:  18px;
    --r-lg:  24px;
    --r-xl:  32px;
    --font-display: 'Syne', 'Unbounded', sans-serif;
    --font-body:    'DM Sans', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.jp { background: var(--paper); min-height: 100svh; font-family: var(--font-body); color: var(--ink); }

/* ════════════════════════════════════════
   VIDEO HERO
════════════════════════════════════════ */
.jp-hero {
    position: relative;
    height: clamp(360px, 52vw, 520px);
    overflow: hidden;
    display: flex; align-items: flex-end;
}
.jp-hero-video {
    position: absolute; inset: 0; z-index: 0;
    width: 100%; height: 100%; object-fit: cover;
    pointer-events: none;
}
.jp-hero-overlay {
    position: absolute; inset: 0; z-index: 1;
    background:
        linear-gradient(to bottom,
            rgba(13,9,6,0.30) 0%,
            rgba(13,9,6,0.20) 35%,
            rgba(13,9,6,0.75) 75%,
            rgba(13,9,6,0.97) 100%),
        radial-gradient(ellipse 80% 60% at 40% 40%, rgba(249,115,22,0.08) 0%, transparent 60%);
}
.jp-hero-grain {
    position: absolute; inset: 0; z-index: 2; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.045'/%3E%3C/svg%3E");
}
.jp-hero-content {
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

.jp-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 5px 14px 5px 8px;
    border-radius: 99px;
    border: 1px solid rgba(249,115,22,0.3);
    background: rgba(249,115,22,0.09);
    backdrop-filter: blur(8px);
    margin-bottom: 14px;
}
.jp-eyebrow-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--fire);
    box-shadow: 0 0 10px var(--fire);
    animation: jpblink 2.4s ease infinite;
}
@keyframes jpblink { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.8)} }
.jp-eyebrow-text {
    font-family: var(--font-display);
    font-size: 9.5px; font-weight: 700;
    letter-spacing: .18em; text-transform: uppercase;
    color: var(--fire);
}

.jp-hero-title {
    font-family: var(--font-display);
    font-size: clamp(24px, 4.5vw, 44px); font-weight: 800;
    color: #fff; letter-spacing: -.03em; line-height: 1.08;
    margin-bottom: 8px;
}
.jp-hero-sub {
    font-size: 13.5px; color: var(--ash);
    line-height: 1.65; max-width: 420px;
}

.jp-stats {
    display: flex; gap: 8px; flex-shrink: 0; flex-wrap: wrap;
    align-self: flex-end;
}
.jp-stat {
    display: flex; flex-direction: column; align-items: center;
    padding: 12px 18px;
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: var(--r-md);
}
.jp-stat-val {
    font-family: var(--font-display);
    font-size: 22px; font-weight: 800;
    color: var(--fire); line-height: 1;
}
.jp-stat-lbl {
    font-size: 9px; color: var(--ash-2);
    text-transform: uppercase; letter-spacing: .08em;
    margin-top: 4px;
}

/* ════════════════════════════════════════
   DATE TABS STRIP
════════════════════════════════════════ */
.jp-date-strip-wrap {
    background: var(--night);
    border-bottom: 1px solid rgba(255,255,255,0.07);
    position: sticky;
    top: 64px; z-index: 40;
}
@media (min-width:640px)  { .jp-date-strip-wrap { top: 80px; } }
@media (min-width:1024px) { .jp-date-strip-wrap { top: 96px; } }

.jp-date-strip {
    max-width: 1120px; margin: 0 auto;
    padding: 0 24px;
    display: flex; align-items: stretch;
    overflow-x: auto; gap: 0;
    scrollbar-width: none;
}
.jp-date-strip::-webkit-scrollbar { display: none; }

.jp-date-tab {
    flex-shrink: 0;
    padding: 14px 18px;
    display: flex; flex-direction: column; align-items: center; gap: 2px;
    cursor: pointer;
    border: none; background: transparent;
    position: relative; transition: background .2s;
}
.jp-date-tab::after {
    content: '';
    position: absolute; bottom: 0; left: 50%; right: 50%;
    height: 2px; background: var(--fire);
    border-radius: 2px 2px 0 0;
    transition: left .25s ease, right .25s ease;
}
.jp-date-tab:hover { background: rgba(255,255,255,0.04); }
.jp-date-tab.active { background: rgba(249,115,22,0.07); }
.jp-date-tab.active::after { left: 8%; right: 8%; }

.jp-dtab-day {
    font-family: var(--font-display);
    font-size: 8.5px; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase;
    color: rgba(255,255,255,0.3); transition: color .2s;
}
.jp-dtab-num {
    font-family: var(--font-display);
    font-size: 19px; font-weight: 800;
    color: rgba(255,255,255,0.4); line-height: 1; transition: color .2s;
}
.jp-dtab-mon {
    font-size: 9.5px; color: rgba(255,255,255,0.28);
    font-weight: 500; transition: color .2s;
}
.jp-date-tab.active .jp-dtab-day { color: var(--fire); }
.jp-date-tab.active .jp-dtab-num { color: #fff; }
.jp-date-tab.active .jp-dtab-mon { color: rgba(255,255,255,0.5); }
.jp-date-tab:hover .jp-dtab-num  { color: rgba(255,255,255,0.8); }

.jp-tab-sep {
    width: 1px; background: rgba(255,255,255,0.06);
    align-self: stretch; margin: 8px 0; flex-shrink: 0;
}

/* ════════════════════════════════════════
   FILTER BAR
════════════════════════════════════════ */
.jp-filter-bar {
    background: var(--white);
    border-bottom: 1px solid var(--ink-12);
    position: sticky;
    top: calc(64px + 71px); z-index: 39;
    box-shadow: 0 2px 16px rgba(26,16,7,0.04);
}
@media (min-width:640px)  { .jp-filter-bar { top: calc(80px  + 71px); } }
@media (min-width:1024px) { .jp-filter-bar { top: calc(96px  + 71px); } }

.jp-filter-inner {
    max-width: 1120px; margin: 0 auto;
    padding: 11px 24px;
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap;
}

.jp-search-wrap { position: relative; flex: 1; min-width: 180px; }
.jp-search-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: var(--ink-25); pointer-events: none; display: flex; align-items: center;
}
.jp-search {
    width: 100%; padding: 9px 13px 9px 38px;
    border: 1.5px solid var(--ink-12);
    border-radius: var(--r-sm);
    font-family: var(--font-body); font-size: 13px; color: var(--ink);
    background: var(--paper); outline: none; transition: all .22s;
}
.jp-search:focus {
    border-color: var(--fire); background: #fff;
    box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
}
.jp-search::placeholder { color: var(--ink-25); }

.jp-kat-pills { display: flex; gap: 6px; flex-wrap: wrap; }
.jp-pill {
    padding: 6px 12px; border-radius: 99px;
    border: 1.5px solid var(--ink-12); background: var(--paper);
    font-family: var(--font-display); font-size: 9px; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    color: var(--ink-45); cursor: pointer; transition: all .2s;
}
.jp-pill:hover { border-color: rgba(249,115,22,.35); color: var(--fire); background: rgba(249,115,22,.05); }
.jp-pill.active {
    background: linear-gradient(135deg, var(--fire), var(--fire-deep));
    border-color: transparent; color: #fff;
    box-shadow: 0 4px 14px rgba(249,115,22,.3);
}

.jp-count-badge {
    margin-left: auto; flex-shrink: 0;
    display: flex; align-items: center; gap: 6px;
    font-family: var(--font-display); font-size: 9.5px; font-weight: 700;
    letter-spacing: .06em; color: var(--ink-45);
}
.jp-count-num {
    background: var(--night); color: #fff;
    padding: 2px 8px; border-radius: 6px; font-size: 12px;
}

/* ════════════════════════════════════════
   MAIN
════════════════════════════════════════ */
.jp-main { max-width: 1120px; margin: 0 auto; padding: 28px 24px 80px; }

.jp-date-label {
    display: flex; align-items: center; gap: 14px; margin-bottom: 22px;
}
.jp-date-label-text {
    font-family: var(--font-display);
    font-size: clamp(12px,2vw,14px); font-weight: 800;
    color: var(--ink); letter-spacing: .03em; text-transform: uppercase;
    white-space: nowrap; display: flex; align-items: center; gap: 8px;
}
.jp-date-label-fire { color: var(--fire); display:flex; align-items:center; }
.jp-date-label-line { flex:1; height:1px; background: linear-gradient(90deg, var(--ink-12), transparent); }
.jp-date-label-count { font-size:11px; color:var(--ink-25); font-weight:600; white-space:nowrap; }

.jp-time-slot { margin-bottom: 22px; }
.jp-time-pill {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 5px 14px 5px 10px;
    background: var(--night); border-radius: 99px;
    margin-bottom: 11px;
}
.jp-time-dot { width:6px; height:6px; border-radius:50%; background:var(--fire); box-shadow:0 0 8px var(--fire); }
.jp-time-text { font-family:var(--font-display); font-size:10px; font-weight:800; letter-spacing:.14em; color:rgba(255,255,255,.9); }

.jp-match-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
    gap: 12px;
}

/* ════════════════════════════════════════
   MATCH CARD
════════════════════════════════════════ */
.jp-card {
    background: var(--white);
    border: 1px solid var(--ink-12);
    border-radius: var(--r-lg);
    padding: 17px 18px 14px 21px;
    position: relative; overflow: hidden;
    transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s, border-color .3s;
}
.jp-card::before {
    content: '';
    position: absolute; left:0; top:14px; bottom:14px; width:3px;
    background: linear-gradient(to bottom, var(--fire), var(--fire-deep));
    border-radius: 0 3px 3px 0; opacity:.4; transition: opacity .25s;
}
.jp-card:hover { transform:translateY(-4px); box-shadow:0 16px 48px rgba(249,115,22,0.1), 0 2px 8px rgba(26,16,7,0.05); border-color:rgba(249,115,22,0.22); }
.jp-card:hover::before { opacity:1; }

.jp-card.conflict { background:#fffafa; border-color:rgba(239,68,68,0.18); }
.jp-card.conflict::before { background:linear-gradient(to bottom,#ef4444,#dc2626); opacity:.4; }
.jp-card.conflict:hover { border-color:rgba(239,68,68,0.4); }

.jp-card.is-tbd { opacity:.55; }
.jp-card.is-tbd:hover { opacity:1; }

.jp-card-top { display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:10px; }

.jp-kat-badge {
    padding:3px 9px; border-radius:99px;
    font-family:var(--font-display); font-size:8px; font-weight:700;
    letter-spacing:.1em; text-transform:uppercase;
}
.kgp  { background:rgba(59,130,246,.1);  color:#2563eb; border:1px solid rgba(59,130,246,.18); }
.kgpi { background:rgba(244,63,94,.1);   color:#e11d48; border:1px solid rgba(244,63,94,.18); }
.kvp  { background:rgba(249,115,22,.1);  color:var(--fire-deep); border:1px solid rgba(249,115,22,.18); }
.kvpi { background:rgba(168,85,247,.1);  color:#7c3aed; border:1px solid rgba(168,85,247,.18); }
.kber { background:rgba(20,184,166,.1);  color:#0d9488; border:1px solid rgba(20,184,166,.18); }
.kdef { background:var(--ink-06); color:var(--ink-45); border:1px solid var(--ink-12); }

.jp-match-id { font-family:var(--font-display); font-size:9px; font-weight:700; color:var(--ink-25); letter-spacing:.05em; display:flex; align-items:center; gap:4px; }
.jp-match-id strong { background:var(--night); color:#fff; border-radius:5px; padding:1px 7px; font-size:11px; }

.jp-players { font-size:13.5px; font-weight:600; color:var(--ink); line-height:1.55; margin-bottom:12px; }
.jp-vs { display:block; font-family:var(--font-display); font-size:7.5px; font-weight:800; letter-spacing:.18em; text-transform:uppercase; color:var(--fire); opacity:.7; margin:4px 0; }
.jp-pb { font-size:11px; color:var(--ink-25); font-weight:400; }

.jp-card-foot { display:flex; align-items:center; justify-content:space-between; padding-top:10px; border-top:1px dashed var(--ink-12); }
.jp-court { display:flex; align-items:center; gap:5px; font-size:11px; font-weight:700; color:var(--fire-deep); }
.jp-babak { font-family:var(--font-display); font-size:8px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; color:var(--ink-25); background:var(--ink-06); padding:3px 8px; border-radius:5px; }
.jp-conflict-tag { display:flex; align-items:center; gap:4px; font-size:9px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#ef4444; }

/* ════════════════════════════════════════
   EMPTY / SKELETON
════════════════════════════════════════ */
.jp-empty { text-align:center; padding:80px 24px; }
.jp-empty-icon { width:60px; height:60px; border-radius:18px; background:var(--ink-06); display:flex; align-items:center; justify-content:center; margin:0 auto 18px; color:var(--ink-25); }
.jp-empty-title { font-family:var(--font-display); font-size:16px; font-weight:800; color:var(--ink); margin-bottom:6px; }
.jp-empty-sub { font-size:13px; color:var(--ink-45); }

.jp-skel-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(310px,1fr)); gap:12px; }
.jp-skel-card { background:var(--white); border:1px solid var(--ink-12); border-radius:var(--r-lg); padding:20px; }
.skel { border-radius:6px; background:linear-gradient(90deg,var(--ink-06) 25%,var(--ink-12) 50%,var(--ink-06) 75%); background-size:200% 100%; animation:shimmer 1.4s infinite; }
@keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

/* ════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════ */
@media (max-width:640px) {
    .jp-hero { height:300px; }
    .jp-hero-content { padding:0 18px 28px; }
    .jp-stats { display:none; }
    .jp-hero-title { font-size:24px; }
    .jp-filter-inner { padding:10px 16px; }
    .jp-main { padding:20px 16px 60px; }
    .jp-match-grid { grid-template-columns:1fr; }
    .jp-date-tab { padding:12px 13px; }
    .jp-dtab-num { font-size:17px; }
}
</style>
@endpush

@section('content')
<div class="jp">

    {{-- ══ VIDEO HERO ══ --}}
    <div class="jp-hero">
        <video class="jp-hero-video"
            src="https://res.cloudinary.com/djs5pi7ev/video/upload/q_50,w_1280/v1769502814/bayanopen-hero_iqhyip.mp4"
            autoplay muted loop playsinline preload="auto"></video>
        <div class="jp-hero-overlay"></div>
        <div class="jp-hero-grain"></div>

        <div class="jp-hero-content">
            <div>
                <div class="jp-eyebrow">
                    <span class="jp-eyebrow-dot"></span>
                    <span class="jp-eyebrow-text">Live Schedule 2026</span>
                </div>
                <h1 class="jp-hero-title">Jadwal Pertandingan</h1>
                <p class="jp-hero-sub">Bayan Open 2026 &nbsp;·&nbsp; DOME &amp; HEVINDO &nbsp;·&nbsp; Balikpapan</p>
            </div>
            <div class="jp-stats" id="heroStats" style="display:none;">
                <div class="jp-stat">
                    <span class="jp-stat-val" id="statTotal">—</span>
                    <span class="jp-stat-lbl">Total Match</span>
                </div>
                <div class="jp-stat">
                    <span class="jp-stat-val" id="statDates">—</span>
                    <span class="jp-stat-lbl">Hari</span>
                </div>
                <div class="jp-stat">
                    <span class="jp-stat-val" id="statKat">—</span>
                    <span class="jp-stat-lbl">Kategori</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ DATE TABS ══ --}}
    <div class="jp-date-strip-wrap">
        <div class="jp-date-strip" id="dateTabs">
            <div style="padding:18px 20px;color:rgba(255,255,255,0.25);font-size:12px;font-family:var(--font-display);">
                Memuat…
            </div>
        </div>
    </div>

    {{-- ══ FILTER BAR ══ --}}
    <div class="jp-filter-bar">
        <div class="jp-filter-inner">
            <div class="jp-search-wrap">
                <span class="jp-search-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                </span>
                <input type="text" id="searchInput" class="jp-search"
                    placeholder="Cari nama pemain atau nomor pertandingan…"
                    oninput="renderSchedule()">
            </div>
            <div class="jp-kat-pills" id="katPills">
                <button class="jp-pill active" data-kat="ALL" onclick="setKat('ALL',this)">Semua</button>
            </div>
            <div class="jp-count-badge">
                <span>Match</span>
                <span class="jp-count-num" id="countNum">—</span>
            </div>
        </div>
    </div>

    {{-- ══ SCHEDULE BODY ══ --}}
    <div class="jp-main" id="jpMain">
        <div id="loadingSkeleton">
            <div style="height:20px;width:220px;border-radius:8px;margin-bottom:20px;" class="skel"></div>
            <div class="jp-skel-grid">
                @for($i=0;$i<6;$i++)
                <div class="jp-skel-card">
                    <div class="skel" style="height:11px;width:55%;margin-bottom:13px;"></div>
                    <div class="skel" style="height:14px;width:90%;margin-bottom:8px;"></div>
                    <div class="skel" style="height:14px;width:70%;margin-bottom:16px;"></div>
                    <div class="skel" style="height:10px;width:45%;"></div>
                </div>
                @endfor
            </div>
        </div>
        <div id="scheduleContent" style="display:none;"></div>
    </div>

</div>
@endsection

@push('scripts')
<script>
/* ═══════════════════════════════════════
   JADWAL JS — Bayan Open 2026
═══════════════════════════════════════ */
let allData    = [];
let activeKat  = 'ALL';
let activeDate = null;

const HARI  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const BULAN = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
const BULAN_PANJANG = ['Januari','Februari','Maret','April','Mei','Juni',
                       'Juli','Agustus','September','Oktober','November','Desember'];

function parseDate(str) { const [y,m,d]=str.split('-').map(Number); return new Date(y,m-1,d); }
function fmtTabDay(s)   { const d=parseDate(s); return HARI[d.getDay()].substring(0,3).toUpperCase(); }
function fmtTabNum(s)   { return parseDate(s).getDate(); }
function fmtTabMon(s)   { const d=parseDate(s); return BULAN[d.getMonth()]; }
function fmtLong(s)     { const d=parseDate(s); return `${HARI[d.getDay()]}, ${d.getDate()} ${BULAN_PANJANG[d.getMonth()]} ${d.getFullYear()}`; }

// ── FETCH ─────────────────────────────────────────────────
async function init() {
    try {
        const res = await fetch('https://result.bayanopen.com/get-schedule');
        allData   = await res.json();

        const dates = [...new Set(allData.map(d => d.tanggal))].sort();
        const kats  = [...new Set(allData.map(d => d.kategori).filter(Boolean))].sort();

        // Stats
        document.getElementById('statTotal').textContent = allData.length;
        document.getElementById('statDates').textContent = dates.length;
        document.getElementById('statKat').textContent   = kats.length;
        document.getElementById('heroStats').style.display = 'flex';

        buildDateTabs(dates);
        buildKatPills(kats);

        activeDate = dates[0];
        document.querySelector(`.jp-date-tab[data-date="${activeDate}"]`)?.classList.add('active');

        document.getElementById('loadingSkeleton').style.display = 'none';
        document.getElementById('scheduleContent').style.display = '';
        renderSchedule();

    } catch(err) {
        console.error(err);
        document.getElementById('loadingSkeleton').innerHTML = `
            <div class="jp-empty">
                <div class="jp-empty-icon">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                    </svg>
                </div>
                <p class="jp-empty-title">Gagal memuat jadwal</p>
                <p class="jp-empty-sub">Periksa koneksi internet dan coba lagi.</p>
            </div>`;
    }
}

// ── DATE TABS ─────────────────────────────────────────────
function buildDateTabs(dates) {
    const strip = document.getElementById('dateTabs');
    strip.innerHTML = '';
    dates.forEach((d, i) => {
        if (i > 0) {
            const sep = document.createElement('div');
            sep.className = 'jp-tab-sep';
            strip.appendChild(sep);
        }
        const btn = document.createElement('button');
        btn.className = 'jp-date-tab';
        btn.dataset.date = d;
        btn.innerHTML = `
            <span class="jp-dtab-day">${fmtTabDay(d)}</span>
            <span class="jp-dtab-num">${fmtTabNum(d)}</span>
            <span class="jp-dtab-mon">${fmtTabMon(d)}</span>
        `;
        btn.onclick = () => selectDate(d);
        strip.appendChild(btn);
    });
}

function selectDate(d) {
    activeDate = d;
    document.querySelectorAll('.jp-date-tab').forEach(b => b.classList.remove('active'));
    const active = document.querySelector(`.jp-date-tab[data-date="${d}"]`);
    active?.classList.add('active');
    active?.scrollIntoView({ behavior:'smooth', block:'nearest', inline:'center' });
    renderSchedule();
}

// ── KAT PILLS ─────────────────────────────────────────────
function buildKatPills(kats) {
    const pills = document.getElementById('katPills');
    kats.forEach(k => {
        const btn = document.createElement('button');
        btn.className = 'jp-pill';
        btn.dataset.kat = k;
        btn.textContent = k;
        btn.onclick = function() { setKat(k, this); };
        pills.appendChild(btn);
    });
}

function setKat(kat, btn) {
    activeKat = kat;
    document.querySelectorAll('.jp-pill').forEach(p => p.classList.remove('active'));
    if (btn) btn.classList.add('active');
    renderSchedule();
}

// ── RENDER ────────────────────────────────────────────────
function renderSchedule() {
    const search  = document.getElementById('searchInput').value.toLowerCase().trim();
    const content = document.getElementById('scheduleContent');

    let filtered = [...allData];
    if (activeDate)          filtered = filtered.filter(d => d.tanggal === activeDate);
    if (activeKat !== 'ALL') filtered = filtered.filter(d => d.kategori === activeKat);
    if (search)              filtered = filtered.filter(d =>
        (d.partai||'').toLowerCase().includes(search) ||
        String(d.match_id) === search
    );

    document.getElementById('countNum').textContent = filtered.length;

    if (filtered.length === 0) {
        content.innerHTML = `
            <div class="jp-empty">
                <div class="jp-empty-icon">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                </div>
                <p class="jp-empty-title">Tidak ada jadwal ditemukan</p>
                <p class="jp-empty-sub">Coba ubah filter atau kata kunci pencarian.</p>
            </div>`;
        return;
    }

    // Group date → jam
    const byDate = {};
    filtered.forEach(m => {
        if (!byDate[m.tanggal]) byDate[m.tanggal] = {};
        if (!byDate[m.tanggal][m.jam]) byDate[m.tanggal][m.jam] = [];
        byDate[m.tanggal][m.jam].push(m);
    });

    let html = '';
    Object.keys(byDate).sort().forEach(date => {
        const byJam = byDate[date];
        const total = Object.values(byJam).flat().length;
        html += `<div style="margin-bottom:40px;">
            <div class="jp-date-label">
                <div class="jp-date-label-text">
                    <span class="jp-date-label-fire">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                    </span>
                    ${fmtLong(date)}
                </div>
                <div class="jp-date-label-line"></div>
                <span class="jp-date-label-count">${total} pertandingan</span>
            </div>`;

        Object.keys(byJam).sort().forEach(jam => {
            html += `
            <div class="jp-time-slot">
                <div class="jp-time-pill">
                    <span class="jp-time-dot"></span>
                    <span class="jp-time-text">${jam.substring(0,5)} WITA</span>
                </div>
                <div class="jp-match-grid">
                    ${byJam[jam].map(m => buildCard(m)).join('')}
                </div>
            </div>`;
        });
        html += `</div>`;
    });

    content.innerHTML = html;
}

// ── CARD ──────────────────────────────────────────────────
function buildCard(m) {
    const isConflict = m.status === 'CONFLICT';
    const isTBD      = (m.partai||'').trim() === 'TBD vs TBD';

    let partaiHtml = m.partai || '—';
    partaiHtml = partaiHtml.replace(/\(([^)]+)\)/g,
        '<span class="jp-pb"> ($1)</span>');
    const vsParts = partaiHtml.split(' vs ');
    if (vsParts.length === 2) {
        partaiHtml = vsParts[0] + '<span class="jp-vs">VS</span>' + vsParts[1];
    }

    const venueText = m.venue ? `${m.venue} · Lap ${m.court}` : `Lap ${m.court}`;

    const kat = (m.kategori||'').toLowerCase();
    let kc = 'kdef';
    if      (kat.includes('ganda putra') && !kat.includes('veteran')) kc='kgp';
    else if (kat.includes('ganda putri') && !kat.includes('veteran')) kc='kgpi';
    else if (kat.includes('veteran putra')) kc='kvp';
    else if (kat.includes('veteran putri')) kc='kvpi';
    else if (kat.includes('beregu'))        kc='kber';

    return `
    <div class="jp-card${isConflict?' conflict':''}${isTBD?' is-tbd':''}">
        <div class="jp-card-top">
            <span class="jp-kat-badge ${kc}">${m.kategori||'—'}</span>
            <span class="jp-match-id">No. <strong>${m.match_id}</strong></span>
        </div>
        <div class="jp-players">${partaiHtml}</div>
        <div class="jp-card-foot">
            <span class="jp-court">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                ${venueText}
            </span>
            ${isConflict
                ? `<span class="jp-conflict-tag">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                    </svg>
                    Konflik
                   </span>`
                : `<span class="jp-babak">${m.babak}</span>`
            }
        </div>
    </div>`;
}

// ── BOOT ──────────────────────────────────────────────────
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
else init();
</script>
@endpush