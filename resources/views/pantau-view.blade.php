<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pantau View</title>
<style>
  :root{
    --fire:#f97316;
    --fire-deep:#c2410c;
    --gold:#fbbf24;
    --paper:#faf8f5;
    --paper-2:#f2ede6;
    --ink:#1a1007;
    --ink-70:rgba(26,16,7,.70);
    --ink-45:rgba(26,16,7,.45);
    --ink-25:rgba(26,16,7,.25);
    --ink-12:rgba(26,16,7,.10);
    --ink-06:rgba(26,16,7,.05);
    --white:#ffffff;
  }
  * { box-sizing:border-box; margin:0; padding:0; }
  body {
    background:var(--paper);
    color:var(--ink);
    font-family:-apple-system,'Segoe UI',sans-serif;
    padding:28px 16px 60px;
  }
  .wrap { max-width:920px; margin:0 auto; }

  .header { margin-bottom:22px; }
  .eyebrow {
    display:inline-flex; align-items:center; gap:6px;
    padding:4px 12px 4px 8px; border-radius:99px;
    border:1px solid rgba(249,115,22,.25);
    background:rgba(249,115,22,.08);
    font-size:9.5px; font-weight:700; letter-spacing:.14em; text-transform:uppercase;
    color:var(--fire-deep); margin-bottom:10px;
  }
  .eyebrow-dot { width:6px; height:6px; border-radius:50%; background:var(--fire); box-shadow:0 0 6px var(--fire); }
  h1 { font-size:20px; font-weight:800; letter-spacing:-.02em; margin-bottom:4px; }
  .sub { color:var(--ink-45); font-size:12px; line-height:1.5; }

  .summary { display:flex; gap:10px; margin-bottom:22px; flex-wrap:wrap; }
  .sum-card {
    flex:1 1 140px;
    background:var(--white);
    border:1px solid var(--ink-12);
    border-radius:14px;
    padding:14px 18px;
    box-shadow:0 2px 10px rgba(26,16,7,.03);
  }
  .sum-val { font-size:24px; font-weight:800; color:var(--fire-deep); line-height:1; }
  .sum-lbl { font-size:10px; text-transform:uppercase; letter-spacing:.07em; color:var(--ink-45); margin-top:6px; font-weight:600; }

  .card {
    background:var(--white);
    border:1px solid var(--ink-12);
    border-radius:16px;
    padding:0;
    margin-bottom:14px;
    overflow:hidden;
    box-shadow:0 2px 10px rgba(26,16,7,.03);
  }
  .card-top {
    display:flex; justify-content:space-between; align-items:center;
    gap:8px; flex-wrap:wrap;
    padding:14px 18px;
    background:var(--paper-2);
    border-bottom:1px solid var(--ink-12);
  }
  .card-title { font-size:14.5px; font-weight:700; color:var(--ink); }
  .card-slug {
    font-size:9.5px; color:var(--ink-45); font-family:monospace;
    background:var(--ink-06); padding:2px 8px; border-radius:5px;
  }

  .stats-row {
    display:grid;
    grid-template-columns:repeat(4, 1fr);
    gap:10px;
    padding:16px 18px 12px;
  }
  .stat { min-width:0; }
  .stat-val { font-size:17px; font-weight:800; color:var(--ink); line-height:1.1; word-break:break-word; }
  .stat-lbl { font-size:8.5px; text-transform:uppercase; letter-spacing:.05em; color:var(--ink-25); font-weight:700; margin-top:3px; }
  .stat.accent .stat-val { color:var(--fire-deep); }

  .chart-wrap { padding:4px 18px 18px; }
  .chart-label {
    font-size:9px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
    color:var(--ink-25); margin-bottom:8px;
  }
  .chart { display:flex; align-items:flex-end; gap:6px; height:52px; }
  .bar-wrap { flex:1; display:flex; flex-direction:column; align-items:center; gap:5px; height:100%; justify-content:flex-end; }
  .bar {
    width:100%; max-width:26px;
    background:linear-gradient(to top,var(--fire-deep),var(--gold));
    border-radius:4px 4px 0 0;
    min-height:2px;
  }
  .bar-lbl { font-size:8px; color:var(--ink-25); font-weight:600; }

  .empty {
    text-align:center; padding:60px 20px; color:var(--ink-25);
    background:var(--white); border:1px dashed var(--ink-12); border-radius:16px;
    font-size:13px;
  }

  /* ── MOBILE ── */
  @media (max-width:480px){
    body { padding:20px 12px 48px; }
    h1 { font-size:18px; }
    .summary { gap:8px; }
    .sum-card { flex:1 1 45%; padding:12px 14px; }
    .sum-val { font-size:20px; }
    .stats-row { grid-template-columns:repeat(2, 1fr); row-gap:14px; padding:14px 14px 10px; }
    .card-top { padding:12px 14px; }
    .card-title { font-size:13.5px; }
    .chart-wrap { padding:2px 14px 14px; }
    .chart { height:44px; }
    .bar-lbl { font-size:7.5px; }
  }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <div class="eyebrow"><span class="eyebrow-dot"></span>Monitoring</div>
    <h1>Pantau View</h1>
    <p class="sub">Statistik akses halaman Bayan Open 2026 · diperbarui real-time · {{ now('Asia/Makassar')->format('d M Y, H:i') }} WITA</p>
  </div>

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
          <div class="card-title">{{ $page['label'] }}</div>
          <div class="card-slug">{{ $slug }}</div>
        </div>

        <div class="stats-row">
          <div class="stat accent">
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
            <div class="stat-val" style="font-size:12px;color:var(--ink-45);font-weight:600;">
                {{ $page['last_visit'] ? \Carbon\Carbon::parse($page['last_visit'])->diffForHumans() : '—' }}
            </div>
            <div class="stat-lbl">Akses Terakhir</div>
          </div>
        </div>

        <div class="chart-wrap">
          <div class="chart-label">7 Hari Terakhir</div>
          <div class="chart">
            @foreach($last7 as $d)
              <div class="bar-wrap">
                <div class="bar" style="height:{{ max(2, (($days[$d] ?? 0) / $maxDay) * 52) }}px" title="{{ $days[$d] ?? 0 }} akses"></div>
                <span class="bar-lbl">{{ \Carbon\Carbon::parse($d)->format('d/m') }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endforeach
  @endif
</div>
</body>
</html>