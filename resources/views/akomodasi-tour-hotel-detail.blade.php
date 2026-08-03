@extends('layouts.app')

@section('title', $hotel['name'] . ' — Akomodasi Bayan Open 2026')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
:root {
    --fire: #f97316; --fire-deep: #c2410c;
    --night: #0d0906; --night-2: #140c07;
    --paper: #faf8f5; --paper-2: #f2ede6;
    --ink: #1a1007; --ink-60: rgba(26,16,7,0.6); --ink-30: rgba(26,16,7,0.3); --ink-12: rgba(26,16,7,0.1);
    --r-sm: 12px; --r-md: 18px; --r-lg: 24px; --r-xl: 32px;
    --font-display: 'Montserrat', sans-serif; --font-body: 'Montserrat', sans-serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
.hd-page { font-family: var(--font-body); background: var(--paper); color: var(--ink); }

.hd-hero { padding: 32px 24px 0; }
.hd-hero-inner { max-width: 1080px; margin: 0 auto; }
.hd-breadcrumb { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; font-family: var(--font-display); font-size: 11px; font-weight: 700; letter-spacing: 0.04em; }
.hd-breadcrumb a { color: var(--ink-30); text-decoration: none; transition: color 0.2s; }
.hd-breadcrumb a:hover { color: var(--fire-deep); }
.hd-breadcrumb span { color: var(--ink-12); }
.hd-breadcrumb .current { color: var(--ink-60); }

.hd-section { padding: 40px 24px 90px; }
.hd-inner { max-width: 1080px; margin: 0 auto; display: grid; grid-template-columns: 1.6fr 1fr; gap: 36px; align-items: start; }

/* Gallery */
.dg-wrap { display: flex; flex-direction: column; gap: 8px; }
.dg-main { width: 100%; aspect-ratio: 16/10; border-radius: var(--r-lg); overflow: hidden; background: var(--paper-2); border: 1px solid var(--ink-12); }
.dg-main img { width: 100%; height: 100%; object-fit: cover; display: block; }
.dg-fallback { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--paper-2), #ede8df); }
.dg-thumbs { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 2px; }
.dg-thumb { flex-shrink: 0; width: 88px; height: 64px; border-radius: 10px; overflow: hidden; border: 2px solid transparent; padding: 0; cursor: pointer; background: none; opacity: 0.6; transition: all 0.2s; }
.dg-thumb.active, .dg-thumb:hover { opacity: 1; border-color: var(--fire); }
.dg-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

.hd-title-row { margin-top: 26px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.hd-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px 5px 8px; border-radius: 99px; background: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.25); }
.hd-badge-text { font-family: var(--font-display); font-size: 9.5px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: var(--fire-deep); }
.hd-tier-pill { font-family: var(--font-display); font-size: 9.5px; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase; padding: 5px 12px; border-radius: 99px; }
.hd-tier-budget   { background: rgba(16,185,129,0.1); color: #0d9488; }
.hd-tier-standard { background: rgba(59,130,246,0.1); color: #2563eb; }
.hd-tier-premium  { background: rgba(249,115,22,0.1); color: var(--fire-deep); }
.hd-name { font-family: var(--font-display); font-size: clamp(24px,3.4vw,34px); font-weight: 800; letter-spacing: -0.02em; margin-top: 14px; line-height: 1.2; }
.hd-room { font-size: 13px; color: var(--ink-60); margin-top: 6px; }
.hd-desc { font-size: 14px; line-height: 1.85; color: var(--ink-60); margin-top: 22px; }

.hd-map-block { margin-top: 34px; }
.hd-map-title { font-family: var(--font-display); font-size: 15px; font-weight: 800; margin-bottom: 12px; }
.hd-map-wrap { border-radius: var(--r-lg); overflow: hidden; border: 1px solid var(--ink-12); }
.hd-map-wrap iframe { display: block; width: 100%; height: 340px; border: 0; }

.hd-venue-block { margin-top: 30px; }
.hd-venue-title { font-family: var(--font-display); font-size: 15px; font-weight: 800; margin-bottom: 12px; }
.hd-venue-list { display: flex; flex-direction: column; gap: 8px; }
.hd-venue-item {
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    background: #fff; border: 1px solid var(--ink-12); border-radius: 14px;
    padding: 13px 16px;
}
.hd-venue-name-wrap { display: flex; align-items: center; gap: 10px; min-width: 0; }
.hd-venue-icon { width: 32px; height: 32px; border-radius: 9px; background: rgba(249,115,22,0.08); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.hd-venue-name { font-size: 12.5px; font-weight: 700; color: var(--ink); line-height: 1.3; }
.hd-venue-stats { display: flex; align-items: baseline; gap: 6px; flex-shrink: 0; white-space: nowrap; }
.hd-venue-time { font-family: var(--font-display); font-size: 14px; font-weight: 800; color: var(--fire-deep); }
.hd-venue-km { font-size: 11px; color: var(--ink-30); }

/* Sidebar */
.hd-sidebar { position: sticky; top: 90px; }
.hd-price-card { background: #fff; border: 1px solid var(--ink-12); border-radius: var(--r-xl); padding: 26px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
.hd-price-label { font-size: 11px; color: var(--ink-30); text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700; }
.hd-price-val { font-family: var(--font-display); font-size: 30px; font-weight: 800; color: var(--ink); margin: 6px 0 2px; }
.hd-price-per { font-size: 11.5px; color: var(--ink-30); }
.hd-price-note { margin-top: 18px; padding: 14px 16px; background: var(--paper-2); border-radius: 14px; font-size: 12px; line-height: 1.6; color: var(--ink-60); font-weight: 700; }
.hd-back-btn { display: block; text-align: center; margin-top: 14px; padding: 13px; border-radius: 13px; border: 1px solid var(--ink-12); background: transparent; text-decoration: none; font-family: var(--font-display); font-size: 11px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--ink-60); transition: all 0.2s; }
.hd-back-btn:hover { background: var(--paper-2); }
.hd-cta-btn { display: block; text-align: center; margin-top: 10px; padding: 14px; border-radius: 13px; border: none; background: linear-gradient(135deg,var(--fire),var(--fire-deep)); text-decoration: none; font-family: var(--font-display); font-size: 11px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #fff; box-shadow: 0 8px 22px rgba(249,115,22,0.3); transition: all 0.2s; }
.hd-cta-btn:hover { transform: translateY(-1px); }

/* Others */
.hd-others { max-width: 1080px; margin: 0 auto; padding: 0 24px 90px; }
.hd-others-title { font-family: var(--font-display); font-size: 19px; font-weight: 800; margin-bottom: 18px; }
.hd-others-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 14px; }
.hd-oth-card { display: block; background: #fff; border: 1px solid var(--ink-12); border-radius: var(--r-lg); overflow: hidden; text-decoration: none; transition: all 0.25s; }
.hd-oth-card:hover { transform: translateY(-3px); box-shadow: 0 16px 36px rgba(0,0,0,0.08); border-color: rgba(249,115,22,0.2); }
.hd-oth-photo { width: 100%; aspect-ratio: 4/3; background: var(--paper-2); overflow: hidden; }
.hd-oth-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }
.hd-oth-body { padding: 12px 14px; }
.hd-oth-name { font-size: 12.5px; font-weight: 700; color: var(--ink); line-height: 1.3; margin-bottom: 8px; }
.hd-oth-price { font-family: var(--font-display); font-size: 13px; font-weight: 800; }

@media (max-width: 860px) {
    .hd-inner { grid-template-columns: 1fr; }
    .hd-sidebar { position: static; }
}
</style>
@endpush

@section('content')
<div class="hd-page">

    <div class="hd-hero">
        <div class="hd-hero-inner">
            <nav class="hd-breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('akomodasi-tour') }}#hotel">Akomodasi &amp; Tour</a>
                <span>/</span>
                <span class="current">{{ $hotel['name'] }}</span>
            </nav>
        </div>
    </div>

    <section class="hd-section">
        <div class="hd-inner">
            <div>
                @include('partials.detail-gallery', [
                    'images' => $hotel['images'],
                    'alt'    => $hotel['name'],
                    'uid'    => 'hotel',
                ])

                <div class="hd-title-row">
                    @if($hotel['is_official'])
                    <div class="hd-badge">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#c2410c" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                        <span class="hd-badge-text">Hotel Resmi Bayan Open</span>
                    </div>
                    @endif
                    <span class="hd-tier-pill hd-tier-{{ $hotel['tier'] }}">
                        {{ ['budget'=>'Hemat','standard'=>'Standard','premium'=>'Premium'][$hotel['tier']] }}
                    </span>
                </div>

                <h1 class="hd-name">{{ $hotel['name'] }}</h1>
                <p class="hd-room">Tipe kamar {{ $hotel['room_type'] }} &middot; Balikpapan, Kalimantan Timur</p>
                <p class="hd-desc">{{ $hotel['description'] }}</p>

                @if(!empty($hotel['venues']))
                <div class="hd-venue-block">
                    <p class="hd-venue-title">Jarak ke Venue Bayan Open 2026</p>
                    <div class="hd-venue-list">
                        @foreach($hotel['venues'] as $v)
                        <div class="hd-venue-item">
                            <div class="hd-venue-name-wrap">
                                <span class="hd-venue-icon">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                </span>
                                <span class="hd-venue-name">{{ $v['name'] }}</span>
                            </div>
                            @if($v['distance_km'] !== null)
                            <div class="hd-venue-stats">
                                <span class="hd-venue-time">&plusmn;{{ $v['duration_min'] }} menit</span>
                                <span class="hd-venue-km">({{ number_format($v['distance_km'],1) }} km)</span>
                            </div>
                            @else
                            <span class="hd-venue-km">data belum tersedia</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="hd-map-block">
                    <p class="hd-map-title">Lokasi</p>
                    <div class="hd-map-wrap">
                        <iframe
                            src="https://www.google.com/maps?q={{ urlencode($hotel['maps_query']) }}&output=embed"
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <aside class="hd-sidebar">
                <div class="hd-price-card">
                    {{-- <p class="hd-price-label">Mulai dari</p>
                    <p class="hd-price-val">Rp {{ number_format($hotel['rate'],0,',','.') }}</p>
                    <p class="hd-price-per">per malam &middot; tipe {{ $hotel['room_type'] }}</p> --}}
                    <div class="hd-price-note">
                        Reservasi &amp; pembayaran dilakukan langsung ke pihak hotel dan aplikasi online, bukan melalui panitia Bayan Open. Harga dapat berubah sewaktu-waktu, silakan konfirmasi ketersediaan kamar langsung ke pihak hotel dan melalui aplikasi online.
                    </div>
                    <!--<a href="{{ route('home') }}#kategori" class="hd-cta-btn">Daftar Bayan Open 2026</a>-->
                    <a href="{{ route('akomodasi-tour') }}#hotel" class="hd-back-btn">&larr; Lihat Semua Hotel</a>
                </div>
            </aside>
        </div>
    </section>

    @if($others->count())
    <section class="hd-others">
        <p class="hd-others-title">Hotel Lainnya</p>
        <div class="hd-others-grid">
            @foreach($others as $h)
            <a href="{{ route('akomodasi-tour.hotel', $h['slug']) }}" class="hd-oth-card">
                <div class="hd-oth-photo">
                    @if(!empty($h['images'][0]))
                    <img src="{{ $h['images'][0] }}" alt="{{ $h['name'] }}" loading="lazy">
                    @endif
                </div>
                <div class="hd-oth-body">
                    <p class="hd-oth-name">{{ $h['name'] }}</p>
                    {{-- <p class="hd-oth-price">Rp {{ number_format($h['rate'],0,',','.') }}</p> --}}
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection

@push('scripts')
<script>
function dgSwap(btn) {
    var targetId = btn.getAttribute('data-target');
    var img = btn.querySelector('img');
    var main = document.getElementById(targetId);
    if (main && img) main.src = img.src;
    var group = btn.parentElement;
    group.querySelectorAll('.dg-thumb').forEach(function (b) { b.classList.remove('active'); });
    btn.classList.add('active');
}
</script>
@endpush