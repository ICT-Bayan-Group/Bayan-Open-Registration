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
        // tempat_lahir & jenis_kelamin DIHAPUS (tidak dikirim dari frontend)
        'nik',         // array NIK
        'tgl_lahir',   // array tanggal lahir
        'ktp_files',   // array path file KTP di disk private
        'ktp_data',    // raw OCR data per pemain

        // ── Kategori & Pembayaran ──
        'kategori',
        'harga',
        'status',

        // ── Midtrans ──
        'midtrans_order_id',
        'midtrans_transaction_id',
        'payment_type',
        'payment_time',
        'fraud_status',

        // ── Receipt ──
        'pdf_receipt_path',

        // ── Khusus Veteran ──
        'tgl_lahir_pemain',
        'usia_pemain',
    ];

    protected $casts = [
        // JSON fields — auto encode/decode
        'pemain'           => 'array',
        'nik'              => 'array',
        'tgl_lahir'        => 'array',
        'ktp_files'        => 'array',
        'ktp_data'         => 'array',
        'tgl_lahir_pemain' => 'array',
        'usia_pemain'      => 'array',

        // Scalar
        'payment_time' => 'datetime',
        'harga'        => 'integer',
    ];

    // ── Auto-generate UUID & Order ID saat create ──────────────────

    protected static function booted(): void
    {
        static::creating(function (Registration $registration) {
            if (empty($registration->uuid)) {
                $registration->uuid = (string) Str::uuid();
            }
            if (empty($registration->midtrans_order_id)) {
                $registration->midtrans_order_id = 'BO2026-' . strtoupper(Str::random(8));
            }
        });
    }

    // ── Accessors ──────────────────────────────────────────────────

    /** Harga diformat: "Rp 150.000" */
    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /** Label kategori human-readable */
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

    /** Daftar nama pemain digabung koma */
    public function getPemainListAttribute(): string
    {
        if (empty($this->pemain)) return '-';
        return implode(', ', $this->pemain);
    }

    /** Jumlah pemain terdaftar */
    public function getJumlahPemainAttribute(): int
    {
        return count($this->pemain ?? []);
    }

    /**
     * Ringkasan data KTP per pemain untuk admin:
     * [['index', 'nama', 'nik', 'tgl_lahir', 'usia', 'ktp_file', 'ktp_raw'], ...]
     */
    public function getKtpPerPemainAttribute(): array
    {
        $pemain   = $this->pemain    ?? [];
        $nik      = $this->nik       ?? [];
        $tglLahir = $this->tgl_lahir ?? [];
        $usia     = $this->usia_pemain ?? [];
        $ktpFiles = $this->ktp_files ?? [];
        $ktpData  = $this->ktp_data  ?? [];

        $result = [];
        foreach ($pemain as $i => $nama) {
            $result[] = [
                'index'     => $i + 1,
                'nama'      => $nama,
                'nik'       => $nik[$i]      ?? null,
                'tgl_lahir' => $tglLahir[$i] ?? null,
                'usia'      => $usia[$i]      ?? null,
                'ktp_file'  => $ktpFiles[$i]  ?? null,
                'ktp_raw'   => $ktpData[$i]   ?? [],
            ];
        }
        return $result;
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeKategori($query, string $kategori)
    {
        return $query->where('kategori', $kategori);
    }
}