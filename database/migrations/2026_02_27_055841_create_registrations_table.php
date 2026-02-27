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
            $table->uuid('uuid')->unique();

            // ── Data Tim & Kontak ──
            $table->string('nama');                  // Nama ketua / PIC
            $table->string('tim_pb');                // Nama tim / PB
            $table->string('email');                 // Email (untuk kirim receipt)
            $table->string('no_hp');                 // No. HP / WhatsApp
            $table->string('provinsi');              // Provinsi
            $table->string('kota');                  // Kota / Kabupaten
            $table->text('alamat');                  // Alamat lengkap

            // ── Data Pelatih ──
            $table->string('nama_pelatih')->nullable();   // Nama pelatih
            $table->string('no_hp_pelatih')->nullable();  // No. HP pelatih

            // ── Data Pemain ──
            $table->json('pemain');                  // Array nama pemain

            // ── Kategori & Pembayaran ──
            $table->enum('kategori', ['regu', 'open']);
            $table->unsignedInteger('harga');
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])->default('pending');

            // ── Midtrans ──
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->timestamp('payment_time')->nullable();
            $table->string('fraud_status')->nullable();

            // ── Receipt ──
            $table->string('pdf_receipt_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};