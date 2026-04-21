<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan · Bayan Open 2026</title>
    <meta name="robots" content="noindex">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
    :root {
        --fire:      #f97316;
        --fire-deep: #c2410c;
        --gold:      #fbbf24;
        --night:     #0d0906;
        --font-display: 'Montserrat', sans-serif;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body {
        height: 100%;
        font-family: var(--font-display);
        background: var(--night);
        color: #fff;
        overflow-x: hidden;
    }

    /* ── Page wrapper ── */
    .page-404 {
        min-height: 100svh;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        position: relative; overflow: hidden;
        padding: 60px 24px;
    }

    /* ── Background ── */
    .bg-grain {
        position:absolute;inset:0;z-index:0;pointer-events:none;
        background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    }
    .bg-radial {
        position:absolute;inset:0;z-index:0;pointer-events:none;
        background:
            radial-gradient(ellipse 80% 60% at 50% 20%,rgba(249,115,22,0.12) 0%,transparent 65%),
            radial-gradient(ellipse 60% 40% at 20% 80%,rgba(251,191,36,0.06) 0%,transparent 60%),
            radial-gradient(ellipse 50% 30% at 80% 70%,rgba(249,115,22,0.05) 0%,transparent 55%);
    }
    .scanlines {
        position:absolute;inset:0;z-index:0;pointer-events:none;
        background:repeating-linear-gradient(to bottom,transparent 0px,transparent 3px,rgba(255,255,255,0.012) 3px,rgba(255,255,255,0.012) 4px);
    }

    /* ── Orbs ── */
    .orb { position:absolute;border-radius:50%;pointer-events:none;z-index:0;will-change:transform; }
    .orb-1 { width:500px;height:500px;top:-160px;left:-160px;background:radial-gradient(circle,rgba(249,115,22,0.10) 0%,transparent 70%);animation:orb-a 14s ease-in-out infinite alternate; }
    .orb-2 { width:360px;height:360px;bottom:-100px;right:-100px;background:radial-gradient(circle,rgba(251,191,36,0.08) 0%,transparent 70%);animation:orb-b 18s ease-in-out infinite alternate; }
    .orb-3 { width:220px;height:220px;top:40%;left:5%;background:radial-gradient(circle,rgba(249,115,22,0.06) 0%,transparent 70%);animation:orb-a 10s ease-in-out infinite alternate-reverse; }
    @keyframes orb-a { from{transform:translate(0,0)scale(1)} to{transform:translate(28px,-22px)scale(1.1)} }
    @keyframes orb-b { from{transform:translate(0,0)scale(1)} to{transform:translate(-22px,18px)scale(1.08)} }

    /* ── Deco rings ── */
    .deco-shuttle { position:absolute;pointer-events:none;z-index:0;opacity:0.04;animation:shuttle-spin 40s linear infinite; }
    .deco-shuttle-1 { width:480px;height:480px;top:-120px;right:-120px; }
    .deco-shuttle-2 { width:320px;height:320px;bottom:-80px;left:-80px;animation-direction:reverse;animation-duration:55s; }
    @keyframes shuttle-spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }

    /* ── Content ── */
    .content {
        position:relative;z-index:2;
        display:flex;flex-direction:column;
        align-items:center;text-align:center;
        max-width:640px;width:100%;
    }

    /* Eyebrow */
    .eyebrow-404 {
        display:inline-flex;align-items:center;gap:8px;
        padding:5px 16px 5px 8px;border-radius:99px;
        background:rgba(249,115,22,0.10);border:1px solid rgba(249,115,22,0.25);
        backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
        margin-bottom:32px;
        animation:fade-up 0.6s cubic-bezier(0.22,1,0.36,1) 0.1s both;
    }
    .eyebrow-dot {
        width:7px;height:7px;border-radius:50%;
        background:var(--fire);box-shadow:0 0 8px rgba(249,115,22,0.9);
        animation:blink-dot 2.4s ease infinite;
    }
    @keyframes blink-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(0.7)} }
    .eyebrow-text { font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:rgba(255,255,255,0.85); }

    /* 404 number */
    .num-wrap { position:relative;animation:fade-up 0.75s cubic-bezier(0.22,1,0.36,1) 0.2s both;margin-bottom:8px; }
    .num-404 {
        font-size:clamp(110px,22vw,200px);font-weight:900;letter-spacing:-0.06em;line-height:1;
        background:linear-gradient(135deg,rgba(255,255,255,0.90) 0%,rgba(249,115,22,0.75) 50%,rgba(194,65,12,0.55) 100%);
        -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
        user-select:none;filter:drop-shadow(0 0 48px rgba(249,115,22,0.20));
    }
    .num-404-glitch {
        position:absolute;inset:0;
        font-size:clamp(110px,22vw,200px);font-weight:900;letter-spacing:-0.06em;line-height:1;
        user-select:none;pointer-events:none;
    }
    .num-404-glitch.g1 { color:rgba(249,115,22,0.18);clip-path:inset(30% 0 40% 0);animation:glitch-1 5s steps(2) infinite; }
    .num-404-glitch.g2 { color:rgba(251,191,36,0.12);clip-path:inset(55% 0 15% 0);animation:glitch-2 7s steps(2) infinite 1.5s; }
    @keyframes glitch-1 {
        0%,90%{transform:translateX(0);clip-path:inset(30% 0 40% 0)}
        92%{transform:translateX(-6px);clip-path:inset(25% 0 42% 0)}
        94%{transform:translateX(5px);clip-path:inset(32% 0 38% 0)}
        96%,100%{transform:translateX(0);clip-path:inset(30% 0 40% 0)}
    }
    @keyframes glitch-2 {
        0%,88%{transform:translateX(0);clip-path:inset(55% 0 15% 0)}
        91%{transform:translateX(8px);clip-path:inset(50% 0 20% 0)}
        93%{transform:translateX(-5px);clip-path:inset(58% 0 12% 0)}
        96%,100%{transform:translateX(0);clip-path:inset(55% 0 15% 0)}
    }

    /* Shuttlecock */
    .shuttle-icon-wrap {
        position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
        animation:shuttle-float 4s ease-in-out infinite;
        filter:drop-shadow(0 0 24px rgba(249,115,22,0.5));pointer-events:none;
    }
    @keyframes shuttle-float {
        0%,100%{transform:translate(-50%,-50%) translateY(0) rotate(-8deg)}
        50%{transform:translate(-50%,-50%) translateY(-12px) rotate(4deg)}
    }

    /* Title */
    .title-404 {
        font-size:clamp(22px,4vw,34px);font-weight:800;letter-spacing:-0.03em;line-height:1.2;
        color:#fff;margin-bottom:14px;
        animation:fade-up 0.7s cubic-bezier(0.22,1,0.36,1) 0.32s both;
    }
    .title-404 em { font-style:normal;color:var(--fire); }

    /* Sub */
    .sub-404 {
        font-size:14px;line-height:1.75;color:rgba(255,255,255,0.42);
        max-width:380px;margin-bottom:40px;
        animation:fade-up 0.65s cubic-bezier(0.22,1,0.36,1) 0.42s both;
    }

    /* CTA */
    .cta-row-404 {
        display:flex;gap:10px;flex-wrap:wrap;justify-content:center;
        animation:fade-up 0.6s cubic-bezier(0.22,1,0.36,1) 0.52s both;
        margin-bottom:56px;
    }
    .btn-fire {
        display:inline-flex;align-items:center;gap:9px;
        font-family:var(--font-display);font-size:10.5px;font-weight:700;
        letter-spacing:0.12em;text-transform:uppercase;
        color:#fff;text-decoration:none;
        background:linear-gradient(135deg,var(--fire) 0%,var(--fire-deep) 100%);
        padding:14px 30px;border-radius:14px;border:none;cursor:pointer;
        box-shadow:0 0 0 1px rgba(249,115,22,0.4),0 8px 28px rgba(249,115,22,0.45),inset 0 1px 0 rgba(255,255,255,0.18);
        transition:all 0.3s cubic-bezier(0.22,1,0.36,1);
    }
    .btn-fire:hover { transform:translateY(-2px);box-shadow:0 0 0 1px rgba(249,115,22,0.5),0 16px 44px rgba(249,115,22,0.6),inset 0 1px 0 rgba(255,255,255,0.22); }
    .btn-glass {
        display:inline-flex;align-items:center;gap:8px;
        font-family:var(--font-display);font-size:10.5px;font-weight:600;
        letter-spacing:0.10em;text-transform:uppercase;
        color:rgba(255,255,255,0.7);text-decoration:none;
        background:rgba(255,255,255,0.07);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
        padding:14px 26px;border-radius:14px;border:1px solid rgba(255,255,255,0.15);cursor:pointer;
        transition:all 0.25s ease;
    }
    .btn-glass:hover { background:rgba(255,255,255,0.14);border-color:rgba(255,255,255,0.28);color:#fff;transform:translateY(-1px); }

    /* Divider */
    .line-divider {
        width:100%;height:1px;
        background:linear-gradient(90deg,transparent,rgba(255,255,255,0.08) 20%,rgba(249,115,22,0.15) 50%,rgba(255,255,255,0.08) 80%,transparent);
        margin:0 0 36px;
        animation:fade-up 0.5s ease 0.55s both;
    }

    /* Quick links */
    .quick-links { animation:fade-up 0.55s cubic-bezier(0.22,1,0.36,1) 0.62s both; }
    .quick-label { font-size:9px;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;color:rgba(255,255,255,0.2);margin-bottom:16px; }
    .quick-grid { display:flex;gap:10px;flex-wrap:wrap;justify-content:center; }
    .quick-chip {
        display:inline-flex;align-items:center;gap:7px;
        padding:8px 16px;border-radius:99px;
        border:1px solid rgba(255,255,255,0.10);background:rgba(255,255,255,0.04);
        color:rgba(255,255,255,0.5);text-decoration:none;
        font-family:var(--font-display);font-size:11px;font-weight:600;
        transition:all 0.22s ease;
    }
    .quick-chip:hover { border-color:rgba(249,115,22,0.35);background:rgba(249,115,22,0.08);color:rgba(255,255,255,0.85);transform:translateY(-1px); }
    .quick-chip svg { flex-shrink:0;opacity:0.5;transition:opacity 0.2s; }
    .quick-chip:hover svg { opacity:1; }

    /* Brand */
    .brand-footer { animation:fade-up 0.5s ease 0.72s both;margin-top:36px; }
    .brand-footer img { height:36px;width:auto;opacity:0.3;filter:brightness(0) invert(1);transition:opacity 0.25s; }
    .brand-footer img:hover { opacity:0.6; }

    @keyframes fade-up { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

    @media (max-width:640px) {
        .num-404,.num-404-glitch { font-size:clamp(88px,26vw,130px); }
        .cta-row-404 { flex-direction:column;align-items:stretch; }
        .btn-fire,.btn-glass { justify-content:center; }
    }
    @media (prefers-reduced-motion:reduce) {
        .orb,.shuttle-icon-wrap,.eyebrow-dot,.num-404-glitch,.deco-shuttle { animation:none !important; }
    }
    </style>
</head>
<body>

<main class="page-404">

    <div class="bg-grain"  aria-hidden="true"></div>
    <div class="bg-radial" aria-hidden="true"></div>
    <div class="scanlines" aria-hidden="true"></div>
    <div class="orb orb-1" aria-hidden="true"></div>
    <div class="orb orb-2" aria-hidden="true"></div>
    <div class="orb orb-3" aria-hidden="true"></div>

    <svg class="deco-shuttle deco-shuttle-1" aria-hidden="true" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="100" cy="100" r="95" stroke="white" stroke-width="2"/>
        <circle cx="100" cy="100" r="60" stroke="white" stroke-width="1.5"/>
        <line x1="100" y1="5"   x2="100" y2="195" stroke="white" stroke-width="1"/>
        <line x1="5"   y1="100" x2="195" y2="100" stroke="white" stroke-width="1"/>
        <line x1="30"  y1="30"  x2="170" y2="170" stroke="white" stroke-width="1"/>
        <line x1="170" y1="30"  x2="30"  y2="170" stroke="white" stroke-width="1"/>
    </svg>
    <svg class="deco-shuttle deco-shuttle-2" aria-hidden="true" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="100" cy="100" r="95" stroke="white" stroke-width="2.5"/>
        <circle cx="100" cy="100" r="55" stroke="white" stroke-width="1.5"/>
        <line x1="100" y1="5"   x2="100" y2="195" stroke="white" stroke-width="1.5"/>
        <line x1="5"   y1="100" x2="195" y2="100" stroke="white" stroke-width="1.5"/>
    </svg>

    <div class="content">

        <div class="eyebrow-404">
            <div class="eyebrow-dot"></div>
            <span class="eyebrow-text">Bayan Open 2026 · Error 404</span>
        </div>

        <div class="num-wrap" aria-label="404">
            <div class="num-404" aria-hidden="true">404</div>
            <div class="num-404-glitch g1" aria-hidden="true">404</div>
            <div class="num-404-glitch g2" aria-hidden="true">404</div>
            <div class="shuttle-icon-wrap" aria-hidden="true">
                <svg width="56" height="72" viewBox="0 0 56 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="28" cy="62" rx="10" ry="8" fill="rgba(249,115,22,0.9)" stroke="rgba(249,115,22,0.5)" stroke-width="1.5"/>
                    <ellipse cx="28" cy="60" rx="8"  ry="6"  fill="rgba(255,200,130,0.8)"/>
                    <line x1="28" y1="55" x2="10" y2="8"  stroke="rgba(255,255,255,0.6)" stroke-width="1"/>
                    <line x1="28" y1="55" x2="18" y2="5"  stroke="rgba(255,255,255,0.6)" stroke-width="1"/>
                    <line x1="28" y1="55" x2="26" y2="4"  stroke="rgba(255,255,255,0.7)" stroke-width="1"/>
                    <line x1="28" y1="55" x2="34" y2="4"  stroke="rgba(255,255,255,0.7)" stroke-width="1"/>
                    <line x1="28" y1="55" x2="40" y2="6"  stroke="rgba(255,255,255,0.6)" stroke-width="1"/>
                    <line x1="28" y1="55" x2="46" y2="10" stroke="rgba(255,255,255,0.5)" stroke-width="1"/>
                    <path d="M10 8 Q16 2 22 4 Q28 6 34 4 Q40 2 46 10" stroke="rgba(255,255,255,0.5)" stroke-width="1.2" fill="none"/>
                    <circle cx="28" cy="55" r="3" fill="rgba(249,115,22,0.7)" stroke="rgba(249,115,22,0.4)" stroke-width="1"/>
                </svg>
            </div>
        </div>

        <h1 class="title-404">Halaman <em>Tidak Ditemukan</em></h1>

        <p class="sub-404">
            Halaman yang Anda cari tidak ada, sudah dipindah, atau mungkin sedang bertanding di lapangan lain.
            Yuk balik ke arena utama!
        </p>

        <div class="cta-row-404">
            <a href="{{ url('/') }}" class="btn-fire">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg>
                Kembali ke Beranda
            </a>
            <a href="{{ url('/#kategori') }}" class="btn-glass">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Lihat Kategori
            </a>
        </div>

        <div class="line-divider"></div>

        <div class="quick-links">
            <p class="quick-label">Atau langsung ke</p>
            <div class="quick-grid">
                <a href="{{ route('registration.ganda-dewasa-putra') }}" class="quick-chip">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    Ganda Dewasa Putra
                </a>
                <a href="{{ route('registration.ganda-dewasa-putri') }}" class="quick-chip">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    Ganda Dewasa Putri
                </a>
                <a href="{{ route('registration.ganda-veteran-putra') }}" class="quick-chip">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    Ganda Veteran Putra
                </a>
                <a href="{{ route('registration.beregu') }}" class="quick-chip">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    Beregu
                </a>
                <a href="https://si.pbsi.id/" target="_blank" rel="noopener noreferrer" class="quick-chip">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                    Sirkuit Nasional (PBSI)
                </a>
            </div>
        </div>

        <div class="brand-footer">
            <a href="{{ url('/') }}">
                <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775803080/bayanopen-logo_mfcb55.png"
                     alt="Bayan Open 2026">
            </a>
        </div>

    </div>
</main>

</body>
</html>