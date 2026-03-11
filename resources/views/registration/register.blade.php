@extends('layouts.app')

@section('title', 'Daftar Turnamen')

@push('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root {
    --orange:    #f97316;
    --orange-dk: #ea580c;
    --ink:       #1a1209;
    --ink-60:    rgba(26,18,9,0.6);
    --ink-35:    rgba(26,18,9,0.35);
    --ink-12:    rgba(26,18,9,0.12);
    --cream:     #f8f6f2;
    --cream-dk:  #f0ede8;
    --white:     #ffffff;
}

/* ── OVERLAY ───────────────────────────────────── */
@keyframes fadeInOverlay {
    from { opacity: 0; } to { opacity: 1; }
}
@keyframes fadeOutOverlay {
    from { opacity: 1; } to { opacity: 0; }
}
@keyframes slideUpCard {
    from { opacity: 0; transform: translateY(28px) scale(0.97); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes slideDownCard {
    from { opacity: 1; transform: translateY(0) scale(1); }
    to   { opacity: 0; transform: translateY(16px) scale(0.97); }
}
@keyframes pulseDot {
    0%,100% { box-shadow: 0 0 0 0 rgba(249,115,22,0.5); }
    50%      { box-shadow: 0 0 0 4px rgba(249,115,22,0); }
}

/* Full-screen base so modals cover the whole viewport */
body, html { margin: 0; padding: 0; height: 100%; background: var(--cream); }

.mo-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    background: rgba(248,246,242,0.92);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}
.mo-overlay.anim-in  { animation: fadeInOverlay  0.3s ease forwards; }
.mo-overlay.anim-out { animation: fadeOutOverlay 0.2s ease forwards; pointer-events: none; }
.mo-card-in          { animation: slideUpCard 0.4s cubic-bezier(0.22,1,0.36,1) 0.04s both; }

/* ── MODAL CARD ─────────────────────────────────── */
.mo-card {
    background: var(--white);
    border: 1px solid var(--ink-12);
    border-radius: 24px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.12), 0 4px 16px rgba(0,0,0,0.06);
    position: relative;
    overflow: hidden;
}
.mo-top-line {
    position: absolute; top: 0; left: 50%;
    transform: translateX(-50%);
    width: 90px; height: 3px; border-radius: 0 0 6px 6px;
    background: linear-gradient(90deg, transparent, var(--orange), transparent);
}

/* ── BADGE ──────────────────────────────────────── */
.mo-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 14px; border-radius: 99px;
    border: 1.5px solid rgba(249,115,22,0.25);
    background: rgba(249,115,22,0.06);
}
.mo-badge-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--orange); animation: pulseDot 2s ease infinite;
}
.mo-badge-text {
    font-family: 'Unbounded', sans-serif;
    font-size: 10px; font-weight: 700;
    letter-spacing: 0.12em; text-transform: uppercase;
    color: var(--orange-dk);
}

/* ── JALUR CARDS (Modal 1) ──────────────────────── */
.jalur-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

.jalur-card {
    display: block; text-decoration: none;
    border-radius: 16px; padding: 26px 16px;
    text-align: center; position: relative; overflow: hidden;
    transition: all 0.25s cubic-bezier(0.22,1,0.36,1);
    cursor: pointer;
}
.jalur-card.jc-indigo {
    border: 1.5px solid rgba(99,102,241,0.2);
    background: rgba(99,102,241,0.04);
}
.jalur-card.jc-orange {
    border: 1.5px solid rgba(249,115,22,0.2);
    background: rgba(249,115,22,0.04);
    font-family: inherit; width: 100%;
}
.jalur-card.jc-indigo:hover {
    border-color: rgba(99,102,241,0.5);
    background: rgba(99,102,241,0.08);
    box-shadow: 0 8px 28px rgba(99,102,241,0.12);
    transform: translateY(-2px);
}
.jalur-card.jc-orange:hover {
    border-color: rgba(249,115,22,0.45);
    background: rgba(249,115,22,0.08);
    box-shadow: 0 8px 28px rgba(249,115,22,0.15);
    transform: translateY(-2px);
}
.jalur-shimmer {
    position: absolute; top: 0; left: 0; right: 0; height: 1px;
    opacity: 0; transition: opacity 0.3s;
}
.jc-indigo .jalur-shimmer { background: linear-gradient(90deg,transparent,rgba(99,102,241,0.5),transparent); }
.jc-orange .jalur-shimmer { background: linear-gradient(90deg,transparent,rgba(249,115,22,0.6),transparent); }
.jalur-card:hover .jalur-shimmer { opacity: 1; }

