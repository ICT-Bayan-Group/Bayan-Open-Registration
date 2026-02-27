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
        // Data Tim & Kontak
        'nama',
        'tim_pb',
        'email',
        'no_hp',
        'provinsi',
        'kota',
        'alamat',
        // Data Pelatih
        'nama_pelatih',
        'no_hp_pelatih',
        // Data Pemain
        'pemain',
        // Kategori & Pembayaran
        'kategori',
        'harga',
        'status',
        // Midtrans
        'midtrans_order_id',
        'midtrans_transaction_id',
        'payment_type',
        'payment_time',
        'fraud_status',
        // Receipt
        'pdf_receipt_path',
    ];

    protected $casts = [
        'pemain'       => 'array',   // ← auto encode/decode JSON
        'payment_time' => 'datetime',
        'harga'        => 'integer',
    ];

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

    // ── Accessors ──

    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori) {
            'regu' => 'Regu',
            'open' => 'Open',
            default => ucfirst($this->kategori),
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

    // ── Scopes ──

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRegu($query)
    {
        return $query->where('kategori', 'regu');
    }

    public function scopeOpen($query)
    {
        return $query->where('kategori', 'open');
    }
}