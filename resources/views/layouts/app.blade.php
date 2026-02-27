<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pendaftaran') | Bayan Open 2026</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;600;700;900&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
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

        .bg-noise {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
        }

        .gradient-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0f172a 100%);
        }

        .card-glass {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.12);
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
<body class="gradient-hero min-h-screen bg-noise text-white">

    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-black/30 backdrop-blur-md border-b border-white/10">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img
                    src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1767777416/LOGO_BO2025_resdzo.png"
                    alt="Bayan Open 2026"
                    class="h-10 w-auto object-contain"
                >
            </a>
            <div class="flex items-center gap-4 text-sm text-white/70">
                <a href="{{ url('/') }}" class="hover:text-white transition">Home</a>
                <a href="{{ route('registration.create') }}" class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-1.5 rounded-lg font-medium transition">Daftar</a>
            </div>
        </div>
    </nav>

    <main class="pt-16">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-20 border-t border-white/10 py-8 text-center text-white/40 text-sm">
        <p class="font-display text-xs tracking-widest mb-2">BAYAN OPEN 2026</p>
        <p>© 2026 Bayan Open. Sistem Pendaftaran Online.</p>
    </footer>

    @stack('scripts')
</body>
</html>