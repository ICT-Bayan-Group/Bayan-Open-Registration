<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom ktp_type dan paspor_number ke tabel registrations.
     *
     * ktp_type      → JSON array per pemain: ["ktp","paspor","ktp", ...]
     * paspor_number → JSON array per pemain: [null,"A1234567",null, ...]
     */
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Tipe dokumen per pemain: "ktp" atau "paspor"
            $table->json('ktp_type')
                  ->nullable()
                  ->after('ktp_city_valid')
                  ->comment('Tipe dokumen per pemain: ktp atau paspor');

            // Nomor paspor per pemain (diisi jika ktp_type = paspor, null jika KTP)
            $table->json('paspor_number')
                  ->nullable()
                  ->after('ktp_type')
                  ->comment('Nomor paspor per pemain, null jika menggunakan KTP');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['ktp_type', 'paspor_number']);
        });
    }
};