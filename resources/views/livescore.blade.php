@extends('layouts.app')

@section('title', 'Live Score - Bayan Open 2026')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════
   LIVE SCORE PAGE — BAYAN OPEN 2026
   Dark fire theme · Real-time score cards
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
   FILTER BAR (sticky, dark)
════════════════════════════════════════ */
.ls-filter-strip-wrap {
    background: var(--night);
    border-bottom: 1px solid rgba(255,255,255,0.07);
    position: sticky;
    top: 64px; z-index: 40;
}
@media (min-width:640px)  { .ls-filter-strip-wrap { top: 80px; } }
@media (min-width:1024px) { .ls-filter-strip-wrap { top: 96px; } }

.ls-filter-strip {
    max-width: 1120px; margin: 0 auto;
    padding: 13px 24px;
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap;
}

/* selects */
.ls-sel-group { display: flex; gap: 8px; flex-wrap: wrap; }
.ls-sel-wrap { display: flex; flex-direction: column; gap: 3px; }
.ls-sel-wrap label {
    font-family: var(--font-display);
    font-size: 8px; font-weight: 700;
    letter-spacing: .14em; text-transform: uppercase;
    color: rgba(255,255,255,0.3);
}
.ls-sel {
    padding: 7px 28px 7px 10px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: var(--r-xs);
    color: rgba(255,255,255,0.75);
    font-family: var(--font-display); font-size: 10px; font-weight: 700;
    appearance: none; cursor: pointer; outline: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='rgba(255,255,255,0.4)' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    transition: border-color .2s, background-color .2s;
    min-width: 140px;
}
.ls-sel:focus, .ls-sel:hover {
    border-color: rgba(249,115,22,0.5);
    background-color: rgba(249,115,22,0.07);
}
.ls-sel option { background: #1a0a04; color: #fff; }

/* refresh info */
.ls-refresh-info {
    margin-left: auto; flex-shrink: 0;
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
}
.ls-count-badge {
    display: flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: var(--r-xs);
    padding: 5px 10px;
}
.ls-count-num {
    font-family: var(--font-display); font-size: 13px; font-weight: 800;
    color: var(--fire);
}
.ls-count-lbl {
    font-family: var(--font-display); font-size: 8px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    color: rgba(255,255,255,0.25);
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

/* selesai = green accent */
.ls-card.selesai::before { background: linear-gradient(to bottom, var(--success), #059669); opacity:.6; }
.ls-card.selesai:hover   { border-color: rgba(16,185,129,0.3); box-shadow: 0 16px 48px rgba(16,185,129,0.08), 0 2px 8px rgba(26,16,7,0.05); }

.ls-card-head {
    padding: 14px 16px 10px 20px;
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
.ls-jam {
    font-family: var(--font-display); font-size: 10px; font-weight: 800;
    color: var(--ink-45); letter-spacing: .04em;
}

/* players vs score section */
.ls-card-body { padding: 16px 20px 14px; }

.ls-player-row {
    display: flex; align-items: flex-start; justify-content: space-between;
    gap: 12px; margin-bottom: 8px;
}
.ls-player-row:last-of-type { margin-bottom: 0; }

.ls-player-info { flex: 1; min-width: 0; }
.ls-player-name {
    font-size: 13.5px; font-weight: 600; color: var(--ink);
    line-height: 1.4; word-break: break-word;
}
.ls-player-pb {
    font-size: 10.5px; color: var(--ink-25); font-weight: 400;
    margin-top: 2px; display: block;
}

.ls-player-score {
    font-family: var(--font-mono);
    font-size: 24px; font-weight: 800;
    color: var(--ink); line-height: 1;
    flex-shrink: 0; min-width: 36px; text-align: right;
}
.ls-player-score.winner { color: var(--fire); }

.ls-vs-divider {
    display: flex; align-items: center; gap: 10px; margin: 6px 0;
}
.ls-vs-line { flex: 1; height: 1px; background: var(--ink-12); }
.ls-vs-text {
    font-family: var(--font-display); font-size: 7.5px; font-weight: 800;
    letter-spacing: .18em; text-transform: uppercase; color: var(--fire); opacity: .7;
}

/* set scores inline */
.ls-sets {
    margin-top: 12px; padding-top: 10px;
    border-top: 1px dashed var(--ink-12);
    display: flex; gap: 6px; flex-wrap: wrap; align-items: center;
}
.ls-set-label {
    font-family: var(--font-display); font-size: 8px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase;
    color: var(--ink-25); margin-right: 2px;
}
.ls-set-chip {
    padding: 4px 10px; border-radius: 7px;
    font-family: var(--font-mono); font-size: 12px; font-weight: 800;
    background: var(--ink-06); color: var(--ink);
    border: 1px solid var(--ink-12);
}

/* card footer: winner */
.ls-card-foot {
    padding: 10px 20px 14px;
}
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

/* waiting state */
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
   TIME SLOT
════════════════════════════════════════ */
.ls-time-slot { margin-bottom: 20px; }
.ls-time-pill {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 5px 14px 5px 10px;
    background: var(--night); border-radius: 99px; margin-bottom: 12px;
}
.ls-time-dot { width:6px; height:6px; border-radius:50%; background:var(--fire); box-shadow:0 0 8px var(--fire); }
.ls-time-text { font-family:var(--font-display); font-size:10px; font-weight:800; letter-spacing:.14em; color:rgba(255,255,255,.9); }

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
    .ls-filter-strip { padding:10px 16px; gap:8px; }
    .ls-sel-group { gap:6px; }
    .ls-sel { min-width:110px; font-size:9px; }
    .ls-main { padding:20px 16px 60px; }
    .ls-card-grid { grid-template-columns:1fr; }
    .ls-refresh-info { display:none; }
}
</style>
@endpush

@section('content')
<div class="ls">

    {{-- ══ VIDEO HERO ══ --}}
    <div class="ls-hero">
        <video class="ls-hero-video"
            src="https://res.cloudinary.com/djs5pi7ev/video/upload/q_50,w_1280/v1769502814/bayanopen-hero_iqhyip.mp4"
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

    {{-- ══ FILTER STRIP (dark) ══ --}}
    <div class="ls-filter-strip-wrap">
        <div class="ls-filter-strip">
            <div class="ls-sel-group">
                <div class="ls-sel-wrap">
                    <label>Tanggal</label>
                    <select id="filterTanggal" class="ls-sel" onchange="applyFilter()">
                        <option value="ALL">Semua Tanggal</option>
                    </select>
                </div>
                <div class="ls-sel-wrap">
                    <label>Kategori</label>
                    <select id="filterKategori" class="ls-sel" onchange="applyFilter()">
                        <option value="ALL">Semua Kategori</option>
                    </select>
                </div>
                <div class="ls-sel-wrap">
                    <label>Lapangan</label>
                    <select id="filterLapangan" class="ls-sel" onchange="applyFilter()">
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
        {{-- Skeleton --}}
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
/* ═══════════════════════════════════════
   LIVE SCORE JS — Bayan Open 2026
   API: result.bayanopen.com
═══════════════════════════════════════ */
let masterData = [];

const HARI        = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const BULAN       = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
const BULAN_PANJANG = ['Januari','Februari','Maret','April','Mei','Juni',
                       'Juli','Agustus','September','Oktober','November','Desember'];

function parseDate(str) { const [y,m,d]=str.split('-').map(Number); return new Date(y,m-1,d); }
function fmtLong(s) { const d=parseDate(s); return `${HARI[d.getDay()]}, ${d.getDate()} ${BULAN_PANJANG[d.getMonth()]} ${d.getFullYear()}`; }

// ── INIT ─────────────────────────────
async function init() {
    await fetchData(true);
    // Auto-refresh every 30 seconds
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

// ── FILTER POPULATION ────────────────
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

// ── STATS ────────────────────────────
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

// ── RENDER ───────────────────────────
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

    // Group by tanggal → jam
    const byDate = {};
    filtered.forEach(m => {
        if (!byDate[m.tanggal]) byDate[m.tanggal] = {};
        const jam = m.jam || '00:00';
        if (!byDate[m.tanggal][jam]) byDate[m.tanggal][jam] = [];
        byDate[m.tanggal][jam].push(m);
    });

    let html = '';
    Object.keys(byDate).sort().forEach(date => {
        const byJam = byDate[date];
        const totalDate = Object.values(byJam).flat().length;
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
                <span class="ls-date-count">${totalDate} pertandingan</span>
            </div>`;

        Object.keys(byJam).sort().forEach(jam => {
            html += `
            <div class="ls-time-slot">
                <div class="ls-time-pill">
                    <span class="ls-time-dot"></span>
                    <span class="ls-time-text">${jam.substring(0,5)} WITA</span>
                </div>
                <div class="ls-card-grid">
                    ${byJam[jam].map(m => buildCard(m)).join('')}
                </div>
            </div>`;
        });

        html += `</div>`;
    });

    content.innerHTML = html;
}

// ── BUILD CARD ───────────────────────
function buildCard(m) {
    const isSelesai = (m.status_label||'').toUpperCase() === 'SELESAI';

    // Kategori badge class
    const kat = (m.kategori||'').toLowerCase();
    let kc = 'kdef';
    if      (kat.includes('ganda putra') && !kat.includes('veteran')) kc='kgp';
    else if (kat.includes('ganda putri') && !kat.includes('veteran')) kc='kgpi';
    else if (kat.includes('veteran putra')) kc='kvp';
    else if (kat.includes('veteran putri')) kc='kvpi';
    else if (kat.includes('beregu'))        kc='kber';

    // Parse partai → player1 vs player2
    const partai  = m.partai || '—';
    const vsSplit = partai.split(' vs ');
    let p1Html = vsSplit[0] || '—';
    let p2Html = vsSplit[1] || '';

    // Move (PB) into separate line
    const formatPlayer = (txt) => txt.replace(/\s*\(([^)]+)\)/g,
        '<span class="ls-player-pb">($1)</span>');

    // Parse skor → sets
    const skor = (m.skor || '').toString().trim();
    let setsHtml = '';
    if (skor && skor !== '-') {
        const sets = skor.split(/\s+/);
        setsHtml = `
        <div class="ls-sets">
            <span class="ls-set-label">Set</span>
            ${sets.map(s => `<span class="ls-set-chip">${s}</span>`).join('')}
        </div>`;
    }

    // Detect winner from skor for score highlight
    // winner name from m.winner
    const winner = m.winner || '';

    // Determine which player won for score color
    const p1IsWinner = winner && p1Html.toLowerCase().includes(winner.split('/')[0]?.trim().toLowerCase());

    // Score display
    let scoreDisplay = '';
    if (skor && skor !== '-') {
        // Try to extract final set totals
        scoreDisplay = skor;
    }

    const courtLabel = m.venue ? `${m.venue} · Lap ${m.court}` : `Lap ${m.court}`;

    return `
    <div class="ls-card${isSelesai ? ' selesai' : ''}">
        <div class="ls-card-head">
            <span class="ls-kat-badge ${kc}">${m.kategori || '—'}</span>
            <div class="ls-head-right">
                <span class="ls-jam">${(m.jam||'').substring(0,5)}</span>
                <div class="ls-court-pill">
                    <span class="ls-court-dot"></span>
                    ${courtLabel}
                </div>
                <span class="ls-babak-tag">${m.babak || '—'}</span>
            </div>
        </div>

        <div class="ls-card-body">
            <div class="ls-player-row">
                <div class="ls-player-info">${formatPlayer(p1Html)}</div>
                ${isSelesai ? `<span class="ls-player-score ${!isSelesai || p1IsWinner ? 'winner' : ''}">
                    ${skor ? skor.split(/\s+/).length > 0 ? '&nbsp;' : '' : ''}
                </span>` : ''}
            </div>

            <div class="ls-vs-divider">
                <div class="ls-vs-line"></div>
                <span class="ls-vs-text">VS</span>
                <div class="ls-vs-line"></div>
            </div>

            <div class="ls-player-row">
                <div class="ls-player-info">${formatPlayer(p2Html || '—')}</div>
            </div>

            ${setsHtml}
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

// ── BOOT ─────────────────────────────
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
else init();
</script>
@endpush