@extends('layouts.app')

@section('title', 'Pendaftaran Bayan Open 2026')

@section('content')
{{-- Halaman kosong — modal dirender via @stack('modals') di luar <main> --}}
<div class="min-h-screen"></div>
@endsection

@push('modals')

{{-- ============================================================
     STYLE — didefinisikan di sini karena pakai @push bukan @section
     ============================================================ --}}
<style>
    @keyframes fadeInOverlay {
        from { opacity: 0; } to { opacity: 1; }
    }
    @keyframes fadeOutOverlay {
        from { opacity: 1; } to { opacity: 0; }
    }
    @keyframes slideUpCard {
        from { opacity: 0; transform: translateY(32px) scale(0.96); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes slideDownCard {
        from { opacity: 1; transform: translateY(0) scale(1); }
        to   { opacity: 0; transform: translateY(20px) scale(0.97); }
    }
    @keyframes pulseDot {
        0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(249,115,22,0.5); }
        50%       { opacity: 0.7; box-shadow: 0 0 0 4px rgba(249,115,22,0); }
    }

    .mo-overlay {
        position: fixed !important;
        top: 0 !important; left: 0 !important;
        right: 0 !important; bottom: 0 !important;
        z-index: 9999 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: radial-gradient(ellipse at center, rgba(3,7,28,0.95) 0%, rgba(1,4,16,0.99) 100%);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }
    .mo-overlay.anim-in  { animation: fadeInOverlay  0.35s ease forwards; }
    .mo-overlay.anim-out { animation: fadeOutOverlay 0.22s ease forwards; pointer-events: none; }
    .mo-card-in  { animation: slideUpCard   0.42s cubic-bezier(0.22,1,0.36,1) 0.05s both; }
    .mo-card-out { animation: slideDownCard 0.2s  ease forwards; }

    .sub-card {
        display: flex; align-items: center; gap: 14px; width: 100%; font-family: inherit;
        border-radius: 14px; padding: 15px 16px;
        border: 1.5px solid rgba(255,255,255,0.07);
        background: rgba(255,255,255,0.03);
        text-align: left; cursor: pointer; position: relative; overflow: hidden;
        transition: all 0.2s cubic-bezier(0.22,1,0.36,1);
    }
    .sub-card:hover { transform: translateY(-1px); }
    .sub-card .sc-arrow { margin-left:auto; flex-shrink:0; opacity:0.25; transition: opacity 0.2s, transform 0.2s; }
    .sub-card:hover .sc-arrow { opacity:0.8; transform:translateX(3px); }

    .sub-card.sc-blue   { border-color:rgba(59,130,246,0.2);  background:rgba(12,20,45,0.8); }
    .sub-card.sc-pink   { border-color:rgba(236,72,153,0.2);  background:rgba(22,8,18,0.8); }
    .sub-card.sc-yellow { border-color:rgba(234,179,8,0.2);   background:rgba(20,16,4,0.8); }
    .sub-card.sc-green  { border-color:rgba(16,185,129,0.2);  background:rgba(4,18,12,0.8); }
    .sub-card.sc-blue:hover   { border-color:rgba(59,130,246,0.55);  background:rgba(59,130,246,0.09);  box-shadow:0 6px 24px rgba(59,130,246,0.13); }
    .sub-card.sc-pink:hover   { border-color:rgba(236,72,153,0.55);  background:rgba(236,72,153,0.09);  box-shadow:0 6px 24px rgba(236,72,153,0.13); }
    .sub-card.sc-yellow:hover { border-color:rgba(234,179,8,0.55);   background:rgba(234,179,8,0.09);   box-shadow:0 6px 24px rgba(234,179,8,0.13); }
    .sub-card.sc-green:hover  { border-color:rgba(16,185,129,0.55);  background:rgba(16,185,129,0.09);  box-shadow:0 6px 24px rgba(16,185,129,0.13); }

    .sub-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:transform 0.2s; }
    .sub-card:hover .sub-icon { transform:scale(1.08); }
</style>

{{-- ============================================================
     MODAL 1 — PILIH JALUR
     ============================================================ --}}
