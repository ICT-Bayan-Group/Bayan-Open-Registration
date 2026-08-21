<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pantau View</title>
<style>
  * { box-sizing:border-box; margin:0; padding:0; }
  body { background:#0d0906; color:#f5f0eb; font-family:-apple-system,'Segoe UI',sans-serif; padding:32px 20px; }
  .wrap { max-width:920px; margin:0 auto; }
  h1 { font-size:20px; margin-bottom:4px; }
  .sub { color:rgba(255,255,255,.4); font-size:12px; margin-bottom:28px; }
  .summary { display:flex; gap:12px; margin-bottom:28px; flex-wrap:wrap; }
  .sum-card { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08); border-radius:12px; padding:14px 20px; }
  .sum-val { font-size:24px; font-weight:800; color:#f97316; }
  .sum-lbl { font-size:10px; text-transform:uppercase; letter-spacing:.08em; color:rgba(255,255,255,.35); margin-top:2px; }
  .card { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.08); border-radius:14px; padding:18px 20px; margin-bottom:14px; }
  .card-top { display:flex; justify-content:space-between; align-items:baseline; margin-bottom:10px; flex-wrap:wrap; gap:6px; }
  .card-title { font-size:15px; font-weight:700; }
  .card-slug { font-size:10px; color:rgba(255,255,255,.3); font-family:monospace; }
  .stats-row { display:flex; gap:18px; margin-bottom:14px; flex-wrap:wrap; }
  .stat { }
  .stat-val { font-size:18px; font-weight:800; color:#fbbf24; }
  .stat-lbl { font-size:9px; text-transform:uppercase; color:rgba(255,255,255,.3); }
  .chart { display:flex; align-items:flex-end; gap:4px; height:50px; }
  .bar-wrap { flex:1; display:flex; flex-direction:column; align-items:center; gap:4px; }
  .bar { width:100%; background:linear-gradient(to top,#f97316,#fbbf24); border-radius:3px 3px 0 0; min-height:2px; }
  .bar-lbl { font-size:8px; color:rgba(255,255,255,.25); }
  .empty { text-align:center; padding:60px 20px; color:rgba(255,255,255,.3); }
</style>
</head>
<body>
<div class="wrap">
  <h1>Pantau View</h1>
  <p class="sub">Statistik akses halaman Bayan Open 2026 · diperbarui real-time · {{ now('Asia/Makassar')->format('d M Y, H:i') }} WITA</p>

  @if(count($data) === 0)
    <div class="empty">Belum ada data akses tercatat.</div>
  @else
    <div class="summary">
      <div class="sum-card">
        <div class="sum-val">{{ array_sum(array_column($data, 'total')) }}</div>
        <div class="sum-lbl">Total Akses Semua Page</div>
      </div>
      <div class="sum-card">
        <div class="sum-val">{{ count($data) }}</div>
        <div class="sum-lbl">Page Terpantau</div>
      </div>
    </div>

    @foreach($data as $slug => $page)
      @php
        $days = $page['days'] ?? [];
        $maxDay = collect($last7)->max(fn($d) => $days[$d] ?? 0) ?: 1;
        $todayKey = now('Asia/Makassar')->format('Y-m-d');
      @endphp
      <div class="card">
        <div class="card-top">
          <div>
            <div class="card-title">{{ $page['label'] }}</div>
            <div class="card-slug">{{ $slug }}</div>
          </div>
        </div>
        <div class="stats-row">
          <div class="stat">
            <div class="stat-val">{{ $page['total'] }}</div>
            <div class="stat-lbl">Total Akses</div>
          </div>
          <div class="stat">
            <div class="stat-val">{{ $days[$todayKey] ?? 0 }}</div>
            <div class="stat-lbl">Hari Ini</div>
          </div>
          <div class="stat">
            <div class="stat-val">{{ array_sum(array_intersect_key($days, array_flip($last7->all()))) }}</div>
            <div class="stat-lbl">7 Hari Terakhir</div>
          </div>
          <div class="stat">
            <div class="stat-val" style="font-size:11px;color:rgba(255,255,255,.5)">
                {{ $page['last_visit'] ? \Carbon\Carbon::parse($page['last_visit'])->diffForHumans() : '—' }}
            </div>
            <div class="stat-lbl">Akses Terakhir</div>
          </div>
        </div>
        <div class="chart">
          @foreach($last7 as $d)
            <div class="bar-wrap">
              <div class="bar" style="height:{{ max(2, (($days[$d] ?? 0) / $maxDay) * 50) }}px" title="{{ $days[$d] ?? 0 }} akses"></div>
              <span class="bar-lbl">{{ \Carbon\Carbon::parse($d)->format('d/m') }}</span>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  @endif
</div>
</body>
</html>