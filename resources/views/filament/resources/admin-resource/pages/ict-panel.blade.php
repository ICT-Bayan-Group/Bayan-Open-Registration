<x-filament-panels::page>

{{-- ═══════════════════════════════════════════════════════════
     HEADER BANNER
═══════════════════════════════════════════════════════════ --}}
<div style="margin-bottom:1.5rem;border-radius:12px;border:1px solid #fed7aa;background:#fff7ed;padding:16px 20px;">
    <p style="font-size:13px;font-weight:700;color:#c2410c;margin:0 0 4px;">
        🔒 ICT Panel — Full Access
    </p>
    <p style="font-size:12px;color:#ea580c;margin:0;">
        Halaman ini tidak ditampilkan di sidebar. Hanya dapat diakses melalui URL langsung.
        Semua perubahan bersifat <strong>permanen</strong>.
    </p>
</div>

@php
    $total   = \App\Models\Registration::count();
    $paid    = \App\Models\Registration::where('status','paid')->count();
    $pending = \App\Models\Registration::where('status','pending')->count();
    $expired = \App\Models\Registration::where('status','expired')->count();
    $failed  = \App\Models\Registration::where('status','failed')->count();

    $revenue = \App\Models\Registration::where('status','paid')->sum('harga');

    // Per kategori
    $statKat = \App\Models\Registration::selectRaw('kategori, count(*) as total, sum(case when status="paid" then 1 else 0 end) as paid')
        ->groupBy('kategori')->get()->keyBy('kategori');

    $katMap = [
        'ganda-dewasa-putra'  => ['label'=>'Dewasa Putra',  'color'=>'#3b82f6'],
        'ganda-dewasa-putri'  => ['label'=>'Dewasa Putri',  'color'=>'#ec4899'],
        'ganda-veteran-putra' => ['label'=>'Veteran Putra', 'color'=>'#f59e0b'],
        'beregu'              => ['label'=>'Beregu',        'color'=>'#10b981'],
    ];

    // Registrasi hari ini
    $today = \App\Models\Registration::whereDate('created_at', today())->count();

    // Revenue hari ini
    $revenueToday = \App\Models\Registration::where('status','paid')
        ->whereDate('payment_time', today())->sum('harga');
@endphp

{{-- ═══════════════════════════════════════════════════════════
     STATS UTAMA
═══════════════════════════════════════════════════════════ --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;">

    {{-- Total --}}
    <div style="border-radius:12px;border:1px solid #e5e7eb;background:#ffffff;padding:16px 20px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <p style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin:0 0 6px;">Total Peserta</p>
        <p style="font-size:32px;font-weight:800;color:#111827;margin:0;line-height:1;">{{ $total }}</p>
        <p style="font-size:11px;color:#9ca3af;margin:6px 0 0;">+{{ $today }} hari ini</p>
    </div>

    {{-- Paid --}}
    <div style="border-radius:12px;border:1px solid #bbf7d0;background:#f0fdf4;padding:16px 20px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <p style="font-size:11px;font-weight:600;color:#15803d;text-transform:uppercase;letter-spacing:.05em;margin:0 0 6px;">✅ Paid</p>
        <p style="font-size:32px;font-weight:800;color:#15803d;margin:0;line-height:1;">{{ $paid }}</p>
        <p style="font-size:11px;color:#16a34a;margin:6px 0 0;">
            {{ $total > 0 ? round($paid/$total*100) : 0 }}% dari total
        </p>
    </div>

    {{-- Revenue --}}
    <div style="border-radius:12px;border:1px solid #bfdbfe;background:#eff6ff;padding:16px 20px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <p style="font-size:11px;font-weight:600;color:#1d4ed8;text-transform:uppercase;letter-spacing:.05em;margin:0 0 6px;">💰 Total Revenue</p>
        <p style="font-size:22px;font-weight:800;color:#1d4ed8;margin:0;line-height:1;">Rp {{ number_format($revenue,0,',','.') }}</p>
        <p style="font-size:11px;color:#2563eb;margin:6px 0 0;">+Rp {{ number_format($revenueToday,0,',','.') }} hari ini</p>
    </div>

</div>

{{-- Row 2: Pending / Expired / Failed --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px;">

    <div style="border-radius:12px;border:1px solid #fef08a;background:#fefce8;padding:14px 20px;">
        <p style="font-size:11px;font-weight:600;color:#a16207;text-transform:uppercase;letter-spacing:.05em;margin:0 0 4px;">⏳ Pending</p>
        <p style="font-size:28px;font-weight:800;color:#a16207;margin:0;">{{ $pending }}</p>
    </div>

    <div style="border-radius:12px;border:1px solid #fecaca;background:#fef2f2;padding:14px 20px;">
        <p style="font-size:11px;font-weight:600;color:#dc2626;text-transform:uppercase;letter-spacing:.05em;margin:0 0 4px;">❌ Failed</p>
        <p style="font-size:28px;font-weight:800;color:#dc2626;margin:0;">{{ $failed }}</p>
    </div>

    <div style="border-radius:12px;border:1px solid #e5e7eb;background:#f9fafb;padding:14px 20px;">
        <p style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin:0 0 4px;">🕐 Expired</p>
        <p style="font-size:28px;font-weight:800;color:#6b7280;margin:0;">{{ $expired }}</p>
    </div>

</div>

{{-- ═══════════════════════════════════════════════════════════
     STATS PER KATEGORI
═══════════════════════════════════════════════════════════ --}}
<div style="border-radius:12px;border:1px solid #e5e7eb;background:#ffffff;padding:16px 20px;margin-bottom:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
    <p style="font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;margin:0 0 14px;">📊 Breakdown Per Kategori</p>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
        @foreach($katMap as $key => $meta)
            @php $k = $statKat[$key] ?? null; @endphp
            <div style="border-radius:10px;border:1px solid #f3f4f6;background:#f9fafb;padding:12px 14px;text-align:center;">
                <div style="width:10px;height:10px;border-radius:50%;background:{{ $meta['color'] }};margin:0 auto 6px;"></div>
                <p style="font-size:11px;font-weight:600;color:#6b7280;margin:0 0 4px;">{{ $meta['label'] }}</p>
                <p style="font-size:22px;font-weight:800;color:#111827;margin:0;line-height:1;">{{ $k?->total ?? 0 }}</p>
                <p style="font-size:11px;color:#10b981;margin:4px 0 0;font-weight:600;">{{ $k?->paid ?? 0 }} paid</p>
            </div>
        @endforeach
    </div>

    {{-- Progress bar total paid --}}
    @if($total > 0)
    <div style="margin-top:16px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
            <span style="font-size:11px;color:#6b7280;">Konversi Paid</span>
            <span style="font-size:11px;font-weight:700;color:#15803d;">{{ round($paid/$total*100) }}%</span>
        </div>
        <div style="height:8px;background:#e5e7eb;border-radius:99px;overflow:hidden;">
            <div style="height:100%;width:{{ round($paid/$total*100) }}%;background:#22c55e;border-radius:99px;transition:width .3s;"></div>
        </div>
    </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════════
     TABLE
═══════════════════════════════════════════════════════════ --}}
<div style="border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;">
    {{ $this->table }}
</div>

</x-filament-panels::page>