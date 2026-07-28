@once
<style>
    /* ── Card + Header ── */
    .ktp-card { border-radius:12px; overflow:hidden; background:#fff; border:1.5px solid #d1d5db; }
    html.dark .ktp-card { background:rgba(255,255,255,0.03); border-color:rgba(255,255,255,0.12); }
    .ktp-header { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; border-bottom:1.5px solid inherit; }

    .ktp-state-edit { border-color:#2563eb !important; }
    .ktp-state-edit .ktp-header { background:#eff6ff; border-bottom-color:#2563eb; }
    html.dark .ktp-state-edit { border-color:#3b82f6 !important; }
    html.dark .ktp-state-edit .ktp-header { background:rgba(59,130,246,0.12); border-bottom-color:#3b82f6; }

    .ktp-state-valid { border-color:#059669 !important; }
    .ktp-state-valid .ktp-header { background:#f0fdf4; border-bottom-color:#059669; }
    html.dark .ktp-state-valid { border-color:#22c55e !important; }
    html.dark .ktp-state-valid .ktp-header { background:rgba(34,197,94,0.12); border-bottom-color:#22c55e; }

    .ktp-state-invalid { border-color:#dc2626 !important; }
    .ktp-state-invalid .ktp-header { background:#fef2f2; border-bottom-color:#dc2626; }
    html.dark .ktp-state-invalid { border-color:#f87171 !important; }
    html.dark .ktp-state-invalid .ktp-header { background:rgba(248,113,113,0.12); border-bottom-color:#f87171; }

    .ktp-state-neutral { border-color:#d1d5db !important; }
    .ktp-state-neutral .ktp-header { background:#f9fafb; border-bottom-color:#d1d5db; }
    html.dark .ktp-state-neutral { border-color:rgba(255,255,255,0.15) !important; }
    html.dark .ktp-state-neutral .ktp-header { background:rgba(255,255,255,0.04); border-bottom-color:rgba(255,255,255,0.15); }

    .ktp-state-paspor { border-color:#c4b5fd !important; }
    .ktp-state-paspor .ktp-header { background:#f5f3ff; border-bottom-color:#c4b5fd; }
    html.dark .ktp-state-paspor { border-color:#a78bfa !important; }
    html.dark .ktp-state-paspor .ktp-header { background:rgba(167,139,250,0.12); border-bottom-color:#a78bfa; }

    /* ── Avatar / Name ── */
    .ktp-avatar { width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;background:#e0e7ff;color:#4338ca; }
    html.dark .ktp-avatar { background:rgba(99,102,241,0.18); color:#a5b4fc; }
    .ktp-name { font-weight:700; font-size:14px; color:#111827; }
    html.dark .ktp-name { color:#f9fafb; }

    /* ── Badges ── */
    .badge-paspor { color:#8b5cf6;background:#f3e8ff;border:1px solid #c4b5fd;border-radius:99px;padding:4px 12px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px; }
    html.dark .badge-paspor { color:#c4b5fd; background:rgba(139,92,246,0.15); border-color:rgba(139,92,246,0.4); }
    .badge-ktp { color:#f97316;background:#fed7aa;border:1px solid #fdba74;border-radius:99px;padding:4px 12px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:4px; }
    html.dark .badge-ktp { color:#fdba74; background:rgba(249,115,22,0.15); border-color:rgba(249,115,22,0.4); }
    .badge-city-ok { font-size:11px;font-weight:700;border-radius:99px;padding:4px 12px;border:1px solid;color:#15803d;background:#dcfce7;border-color:#86efac; }
    html.dark .badge-city-ok { color:#4ade80; background:rgba(34,197,94,0.15); border-color:rgba(34,197,94,0.4); }
    .badge-city-bad { font-size:11px;font-weight:700;border-radius:99px;padding:4px 12px;border:1px solid;color:#b91c1c;background:#fee2e2;border-color:#fca5a5; }
    html.dark .badge-city-bad { color:#f87171; background:rgba(248,113,113,0.15); border-color:rgba(248,113,113,0.4); }

    /* ── Buttons ── */
    .btn-edit-small { border:none;background:#e5e7eb;color:#374151;border-radius:8px;padding:6px 10px;font-size:12px;font-weight:600;cursor:pointer; }
    html.dark .btn-edit-small { background:rgba(255,255,255,0.1); color:#e5e7eb; }
    .ktp-edit-mode-label { font-size:11px;font-weight:700;color:#2563eb; }
    html.dark .ktp-edit-mode-label { color:#60a5fa; }
    .btn-save { flex:1;background:#059669;color:#fff;border:none;border-radius:8px;padding:8px 12px;font-size:12px;font-weight:700;cursor:pointer; }
    html.dark .btn-save { background:#16a34a; }
    .btn-cancel { flex:1;background:#e5e7eb;color:#374151;border:none;border-radius:8px;padding:8px 12px;font-size:12px;font-weight:700;cursor:pointer; }
    html.dark .btn-cancel { background:rgba(255,255,255,0.1); color:#e5e7eb; }

    /* ── Read-mode rows ── */
    .ktp-row { display:flex; gap:8px; padding:6px 0; border-bottom:1px solid #f3f4f6; }
    html.dark .ktp-row { border-bottom-color:rgba(255,255,255,0.08); }
    .ktp-row-label { font-size:10px;font-weight:700;text-transform:uppercase;color:#9ca3af;min-width:76px;flex-shrink:0; }
    .ktp-row-value { font-size:12px;font-weight:600;color:#374151; }
    html.dark .ktp-row-value { color:#e5e7eb; }
    .ktp-row-value.mono { font-family:monospace;font-weight:700; }
    .ktp-section-label { font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px; }
    html.dark .ktp-section-label { color:#9ca3af; }

    /* ── Edit-mode fields ── */
    .ktp-field-label { font-size:10px;font-weight:700;text-transform:uppercase;color:#6b7280; }
    html.dark .ktp-field-label { color:#9ca3af; }
    .ktp-input, .ktp-select { width:100%;margin-top:2px;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;background:#fff;color:#111827; }
    html.dark .ktp-input, html.dark .ktp-select { background:rgba(255,255,255,0.05); border-color:rgba(255,255,255,0.15); color:#f3f4f6; }
    .ktp-input.mono { font-family:monospace; }
    .ktp-checkbox-label { display:flex;align-items:center;gap:8px;font-size:12px;font-weight:600;color:#374151; }
    html.dark .ktp-checkbox-label { color:#d1d5db; }
    .ktp-error { color:#dc2626;font-size:11px; }
    html.dark .ktp-error { color:#f87171; }

    /* ── Photo ── */
    .ktp-photo { width:100%;max-height:180px;object-fit:contain;border-radius:8px;border:1px solid #e5e7eb;background:#f9fafb;cursor:pointer; }
    html.dark .ktp-photo { border-color:rgba(255,255,255,0.12); background:rgba(255,255,255,0.03); }
    .ktp-photo-empty { height:100px;display:flex;align-items:center;justify-content:center;border:1px dashed #d1d5db;border-radius:8px;background:#f9fafb; }
    html.dark .ktp-photo-empty { border-color:rgba(255,255,255,0.15); background:rgba(255,255,255,0.03); }
    .ktp-photo-empty p, .ktp-photo-caption { color:#9ca3af;font-size:12px;margin:0; }

    /* ── Semantic text colors (dipakai di beberapa tempat) ── */
    .txt-ok { color:#15803d; } html.dark .txt-ok { color:#4ade80; }
    .txt-bad { color:#dc2626; } html.dark .txt-bad { color:#f87171; }
    .txt-blue { color:#1d4ed8; } html.dark .txt-blue { color:#60a5fa; }
    .txt-pink { color:#be185d; } html.dark .txt-pink { color:#f472b6; }
    .txt-purple { color:#6d28d9; } html.dark .txt-purple { color:#c4b5fd; }
    .txt-orange { color:#c2410c; } html.dark .txt-orange { color:#fb923c; }
    .txt-muted { color:#6b7280; } html.dark .txt-muted { color:#9ca3af; }
    .txt-body { color:#374151; } html.dark .txt-body { color:#e5e7eb; }
    .txt-heading { color:#111827; } html.dark .txt-heading { color:#f9fafb; }

    /* ── Veteran summary box ── */
    .vet-box { border-radius:12px; padding:16px; border:1px solid; }
    .vet-box.vet-ok { background:#f0fdf4; border-color:#86efac; }
    html.dark .vet-box.vet-ok { background:rgba(34,197,94,0.08); border-color:rgba(34,197,94,0.35); }
    .vet-box.vet-bad { background:#fef2f2; border-color:#fca5a5; }
    html.dark .vet-box.vet-bad { background:rgba(248,113,113,0.08); border-color:rgba(248,113,113,0.35); }
    .vet-row { display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid #e5e7eb; }
    html.dark .vet-row { border-bottom-color:rgba(255,255,255,0.1); }
    .vet-total-label { font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em; }
    html.dark .vet-total-label { color:#9ca3af; }

    /* ── Confirmation boxes (approve/reject modal) ── */
    .confirm-box { padding:12px 14px;border-radius:10px;border:1px solid;margin-bottom:4px; }
    .confirm-box.confirm-success { background:#f0fdf4;border-color:#86efac; }
    html.dark .confirm-box.confirm-success { background:rgba(34,197,94,0.08); border-color:rgba(34,197,94,0.35); }
    .confirm-box.confirm-danger { background:#fef2f2;border-color:#fca5a5; }
    html.dark .confirm-box.confirm-danger { background:rgba(248,113,113,0.08); border-color:rgba(248,113,113,0.35); }

    /* ── Payment proof / doc modal ── */
    .proof-img { width:100%;max-height:540px;object-fit:contain;border-radius:10px;border:1px solid #d1d5db;background:#f9fafb; }
    html.dark .proof-img { border-color:rgba(255,255,255,0.12); background:rgba(255,255,255,0.03); }
    .doc-empty-paspor { display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;border-radius:8px;border:1.5px dashed #c4b5fd;background:#faf5ff;text-align:center; }
    html.dark .doc-empty-paspor { border-color:rgba(167,139,250,0.4); background:rgba(167,139,250,0.06); }
    .paspor-num-box { padding:16px;border-radius:8px;border:1.5px dashed #c4b5fd;background:#faf5ff;text-align:center; }
    html.dark .paspor-num-box { border-color:rgba(167,139,250,0.4); background:rgba(167,139,250,0.06); }
</style>
@endonce