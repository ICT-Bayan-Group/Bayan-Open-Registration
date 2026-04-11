{{--
  resources/views/filament/resources/admin-resource/pages/login.blade.php
--}}

<x-filament-panels::page.simple>
    <style>
        /* ── Nuclear reset for ALL Filament wrappers ───────────────── */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            overflow: hidden !important;
            background: #faf8f5 !important;
        }

        /* Target every possible Filament ancestor */
        .fi-simple-layout,
        .fi-simple-layout > div,
        .fi-simple-page,
        .fi-simple-main,
        .fi-simple-page > div,
        .fi-body,
        .fi-body > div {
            all: unset !important;
            display: block !important;
        }

        /* Hide default Filament brand logo + "Sign in" heading */
        .fi-simple-header,
        .fi-simple-page > .fi-simple-header,
        .fi-logo,
        .fi-simple-page header,
        .fi-simple-page > header {
            display: none !important;
        }

        /* ── Page shell — fixed to viewport ────────────────────────── */
        .bo-login-shell {
            position: fixed;
            inset: 0;
            display: flex;
            font-family: 'Montserrat', sans-serif;
            background: #faf8f5;
            overflow: hidden;
            z-index: 0;
        }

        /* ── Left panel — decorative ────────────────────────────────── */
        .bo-left {
            flex: 0 0 52%;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bo-left-bg {
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 80% 70% at 30% 40%, rgba(249,115,22,0.13) 0%, transparent 65%),
                radial-gradient(ellipse 60% 80% at 70% 80%, rgba(251,191,36,0.08) 0%, transparent 60%),
                linear-gradient(135deg, #fdf6ee 0%, #faf0e4 50%, #f5e8d8 100%);
        }
        /* Subtle grid pattern */
        .bo-left-bg::after {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(249,115,22,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(249,115,22,0.05) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        .bo-left-content {
            position: relative; z-index: 2;
            text-align: center;
            padding: 60px 64px;
        }
        .bo-logo {
            height: 120px; width: auto;
            filter: drop-shadow(0 8px 32px rgba(249,115,22,0.2));
            margin-bottom: 36px;
            display: block; margin-left: auto; margin-right: auto;
        }
        .bo-left-title {
            font-size: 13px; font-weight: 700;
            letter-spacing: 0.22em; text-transform: uppercase;
            color: rgba(249,115,22,0.7);
            margin-bottom: 16px;
        }
        .bo-left-headline {
            font-size: clamp(28px, 3vw, 42px);
            font-weight: 800;
            color: #1a1007;
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin-bottom: 20px;
        }
        .bo-left-headline em { font-style: normal; color: #f97316; }
        .bo-left-sub {
            font-size: 14px;
            color: rgba(26,16,7,0.5);
            line-height: 1.7;
            max-width: 340px;
            margin: 0 auto;
        }

        /* Decorative floating orbs */
        .bo-orb {
            position: absolute; border-radius: 50%;
            pointer-events: none;
        }
        .bo-orb-1 {
            width: 280px; height: 280px;
            top: -60px; left: -80px;
            background: radial-gradient(circle, rgba(249,115,22,0.08) 0%, transparent 70%);
        }
        .bo-orb-2 {
            width: 200px; height: 200px;
            bottom: -40px; right: -40px;
            background: radial-gradient(circle, rgba(251,191,36,0.1) 0%, transparent 70%);
        }
        .bo-orb-3 {
            width: 120px; height: 120px;
            top: 40%; right: 10%;
            background: radial-gradient(circle, rgba(249,115,22,0.06) 0%, transparent 70%);
        }

        /* Vertical divider */
        .bo-divider {
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgba(249,115,22,0.2) 20%, rgba(249,115,22,0.2) 80%, transparent);
            flex-shrink: 0;
        }

        /* ── Right panel — form ──────────────────────────────────────── */
        .bo-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 64px;
            position: relative;
            overflow-y: auto;
        }
        .bo-form-wrap {
            width: 100%;
            max-width: 380px;
        }

        /* Form header */
        .bo-form-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 5px 14px; border-radius: 99px;
            border: 1px solid rgba(249,115,22,0.25);
            background: rgba(249,115,22,0.06);
            margin-bottom: 28px;
        }
        .bo-form-eyebrow-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #f97316;
            animation: pulse-dot 2.4s ease infinite;
        }
        @keyframes pulse-dot {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:0.5; transform:scale(0.8); }
        }
        .bo-form-eyebrow-text {
            font-size: 10px; font-weight: 700;
            letter-spacing: 0.16em; text-transform: uppercase;
            color: #ea580c;
        }
        .bo-form-title {
            font-size: clamp(28px, 3vw, 38px);
            font-weight: 800;
            color: #1a1007;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 8px;
        }
        .bo-form-sub {
            font-size: 14px;
            color: rgba(26,16,7,0.45);
            margin-bottom: 44px;
        }

        /* Override Filament form field styles */
        .fi-fo-field-wrp-label label,
        .fi-fo-field-wrp-label .fi-fo-field-wrp-label-wrapper {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            letter-spacing: 0.08em !important;
            text-transform: uppercase !important;
            color: rgba(26,16,7,0.45) !important;
            margin-bottom: 8px !important;
        }
        .fi-input {
            background: #fff !important;
            border: 1.5px solid rgba(26,16,7,0.1) !important;
            border-radius: 14px !important;
            padding: 16px 18px !important;
            font-family: 'Montserrat', sans-serif !important;
            font-size: 15px !important;
            color: #1a1007 !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
        }
        .fi-input:focus {
            border-color: #f97316 !important;
            box-shadow: 0 0 0 3px rgba(249,115,22,0.12) !important;
            outline: none !important;
        }
        .fi-input-wrp {
            border-radius: 14px !important;
            overflow: hidden !important;
        }

        /* Submit button */
        .fi-btn-primary,
        button[type="submit"].fi-btn {
            background: linear-gradient(135deg, #f97316 0%, #c2410c 100%) !important;
            border: none !important;
            border-radius: 14px !important;
            padding: 16px 28px !important;
            font-family: 'Montserrat', sans-serif !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            letter-spacing: 0.12em !important;
            text-transform: uppercase !important;
            color: #fff !important;
            width: 100% !important;
            box-shadow: 0 8px 24px rgba(249,115,22,0.35) !important;
            transition: all 0.3s cubic-bezier(0.22,1,0.36,1) !important;
            margin-top: 8px !important;
        }
        .fi-btn-primary:hover,
        button[type="submit"].fi-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 16px 40px rgba(249,115,22,0.5) !important;
        }

        /* Checkbox */
        .fi-checkbox-input {
            border-radius: 6px !important;
            border-color: rgba(26,16,7,0.15) !important;
            color: #f97316 !important;
        }
        .fi-checkbox-label {
            font-size: 13px !important;
            color: rgba(26,16,7,0.5) !important;
        }

        /* Error states */
        .fi-fo-field-wrp-error-message {
            font-size: 12px !important;
            color: #ef4444 !important;
        }

        /* Footer credit */
        .bo-footer {
            position: absolute; bottom: 28px; left: 0; right: 0;
            text-align: center;
            font-size: 11px;
            color: rgba(26,16,7,0.25);
            letter-spacing: 0.04em;
        }

        /* ── Responsive ──────────────────────────────────────────────── */
        @media (max-width: 768px) {
            .bo-left { display: none; }
            .bo-divider { display: none; }
            .bo-right { padding: 40px 28px; }
            .bo-form-wrap { max-width: 100%; }
        }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <div class="bo-login-shell">

        {{-- ── Left decorative panel ────────────────────────────── --}}
        <div class="bo-left">
            <div class="bo-left-bg"></div>
            <div class="bo-orb bo-orb-1"></div>
            <div class="bo-orb bo-orb-2"></div>
            <div class="bo-orb bo-orb-3"></div>

            <div class="bo-left-content">
                <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775803080/bayanopen-logo_mfcb55.png"
                     alt="Bayan Open 2026" class="bo-logo">

                <p class="bo-left-title">Admin Dashboard</p>
                <h1 class="bo-left-headline">
                    Administrasi<br>
                    <em>Bayan Open 2026</em>
                </h1>
                <p class="bo-left-sub">
                    Panel administrasi untuk mengelola pendaftaran, verifikasi peserta, dan data turnamen.
                </p>
            </div>
        </div>

        {{-- Vertical divider --}}
        <div class="bo-divider"></div>

        {{-- ── Right form panel ─────────────────────────────────── --}}
        <div class="bo-right">
            <div class="bo-form-wrap">

                <h2 class="bo-form-title">Masuk ke<br>Dashboard</h2>
                <p class="bo-form-sub">Khusus administrator turnamen.</p>

                <x-filament-panels::form id="form" wire:submit="authenticate">
                    {{ $this->form }}

                    <x-filament-panels::form.actions
                        :actions="$this->getCachedFormActions()"
                        :full-width="$this->hasFullWidthFormActions()"
                    />
                </x-filament-panels::form>

            </div>

            <p class="bo-footer">© 2026 Bayan Open · All rights reserved</p>
        </div>

    </div>

</x-filament-panels::page.simple>