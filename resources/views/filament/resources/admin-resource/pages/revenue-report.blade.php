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

{{-- Stats Grid --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">

    <div style="border-radius:12px;border:1px solid #bfdbfe;background:#eff6ff;padding:16px 20px;">
        <p style="font-size:11px;font-weight:600;color:#1d4ed8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 6px;">💰 Total Revenue</p>
        <p style="font-size:22px;font-weight:800;color:#1d4ed8;margin:0;">Rp {{ number_format($totalRevenue,0,',','.') }}</p>
    </div>

    <div style="border-radius:12px;border:1px solid #bbf7d0;background:#f0fdf4;padding:16px 20px;">
        <p style="font-size:11px;font-weight:600;color:#15803d;text-transform:uppercase;letter-spacing:.05em;margin:0 0 6px;">✅ Bulan Ini</p>
        <p style="font-size:22px;font-weight:800;color:#15803d;margin:0;">Rp {{ number_format($thisMonthRevenue,0,',','.') }}</p>
    </div>

    <div style="border-radius:12px;border:1px solid #fef08a;background:#fefce8;padding:16px 20px;">
        <p style="font-size:11px;font-weight:600;color:#a16207;text-transform:uppercase;letter-spacing:.05em;margin:0 0 6px;">⏳ Pending (Potensi)</p>
        <p style="font-size:22px;font-weight:800;color:#a16207;margin:0;">Rp {{ number_format($pendingRevenue,0,',','.') }}</p>
    </div>

</div>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px;">

    <div style="border-radius:12px;border:1px solid #e5e7eb;background:#f9fafb;padding:14px 20px;">
        <p style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin:0 0 4px;">Hari Ini</p>
        <p style="font-size:20px;font-weight:800;color:#111827;margin:0;">Rp {{ number_format($todayRevenue,0,',','.') }}</p>
    </div>

    <div style="border-radius:12px;border:1px solid #e5e7eb;background:#f9fafb;padding:14px 20px;">
        <p style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin:0 0 4px;">Total Transaksi Paid</p>
        <p style="font-size:20px;font-weight:800;color:#111827;margin:0;">{{ $totalPaid }} peserta</p>
    </div>

    <div style="border-radius:12px;border:1px solid #e5e7eb;background:#f9fafb;padding:14px 20px;">
        <p style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin:0 0 4px;">Rata-rata / Transaksi</p>
        <p style="font-size:20px;font-weight:800;color:#111827;margin:0;">Rp {{ number_format($avgOrderValue,0,',','.') }}</p>
    </div>

</div>

{{-- Breakdown Kategori --}}
<div style="border-radius:12px;border:1px solid #e5e7eb;background:#fff;padding:16px 20px;margin-bottom:24px;">
    <p style="font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;margin:0 0 14px;">📊 Breakdown Kategori</p>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;">
        <div style="padding:12px 16px;border-radius:10px;background:#eff6ff;border:1px solid #bfdbfe;">
            <p style="font-size:11px;color:#3b82f6;font-weight:600;margin:0 0 4px;">Beregu</p>
            <p style="font-size:18px;font-weight:800;color:#1d4ed8;margin:0;">Rp {{ number_format($revenueRegu,0,',','.') }}</p>
        </div>
        <div style="padding:12px 16px;border-radius:10px;background:#f0fdf4;border:1px solid #bbf7d0;">
            <p style="font-size:11px;color:#10b981;font-weight:600;margin:0 0 4px;">Ganda (Open)</p>
            <p style="font-size:18px;font-weight:800;color:#15803d;margin:0;">Rp {{ number_format($revenueOpen,0,',','.') }}</p>
        </div>
    </div>
</div>

{{-- Table --}}
{{ $this->table }}

</x-filament-panels::page>