<div id="modal1" class="mo-overlay anim-in">
    <div id="modal1Card" class="mo-card-in" style="position:relative;width:100%;max-width:560px;">

        {{-- Glow --}}
        <div style="position:absolute;inset:-60px;pointer-events:none;background:radial-gradient(ellipse at 50% 55%,rgba(249,115,22,0.1) 0%,transparent 68%);"></div>

        <div style="background:rgba(8,12,32,0.98);border:1px solid rgba(99,130,255,0.1);border-radius:24px;padding:36px 32px 30px;position:relative;overflow:hidden;box-shadow:0 40px 80px rgba(0,0,8,0.8),inset 0 1px 0 rgba(255,255,255,0.04);">

            {{-- Grain --}}
            <div style="position:absolute;inset:0;pointer-events:none;border-radius:24px;background-image:url('data:image/svg+xml,%3Csvg viewBox%3D%220 0 200 200%22 xmlns%3D%22http://www.w3.org/2000/svg%22%3E%3Cfilter id%3D%22n%22%3E%3CfeTurbulence type%3D%22fractalNoise%22 baseFrequency%3D%220.9%22 numOctaves%3D%224%22 stitchTiles%3D%22stitch%22/%3E%3C/filter%3E%3Crect width%3D%22100%25%22 height%3D%22100%25%22 filter%3D%22url(%23n)%22 opacity%3D%220.03%22/%3E%3C/svg%3E');"></div>
            {{-- Top line --}}
            <div style="position:absolute;top:0;left:50%;transform:translateX(-50%);width:100px;height:2px;border-radius:0 0 4px 4px;background:linear-gradient(90deg,transparent,#f97316,transparent);"></div>

            {{-- Badge --}}
            <div style="display:flex;justify-content:center;margin-bottom:22px;">
                <div style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:99px;border:1px solid rgba(249,115,22,0.3);background:rgba(249,115,22,0.08);">
                    <span style="width:6px;height:6px;border-radius:50%;display:inline-block;background:#f97316;box-shadow:0 0 6px #f97316;animation:pulseDot 2s ease infinite;"></span>
                    <span style="color:#f97316;font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;font-family:'Unbounded',sans-serif;">Bayan Open 2026</span>
                </div>
            </div>

            {{-- Heading --}}
            <div style="text-align:center;margin-bottom:26px;">
                <h2 style="font-family:'Unbounded',sans-serif;font-size:26px;font-weight:800;color:#fff;margin:0 0 8px;letter-spacing:-0.02em;line-height:1.2;">Pilih Jalur Pendaftaran</h2>
                <p style="color:rgba(255,255,255,0.4);font-size:13px;margin:0;font-family:'DM Sans',sans-serif;">Silakan pilih jalur turnamen yang ingin Anda ikuti</p>
            </div>

            {{-- 2 Kartu --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:22px;">

                {{-- SIRKUIT NASIONAL C --}}
                <a href="https://si.pbsi.com" target="_blank" rel="noopener noreferrer"
                    style="display:block;text-decoration:none;border-radius:16px;padding:26px 16px;border:1.5px solid rgba(99,102,241,0.25);background:rgba(15,18,50,0.8);text-align:center;position:relative;overflow:hidden;transition:all 0.25s cubic-bezier(0.22,1,0.36,1);"
                    onmouseenter="hoverCard(this,'indigo')" onmouseleave="unhoverCard(this,'indigo')">
                    <div class="m1-shimmer" style="position:absolute;top:0;left:0;right:0;height:1px;opacity:0;background:linear-gradient(90deg,transparent,rgba(99,102,241,0.6),transparent);transition:opacity 0.3s;"></div>
                    <div class="m1-icon" style="width:52px;height:52px;border-radius:14px;margin:0 auto 14px;background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.2);display:flex;align-items:center;justify-content:center;transition:all 0.25s;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(129,140,248,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <p style="font-family:'Unbounded',sans-serif;font-weight:800;font-size:12px;color:#fff;margin:0 0 4px;letter-spacing:0.04em;line-height:1.4;">SIRKUIT<br>NASIONAL C</p>
                    <p style="font-size:11px;color:rgba(129,140,248,0.75);margin:0 0 13px;font-family:'DM Sans',sans-serif;">via si.pbsi.com</p>
                    <div style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.2);">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="rgba(129,140,248,0.9)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="font-size:10px;font-weight:700;color:rgba(129,140,248,0.9);letter-spacing:0.06em;font-family:'Unbounded',sans-serif;">RESMI PBSI</span>
                    </div>
                    <div class="m1-ext" style="position:absolute;top:10px;right:10px;opacity:0.25;transition:opacity 0.2s;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                    </div>
                </a>

                {{-- OPEN --}}
                <button type="button" onclick="bukaModal2()"
                    style="display:block;width:100%;font-family:inherit;border-radius:16px;padding:26px 16px;border:1.5px solid rgba(249,115,22,0.28);background:rgba(20,14,8,0.8);text-align:center;cursor:pointer;position:relative;overflow:hidden;transition:all 0.25s cubic-bezier(0.22,1,0.36,1);"
                    onmouseenter="hoverCard(this,'orange')" onmouseleave="unhoverCard(this,'orange')">
                    <div class="m1-shimmer" style="position:absolute;top:0;left:0;right:0;height:1px;opacity:0;background:linear-gradient(90deg,transparent,rgba(249,115,22,0.7),transparent);transition:opacity 0.3s;"></div>
                    <div class="m1-icon" style="width:52px;height:52px;border-radius:14px;margin:0 auto 14px;background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.22);display:flex;align-items:center;justify-content:center;transition:all 0.25s;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(251,146,60,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    </div>
                    <p style="font-family:'Unbounded',sans-serif;font-weight:800;font-size:12px;color:#fff;margin:0 0 4px;letter-spacing:0.04em;line-height:1.4;">OPEN</p>
                    <p style="font-size:11px;color:rgba(251,146,60,0.75);margin:0 0 13px;font-family:'DM Sans',sans-serif;">Daftar langsung di sini</p>
                    <div style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.22);">
                        <span style="width:6px;height:6px;border-radius:50%;display:inline-block;background:#f97316;animation:pulseDot 2s ease infinite;"></span>
                        <span style="font-size:10px;font-weight:700;color:rgba(251,146,60,0.9);letter-spacing:0.06em;font-family:'Unbounded',sans-serif;">DAFTAR SEKARANG</span>
                    </div>
                    <div style="position:absolute;bottom:11px;right:11px;opacity:0.25;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </button>

            </div>

            {{-- Info footer --}}
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <div style="flex:1;height:1px;background:rgba(255,255,255,0.06);"></div>
                <span style="font-size:9px;color:rgba(255,255,255,0.18);letter-spacing:0.1em;text-transform:uppercase;font-family:'Unbounded',sans-serif;">Info</span>
                <div style="flex:1;height:1px;background:rgba(255,255,255,0.06);"></div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div style="padding:10px 12px;border-radius:10px;background:rgba(10,15,40,0.8);border:1px solid rgba(99,130,255,0.07);">
                    <p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.22);margin:0 0 3px;font-family:'Unbounded',sans-serif;">Sirkuit Nasional C</p>
                    <p style="font-size:11px;color:rgba(255,255,255,0.5);margin:0;line-height:1.45;font-family:'DM Sans',sans-serif;">Didaftarkan melalui sistem resmi PBSI</p>
                </div>
                <div style="padding:10px 12px;border-radius:10px;background:rgba(10,15,40,0.8);border:1px solid rgba(99,130,255,0.07);">
                    <p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.22);margin:0 0 3px;font-family:'Unbounded',sans-serif;">Open</p>
                    <p style="font-size:11px;color:rgba(255,255,255,0.5);margin:0;line-height:1.45;font-family:'DM Sans',sans-serif;">Pendaftaran mandiri, pilih kategori sesuai kelas</p>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ============================================================
     MODAL 2 — PILIH KATEGORI OPEN
     ============================================================ --}}
