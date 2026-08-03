@extends('layouts.app')

@section('title', 'Akomodasi & Tour')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════
   TOKENS (samakan dengan welcome.blade.php)
═══════════════════════════════════════ */
:root {
    --fire:      #f97316;
    --fire-deep: #c2410c;
    --night:     #0d0906;
    --night-2:   #140c07;
    --paper:     #faf8f5;
    --paper-2:   #f2ede6;
    --ink:       #1a1007;
    --ink-60:    rgba(26,16,7,0.6);
    --ink-30:    rgba(26,16,7,0.3);
    --ink-12:    rgba(26,16,7,0.1);
    --r-sm: 12px; --r-md: 18px; --r-lg: 24px; --r-xl: 32px;
    --font-display: 'Montserrat', sans-serif;
    --font-body:    'Montserrat', sans-serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
.at-page { font-family: var(--font-body); background: var(--paper); color: var(--ink); }

/* ── Hero mini ── */
.at-hero {
    background:
        linear-gradient(160deg, rgba(13,9,6,0.6) 0%, rgba(20,12,7,0.7) 100%),
        url('https://www.amazingborneo.id/wp-content/uploads/2019/09/Balikpapan.jpg') center 30% / cover no-repeat;
    padding: 90px 24px 110px;
    min-height: 460px;
    position: relative; overflow: hidden;
}
.at-hero::before {
    content: ''; position: absolute; top: -100px; right: -80px;
    width: 340px; height: 340px; border-radius: 50%;
    background: radial-gradient(circle, rgba(249,115,22,0.18) 0%, transparent 70%);
    pointer-events: none;
}
.at-hero-inner { max-width: 1120px; margin: 0 auto; position: relative; z-index: 1; }
.at-breadcrumb {
    display: inline-flex; align-items: center; gap: 6px;
    font-family: var(--font-display); font-size: 11px; font-weight: 700;
    letter-spacing: 0.05em; color: rgba(255,255,255,0.45); text-decoration: none;
    margin-bottom: 22px; transition: color 0.2s;
}
.at-breadcrumb:hover { color: var(--fire); }
.at-hero-title { font-family: var(--font-display); font-size: clamp(28px,5vw,44px); font-weight: 800; color: #fff; letter-spacing: -0.03em; margin-bottom: 10px; }
.at-hero-sub { font-size: 14px; color: rgba(255,255,255,0.5); max-width: 480px; line-height: 1.7; }

/* ── Disclaimer bar (reuse pola sirnas-note) ── */
.at-disclaimer {
    max-width: 1120px; margin: -26px auto 0; padding: 0 24px; position: relative; z-index: 2;
}
.at-disclaimer-inner {
    display: flex; align-items: center; gap: 12px;
    background: #fff;
    border: 1px solid rgba(249,115,22,0.18);
    box-shadow: 0 12px 40px rgba(0,0,0,0.08);
    border-radius: var(--r-md);
    padding: 14px 20px;
}
.at-disclaimer-icon { width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0; background: rgba(249,115,22,0.09); display: flex; align-items: center; justify-content: center; }
.at-disclaimer-text { font-size: 13px; color:black; line-height: 1.6; font-weight: 700; }

/* ── Section commons ── */
.at-section { padding: 76px 24px 20px; }
.at-section-inner { max-width: 1120px; margin: 0 auto; }
.at-sec-tag { font-family: var(--font-display); font-size: 10px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--fire); margin-bottom: 12px; display: block; }
.at-sec-title { font-family: var(--font-display); font-size: clamp(24px,4vw,36px); font-weight: 800; color: var(--ink); letter-spacing: -0.03em; }
.at-sec-sub { font-size: 14px; color: var(--ink-60); line-height: 1.7; max-width: 520px; margin-top: 12px; }

/* ── Search + tab filter ── */
.at-hotel-controls { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-top: 32px; margin-bottom: 8px; }
.at-search-wrap { position: relative; min-width: 220px; flex: 1; max-width: 320px; }
.at-search-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--ink-30); display: flex; }
.at-search { width: 100%; padding: 10px 14px 10px 38px; border: 1.5px solid var(--ink-12); border-radius: var(--r-sm); font-size: 13px; background: #fff; outline: none; transition: all 0.2s; }
.at-search:focus { border-color: var(--fire); box-shadow: 0 0 0 3px rgba(249,115,22,0.1); }

.at-tab-switcher { display: inline-flex; background: var(--ink-12); border-radius: 14px; padding: 5px; gap: 4px; }
.at-tab-btn {
    padding: 9px 18px; border-radius: 10px; border: none; cursor: pointer;
    font-family: var(--font-display); font-size: 10.5px; font-weight: 700;
    letter-spacing: 0.08em; text-transform: uppercase; color: var(--ink-30);
    background: transparent; transition: all 0.2s;
}
.at-tab-btn.active { background: #fff; color: var(--ink); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.at-tab-btn.active[data-tier="budget"]   { background: linear-gradient(135deg,#10b981,#0d9488); color: #fff; }
.at-tab-btn.active[data-tier="standard"] { background: linear-gradient(135deg,#3b82f6,#2563eb); color: #fff; }
.at-tab-btn.active[data-tier="premium"]  { background: linear-gradient(135deg,var(--fire),var(--fire-deep)); color: #fff; }

/* ── Hotel grid ── */
.at-hotel-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 14px; margin-top: 26px; }
.at-hotel-card {
    background: #fff; border: 1px solid var(--ink-12); border-radius: var(--r-lg);
    padding: 0 0 16px; position: relative; overflow: hidden;
    transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
    display: flex; flex-direction: column;
}
.at-hotel-card:hover { transform: translateY(-4px); box-shadow: 0 20px 44px rgba(0,0,0,0.08); border-color: rgba(249,115,22,0.2); }
.at-hotel-card.official { border-color: rgba(249,115,22,0.4); background: linear-gradient(160deg,#fff7ed,#fff); grid-column: 1 / -1; display: flex; flex-direction: row; align-items: center; gap: 20px; flex-wrap: wrap; padding: 22px 24px; }
.at-hotel-card.official .at-hotel-icon { width: 220px; height: 220px; margin: 0; border-radius: var(--r-md); }
.at-hotel-icon {
    width: 100%; height: 168px; border-radius: 0; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
    margin-bottom: 14px;
}
.at-hotel-card:not(.official) .at-hotel-icon { padding: 0; }
.at-hotel-card:not(.official) .at-hotel-body { padding: 0 18px 0; }
.at-hotel-icon img { width: 100%; height: 100%; object-fit: cover; display: block; border-radius: inherit; }
.at-tier-budget   .at-hotel-icon { background: rgba(16,185,129,0.09); border: 1px solid rgba(16,185,129,0.18); }
.at-tier-standard .at-hotel-icon { background: rgba(59,130,246,0.09);  border: 1px solid rgba(59,130,246,0.18); }
.at-tier-premium  .at-hotel-icon { background: rgba(249,115,22,0.09); border: 1px solid rgba(249,115,22,0.18); }
.at-hotel-name { font-size: 13.5px; font-weight: 700; color: var(--ink); line-height: 1.35; margin-bottom: 3px; }
.at-hotel-room { font-size: 11px; color: var(--ink-30); margin-bottom: 12px; }
.at-hotel-official-flex { flex: 1; min-width: 200px; }
.at-hotel-official-flex .at-hotel-name { font-size: 17px; margin-bottom: 5px; }
.at-official-tag {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(249,115,22,0.12); border: 1px solid rgba(249,115,22,0.3);
    padding: 4px 11px 4px 7px; border-radius: 99px; margin-bottom: 10px;
}
.at-official-tag-text { font-family: var(--font-display); font-size: 9px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: var(--fire-deep); }
.at-hotel-foot { display: flex; align-items: center; justify-content: space-between; padding: 12px 18px 0; border-top: 1px dashed var(--ink-12); margin-top: auto; }
.at-hotel-card.official .at-hotel-foot { padding: 12px 0 0; }
.at-hotel-price { font-family: var(--font-display); font-size: 15px; font-weight: 800; color: var(--ink); }
.at-hotel-price-per { font-size: 9.5px; color: var(--ink-30); }
.at-tier-badge { font-size: 8.5px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; padding: 3px 9px; border-radius: 99px; }
.at-tier-budget   .at-tier-badge { background: rgba(16,185,129,0.1); color: #0d9488; }
.at-tier-standard .at-tier-badge { background: rgba(59,130,246,0.1); color: #2563eb; }
.at-tier-premium  .at-tier-badge { background: rgba(249,115,22,0.1); color: var(--fire-deep); }

.at-hotel-empty { grid-column: 1/-1; text-align: center; padding: 50px 20px; color: var(--ink-30); font-size: 13px; }

.at-footnote { font-size: 11.5px; color: var(--ink-30); margin-top: 24px; text-align: center; }

/* ── Tour section ── */
.at-tour-grid { display: flex; flex-direction: column; gap: 20px; margin-top: 32px; }
.at-tour-card {
    background: #fff; border: 1px solid var(--ink-12); border-radius: var(--r-xl);
    display: grid; grid-template-columns: 220px 1fr; overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}
.at-tour-visual {
    background: linear-gradient(160deg, var(--night), var(--night-2));
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden; padding: 24px;
}
.at-tour-visual.has-photo { padding: 0; }
.at-tour-visual::before { content:''; position:absolute; inset:-40%; background: radial-gradient(circle, rgba(249,115,22,0.25) 0%, transparent 65%); }
.at-tour-visual svg { position: relative; z-index: 1; }
.at-tour-visual img { position: relative; z-index: 1; width: 100%; height: 100%; object-fit: cover; }
.at-tour-body { padding: 26px 28px 24px; }
.at-tour-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 12px; flex-wrap: wrap; }
.at-tour-dur { font-family: var(--font-display); font-size: 9.5px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: var(--fire-deep); background: rgba(249,115,22,0.08); padding: 5px 12px; border-radius: 99px; }
.at-tour-title { font-family: var(--font-display); font-size: 18px; font-weight: 800; color: var(--ink); line-height: 1.3; margin-bottom: 10px; }
.at-tour-desc { font-size: 13px; color: var(--ink-60); line-height: 1.75; margin-bottom: 16px; }
.at-tour-hl-list { display: flex; flex-direction: column; gap: 7px; margin-bottom: 14px; }
.at-tour-hl-item { display: flex; align-items: flex-start; gap: 8px; font-size: 12.5px; color: var(--ink); font-weight: 500; }
.at-tour-hl-icon { width: 18px; height: 18px; border-radius: 6px; background: rgba(249,115,22,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
.at-tour-includes { font-size: 11.5px; color: var(--ink-30); margin-bottom: 18px; }
.at-tour-includes strong { color: var(--ink-60); font-weight: 600; }
.at-tour-foot { display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; padding-top: 16px; border-top: 1px dashed var(--ink-12); }
.at-tour-price-wrap .at-tour-price { font-family: var(--font-display); font-size: 22px; font-weight: 800; color: var(--fire-deep); }
.at-tour-price-wrap .at-tour-price-note { font-size: 10.5px; color: var(--ink-30); }
.at-btn-wa {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg,#25d366,#128c4a); color: #fff; text-decoration: none;
    font-family: var(--font-display); font-size: 11px; font-weight: 700;
    letter-spacing: 0.06em; text-transform: uppercase;
    padding: 12px 22px; border-radius: 13px;
    box-shadow: 0 8px 22px rgba(37,211,102,0.3); transition: all 0.25s;
}
.at-btn-wa:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(37,211,102,0.4); }

/* ── CTA banner (reuse) ── */
.at-cta-wrap { padding: 60px 24px 90px; background: var(--paper-2); }
.at-cta-banner {
    max-width: 1120px; margin: 0 auto;
    background: var(--night-2); border-radius: 28px; padding: 60px 40px;
    text-align: center; position: relative; overflow: hidden; border: 1px solid rgba(249,115,22,0.15);
}
.at-btn-detail-outline {
    display: inline-flex; align-items: center; gap: 6px;
    font-family: var(--font-display); font-size: 11px; font-weight: 700;
    letter-spacing: 0.06em; text-transform: uppercase; text-decoration: none;
    color: var(--ink-60); border: 1px solid var(--ink-12);
    padding: 12px 18px; border-radius: 13px; transition: all 0.2s;
}
.at-btn-detail-outline:hover { background: var(--paper-2); border-color: rgba(249,115,22,0.3); color: var(--ink); }
.at-cta-banner::before { content:''; position:absolute; top:-80px; right:-80px; width:360px; height:360px; border-radius:50%; background: radial-gradient(circle, rgba(249,115,22,0.2) 0%, transparent 65%); }
.at-cta-line { width: 80px; height: 2px; background: linear-gradient(90deg, transparent, var(--fire), transparent); margin: 0 auto 26px; position: relative; z-index: 1; }
.at-cta-title { font-family: var(--font-display); font-size: clamp(22px,4vw,36px); font-weight: 800; color: #fff; letter-spacing: -0.02em; line-height: 1.2; margin-bottom: 12px; position: relative; z-index: 1; }
.at-cta-title em { font-style: normal; color: var(--fire); }
.at-cta-sub { font-size: 14px; color: rgba(255,255,255,0.4); margin-bottom: 30px; position: relative; z-index: 1; }
.at-btn-fire {
    display: inline-flex; align-items: center; gap: 9px;
    font-family: var(--font-display); font-size: 11px; font-weight: 700;
    letter-spacing: 0.1em; text-transform: uppercase; color: #fff; text-decoration: none;
    background: linear-gradient(135deg, var(--fire), var(--fire-deep));
    padding: 15px 30px; border-radius: 15px; position: relative; z-index: 1;
    box-shadow: 0 8px 28px rgba(249,115,22,0.4);
}

@media (max-width: 720px) {
    .at-tour-card { grid-template-columns: 1fr; }
    .at-tour-visual { padding: 32px; }
    .at-hotel-card.official { flex-direction: row; }
}
@media (max-width: 560px) {
    .at-hotel-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .at-hotel-card.official { flex-direction: column; align-items: flex-start; text-align: left; }
    .at-hotel-card.official .at-hotel-icon { width: 100%; height: 180px; }
    .at-hotel-icon { height: 130px; }
    .at-hotel-controls { flex-direction: column; align-items: stretch; }
    .at-tab-switcher { width: 100%; }
    .at-tab-btn { flex: 1; padding: 9px 6px; font-size: 9px; }
    .at-search-wrap { max-width: none; }
}
</style>
@endpush

@section('content')
<div class="at-page">

    {{-- ══ HERO MINI ══ --}}
    <div class="at-hero">
        <div class="at-hero-inner">
            <a href="{{ route('home') }}" class="at-breadcrumb">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Kembali ke Beranda
            </a>
            <h1 class="at-hero-title">Akomodasi &amp; Tour</h1>
            <p class="at-hero-sub">Rencanakan perjalananmu ke Balikpapan dari tempat menginap sampai jalan-jalan setelah pertandingan selesai.</p>
        </div>
    </div>

    {{-- ══ DISCLAIMER BAR ══ --}}
    <div class="at-disclaimer">
        <div class="at-disclaimer-inner">
            <div class="at-disclaimer-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#c2410c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
            </div>
            <p class="at-disclaimer-text">Hotel &amp; paket wisata di halaman ini adalah rekomendasi pihak ketiga. Reservasi &amp; pembayaran dilakukan langsung ke pihak hotel/penyedia tour dan melalui aplikasi online, bukan melalui panitia Bayan Open. Harga sewaktu-waktu dapat berubah.</p>
            </div>
    </div>

    {{-- ══ SECTION HOTEL ══ --}}
    <section id="hotel" class="at-section">
        <div class="at-section-inner">
            <span class="at-sec-tag reveal">Tempat Menginap</span>
            <h2 class="at-sec-title reveal">Pilihan Hotel di Balikpapan</h2>
            <p class="at-sec-sub reveal">33 hotel dengan berbagai rentang harga, mulai dari yang hemat sampai yang paling dekat dengan venue.</p>

            <div class="at-hotel-controls reveal">
                <div class="at-search-wrap">
                    <span class="at-search-icon">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    </span>
                    <input type="text" id="hotelSearch" class="at-search" placeholder="Cari nama hotel…" oninput="renderHotels()">
                </div>
                <div class="at-tab-switcher" id="tierTabs">
                    <button class="at-tab-btn active" data-tier="all" onclick="setTier('all',this)">Semua</button>
                    <button class="at-tab-btn" data-tier="budget" onclick="setTier('budget',this)">Budget</button>
                    <button class="at-tab-btn" data-tier="standard" onclick="setTier('standard',this)">Standard</button>
                    <button class="at-tab-btn" data-tier="premium" onclick="setTier('premium',this)">Premium</button>
                </div>
            </div>

            <div class="at-hotel-grid" id="hotelGrid"></div>

            <p class="at-footnote">Hubungi hotel langsung untuk cek ketersediaan &amp; reservasi. &middot; Update terakhir: {{ $updatedAt }}</p>
        </div>
    </section>

    {{-- ══ SECTION TOUR ══ --}}
    <section id="tour" class="at-section">
        <div class="at-section-inner">
            <span class="at-sec-tag reveal">Waktunya Liburan</span>
            <h2 class="at-sec-title reveal">Paket Wisata Balikpapan</h2>
            <p class="at-sec-sub reveal">Tiga pilihan tour singkat yang bisa kamu pesan langsung — cocok buat kamu yang datang bareng keluarga atau tim.</p>

            <div class="at-tour-grid">
                @php
                $tourIconsSvg = [
                    'market' => '<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 01-8 0"/></svg>',
                    'crocodile' => '<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12c2-4 6-6 11-6 5 0 8 3 9 5-1 1-3 2-5 2H8l-2 3-1-3-3 1 1-2z"/><circle cx="16" cy="9" r="0.8" fill="#f97316"/></svg>',
                    'ikn' => '<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6"/></svg>',
                ];
                @endphp
                @foreach($tours as $t)
                <div class="at-tour-card reveal">
                    <div class="at-tour-visual {{ !empty($t['images']) ? 'has-photo' : '' }}">
                        @if(!empty($t['images']))
                            @include('partials.tour-image-collage', ['images' => $t['images'], 'alt' => $t['title']])
                        @else
                            {!! $tourIconsSvg[$t['icon']] !!}
                        @endif
                    </div>
                    <div class="at-tour-body">
                        <div class="at-tour-top">
                            <span class="at-tour-dur">{{ $t['duration'] }}</span>
                        </div>
                        <h3 class="at-tour-title">{{ $t['title'] }}</h3>
                        <p class="at-tour-desc">{{ $t['description'] }}</p>
                        <div class="at-tour-hl-list">
                            @foreach($t['highlights'] as $hl)
                            <div class="at-tour-hl-item">
                                <span class="at-tour-hl-icon">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                                </span>
                                {{ $hl }}
                            </div>
                            @endforeach
                        </div>
                        @if(!empty($t['includes']))
                        <p class="at-tour-includes"><strong>Termasuk:</strong> {{ implode(', ', $t['includes']) }}</p>
                        @endif
                        <div class="at-tour-foot">
                            {{--
                            <div class="at-tour-price-wrap">
                                <span class="at-tour-price">Rp {{ number_format($t['price'],0,',','.') }}</span>
                                <div class="at-tour-price-note">per orang &middot; minimal {{ $t['min_person'] }} orang</div>
                            </div>
                            --}}
                            <a href="{{ route('akomodasi-tour.tour', $t['slug']) }}" class="at-btn-detail-outline">Lihat Detail</a>
                            @php
                                $waText = rawurlencode("Halo, saya peserta Bayan Open 2026. Saya mau tanya-tanya soal paket {$t['title']} ({$t['duration']}). Apakah masih tersedia?");
                            @endphp
                            <a href="https://wa.me/{{ $t['contact_phone'] }}?text={{ $waText }}"
                               target="_blank" rel="noopener noreferrer"
                               class="at-btn-wa"
                               aria-label="Chat WhatsApp untuk paket {{ $t['title'] }}">
                                <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/><path d="M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654A11.882 11.882 0 0012.05 23.79h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.468 3.484 11.815 11.815 0 0012.05 0z"/></svg>
                                Chat via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══ CTA BANNER PENUTUP ══ 
    <div class="at-cta-wrap">
        <div class="at-cta-banner reveal">
            <div class="at-cta-line"></div>
            <p class="at-cta-title">SUDAH URUS HOTEL &amp; TOUR-NYA?<br><em>SAATNYA DAFTAR TURNAMENNYA!</em></p>
            <p class="at-cta-sub">Slot pendaftaran Bayan Open 2026 terbatas — amankan tempatmu sekarang.</p>
            <a href="{{ route('home') }}#kategori" class="at-btn-fire">
                Daftar Sekarang
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>--}}

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script>
// ── Data hotel dari server (sudah official-first) ──
var atHotels = @json($hotels);
var atTierLabel = { budget: 'Hemat', standard: 'Standard', premium: 'Premium' };
var atActiveTier = 'all';

function fmtRupiah(n) { return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

// FIX: fallback SVG generik (dipakai kalau hotel TIDAK punya image_url)
function hotelIconSvg(color) {
    return '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="'+color+'" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6"/></svg>';
}

// FIX BARU: kalau hotel punya image_url, render <img>. Kalau tidak, fallback ke SVG icon.
function hotelIconOrImage(h, size, colorFallback) {
    if (h.image_url) {
        return '<img src="' + h.image_url + '" alt="' + h.name.replace(/"/g, '&quot;') + '" loading="lazy">';
    }
    if (h.is_official) {
        return '<svg width="' + size + '" height="' + size + '" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 11h.01M15 11h.01M9 8h.01M15 8h.01"/></svg>';
    }
    return hotelIconSvg(colorFallback);
}

var tierColors = { budget: '#0d9488', standard: '#2563eb', premium: '#c2410c' };

function buildHotelCard(h) {
    if (h.is_official) {
        return `
        <a href="/akomodasi-tour/hotel/${h.slug}" class="at-hotel-card official at-tier-${h.tier}">
            <div class="at-hotel-icon">
                ${hotelIconOrImage(h, 48, '#f97316')}
            </div>
            <div class="at-hotel-official-flex">
                <div class="at-official-tag">
                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#c2410c" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                    <span class="at-official-tag-text">Hotel Resmi</span>
                </div>
                <p class="at-hotel-name">${h.name}</p>
                <p class="at-hotel-room">Tipe ${h.room_type}</p>
                <div class="at-hotel-foot" style="border-top:none;padding-top:0;padding-left:0;padding-right:0;justify-content:flex-end;">
                    <!-- <div><span class="at-hotel-price">${fmtRupiah(h.rate)}</span><div class="at-hotel-price-per">per malam</div></div> -->
                    <span class="at-tier-badge">${atTierLabel[h.tier]}</span>
                </div>
                </div>
            </div>
        </div>`;
    }
    return `
    <a href="/akomodasi-tour/hotel/${h.slug}" class="at-hotel-card at-tier-${h.tier}">
        <div class="at-hotel-icon">${hotelIconOrImage(h, 28, tierColors[h.tier])}</div>
        <div class="at-hotel-body">
            <p class="at-hotel-name">${h.name}</p>
            <p class="at-hotel-room">Tipe ${h.room_type}</p>
        </div>
        <div class="at-hotel-foot" style="justify-content:flex-end;">
            <!-- <span class="at-hotel-price">${fmtRupiah(h.rate)}</span> -->
            <span class="at-tier-badge">${atTierLabel[h.tier]}</span>
        </div>
    </div>`;
}

function renderHotels() {
    var search = document.getElementById('hotelSearch').value.toLowerCase().trim();
    var grid = document.getElementById('hotelGrid');

    var filtered = atHotels.filter(function(h) {
        var matchTier = atActiveTier === 'all' || h.is_official || h.tier === atActiveTier;
        var matchSearch = !search || h.name.toLowerCase().includes(search);
        return matchTier && matchSearch;
    });

    if (filtered.length === 0) {
        grid.innerHTML = '<div class="at-hotel-empty">Tidak ada hotel yang cocok dengan pencarian/filter.</div>';
        return;
    }

    grid.innerHTML = filtered.map(buildHotelCard).join('');
}

function setTier(tier, btn) {
    atActiveTier = tier;
    document.querySelectorAll('#tierTabs .at-tab-btn').forEach(function(b) { b.classList.remove('active'); });
    if (btn) btn.classList.add('active');
    renderHotels();
}

renderHotels();

// ── Reveal on scroll (konsisten dgn homepage) ──
function initReveal() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') { setTimeout(initReveal, 100); return; }
    gsap.registerPlugin(ScrollTrigger);
    document.querySelectorAll('.reveal').forEach(function(el) {
        var rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight) return;
        gsap.set(el, { opacity: 0, y: 24 });
        ScrollTrigger.create({
            trigger: el, start: 'top 90%', once: true,
            onEnter: function() { gsap.to(el, { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }); }
        });
    });
}
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initReveal);
else initReveal();

// ── Deep-link anchor scroll offset fix (karena ada sticky navbar) ──
if (window.location.hash) {
    setTimeout(function() {
        var el = document.querySelector(window.location.hash);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 200);
}
</script>
@endpush