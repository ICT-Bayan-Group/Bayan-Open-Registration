<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pendaftaran') | Bayan Open 2026</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700;900&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'display': ['Unbounded', 'sans-serif'],
                        'body': ['DM Sans', 'sans-serif'],
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
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3, .font-display { font-family: 'Unbounded', sans-serif; }

        .gradient-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0f172a 100%);
        }

        .card-glass {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.12);
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: transparent;
            transition: background 0.4s ease, backdrop-filter 0.4s ease, border-color 0.4s ease;
            border-bottom: 1px solid transparent;
        }

        /* Saat di-scroll → navbar jadi gelap */
        .navbar.scrolled {
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-float { animation: float 4s ease-in-out infinite; }
        .animate-fade-up { animation: fadeInUp 0.6s ease forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }

        .btn-primary {
            background: linear-gradient(135deg, #f97316, #ea580c);
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(249,115,22,0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(249,115,22,0.6);
        }

        .input-field {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.15);
            color: white;
            transition: all 0.3s;
        }

        .input-field:focus {
            outline: none;
            border-color: #f97316;
            background: rgba(249,115,22,0.08);
            box-shadow: 0 0 0 3px rgba(249,115,22,0.15);
        }

        .input-field::placeholder { color: rgba(255,255,255,0.4); }
    </style>

    @stack('head')
</head>
<body class="gradient-hero min-h-screen text-white">

    {{-- NAVBAR --}}
    <nav class="navbar fixed top-0 left-0 right-0 z-50" id="navbar">
        <div class="max-w-7xl mx-auto px-8 h-24 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-4 group">
                <img
                    src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1767777416/LOGO_BO2025_resdzo.png"
                    alt="Bayan Open 2026"
                    class="h-16 w-auto object-contain transition-transform duration-300 group-hover:scale-105"
                    style="filter: drop-shadow(0 2px 12px rgba(249,115,22,0.3));"
                >
            </a>

            {{-- Nav Links --}}
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}"
                   class="text-white/70 hover:text-white transition font-medium text-sm tracking-wide">
                    Home
                </a>
                <a href="#kategori"
                   class="text-white/70 hover:text-white transition font-medium text-sm tracking-wide">
                    Kategori
                </a>
                <a href="{{ route('registration.create') }}"
                   class="btn-primary font-display text-xs font-bold px-6 py-3 rounded-xl text-white tracking-wider">
                    DAFTAR →
                </a>
            </div>

        </div>
    </nav>

    {{-- Konten --}}
    <main class="pt-24">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-20 border-t border-white/10 py-10 text-center text-white/40 text-sm">
        <img
            src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1767777416/LOGO_BO2025_resdzo.png"
            alt="Bayan Open 2026"
            class="h-12 mx-auto object-contain mb-4 opacity-40"
        >
        <p class="font-display text-xs tracking-widest mb-2">© 2026 BAYAN GROUP</p>
        <p>ALL RIGHTS RESERVED.</p>
    </footer>

    {{-- Navbar scroll effect --}}
    <script>
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>