<div id="modal2" class="mo-overlay" style="display:none;">
    <div id="modal2Card" style="position:relative;width:100%;max-width:520px;">

        {{-- Glow --}}
        <div style="position:absolute;inset:-60px;pointer-events:none;background:radial-gradient(ellipse at 50% 40%,rgba(249,115,22,0.11) 0%,transparent 68%);"></div>

        <div style="background:rgba(8,12,32,0.98);border:1px solid rgba(249,115,22,0.12);border-radius:24px;padding:30px 26px 26px;position:relative;overflow:hidden;box-shadow:0 40px 80px rgba(0,0,8,0.8),inset 0 1px 0 rgba(255,255,255,0.04);">

            {{-- Grain --}}
            <div style="position:absolute;inset:0;pointer-events:none;border-radius:24px;background-image:url('data:image/svg+xml,%3Csvg viewBox%3D%220 0 200 200%22 xmlns%3D%22http://www.w3.org/2000/svg%22%3E%3Cfilter id%3D%22n%22%3E%3CfeTurbulence type%3D%22fractalNoise%22 baseFrequency%3D%220.9%22 numOctaves%3D%224%22 stitchTiles%3D%22stitch%22/%3E%3C/filter%3E%3Crect width%3D%22100%25%22 height%3D%22100%25%22 filter%3D%22url(%23n)%22 opacity%3D%220.03%22/%3E%3C/svg%3E');"></div>
            <div style="position:absolute;top:0;left:50%;transform:translateX(-50%);width:90px;height:2px;border-radius:0 0 4px 4px;background:linear-gradient(90deg,transparent,#f97316,transparent);"></div>

            {{-- Header --}}
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                <button type="button" onclick="tutupModal2()"
                    style="width:34px;height:34px;flex-shrink:0;border-radius:99px;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s;"
                    onmouseenter="this.style.borderColor='rgba(255,255,255,0.28)';this.style.background='rgba(255,255,255,0.09)'"
                    onmouseleave="this.style.borderColor='rgba(255,255,255,0.1)';this.style.background='rgba(255,255,255,0.04)'">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.65)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                </button>
                <div>
                    <h2 style="font-family:'Unbounded',sans-serif;font-size:19px;font-weight:800;color:#fff;margin:0 0 3px;letter-spacing:-0.02em;">Pilih Kategori Open</h2>
                    <p style="color:rgba(255,255,255,0.35);font-size:12px;margin:0;font-family:'DM Sans',sans-serif;">Pilih kelas pertandingan yang akan Anda ikuti</p>
                </div>
            </div>

            {{-- List --}}
            <div style="display:flex;flex-direction:column;gap:8px;">

                {{-- Ganda Dewasa Putra --}}
                <button type="button" class="sub-card sc-blue" onclick="pilihKategori('ganda-dewasa-putra')">
                    <div class="sub-icon" style="background:rgba(59,130,246,0.12);border:1px solid rgba(59,130,246,0.22);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(96,165,250,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-weight:700;font-size:13px;color:#fff;margin:0 0 2px;font-family:'DM Sans',sans-serif;">Ganda Dewasa Putra</p>
                        <p style="font-size:11px;color:rgba(255,255,255,0.35);margin:0;font-family:'DM Sans',sans-serif;">Upload KTP di akhir pendaftaran</p>
                    </div>
                    <span style="font-size:12px;font-weight:700;color:rgba(251,146,60,0.85);flex-shrink:0;margin-right:6px;font-family:'DM Sans',sans-serif;">Rp 150.000</span>
                    <svg class="sc-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>

                {{-- Ganda Dewasa Putri --}}
                <button type="button" class="sub-card sc-pink" onclick="pilihKategori('ganda-dewasa-putri')">
                    <div class="sub-icon" style="background:rgba(236,72,153,0.1);border:1px solid rgba(236,72,153,0.22);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(244,114,182,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-weight:700;font-size:13px;color:#fff;margin:0 0 2px;font-family:'DM Sans',sans-serif;">Ganda Dewasa Putri</p>
                        <p style="font-size:11px;color:rgba(255,255,255,0.35);margin:0;font-family:'DM Sans',sans-serif;">Upload KTP di akhir pendaftaran</p>
                    </div>
                    <span style="font-size:12px;font-weight:700;color:rgba(251,146,60,0.85);flex-shrink:0;margin-right:6px;font-family:'DM Sans',sans-serif;">Rp 150.000</span>
                    <svg class="sc-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>

                {{-- Ganda Veteran Putra --}}
                <button type="button" class="sub-card sc-yellow" onclick="pilihKategori('ganda-veteran-putra')">
                    <div class="sub-icon" style="background:rgba(234,179,8,0.1);border:1px solid rgba(234,179,8,0.22);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(250,204,21,1)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;align-items:center;gap:7px;margin-bottom:2px;">
                            <p style="font-weight:700;font-size:13px;color:#fff;margin:0;font-family:'DM Sans',sans-serif;">Ganda Veteran Putra</p>
                        </div>
                        <p style="font-size:11px;color:rgba(255,255,255,0.35);margin:0;font-family:'DM Sans',sans-serif;">Scan KTP · min. lahir ≤ 24 Agustus 1995</p>
                    </div>
                    <span style="font-size:12px;font-weight:700;color:rgba(251,146,60,0.85);flex-shrink:0;margin-right:6px;font-family:'DM Sans',sans-serif;">Rp 150.000</span>
                    <svg class="sc-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>

                {{-- Beregu --}}
                <button type="button" class="sub-card sc-green" onclick="pilihKategori('beregu')">
                    <div class="sub-icon" style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.22);">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="rgba(52,211,153,1)">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-weight:700;font-size:13px;color:#fff;margin:0 0 2px;font-family:'DM Sans',sans-serif;">Beregu</p>
                        <p style="font-size:11px;color:rgba(255,255,255,0.35);margin:0;font-family:'DM Sans',sans-serif;">Upload KTP · min. 3 pemain per regu</p>
                    </div>
                    <span style="font-size:12px;font-weight:700;color:rgba(251,146,60,0.85);flex-shrink:0;margin-right:6px;font-family:'DM Sans',sans-serif;">Rp 200.000</span>
                    <svg class="sc-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>

            </div>
        </div>
    </div>
