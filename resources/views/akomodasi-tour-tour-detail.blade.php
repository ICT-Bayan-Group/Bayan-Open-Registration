@extends('layouts.app')

@section('title', $tour['title'] . ' — Paket Wisata Bayan Open 2026')

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
.td-page { font-family: var(--font-body); background: var(--paper); color: var(--ink); }

.td-hero { padding: 32px 24px 0; }
.td-hero-inner { max-width: 1080px; margin: 0 auto; }
.td-breadcrumb { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; font-family: var(--font-display); font-size: 11px; font-weight: 700; letter-spacing: 0.04em; }
.td-breadcrumb a { color: var(--ink-30); text-decoration: none; transition: color 0.2s; }
.td-breadcrumb a:hover { color: var(--fire-deep); }
.td-breadcrumb span { color: var(--ink-12); }
.td-breadcrumb .current { color: var(--ink-60); }

.td-section { padding: 40px 24px 90px; }
.td-inner { max-width: 1080px; margin: 0 auto; display: grid; grid-template-columns: 1.6fr 1fr; gap: 36px; align-items: start; }

.dg-wrap { display: flex; flex-direction: column; gap: 8px; }
.dg-main { width: 100%; aspect-ratio: 16/10; border-radius: var(--r-lg); overflow: hidden; background: linear-gradient(160deg, var(--night), var(--night-2)); }
.dg-main img { width: 100%; height: 100%; object-fit: cover; display: block; }
.dg-fallback { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.dg-thumbs { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 2px; }
.dg-thumb { flex-shrink: 0; width: 88px; height: 64px; border-radius: 10px; overflow: hidden; border: 2px solid transparent; padding: 0; cursor: pointer; background: none; opacity: 0.6; transition: all 0.2s; }
.dg-thumb.active, .dg-thumb:hover { opacity: 1; border-color: var(--fire); }
.dg-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

.td-dur-badge { display: inline-flex; align-items: center; gap: 6px; margin-top: 24px; padding: 6px 14px; border-radius: 99px; background: rgba(249,115,22,0.09); border: 1px solid rgba(249,115,22,0.2); font-family: var(--font-display); font-size: 10px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: var(--fire-deep); }
.td-title { font-family: var(--font-display); font-size: clamp(24px,3.4vw,34px); font-weight: 800; letter-spacing: -0.02em; margin-top: 14px; line-height: 1.25; }
.td-desc { font-size: 14px; line-height: 1.85; color: var(--ink-60); margin-top: 20px; }

.td-block-title { font-family: var(--font-display); font-size: 14px; font-weight: 800; margin: 26px 0 12px; }
.td-hl-list { display: flex; flex-direction: column; gap: 10px; }
.td-hl-item { display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; color: var(--ink); }
.td-hl-icon { width: 22px; height: 22px; border-radius: 7px; background: rgba(249,115,22,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
.td-includes-row { display: flex; flex-wrap: wrap; gap: 8px; }
.td-include-pill { font-size: 12px; font-weight: 600; color: var(--ink-60); background: var(--paper-2); border: 1px solid var(--ink-12); padding: 6px 14px; border-radius: 99px; }

.td-map-wrap { border-radius: var(--r-lg); overflow: hidden; border: 1px solid var(--ink-12); margin-top: 8px; }
.td-map-wrap iframe { display: block; width: 100%; height: 320px; border: 0; }

.td-sidebar { position: sticky; top: 90px; }
.td-price-card { background: #fff; border: 1px solid var(--ink-12); border-radius: var(--r-xl); padding: 26px 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
.td-price-label { font-size: 11px; color: var(--ink-30); text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700; }
.td-price-val { font-family: var(--font-display); font-size: 30px; font-weight: 800; color: var(--fire-deep); margin: 6px 0 2px; }
.td-price-per { font-size: 11.5px; color: var(--ink-30); }
.td-wa-btn { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 18px; padding: 14px; border-radius: 13px; background: linear-gradient(135deg,#25d366,#128c4a); text-decoration: none; font-family: var(--font-display); font-size: 11px; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase; color: #fff; box-shadow: 0 8px 22px rgba(37,211,102,0.3); transition: all 0.2s; }
.td-wa-btn:hover { transform: translateY(-1px); }
.td-back-btn { display: block; text-align: center; margin-top: 10px; padding: 13px; border-radius: 13px; border: 1px solid var(--ink-12); background: transparent; text-decoration: none; font-family: var(--font-display); font-size: 11px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--ink-60); transition: all 0.2s; }
.td-back-btn:hover { background: var(--paper-2); }

.td-others { max-width: 1080px; margin: 0 auto; padding: 0 24px 90px; }
.td-others-title { font-family: var(--font-display); font-size: 19px; font-weight: 800; margin-bottom: 18px; }
.td-others-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 14px; }
.td-oth-card { display: block; background: #fff; border: 1px solid var(--ink-12); border-radius: var(--r-lg); overflow: hidden; text-decoration: none; transition: all 0.25s; }
.td-oth-card:hover { transform: translateY(-3px); box-shadow: 0 16px 36px rgba(249,115,22,0.1); border-color: rgba(249,115,22,0.25); }
.td-oth-photo { width: 100%; aspect-ratio: 4/3; background: linear-gradient(160deg,var(--night),var(--night-2)); overflow: hidden; }
.td-oth-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }
.td-oth-body { padding: 14px 16px; }
.td-oth-name { font-size: 13px; font-weight: 700; color: var(--ink); line-height: 1.35; margin-bottom: 8px; }
.td-oth-price { font-family: var(--font-display); font-size: 13.5px; font-weight: 800; color: var(--fire-deep); }

@media (max-width: 860px) {
    .td-inner { grid-template-columns: 1fr; }
    .td-sidebar { position: static; }
}
</style>
@endpush

@section('content')
<div class="td-page">

    <div class="td-hero">
        <div class="td-hero-inner">
            <nav class="td-breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('akomodasi-tour') }}#tour">Akomodasi &amp; Tour</a>
                <span>/</span>
                <span class="current">{{ $tour['title'] }}</span>
            </nav>
        </div>
    </div>

    <section class="td-section">
        <div class="td-inner">
            <div>
                @include('partials.detail-gallery', [
                    'images' => $tour['images'],
                    'alt'    => $tour['title'],
                    'uid'    => 'tour',
                ])

                <span class="td-dur-badge">{{ $tour['duration'] }}</span>
                <h1 class="td-title">{{ $tour['title'] }}</h1>
                <p class="td-desc">{{ $tour['description'] }}</p>

                @if(!empty($tour['highlights']))
                <p class="td-block-title">Highlight Perjalanan</p>
                <div class="td-hl-list">
                    @foreach($tour['highlights'] as $hl)
                    <div class="td-hl-item">
                        <span class="td-hl-icon">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                        </span>
                        {{ $hl }}
                    </div>
                    @endforeach
                </div>
                @endif

                @if(!empty($tour['includes']))
                <p class="td-block-title">Sudah Termasuk</p>
                <div class="td-includes-row">
                    @foreach($tour['includes'] as $inc)
                    <span class="td-include-pill">{{ $inc }}</span>
                    @endforeach
                </div>
                @endif

                <p class="td-block-title">Lokasi</p>
                <div class="td-map-wrap">
                    <iframe
                        src="https://www.google.com/maps?q={{ urlencode($tour['maps_query']) }}&output=embed"
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                </div>
            </div>

            <aside class="td-sidebar">
                <div class="td-price-card">
                   <!-- <p class="td-price-label">Harga Paket</p>
                    <p class="td-price-val">Rp {{ number_format($tour['price'],0,',','.') }}</p>
                    <p class="td-price-per">per orang &middot; minimal {{ $tour['min_person'] }} orang</p>-->

                    @php
                        $tdWaText = rawurlencode("Halo, saya peserta Bayan Open 2026. Saya mau tanya-tanya soal paket {$tour['title']} ({$tour['duration']}). Apakah masih tersedia?");
                    @endphp
                    <a href="https://wa.me/{{ $tour['contact_phone'] }}?text={{ $tdWaText }}"
                       target="_blank" rel="noopener noreferrer" class="td-wa-btn">
                        <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/><path d="M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654A11.882 11.882 0 0012.05 23.79h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.468 3.484 11.815 11.815 0 0012.05 0z"/></svg>
                        Chat via WhatsApp
                    </a>
                    <a href="{{ route('akomodasi-tour') }}#tour" class="td-back-btn">&larr; Lihat Semua Paket Wisata</a>
                </div>
            </aside>
        </div>
    </section>

    @if($others->count())
    <section class="td-others">
        <p class="td-others-title">Paket Wisata Lainnya</p>
        <div class="td-others-grid">
            @foreach($others as $t)
            <a href="{{ route('akomodasi-tour.tour', $t['slug']) }}" class="td-oth-card">
                <div class="td-oth-photo">
                    @include('partials.tour-image-collage', ['images' => $t['images'], 'alt' => $t['title']])
                </div>
                <div class="td-oth-body">
                    <p class="td-oth-name">{{ $t['title'] }}</p>
                   <!-- <p class="td-oth-price">Rp {{ number_format($t['price'],0,',','.') }} /orang</p>-->
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