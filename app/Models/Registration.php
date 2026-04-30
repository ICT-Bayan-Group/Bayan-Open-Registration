<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',

        // ── Data Tim & Kontak ──
        'nama',
        'tim_pb',
        'email',
        'no_hp',
        'provinsi',
        'kota',

        // ── Data Pelatih ──
        'nama_pelatih',
        'no_hp_pelatih',

        // ── Data Pemain ──
        'pemain',

        // ── Data KTP per Pemain ──
        'nik',
        'tgl_lahir',
        'ktp_files',
        'paspor_files',
        'ktp_data',
        'ktp_city_valid',   // hasil validasi kota per pemain

        // ── Tipe Dokumen & Paspor ──
        'ktp_type',         // array: ["ktp","paspor",...] per pemain
        'paspor_number',    // array: [null,"A1234567",...] per pemain

        // ── Kategori & Pembayaran ──
        'kategori',
        'harga',
        'status',

        // ── Approval Flow ──
        'approval_status',
        'rejection_reason',
        'approved_at',
        'rejected_at',
        'approved_by',
        'rejected_by',
        'payment_token',
        'payment_token_expires_at',
        'whatsapp_sent_at',
        'whatsapp_reminder_sent',

        // ── Manual Payment Verification ──
        'payment_proof',
        'payment_verified_at',
        'payment_verified_by',
        'payment_note',

        // ── Receipt ──
        'pdf_receipt_path',

        // ── Khusus Veteran ──
        'tgl_lahir_pemain',
        'usia_pemain',

        'revision_token',
        'revision_token_expires_at',
        'revision_notes',
        'revision_requested_by',
        'revision_requested_at',
        'revision_submitted_at',
        'revision_count',
    ];

    protected $casts = [
        'pemain'           => 'array',
        'nik'              => 'array',
        'tgl_lahir'        => 'array',
        'ktp_files'        => 'array',
        'paspor_files'     => 'array',
        'ktp_data'         => 'array',
        'ktp_city_valid'   => 'array',
        'ktp_type'         => 'array',
        'paspor_number'    => 'array',
        'tgl_lahir_pemain' => 'array',
        'usia_pemain'      => 'array',

        'payment_time'             => 'datetime',
        'approved_at'              => 'datetime',
        'rejected_at'              => 'datetime',
        'payment_token_expires_at' => 'datetime',
        'harga'                    => 'integer',
        'revision_token_expires_at' => 'datetime',
        'revision_requested_at'     => 'datetime',
        'revision_submitted_at'     => 'datetime',
        'revision_count'            => 'integer',
        'payment_verified_at'       => 'datetime',
        'whatsapp_sent_at'          => 'datetime',
        'whatsapp_reminder_sent'    => 'boolean',
    ];

    // ── Auto-generate UUID ─────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Registration $r) {
            if (empty($r->uuid)) {
                $r->uuid = (string) Str::uuid();
            }
        });
    }

    // ── Relations ──────────────────────────────────────────────────

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function paymentVerifiedBy()
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    // ── Approval Helpers ───────────────────────────────────────────

    /**
     * Approve pendaftaran: generate payment token & ubah status.
     */
    public function approve(int $adminId): void
    {
        $this->update([
            'approval_status'          => 'approved',
            'approved_at'              => now(),
            'approved_by'              => $adminId,
            'rejected_at'              => null,
            'rejected_by'              => null,
            'rejection_reason'         => null,
            'payment_token'            => (string) \Illuminate\Support\Str::uuid(),
            'payment_token_expires_at' => now()->addDays(3),
        ]);
    }

    public function requestRevision(int $adminId, string $notes): void
    {
        $token = bin2hex(random_bytes(32));

        $this->update([
            'approval_status'           => 'revision_required',
            'revision_token'            => $token,
            'revision_token_expires_at' => now()->addDays(7),
            'revision_notes'            => $notes,
            'revision_requested_by'     => $adminId,
            'revision_requested_at'     => now(),
            'revision_submitted_at'     => null,
            'revision_count'            => $this->revision_count + 1,
        ]);
    }

    public function submitRevision(array $data): void
    {
        $this->update(array_merge($data, [
            'approval_status'           => 'pending_review',
            'revision_token'            => null,
            'revision_token_expires_at' => null,
            'revision_submitted_at'     => now(),
        ]));
    }

    public function isRevisionTokenValid(string $token): bool
    {
        return $this->revision_token === $token
            && $this->revision_token_expires_at
            && $this->revision_token_expires_at->isFuture()
            && $this->approval_status === 'revision_required';
    }

    public function revisionRequestedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'revision_requested_by');
    }

    /**
     * Reject pendaftaran.
     */
    public function reject(int $adminId, string $reason): void
    {
        $this->update([
            'approval_status'  => 'rejected',
            'rejected_at'      => now(),
            'rejected_by'      => $adminId,
            'rejection_reason' => $reason,
            'approved_at'      => null,
            'approved_by'      => null,
            'payment_token'    => null,
            'payment_token_expires_at' => null,
        ]);
    }

    /**
     * Apakah payment link masih valid?
     */
    public function paymentTokenValid(): bool
    {
        return $this->payment_token
            && $this->payment_token_expires_at
            && $this->payment_token_expires_at->isFuture();
    }

    public function paymentLink(): ?string
    {
        if (! $this->payment_token) {
            return null;
        }

        return route('registration.payment.token', $this->payment_token);
    }

    /**
     * Generate ulang payment token baru.
     */
    public function regeneratePaymentToken(int $expiresInHours = 24): void
    {
        $this->update([
            'payment_token'            => (string) Str::uuid(),
            'payment_token_expires_at' => now()->addHours($expiresInHours),
            'status'                   => 'pending',
        ]);
    }

    /**
     * Jumlah anggota dengan kota valid (Balikpapan).
     */
    public function validCityCount(): int
    {
        $cityValid = $this->ktp_city_valid ?? [];
        return count(array_filter($cityValid, fn ($c) => $c['valid'] ?? false));
    }

    /**
     * Apakah syarat minimal 6 KTP kota valid terpenuhi?
     */
    public function meetsMinimumValidKtp(): bool
    {
        return $this->validCityCount() >= 6;
    }

    // ── Accessors ──────────────────────────────────────────────────

    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori) {
            'ganda-dewasa-putra'  => 'Ganda Dewasa Putra',
            'ganda-dewasa-putri'  => 'Ganda Dewasa Putri',
            'ganda-veteran-putra' => 'Ganda Veteran Putra',
            'beregu'              => 'Beregu',
            default               => ucfirst($this->kategori),
        };
    }

    public function getPemainListAttribute(): string
    {
        if (empty($this->pemain)) return '-';
        return implode(', ', $this->pemain);
    }

    public function getJumlahPemainAttribute(): int
    {
        return count($this->pemain ?? []);
    }

    public function getKtpPerPemainAttribute(): array
    {
        $pemain      = $this->pemain        ?? [];
        $nik         = $this->nik           ?? [];
        $tglLahir    = $this->tgl_lahir     ?? [];
        $usia        = $this->usia_pemain   ?? [];
        $ktpFiles    = $this->ktp_files     ?? [];
        $pasporFiles = $this->paspor_files  ?? [];
        $ktpData     = $this->ktp_data      ?? [];
        $cityValid   = $this->ktp_city_valid ?? [];
        $ktpType     = $this->ktp_type      ?? [];
        $pasporNum   = $this->paspor_number ?? [];

        $result = [];
        foreach ($pemain as $i => $nama) {
            $docType = $ktpType[$i] ?? 'ktp';
            $result[] = [
                'index'          => $i + 1,
                'nama'           => $nama,
                'doc_type'       => $docType,
                'nik'            => $docType === 'ktp'    ? ($nik[$i]       ?? null) : null,
                'paspor_number'  => $docType === 'paspor' ? ($pasporNum[$i] ?? null) : null,
                'tgl_lahir'      => $tglLahir[$i]  ?? null,
                'usia'           => $usia[$i]       ?? null,
                'ktp_file'       => $docType === 'ktp'    ? ($ktpFiles[$i]   ?? null) : null,
                'paspor_file'    => $docType === 'paspor' ? ($pasporFiles[$i] ?? null) : null,
                'ktp_raw'        => $ktpData[$i]    ?? [],
                'city_valid'     => $cityValid[$i]  ?? null,
            ];
        }
        return $result;
    }

    // ── Helper: apakah pemain index ke-$i pakai paspor? ────────────

    public function isPaspor(int $index): bool
    {
        $types = $this->ktp_type ?? [];
        return ($types[$index] ?? 'ktp') === 'paspor';
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopePaid($query)               { return $query->where('status', 'paid'); }
    public function scopePending($query)            { return $query->where('status', 'pending'); }
    public function scopeKategori($q, $k)           { return $q->where('kategori', $k); }
    public function scopePendingReview($query)      { return $query->where('approval_status', 'pending_review'); }
    public function scopeApproved($query)           { return $query->where('approval_status', 'approved'); }
    public function scopeRejected($query)           { return $query->where('approval_status', 'rejected'); }
    public function scopePendingVerification($query){ return $query->where('status', 'pending_verification'); }
    public function scopeFailed($query)             { return $query->where('status', 'failed'); }

    // ── Payment Verification Methods ───────────────────────────────

    public function approvePayment(int $adminId, string $note = null): void
    {
        $this->update([
            'status'              => 'paid',
            'payment_verified_at' => now(),
            'payment_verified_by' => $adminId,
            'payment_note'        => $note,
        ]);
    }

    public function rejectPayment(int $adminId, string $note = null): void
    {
        $this->update([
            'status'              => 'failed',
            'payment_verified_at' => now(),
            'payment_verified_by' => $adminId,
            'payment_note'        => $note,
        ]);
    }

    public function hasPaymentProof(): bool
    {
        return !empty($this->payment_proof);
    }

    public function getPaymentProofUrl(): ?string
    {
        if (!$this->payment_proof) return null;
        return asset('storage/' . $this->payment_proof);
    }
}