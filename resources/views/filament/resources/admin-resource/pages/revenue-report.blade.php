<x-filament-panels::page>

@php
    $paid = fn() => \App\Models\Registration::where('status', 'paid');

    $totalRevenue     = $paid()->sum('harga');
    $revenueRegu      = $paid()->where('kategori', 'beregu')->sum('harga');
    $revenueOpen      = $paid()->where('kategori', '!=', 'beregu')->sum('harga');
    $totalPaid        = $paid()->count();
    $avgOrderValue    = $totalPaid > 0 ? $totalRevenue / $totalPaid : 0;
    $todayRevenue     = $paid()->whereDate('payment_time', today())->sum('harga');
    $thisMonthRevenue = $paid()->whereYear('payment_time', now()->year)->whereMonth('payment_time', now()->month)->sum('harga');
    $pendingRevenue   = \App\Models\Registration::where('status', 'pending')->sum('harga');
@endphp

<style>
    .rr-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .rr-grid.rr-mb { margin-bottom: 16px; }
    .rr-grid.rr-mb2 { margin-bottom: 24px; }

    .rr-card { border-radius: 12px; border: 1px solid; padding: 16px 20px; }
    .rr-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; margin: 0 0 6px; }
    .rr-value { font-size: 22px; font-weight: 800; margin: 0; }

    /* Light mode (default) */
    .rr-blue   { background: #eff6ff; border-color: #bfdbfe; }
    .rr-blue   .rr-label, .rr-blue   .rr-value { color: #1d4ed8; }
    .rr-green  { background: #f0fdf4; border-color: #bbf7d0; }
    .rr-green  .rr-label, .rr-green  .rr-value { color: #15803d; }
    .rr-yellow { background: #fefce8; border-color: #fef08a; }
    .rr-yellow .rr-label, .rr-yellow .rr-value { color: #a16207; }
    .rr-gray   { background: #f9fafb; border-color: #e5e7eb; }
    .rr-gray   .rr-label { color: #6b7280; }
    .rr-gray   .rr-value { color: #111827; }
    .rr-white  { background: #fff; border-color: #e5e7eb; }
    .rr-white  .rr-heading { color: #374151; }

    /* Dark mode — Filament toggles class="dark" on <html> */
    html.dark .rr-blue   { background: rgba(59,130,246,0.10); border-color: rgba(59,130,246,0.30); }
    html.dark .rr-blue   .rr-label, html.dark .rr-blue   .rr-value { color: #60a5fa; }
    html.dark .rr-green  { background: rgba(34,197,94,0.10); border-color: rgba(34,197,94,0.30); }
    html.dark .rr-green  .rr-label, html.dark .rr-green  .rr-value { color: #4ade80; }
    html.dark .rr-yellow { background: rgba(234,179,8,0.10); border-color: rgba(234,179,8,0.30); }
    html.dark .rr-yellow .rr-label, html.dark .rr-yellow .rr-value { color: #facc15; }
    html.dark .rr-gray   { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.10); }
    html.dark .rr-gray   .rr-label { color: #9ca3af; }
    html.dark .rr-gray   .rr-value { color: #f9fafb; }
    html.dark .rr-white  { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.10); }
    html.dark .rr-white  .rr-heading { color: #d1d5db; }

    .rr-heading { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin: 0 0 14px; }

    .rr-subcard { padding: 12px 16px; border-radius: 10px; border: 1px solid; }
    .rr-sub-blue  { background: #eff6ff; border-color: #bfdbfe; }
    .rr-sub-blue  .rr-sub-label { color: #3b82f6; }
    .rr-sub-blue  .rr-sub-value { color: #1d4ed8; }
    .rr-sub-green { background: #f0fdf4; border-color: #bbf7d0; }
    .rr-sub-green .rr-sub-label { color: #10b981; }
    .rr-sub-green .rr-sub-value { color: #15803d; }
    html.dark .rr-sub-blue  { background: rgba(59,130,246,0.10); border-color: rgba(59,130,246,0.30); }
    html.dark .rr-sub-blue  .rr-sub-label, html.dark .rr-sub-blue  .rr-sub-value { color: #60a5fa; }
    html.dark .rr-sub-green { background: rgba(34,197,94,0.10); border-color: rgba(34,197,94,0.30); }
    html.dark .rr-sub-green .rr-sub-label, html.dark .rr-sub-green .rr-sub-value { color: #4ade80; }
    .rr-sub-label { font-size: 11px; font-weight: 600; margin: 0 0 4px; }
    .rr-sub-value { font-size: 18px; font-weight: 800; margin: 0; }

    @media (max-width: 768px) {
        .rr-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- Stats Grid --}}
<div class="rr-grid rr-mb">
    <div class="rr-card rr-blue">
        <p class="rr-label">💰 Total Revenue</p>
        <p class="rr-value">Rp {{ number_format($totalRevenue,0,',','.') }}</p>
    </div>
    <div class="rr-card rr-green">
        <p class="rr-label">✅ Bulan Ini</p>
        <p class="rr-value">Rp {{ number_format($thisMonthRevenue,0,',','.') }}</p>
    </div>
    <div class="rr-card rr-yellow">
        <p class="rr-label">⏳ Pending (Potensi)</p>
        <p class="rr-value">Rp {{ number_format($pendingRevenue,0,',','.') }}</p>
    </div>
</div>

<div class="rr-grid rr-mb2">
    <div class="rr-card rr-gray">
        <p class="rr-label">Hari Ini</p>
        <p class="rr-value">Rp {{ number_format($todayRevenue,0,',','.') }}</p>
    </div>
    <div class="rr-card rr-gray">
        <p class="rr-label">Total Transaksi Paid</p>
        <p class="rr-value">{{ $totalPaid }} peserta</p>
    </div>
    <div class="rr-card rr-gray">
        <p class="rr-label">Rata-rata / Transaksi</p>
        <p class="rr-value">Rp {{ number_format($avgOrderValue,0,',','.') }}</p>
    </div>
</div>

{{-- Breakdown Kategori --}}
<div class="rr-card rr-white" style="margin-bottom: 24px;">
    <p class="rr-heading">📊 Breakdown Kategori</p>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;">
        <div class="rr-subcard rr-sub-blue">
            <p class="rr-sub-label">Beregu</p>
            <p class="rr-sub-value">Rp {{ number_format($revenueRegu,0,',','.') }}</p>
        </div>
        <div class="rr-subcard rr-sub-green">
            <p class="rr-sub-label">Ganda (Open)</p>
            <p class="rr-sub-value">Rp {{ number_format($revenueOpen,0,',','.') }}</p>
        </div>
    </div>
</div>

{{-- Table --}}
{{ $this->table }}

</x-filament-panels::page>