.jalur-icon {
    width: 52px; height: 52px; border-radius: 14px;
    margin: 0 auto 14px;
    display: flex; align-items: center; justify-content: center;
    transition: transform 0.25s;
}
.jalur-card:hover .jalur-icon { transform: scale(1.08); }
.jc-indigo .jalur-icon { background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); }
.jc-orange .jalur-icon { background: rgba(249,115,22,0.1);  border: 1px solid rgba(249,115,22,0.2); }

.jalur-title {
    font-family: 'Unbounded', sans-serif; font-weight: 800; font-size: 12px;
    color: var(--ink); letter-spacing: 0.04em; line-height: 1.4; margin: 0 0 4px;
}
.jalur-sub-indigo { font-size: 11px; color: rgba(99,102,241,0.7); margin: 0 0 13px; }
.jalur-sub-orange { font-size: 11px; color: rgba(249,115,22,0.7); margin: 0 0 13px; }

.jalur-badge-pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 99px;
}
.jc-indigo .jalur-badge-pill { background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); }
.jc-orange .jalur-badge-pill { background: rgba(249,115,22,0.1);  border: 1px solid rgba(249,115,22,0.2); }
.jalur-badge-pill-text {
    font-size: 10px; font-weight: 700; letter-spacing: 0.06em;
    font-family: 'Unbounded', sans-serif;
}
.jc-indigo .jalur-badge-pill-text { color: rgba(99,102,241,0.9); }
.jc-orange .jalur-badge-pill-text { color: rgba(249,115,22,0.9); }

.jc-ext {
    position: absolute; top: 10px; right: 10px;
    opacity: 0.2; transition: opacity 0.2s;
}
.jalur-card:hover .jc-ext { opacity: 0.6; }

/* ── INFO ROW (Modal 1) ─────────────────────────── */
.mo-info-row { display: flex; align-items: center; gap: 10px; }
.mo-divider-line { flex: 1; height: 1px; background: var(--ink-12); }
.mo-divider-label {
    font-size: 9px; color: var(--ink-35);
    letter-spacing: 0.1em; text-transform: uppercase;
    font-family: 'Unbounded', sans-serif;
}
.mo-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.mo-info-box {
    padding: 10px 12px; border-radius: 10px;
    background: var(--cream); border: 1px solid var(--ink-12);
}
.mo-info-title {
    font-size: 9px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.08em; color: var(--ink-35);
    margin: 0 0 3px; font-family: 'Unbounded', sans-serif;
}
.mo-info-desc { font-size: 11px; color: var(--ink-60); margin: 0; line-height: 1.45; }

/* ── SUB CARDS (Modal 2) ────────────────────────── */
.mo2-back {
    width: 34px; height: 34px; flex-shrink: 0; border-radius: 99px;
    border: 1px solid var(--ink-12); background: var(--cream);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.2s;
}
.mo2-back:hover { border-color: rgba(249,115,22,0.35); background: rgba(249,115,22,0.06); }

.sub-card {
    display: flex; align-items: center; gap: 14px; width: 100%;
    font-family: inherit; border-radius: 14px; padding: 14px 16px;
    border: 1.5px solid var(--ink-12); background: var(--cream);
    text-align: left; cursor: pointer; position: relative; overflow: hidden;
    transition: all 0.2s cubic-bezier(0.22,1,0.36,1);
}
.sub-card:hover { transform: translateY(-1px); }
.sub-card .sc-arrow {
    margin-left: auto; flex-shrink: 0;
    opacity: 0.2; transition: opacity 0.2s, transform 0.2s;
}
.sub-card:hover .sc-arrow { opacity: 0.65; transform: translateX(3px); }

.sub-card.sc-blue:hover   { border-color:rgba(59,130,246,0.4);  background:rgba(59,130,246,0.06);  box-shadow:0 4px 16px rgba(59,130,246,0.1); }
.sub-card.sc-pink:hover   { border-color:rgba(236,72,153,0.4);  background:rgba(236,72,153,0.06);  box-shadow:0 4px 16px rgba(236,72,153,0.1); }
.sub-card.sc-yellow:hover { border-color:rgba(234,179,8,0.4);   background:rgba(234,179,8,0.06);   box-shadow:0 4px 16px rgba(234,179,8,0.1); }
.sub-card.sc-green:hover  { border-color:rgba(16,185,129,0.4);  background:rgba(16,185,129,0.06);  box-shadow:0 4px 16px rgba(16,185,129,0.1); }

