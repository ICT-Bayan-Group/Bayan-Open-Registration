{{--
    Partial kolase foto untuk kartu tour.
    Pakai: @include('partials.tour-image-collage', ['images' => $t['images'], 'alt' => $t['title']])
    Otomatis render 1, 2, atau 3 foto tergantung berapa banyak yang tersedia.
--}}
@php
    $collageImgs = array_values(array_filter($images ?? []));
    $collageImgs = array_slice($collageImgs, 0, 3);
    $collageCount = count($collageImgs);
    $collageAlt = $alt ?? '';
@endphp

@if($collageCount === 1)
    <img src="{{ $collageImgs[0] }}" alt="{{ $collageAlt }}" loading="lazy" decoding="async"
         style="width:100%;height:100%;object-fit:cover;display:block;">

@elseif($collageCount === 2)
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2px;width:100%;height:100%;">
        @foreach($collageImgs as $img)
        <img src="{{ $img }}" alt="{{ $collageAlt }}" loading="lazy" decoding="async"
             style="width:100%;height:100%;object-fit:cover;display:block;">
        @endforeach
    </div>

@elseif($collageCount >= 3)
    <div style="display:grid;grid-template-columns:1.2fr 1fr;grid-template-rows:1fr 1fr;gap:2px;width:100%;height:100%;">
        <img src="{{ $collageImgs[0] }}" alt="{{ $collageAlt }}" loading="lazy" decoding="async"
             style="grid-row:1 / 3;width:100%;height:100%;object-fit:cover;display:block;">
        <img src="{{ $collageImgs[1] }}" alt="{{ $collageAlt }}" loading="lazy" decoding="async"
             style="width:100%;height:100%;object-fit:cover;display:block;">
        <img src="{{ $collageImgs[2] }}" alt="{{ $collageAlt }}" loading="lazy" decoding="async"
             style="width:100%;height:100%;object-fit:cover;display:block;">
    </div>
@endif