</div>

<script>
const kategoriRoutes = {
    'ganda-dewasa-putra'  : '{{ route("registration.ganda-dewasa-putra") }}',
    'ganda-dewasa-putri'  : '{{ route("registration.ganda-dewasa-putri") }}',
    'ganda-veteran-putra' : '{{ route("registration.ganda-veteran-putra") }}',
    'beregu'              : '{{ route("registration.beregu") }}',
};

function bukaModal2() {
    const m1 = document.getElementById('modal1');
    const m2 = document.getElementById('modal2');
    const m2card = document.getElementById('modal2Card');

    m1.classList.add('anim-out');
    setTimeout(() => {
        m1.style.display = 'none';
        m1.classList.remove('anim-out');
        m2.style.display = 'flex';
        m2.classList.add('anim-in');
        m2card.style.animation = 'none';
        void m2card.offsetWidth;
        m2card.style.animation = 'slideUpCard 0.42s cubic-bezier(0.22,1,0.36,1) 0.05s both';
        setTimeout(() => m2.classList.remove('anim-in'), 400);
    }, 220);
}

function tutupModal2() {
    const m1 = document.getElementById('modal1');
    const m1card = document.getElementById('modal1Card');
    const m2 = document.getElementById('modal2');
    const m2card = document.getElementById('modal2Card');

    m2.classList.add('anim-out');
    m2card.style.animation = 'slideDownCard 0.2s ease forwards';
    setTimeout(() => {
        m2.style.display = 'none';
        m2.classList.remove('anim-out');
        m2card.style.animation = '';
        m1.style.display = 'flex';
        m1.classList.add('anim-in');
        m1card.style.animation = 'none';
        void m1card.offsetWidth;
        m1card.style.animation = 'slideUpCard 0.42s cubic-bezier(0.22,1,0.36,1) 0.05s both';
        setTimeout(() => m1.classList.remove('anim-in'), 400);
    }, 220);
}