.sub-icon {
    width: 42px; height: 42px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: transform 0.2s;
}
.sub-card:hover .sub-icon { transform: scale(1.08); }
.sub-name  { font-weight: 700; font-size: 13px; color: var(--ink); margin: 0 0 2px; }
.sub-desc  { font-size: 11px; color: var(--ink-60); margin: 0; }
.sub-price { font-size: 12px; font-weight: 700; color: var(--orange-dk); flex-shrink: 0; margin-right: 6px; }
</style>
@endpush

@section('content')
{{-- Kosong — halaman hanya berisi modal --}}
@endsection

@push('modals')

{{-- ══════════════════════════════════
     MODAL 1 — PILIH JALUR
══════════════════════════════════ --}}
<div id="modal1" class="mo-overlay anim-in">
    <div id="modal1Card" class="mo-card mo-card-in" style="width:100%;max-width:560px;">
        <div class="mo-top-line"></div>
        <div style="padding:36px 32px 30px;">

            {{-- Badge --}}
            <div style="display:flex;justify-content:center;margin-bottom:22px;">
                <div class="mo-badge">
                    <span class="mo-badge-dot"></span>
                    <span class="mo-badge-text">Bayan Open 2026</span>
                </div>
            </div>

            {{-- Heading --}}
            <div style="text-align:center;margin-bottom:26px;">
                <h2 style="font-family:'Unbounded',sans-serif;font-size:24px;font-weight:800;color:var(--ink);margin:0 0 8px;letter-spacing:-0.02em;line-height:1.2;">Pilih Jalur Pendaftaran</h2>
                <p style="color:var(--ink-60);font-size:13px;margin:0;">Silakan pilih jalur turnamen yang ingin Anda ikuti</p>
            </div>

            {{-- 2 Kartu Jalur --}}
            <div class="jalur-grid" style="margin-bottom:22px;">

                {{-- Sirkuit Nasional C --}}
                <a href="https://si.pbsi.com" target="_blank" rel="noopener noreferrer"
                   class="jalur-card jc-indigo"
                   onmouseenter="hoverJalur(this,'indigo')" onmouseleave="unhoverJalur(this,'indigo')">
                    <div class="jalur-shimmer"></div>
                    <div class="jalur-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(99,102,241,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <p class="jalur-title">SIRKUIT<br>NASIONAL C</p>
                    <p class="jalur-sub-indigo">via si.pbsi.com</p>
                    <div class="jalur-badge-pill">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="rgba(99,102,241,0.9)" stroke-width="2.5"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="jalur-badge-pill-text">RESMI PBSI</span>
                    </div>
                    <div class="jc-ext">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="rgba(0,0,0,0.5)" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                    </div>
                </a>

                {{-- Open --}}
                <button type="button" onclick="bukaModal2()" class="jalur-card jc-orange"
                   onmouseenter="hoverJalur(this,'orange')" onmouseleave="unhoverJalur(this,'orange')">
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

            {{-- Info --}}
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
        </div>
    </div>
</div>

