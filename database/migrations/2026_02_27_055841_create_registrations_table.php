<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->index();

            // ── Data Tim & Kontak ──────────────────────────────────
            $table->string('nama');
            $table->string('tim_pb');
            $table->string('email');
            $table->string('no_hp');
            $table->string('provinsi');
            $table->string('kota');

            // ── Data Pelatih ───────────────────────────────────────
            $table->string('nama_pelatih')->nullable();
            $table->string('no_hp_pelatih')->nullable();

            // ── Data Pemain ────────────────────────────────────────
            $table->json('pemain');  // array nama pemain

            // ── Data KTP per Pemain ────────────────────────────────
            // Hanya NIK & tgl_lahir yang dikirim dari form
            // tempat_lahir & jenis_kelamin DIHAPUS (tidak dikirim frontend)
            $table->json('nik')->nullable();       // array NIK
            $table->json('tgl_lahir')->nullable(); // array tanggal lahir
            $table->json('ktp_files')->nullable(); // array path file KTP
            $table->json('ktp_data')->nullable();  // raw data OCR per pemain

            // ── Khusus Veteran ─────────────────────────────────────
            $table->json('tgl_lahir_pemain')->nullable(); // alias tgl_lahir untuk veteran
            $table->json('usia_pemain')->nullable();      // usia dihitung otomatis backend

            // ── Kategori & Pembayaran ──────────────────────────────
            $table->string('kategori')->index();
            $table->unsignedInteger('harga');
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])
                  ->default('pending')
                  ->index();

            // ── Midtrans ───────────────────────────────────────────
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->timestamp('payment_time')->nullable();
            $table->string('fraud_status')->nullable();

            // ── Receipt ────────────────────────────────────────────
            $table->string('pdf_receipt_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};