function pilihKategori(kategori) {
    const m2 = document.getElementById('modal2');
    const m2card = document.getElementById('modal2Card');
    m2.classList.add('anim-out');
    m2card.style.animation = 'slideDownCard 0.2s ease forwards';
    setTimeout(() => { window.location.href = kategoriRoutes[kategori]; }, 220);
}

function hoverCard(el, color) {
    const c = color === 'indigo'
        ? { border:'rgba(99,102,241,0.6)',  bg:'rgba(99,102,241,0.1)',  shadow:'rgba(99,102,241,0.2)',  iconBg:'rgba(99,102,241,0.25)' }
        : { border:'rgba(249,115,22,0.6)',   bg:'rgba(249,115,22,0.1)',  shadow:'rgba(249,115,22,0.2)',   iconBg:'rgba(249,115,22,0.22)' };
    el.style.borderColor = c.border;
    el.style.background  = c.bg;
    el.style.boxShadow   = `0 8px 32px ${c.shadow}`;
    el.style.transform   = 'translateY(-2px)';
    const shimmer = el.querySelector('.m1-shimmer');
    if (shimmer) shimmer.style.opacity = '1';
    const icon = el.querySelector('.m1-icon');
    if (icon) { icon.style.background = c.iconBg; icon.style.transform = 'scale(1.08)'; }
    const ext = el.querySelector('.m1-ext');
    if (ext) ext.style.opacity = '0.7';
}

function unhoverCard(el, color) {
    const d = color === 'indigo'
        ? { border:'rgba(99,102,241,0.25)', bg:'rgba(15,18,50,0.8)' }
        : { border:'rgba(249,115,22,0.28)',  bg:'rgba(20,14,8,0.8)'  };
    el.style.borderColor = d.border;
    el.style.background  = d.bg;
    el.style.boxShadow   = '';
    el.style.transform   = '';
    const shimmer = el.querySelector('.m1-shimmer');
    if (shimmer) shimmer.style.opacity = '0';
    const icon = el.querySelector('.m1-icon');
    if (icon) { icon.style.background = ''; icon.style.transform = ''; }
    const ext = el.querySelector('.m1-ext');
    if (ext) ext.style.opacity = '0.25';
}
</script>

@endpush