{{-- ══════════════════════════════════
     MODAL 2 — PILIH KATEGORI
══════════════════════════════════ --}}
<div id="modal2" class="mo-overlay" style="display:none;">
    <div id="modal2Card" class="mo-card" style="width:100%;max-width:520px;">
        <div class="mo-top-line"></div>
        <div style="padding:30px 26px 26px;">

            {{-- Header --}}
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                <button type="button" onclick="tutupModal2()" class="mo2-back">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(26,18,9,0.6)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                </button>
                <div>
                    <h2 style="font-family:'Unbounded',sans-serif;font-size:18px;font-weight:800;color:var(--ink);margin:0 0 3px;letter-spacing:-0.02em;">Pilih Kategori Open</h2>
                    <p style="color:var(--ink-60);font-size:12px;margin:0;">Pilih kelas pertandingan yang akan Anda ikuti</p>
                </div>
            </div>

            {{-- Kategori --}}
            <div style="display:flex;flex-direction:column;gap:8px;">

                <button type="button" class="sub-card sc-blue" onclick="pilihKategori('ganda-dewasa-putra')">
                    <div class="sub-icon" style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(59,130,246,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                        </svg>
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
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(236,72,153,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                        </svg>
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
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(234,179,8,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p class="sub-name">Ganda Veteran Putra</p>
                        <p class="sub-desc">Scan KTP · lahir ≤ 24 Agustus 1995</p>
                    </div>
                    <span class="sub-price">Rp 150.000</span>
                    <svg class="sc-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(26,18,9,0.6)" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>

                <button type="button" class="sub-card sc-green" onclick="pilihKategori('beregu')">
                    <div class="sub-icon" style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.2);">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="rgba(16,185,129,1)">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p class="sub-name">Beregu</p>
                        <p class="sub-desc">Upload KTP · min. 3 pemain per regu</p>
                    </div>
                    <span class="sub-price">Rp 200.000</span>
                    <svg class="sc-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(26,18,9,0.6)" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>

            </div>
        </div>
    </div>
</div>

<script>
const kategoriRoutes = {
    'ganda-dewasa-putra'  : '{{ route("registration.ganda-dewasa-putra") }}',
    'ganda-dewasa-putri'  : '{{ route("registration.ganda-dewasa-putri") }}',
    'ganda-veteran-putra' : '{{ route("registration.ganda-veteran-putra") }}',
    'beregu'              : '{{ route("registration.beregu") }}',
};

function bukaModal2() {
    const m1 = document.getElementById('modal1');
    const m2 = document.getElementById('modal2');
    const m2c = document.getElementById('modal2Card');
    m1.classList.add('anim-out');
    setTimeout(() => {
        m1.style.display = 'none'; m1.classList.remove('anim-out');
        m2.style.display = 'flex'; m2.classList.add('anim-in');
        m2c.style.animation = 'none'; void m2c.offsetWidth;
        m2c.style.animation = 'slideUpCard 0.4s cubic-bezier(0.22,1,0.36,1) 0.04s both';
        setTimeout(() => m2.classList.remove('anim-in'), 400);
    }, 200);
}

function tutupModal2() {
    const m1 = document.getElementById('modal1');
    const m1c = document.getElementById('modal1Card');
    const m2 = document.getElementById('modal2');
    const m2c = document.getElementById('modal2Card');
    m2.classList.add('anim-out');
    m2c.style.animation = 'slideDownCard 0.2s ease forwards';
    setTimeout(() => {
        m2.style.display = 'none'; m2.classList.remove('anim-out'); m2c.style.animation = '';
        m1.style.display = 'flex'; m1.classList.add('anim-in');
        m1c.style.animation = 'none'; void m1c.offsetWidth;
        m1c.style.animation = 'slideUpCard 0.4s cubic-bezier(0.22,1,0.36,1) 0.04s both';
        setTimeout(() => m1.classList.remove('anim-in'), 400);
    }, 200);
}

function pilihKategori(k) {
    const m2 = document.getElementById('modal2');
    const m2c = document.getElementById('modal2Card');
    m2.classList.add('anim-out');
    m2c.style.animation = 'slideDownCard 0.2s ease forwards';
    setTimeout(() => { window.location.href = kategoriRoutes[k]; }, 220);
}

function hoverJalur(el, color) {
    const isIndigo = color === 'indigo';
    el.style.borderColor = isIndigo ? 'rgba(99,102,241,0.5)'  : 'rgba(249,115,22,0.45)';
    el.style.background  = isIndigo ? 'rgba(99,102,241,0.08)' : 'rgba(249,115,22,0.08)';
    el.style.boxShadow   = isIndigo ? '0 8px 28px rgba(99,102,241,0.12)' : '0 8px 28px rgba(249,115,22,0.15)';
    el.style.transform   = 'translateY(-2px)';
    const shimmer = el.querySelector('.jalur-shimmer');
    if (shimmer) shimmer.style.opacity = '1';
    const icon = el.querySelector('.jalur-icon');
    if (icon) icon.style.transform = 'scale(1.08)';
}
function unhoverJalur(el, color) {
    const isIndigo = color === 'indigo';
    el.style.borderColor = isIndigo ? 'rgba(99,102,241,0.2)' : 'rgba(249,115,22,0.2)';
    el.style.background  = isIndigo ? 'rgba(99,102,241,0.04)' : 'rgba(249,115,22,0.04)';
    el.style.boxShadow   = '';
    el.style.transform   = '';
    const shimmer = el.querySelector('.jalur-shimmer');
    if (shimmer) shimmer.style.opacity = '0';
    const icon = el.querySelector('.jalur-icon');
    if (icon) icon.style.transform = '';
}

// Entrance animation on load
document.addEventListener('DOMContentLoaded', () => {
    const card = document.getElementById('modal1Card');
    if (card) {
        card.style.animation = 'none';
        void card.offsetWidth;
        card.style.animation = 'slideUpCard 0.45s cubic-bezier(0.22,1,0.36,1) 0.1s both';
    }
});
</script>
@endpush