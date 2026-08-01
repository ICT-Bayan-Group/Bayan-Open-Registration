{{--
    Partial galeri foto untuk halaman detail (hotel/tour). Mendukung 1-5 foto.
    Pakai: @include('partials.detail-gallery', ['images' => $hotel['images'], 'alt' => $hotel['name'], 'uid' => 'hotel', 'fallbackIcon' => $someSvg])
--}}
@php
    $dgImages = array_values(array_filter($images ?? []));
    $dgUid = $uid ?? 'dg';
    $dgAlt = $alt ?? '';
@endphp

<div class="dg-wrap">
    <div class="dg-main">
        @if(count($dgImages))
            <img id="dgMain-{{ $dgUid }}" src="{{ $dgImages[0] }}" alt="{{ $dgAlt }}">
        @else
            <div class="dg-fallback">
                {!! $fallbackIcon ?? '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 11h.01M15 11h.01M9 8h.01M15 8h.01"/></svg>' !!}
            </div>
        @endif
    </div>

    @if(count($dgImages) > 1)
    <div class="dg-thumbs">
        @foreach($dgImages as $i => $img)
        <button type="button"
                class="dg-thumb {{ $i === 0 ? 'active' : '' }}"
                data-target="dgMain-{{ $dgUid }}"
                onclick="dgSwap(this)">
            <img src="{{ $img }}" alt="{{ $dgAlt }} {{ $i + 1 }}">
        </button>
        @endforeach
    </div>
    @endif
</div>