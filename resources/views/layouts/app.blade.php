<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pendaftaran') | Bayan Open 2026</title>
    <link rel="icon" type="image/png" href="{{ asset('images/bayanopen.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/bayanopen.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'display': ['Montserrat', 'sans-serif'],
                        'body':    ['Montserrat', 'sans-serif'],
                    },
                    colors: {
                        'brand': {
                            50:  '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    },
                }
            }
        }
    </script>

    <style>
        /* ── Base ── */
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f8f6f2;
            color: #1a1a1a;
        }
        h1, h2, h3, .font-display { font-family: 'Montserrat', sans-serif; }

        /* ── Hero background ── */
        .gradient-hero { background: #f8f6f2; }

        /* ── Card ── */
        .card-glass {
            background: #ffffff;
            backdrop-filter: none;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 1px 6px rgba(0,0,0,0.06), 0 4px 20px rgba(0,0,0,0.04);
        }

        /* ── Navbar ── */
        .navbar {
            background: rgba(248,246,242,0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0,0,0,0.08);
            transition: background 0.4s ease, box-shadow 0.4s ease;
        }
        .navbar.scrolled {
            background: rgba(255,255,255,0.95);
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        /* Navbar links */
        .navbar a.nav-link {
            color: rgba(0,0,0,0.50);
            position: relative;
            padding-bottom: 2px;
        }
        .navbar a.nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0; right: 0;
            height: 1.5px;
            background: #f97316;
            border-radius: 2px;
            transform: scaleX(0);
            transition: transform 0.2s ease;
        }
        .navbar a.nav-link:hover { color: #1a1a1a; }
        .navbar a.nav-link:hover::after { transform: scaleX(1); }
        .navbar a.nav-link.active {
            color: #f97316 !important;
            font-weight: 600;
        }
        .navbar a.nav-link.active::after { transform: scaleX(1); }

        /* ── Mobile menu overlay ── */
        #mobile-menu {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 40;
            background: rgba(248,246,242,0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
        }
        #mobile-menu.open { display: flex; }
        #mobile-menu a.nav-link-mobile {
            font-family: 'Unbounded', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: rgba(0,0,0,0.50);
            text-decoration: none;
            letter-spacing: 0.08em;
            transition: color 0.2s;
        }
        #mobile-menu a.nav-link-mobile:hover,
        #mobile-menu a.nav-link-mobile.active { color: #f97316; }

        /* Hamburger */
        .hamburger {
            display: flex;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 4px;
            background: none;
            border: none;
        }
        .hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: #1a1a1a;
            border-radius: 2px;
            transition: all 0.3s ease;
            transform-origin: center;
        }
        .hamburger.active span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.active span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .hamburger.active span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* ── Animasi ── */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-10px); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-float    { animation: float 4s ease-in-out infinite; }
        .animate-fade-up  { animation: fadeInUp 0.6s ease forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }

        /* ── Button primary ── */
        .btn-primary {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff !important;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(249,115,22,0.35);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(249,115,22,0.5);
        }

        /* ── Input field ── */
        .input-field {
            background: #ffffff;
            border: 1.5px solid rgba(0,0,0,0.12);
            color: #1a1a1a;
            transition: all 0.25s;
        }
        .input-field:focus {
            outline: none;
            border-color: #f97316;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
        }
        .input-field::placeholder { color: rgba(0,0,0,0.3); }

        select.input-field {
            color: #1a1a1a !important;
            background-color: #ffffff !important;
            cursor: pointer;
        }
        select.input-field option { background-color: #ffffff; color: #1a1a1a; }
        select.input-field option:disabled { color: rgba(0,0,0,0.35); }
        select.input-field:disabled        { opacity: .45 !important; cursor: not-allowed; }

        /* ── Teks helper ── */
        .text-white\/70  { color: rgba(0,0,0,0.65) !important; }
        .text-white\/60  { color: rgba(0,0,0,0.58) !important; }
        .text-white\/50  { color: rgba(0,0,0,0.50) !important; }
        .text-white\/40  { color: rgba(0,0,0,0.42) !important; }
        .text-white\/35  { color: rgba(0,0,0,0.38) !important; }
        .text-white\/30  { color: rgba(0,0,0,0.32) !important; }
        .text-white\/25  { color: rgba(0,0,0,0.28) !important; }
        .text-white\/20  { color: rgba(0,0,0,0.22) !important; }
        .text-white\/10  { color: rgba(0,0,0,0.12) !important; }
        .text-white      { color: #1a1a1a !important; }
        .text-white\/80  { color: rgba(0,0,0,0.75) !important; }
        .text-white\/55  { color: rgba(0,0,0,0.52) !important; }

        .border-white\/10 { border-color: rgba(0,0,0,0.08) !important; }
        .border-white\/12 { border-color: rgba(0,0,0,0.10) !important; }
        .border-white\/08 { border-color: rgba(0,0,0,0.07) !important; }

        /* ── Section cards ── */
        .pemain-ocr-card {
            background: #ffffff !important;
            border-color: rgba(249,115,22,0.2) !important;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }
        .pemain-ocr-card.scanned {
            background: #f0fdf4 !important;
            border-color: rgba(16,185,129,0.35) !important;
        }
        .ktp-data-card.valid-card {
            background: #fffbf5 !important;
            border-color: rgba(249,115,22,0.2) !important;
        }
        .ktp-label { color: rgba(0,0,0,0.38) !important; }
        .ktp-value { color: rgba(0,0,0,0.75) !important; }
        .ktp-value.hl { color: #1a1a1a !important; }
        .ktp-value:hover {
            background: rgba(249,115,22,0.07) !important;
            border-color: rgba(249,115,22,0.25) !important;
            color: #1a1a1a !important;
        }
        .ktp-inline-input  { background: rgba(249,115,22,0.06) !important; color: #1a1a1a !important; }
        .ktp-inline-input:focus { background: rgba(249,115,22,0.1) !important; }
        .ktp-inline-select { background: #ffffff !important; color: #1a1a1a !important; }
        .ktp-inline-select option { background:#fff; color:#1a1a1a; }
        .ktp-row { border-bottom-color: rgba(0,0,0,0.05) !important; }
        .ktp-edit-hint { color: rgba(249,115,22,0.55) !important; }
        .scan-loading-bar { background: rgba(249,115,22,0.1) !important; }
        .bg-white\/05, [style*="background:rgba(255,255,255,0.05)"] { background: #ffffff !important; }

        /* ── Footer ── */
        @keyframes footerBar {
            0%   { background-position: 0% 0%; }
            100% { background-position: 200% 0%; }
        }
        .footer-social-btn {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(0,0,0,.05);
            border: 1px solid rgba(0,0,0,.09);
            color: rgba(0,0,0,.4);
            flex-shrink: 0;
            transition: background .2s, color .2s, border-color .2s, transform .2s;
        }
        .footer-social-btn:hover {
            background: rgba(249,115,22,.1);
            border-color: rgba(249,115,22,.35);
            color: #f97316;
            transform: translateY(-2px);
        }
        .footer-link {
            color: rgba(0,0,0,.45); font-size: 13px;
            transition: color .2s; text-decoration: none;
        }
        .footer-link:hover { color: #f97316; }
        .footer-nav-link {
            color: rgba(0,0,0,.45); font-size: 13px; text-decoration: none;
            display: flex; align-items: center; gap: 8px;
            transition: color .2s;
        }
        .footer-nav-link:hover { color: #f97316; }
        .footer-nav-link .dot {
            width: 5px; height: 5px; border-radius: 50%;
            background: #f97316; flex-shrink: 0;
            opacity: .5; transition: opacity .2s;
        }
        .footer-nav-link:hover .dot { opacity: 1; }
    </style>

    @stack('styles')
    @stack('head')
</head>

<body class="gradient-hero min-h-screen">

    {{-- ══ NAVBAR ══ --}}
    <nav class="navbar fixed top-0 left-0 right-0 z-50" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 h-16 sm:h-20 lg:h-24 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-4 group">
                <img
                    src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1773109896/LOGO_BO2026_pzbvxh.png"
                    alt="Bayan Open 2026"
                    class="h-10 sm:h-12 lg:h-16 w-auto object-contain transition-transform duration-300 group-hover:scale-105"
                >
            </a>

            {{-- Desktop links --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}"
                   class="nav-link transition font-semibold text-sm tracking-wide px-3 py-2 rounded-lg
                          {{ request()->routeIs('home') ? 'active' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('bagan') }}"
                   class="nav-link transition font-semibold text-sm tracking-wide px-3 py-2 rounded-lg
                          {{ request()->routeIs('bagan') ? 'active' : '' }}">
                    Bagan
                </a>
                <a href="{{ route('jadwal') }}"
                   class="nav-link transition font-semibold text-sm tracking-wide px-3 py-2 rounded-lg
                          {{ request()->routeIs('jadwal') ? 'active' : '' }}">
                    Jadwal
                </a>
                <a href="{{ route('livescore') }}"
                   class="nav-link transition font-semibold text-sm tracking-wide px-3 py-2 rounded-lg
                          {{ request()->routeIs('livescore') ? 'active' : '' }}">
                    Hasil Pertandingan
                    {{-- pulsing dot kalau di halaman lain --}}
                    @unless(request()->routeIs('livescore'))
                    <span style="display:inline-flex;align-items:center;justify-content:center;
                                 width:7px;height:7px;border-radius:50%;
                                 background:#ef4444;margin-left:5px;
                                 box-shadow:0 0 0 0 rgba(239,68,68,.4);
                                 animation:liveping 1.8s ease infinite;vertical-align:middle;"></span>
                    @endunless
                </a>
            </div>

            {{-- Mobile: DAFTAR + hamburger --}}
            <div class="flex md:hidden items-center gap-3">
                <button class="hamburger" id="hamburger-btn"
                        aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    {{-- ══ MOBILE MENU OVERLAY ══ --}}
    <div id="mobile-menu" role="dialog" aria-modal="true" aria-label="Menu navigasi">
        <button id="menu-close"
                style="position:absolute;top:1.25rem;right:1.25rem;background:none;border:none;cursor:pointer;padding:0.5rem;"
                aria-label="Tutup menu">
            <svg width="20" height="20" fill="none" stroke="#1a1a1a" stroke-width="2" viewBox="0 0 24 24">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
        </button>

        <a href="{{ route('home') }}"
           class="nav-link-mobile {{ request()->routeIs('home') ? 'active' : '' }}"
           onclick="closeMobileMenu()">Home</a>

        <a href="{{ route('bagan') }}"
           class="nav-link-mobile {{ request()->routeIs('bagan') ? 'active' : '' }}"
           onclick="closeMobileMenu()">Bagan</a>

        <a href="{{ route('jadwal') }}"
           class="nav-link-mobile {{ request()->routeIs('jadwal') ? 'active' : '' }}"
           onclick="closeMobileMenu()">Jadwal</a>

        <a href="{{ route('livescore') }}"
           class="nav-link-mobile {{ request()->routeIs('livescore') ? 'active' : '' }}"
           onclick="closeMobileMenu()">
           Hasil Pertandingan
            <span style="display:inline-flex;width:7px;height:7px;border-radius:50%;
                         background:#ef4444;margin-left:6px;vertical-align:middle;
                         animation:liveping 1.8s ease infinite;"></span>
        </a>
    </div>

    {{-- Live ping keyframe (global) --}}
    <style>
        @keyframes liveping {
            0%   { box-shadow: 0 0 0 0 rgba(239,68,68,.55); }
            70%  { box-shadow: 0 0 0 6px rgba(239,68,68,0); }
            100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
        }
    </style>

    {{-- ══ MAIN CONTENT ══ --}}
    <main class="pt-16 sm:pt-20 lg:pt-24">
        @yield('content')
    </main>

    {{-- ══ FOOTER ══ --}}
    <footer class="mt-24" style="background:#f0ede8;border-top:1px solid rgba(0,0,0,0.07);">

        <div class="h-1 w-full"
             style="background:linear-gradient(90deg,#f97316,#ea580c,#fb923c,#f97316);
                    background-size:200% 100%;
                    animation:footerBar 3s linear infinite;">
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 sm:pt-16 pb-8 sm:pb-10">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 sm:gap-12 mb-10 sm:mb-14">

                {{-- Brand --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <img
                        src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1773109896/LOGO_BO2026_pzbvxh.png"
                        alt="Bayan Open 2026"
                        class="h-12 sm:h-14 object-contain mb-4 sm:mb-5"
                    >
                    <p style="color:rgba(0,0,0,.5);font-size:13px;line-height:1.8;" class="mb-4 sm:mb-5">
                        Turnamen bulu tangkis terbesar di Kalimantan.<br>
                        Bergabunglah dan tunjukkan kemampuan terbaik Anda.
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="https://www.instagram.com/bayan_open/" class="footer-social-btn" title="Instagram">
                            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="footer-social-btn" title="WhatsApp">
                            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        <a href="https://www.youtube.com/@BAYANOPEN" class="footer-social-btn" title="YouTube">
                            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Navigasi --}}
                <div>
                    <h4 class="font-display text-xs font-bold tracking-widest mb-5"
                        style="color:rgba(0,0,0,.7);">NAVIGASI</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('home') }}" class="footer-nav-link ">
                                <span class="dot"></span>Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('bagan') }}" class="footer-nav-link">
                                <span class="dot"></span>Bagan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jadwal') }}" class="footer-nav-link">
                                <span class="dot"></span>Jadwal Pertandingan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('livescore') }}" class="footer-nav-link">
                                <span class="dot"></span>
                                Hasil Pertandingan
                                <span style="display:inline-flex;width:6px;height:6px;border-radius:50%;
                                             background:#ef4444;margin-left:2px;
                                             animation:liveping 1.8s ease infinite;"></span>
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Kategori --}}
                <div>
                    <h4 class="font-display text-xs font-bold tracking-widest mb-5"
                        style="color:rgba(0,0,0,.7);">KATEGORI</h4>
                    <ul class="space-y-3">
                        @foreach(['Ganda Dewasa Putra','Ganda Dewasa Putri','Ganda Veteran Putra','Ganda Veteran Putri','Beregu'] as $kat)
                        <li>
                            <a href="{{ route('home') }}" class="footer-link flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                      style="background:#f97316;"></span>
                                {{ $kat }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Info & Kontak --}}
                <div>
                    <h4 class="font-display text-xs font-bold tracking-widest mb-5"
                        style="color:rgba(0,0,0,.7);">INFORMASI</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex-shrink-0" style="color:#f97316;">
                                <svg width="14" height="14" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                    <path d="M16 2v4M8 2v4M3 10h18"/>
                                </svg>
                            </span>
                            <span style="color:rgba(0,0,0,.5);font-size:13px;line-height:1.6;">
                                <span style="color:rgba(0,0,0,.75);font-weight:600;display:block;">
                                    Tanggal Turnamen
                                </span>
                                22–24 Agustus 2026
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex-shrink-0" style="color:#f97316;">
                                <svg width="14" height="14" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </span>
                            <span style="color:rgba(0,0,0,.5);font-size:13px;line-height:1.6;">
                                <span style="color:rgba(0,0,0,.75);font-weight:600;display:block;">Lokasi</span>
                                BSCC Dome, Balikpapan, Kalimantan Timur
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex-shrink-0" style="color:#f97316;">
                                <svg width="14" height="14" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.68A2 2 0 012 .82h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                                </svg>
                            </span>
                            <span style="color:rgba(0,0,0,.5);font-size:13px;line-height:1.6;">
                                <span style="color:rgba(0,0,0,.75);font-weight:600;display:block;">
                                    Kontak Panitia
                                </span>
                                +62 812-3456-7890
                            </span>
                        </li>
                    </ul>
                </div>

            </div>

            {{-- Divider --}}
            <div class="h-px mb-8" style="background:rgba(0,0,0,.08);"></div>

            {{-- Bottom bar --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="font-display text-xs tracking-widest" style="color:rgba(0,0,0,.35);">
                    © 2026 BAYAN GROUP · All Rights Reserved
                </p>
                <div class="flex items-center gap-5">
                    <a href="{{ route('home') }}"
                       class="font-display text-xs tracking-wider"
                       style="color:rgba(0,0,0,.3);text-decoration:none;transition:color .2s;"
                       onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='rgba(0,0,0,.3)'">
                        Home
                    </a>
                    <a href="{{ route('jadwal') }}"
                       class="font-display text-xs tracking-wider"
                       style="color:rgba(0,0,0,.3);text-decoration:none;transition:color .2s;"
                       onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='rgba(0,0,0,.3)'">
                        Jadwal
                    </a>
                    <a href="{{ route('livescore') }}"
                       class="font-display text-xs tracking-wider"
                       style="color:rgba(0,0,0,.3);text-decoration:none;transition:color .2s;"
                       onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='rgba(0,0,0,.3)'">
                        Live Score
                    </a>
                    <a href="{{ route('bagan') }}"
                       class="font-display text-xs tracking-wider"
                       style="color:rgba(0,0,0,.3);text-decoration:none;transition:color .2s;"
                       onmouseover="this.style.color='#f97316'" onmouseout="this.style.color='rgba(0,0,0,.3)'">
                        Bagan
                    </a>
                </div>
            </div>

        </div>
    </footer>

    {{-- ══ SCRIPTS ══ --}}
    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Mobile menu
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const mobileMenu   = document.getElementById('mobile-menu');
        const menuClose    = document.getElementById('menu-close');

        function openMobileMenu() {
            mobileMenu.classList.add('open');
            hamburgerBtn.classList.add('active');
            hamburgerBtn.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }
        function closeMobileMenu() {
            mobileMenu.classList.remove('open');
            hamburgerBtn.classList.remove('active');
            hamburgerBtn.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        hamburgerBtn.addEventListener('click', () => {
            mobileMenu.classList.contains('open') ? closeMobileMenu() : openMobileMenu();
        });
        menuClose.addEventListener('click', closeMobileMenu);
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMobileMenu(); });
        mobileMenu.addEventListener('click', e => { if (e.target === mobileMenu) closeMobileMenu(); });
    </script>

    @stack('scripts')
    @stack('modals')

</body>
</html>