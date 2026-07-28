@props([
    'label',
    'value',
    'mono' => false,
    'weight' => 'normal',
    'color' => 'ktp-row-value', // sekarang berupa nama CSS class, misal: txt-ok / txt-bad / txt-blue / txt-pink
])

@php
    $weightMap = [
        'bold' => '800',
        'semibold' => '700',
        '600' => '600',
        'normal' => '600',
    ];
    $fontWeight = $weightMap[$weight] ?? $weight;
@endphp

<div class="ktp-row">
    <span class="ktp-row-label">{{ $label }}</span>
    <span class="ktp-row-value {{ $mono ? 'mono' : '' }} {{ $color }}" style="font-weight:{{ $fontWeight }};">
        {{ $value }}
    </span>
</div>