@props(['label', 'value', 'color' => '#374151', 'weight' => 'normal', 'mono' => false])

<div style="display:flex;gap:12px;padding:6px 0;border-bottom:1px solid #e5e7eb;">
    <span style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;min-width:90px;flex-shrink:0;">{{ $label }}</span>
    <span style="font-size:12px;color:{{ $color }};font-weight:{{ $weight }};{{ $mono ? 'font-family:monospace;' : '' }}">{{ $value }}</span>
</div>